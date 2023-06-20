<?php

namespace  App\Repositories\work\workflow\runtime;

use App\Jobs\Workflows\RemindJobsAfterCompleteJob;
use App\Mail\Workflows\EmailWorkflowBase;
use App\Mail\EmailWorkflowComment;
use App\Models\shared\DocumentType;
use App\Models\shared\File;
use App\Models\shared\Group;
use App\Models\shared\Shared;
use App\Models\shared\Timeline;
use App\Models\work\workflow\runtime\WlworkflowDoc;
use App\Models\work\workflow\runtime\WlworkflowJob;
use App\Models\work\workflow\Wldataobject;
use App\Models\work\workflow\WldataobjectItms;
use App\Models\work\workflow\Wljob;
use App\Models\work\workflow\WljobDependency;
use App\Models\work\workflow\Wlphase;
use App\Models\work\workflow\Wlworkflow;
use App\Models\work\workflow\WlworkflowAdmin;
use App\Models\work\workflow\WlworkflowAppdoc;
use App\Models\work\workflow\WlworkflowFollow;
use App\Models\work\workflow\WlworkflowJobDetail;
use App\Models\work\workflow\WlworkflowJobNavigate;
use App\Models\work\workflow\WlworkflowJobType;
use App\Models\work\workflow\WlworkflowMember;
use App\Notifications\CommondNotification;
use App\Notifications\NotiBaseModel;
use App\Notifications\WorkflowNotification;
use App\Repositories\approve\ApproveRoutingHelper;
use App\Repositories\DocumentSerials;
use App\Repositories\work\workflow\define\DataObjectProcess;
use App\Repositories\work\workflow\define\WlJobProcess;
use App\Traits\HasTeamManual;
use App\Ultils\Ultils;
use App\User;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use SoareCostin\FileVault\Facades\FileVault as FacadesFileVault;
use Illuminate\Support\Facades\Storage;
use App\Repositories\work\workflow\define\WorkflowDefineBase;
use Illuminate\Notifications\Channels\MailChannel;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

abstract class WorkflowAbs
{
    use HasTeamManual;
    protected $wlworkflow;
    protected $request;
    protected $errors;
    protected $message;
    protected $layout;
    protected $form_name;
    // protected $documentType;
    public function __construct($request)
    {
        $this->request = $request;
    }
    public function __set($name, $value)
    {
        $this->$name = $value;
    }
    public function __get($name)
    {
        return $this->$name;
    }

    public function index()
    {
        $query = WlworkflowDoc::query();

        try {
            $workflow_code = $this->request->current_workflow_code;
            $query = $query->where('wlworkflow_type_id', $workflow_code);

            if ($this->request->filled('start_date')) {
                if (!$this->request->filled('end_date')) {
                    $this->request->end_date = $this->request->start_date;
                }
                $start_date = Carbon::create($this->request->start_date);
                $end_date = Carbon::create($this->request->end_date);
                $end_date->addHours(23);
                $end_date->addMinutes(59);
                $end_date->addSeconds(59);
                $query = $query->whereBetween('created_at', [$start_date, $end_date]);
            }

            if ($this->request->filled('status')) {
                $query = $query->whereIn('status',  explode(',', $this->request->status));
            }
            if ($this->request->filled('base_workflow_ids')) {
                $query = $query->whereIn('original_id',  explode(',', $this->request->base_workflow_ids));
            }
            if ($this->request->filled('serial_numbers')) {
                $query = $query->whereIn('serial_num',  explode(',', $this->request->serial_numbers));
            }
            if ($this->request->filled('users')) {
                $query = $query->whereIn('user_id',  explode(',', $this->request->users));
            }
            if ($this->request->filled('scopes') && $this->request->scopes != "undefined" && $this->request->scopes != "null") {
                $scopes = explode(",", $this->request->scopes);
                $is_first = true;

                foreach ($scopes as $scope) {
                    $key = substr($scope, 0, 1);
                    $value = substr($scope, 1);

                    if ($key == 'c') {
                        $key = 'company_id';
                    } else if ($key == 'd') {
                        $key = 'department_id';
                    } else if ($key == 'w') {
                        $key = 'workshop_id';
                    } else if ($key == 'p') {
                        $key = 'party_id';
                    }

                    if ($is_first) {
                        $query = $query->Where($key, $value);
                        $is_first = false;
                    } else {
                        $query = $query->orWhere($key, $value);
                    }
                }
            }

            $user = User::find(auth()->user()->id);
            $withModel = ['approveds', 'reminders'];

            if ($user->hasWorkflowPermission($workflow_code, 'review_all')) {
                $documents = $query->orderBy('id', 'desc')->with($withModel)->get();
            } else if ($user->hasWorkflowPermission($workflow_code, 'review_company')) {
                $company_id = $user->company_id;
                $group_ids = $user->groups->pluck('id')->flatten();
                $query = $query->where(function ($q) use ($company_id, $group_ids) {
                    $q->orWhere('company_id', $company_id)
                        ->orWhereIn('group_id', $group_ids)
                        ->orWhereHas('approveds', function (Builder $query) {
                            $query->where('user_id', auth()->user()->id);
                        });
                });
                $documents = $query->orderBy('id', 'desc')->with($withModel)->get();
            } else {
                //Các chứng từ hạn chế xem trong group
                //Những chứng từ chỉ có người tạo mới nhận lại được

                $documentType_ids = DocumentType::where('private', '1')->get();
                $special_ids = $documentType_ids->pluck('id')->flatten();
                $query = $query->where(function ($q) use ($special_ids, $user) {
                    $q->whereNotIn('document_type_id', $special_ids)
                        ->orWhere(function ($sub) use ($special_ids, $user) {
                            $sub->whereIn('document_type_id', $special_ids)
                                ->where('user_id', $user->id);
                        });
                });

                $group_ids = $user->groups->pluck('id')->flatten();
                $user_id = $user->id;
                // Lấy các chứng từ shared group
                $query = $query->where(function ($q) use ($group_ids, $user_id) {
                    $q->whereIn('group_id', $group_ids)
                        ->orWhereHas('shareds', function ($q) use ($group_ids, $user_id) {
                            $q->where(function ($qu) use ($group_ids) {
                                $qu->whereIn('object_id', $group_ids)->where('type', 0);
                            })->orwhere(function ($qu) use ($user_id) {
                                $qu->where('object_id', $user_id)->where('type', 1);
                            });
                        })
                        ->orWhereHas('approveds', function (Builder $query) {
                            $query->where('user_id', auth()->user()->id);
                        })
                        ->orWhere('user_id', $user_id);
                });

                $documents = $query->orderBy('id', 'desc')->with($withModel)->get();
            }

            foreach ($documents as $current_workflow) {
                $workflow = Wlworkflow::find($current_workflow->wlworkflow_id);
                if ($workflow) {
                    $current_phase = Wlphase::find($workflow->current_phase);
                    if ($current_phase) {
                        $current_workflow->current_phase = $current_phase->name;
                    }
                }

                // Giai đoạn duyệt & chờ duyệt
                if ($current_workflow->status == 1) {
                    foreach ($current_workflow->approveds as $approve) {
                        if ($approve->finished == 0 && $approve->release == 0 && $approve->online == "X") {
                            $current_workflow->waiting_approval = User::find($approve->user_id)->name;
                        }
                    }


                    if ($current_workflow->approveds && count($current_workflow->approveds) > 0) {

                        $finalApprove = $current_workflow->approveds[count($current_workflow->approveds) - 1];
                        if ($finalApprove  && $finalApprove->release == 1) {
                            $current_workflow->is_release = 'X';

                            $feedback = array();
                            $feedback['name'] = User::find($finalApprove->user_id)->name;
                            $feedback['date'] = $finalApprove->checkout;
                            $feedback['note'] = $finalApprove->note;

                            $current_workflow->feedback = $feedback;
                        }
                    }
                }
                // else if ($current_workflow->status == 2) {
                //      $current_workflow->total_jobs = Wljob::where('wlphase_id', $current_phase->id)->count();
                //      $current_workflow->total_jobs_done = Wljob::where('wlphase_id', $current_phase->id)->where('is_completed', true)->count();
                // }

                unset($current_workflow['approveds']);
            }
        } catch (Exception $e) {
            $this->errors = $e->getMessage();
        }

        return $documents;
    }
    protected function validate_store()
    {

        $fields = $this->request->all();
        //  dd($fields);
        $validator = Validator::make(
            $this->request->all(),
            [

                'document_type_id' => 'required',
                'wlworkflow_id' => 'required',
                'group_id' => 'required',
                'title' => 'required|max:150',
            ],
            [
                'document_type_id.required' => "Loại chứng từ không được rỗng",
                'wlworkflow_id.required' => "Loại qui trinhf không được rỗng",
                'group_id.required' => "Nhóm không được rỗng",
                'title.required' => "Tiêu đề không được rỗng",
                'title.max' => "Tiêu đề tối đa 150 kí tự",
            ]
        );

        if (!$this->request->filled('wlworkflow_id')) {
            $validator->after(function ($validator) {
                $validator->errors()->add('wlworkflow_id', 'Chứng từ chưa được gán qui trình');
            });
        }
        // $wlworkflow_id = $fields['wlworkflow_id'];
        // $wlworkflow = Wlworkflow::find($wlworkflow_id);
        // $wldataobjects = $wlworkflow->wldataobjects;
        $controls = $fields['controls'];

        foreach ($controls as  $obj) {
            $wlobj = Wldataobject::find($obj['id']);
            if (!$wlobj) {
                $validator->after(function ($validator) use ($obj) {
                    $validator->errors()->add($obj['control_id'], $obj['custom_label_text'] . ' chưa được cấu hình');
                });
            } else {
                if ($obj['require_validation']) {

                    switch ($obj['type']) {
                        case Ultils::$CONTROL_TYPE['USERS']:
                        case Ultils::$CONTROL_TYPE['LIST']:
                            $value_list = $obj['value_list'];
                            if (count($value_list) == 0) {
                                $validator->after(function ($validator) use ($obj) {
                                    $validator->errors()->add($obj['control_id'], $obj['custom_label_text'] . ' không được rỗng');
                                });
                            }
                            break;
                        case Ultils::$CONTROL_TYPE['NUMBER']:
                            if ($obj['value'] == null || $obj['value'] = "" || $obj['value'] = "0" || !is_numeric($obj['value'])) {
                                $validator->after(function ($validator) use ($obj) {
                                    $validator->errors()->add($obj['control_id'], $obj['custom_label_text'] . ' không được rỗng');
                                });
                            }
                            break;

                        case Ultils::$CONTROL_TYPE['DATE']:
                            if ($obj['value'] == null || $obj['value'] = "" || !Ultils::isDate($obj['value'])) {
                                $validator->after(function ($validator) use ($obj) {
                                    $validator->errors()->add($obj['control_id'], $obj['custom_label_text'] . ' không được rỗng');
                                });
                            }
                            break;
                        case Ultils::$CONTROL_TYPE['TIME']:
                            if ($obj['value'] == null || $obj['value'] = "" || !Ultils::isDate($obj['value'])) {
                                $validator->after(function ($validator) use ($obj) {
                                    $validator->errors()->add($obj['control_id'], $obj['custom_label_text'] . ' không được rỗng');
                                });
                            }
                            break;
                        case Ultils::$CONTROL_TYPE['FILE']:
                            $attachment_file = $obj['attachment_file'];
                            if (count($attachment_file) == 0) {
                                $validator->after(function ($validator) use ($obj) {
                                    $validator->errors()->add($obj['control_id'], $obj['custom_label_text'] . ' không được rỗng');
                                });
                            }

                            break;

                        default:

                            if ($obj['value'] == null || $obj['value'] = "") {
                                $validator->after(function ($validator) use ($obj) {
                                    $validator->errors()->add($obj['control_id'], $obj['custom_label_text'] . ' không được rỗng');
                                });
                            }
                            break;
                    }
                }
            }
        }

        // dd($controls);

        $documentType = DocumentType::find($this->request->document_type_id);
        if ($documentType) {
            if ($documentType->approve_manual == '1') {
                $this->request['team_id'] =  $this->createTeam();
                $this->assign_user($this->request['team_id'], $this->request->team_users);

                if ($this->request['team_id'] == '') {
                    $validator->after(function ($validator) {
                        $validator->errors()->add('team_id', 'Tạo Team bị lỗi. Vui lòng liên hệ IT- Admin hệ thống');
                    });
                }
            } else {
                // $group = Group::find($fields['group_id']);

                // if($group->company){


                //     $team_id = ApproveRoutingHelper::get_team($group->company->id, $fields['document_type_id'], $fields['group_id'], $fields['budget_type'], $fields['amount'], $fields['amount'], $fields['currency'],$fields['payment_type_id'],0,0);

                //     if ($team_id == "") {
                //         $validator->after(function ($validator) {
                //             $validator->errors()->add('team_id', 'Chưa phân tuyến duyệt. Vui lòng liên hệ IT- Admin hệ thống');
                //         });

                //     }
                // }

            }
        }

        return $validator;
    }
    public function store()
    {
        date_default_timezone_set("Asia/Ho_Chi_Minh");
        $team_id = "";

        //dd( $this->request->attachment_file);
        $validator = $this->validate_store();

        $failed = $validator->fails();
        $fields = $this->request->all();
        //dd($failed);
        if ($failed) {
            $this->errors = $validator->errors();
        } else {

            try {

                DB::beginTransaction();
                $user = new User();
                $user = auth()->user();
                $fields['user_id'] = $user->id;

                $group = Group::find($fields['group_id']);

                $fields['company_id'] =   $group->company_id;
                $fields['department_id'] = $group->department->id;

                //Cấp dãy số sau khi lưu thành công
                try {

                    $wl = WorkflowDefineBase::copy($fields['wlworkflow_id'], '1');
                    if ($wl->approve_team && $wl->approve_team > 0) {
                        $fields['team_id'] =  $wl->approve_team;
                    }

                    $fields['wlworkflow_id'] = $wl->id;
                    $fields['team_id'] =  $this->getTeam(null);

                    $document = WlworkflowDoc::create($fields);
                    $document->original_id = $wl->original_id;
                    $document->status = 0;
                    $document->save();

                    $documentType = DocumentType::find($document->document_type_id);
                    if ($documentType) {
                        $document->serial_num = DocumentSerials::getSerial(
                            Ultils::$MODULE_WORKFLOW,
                            $wl->wlworkflow_type_id,
                            $document->company_id,
                            Ultils::$MODULE_FORMAT_SERIAL_NUMBER,
                            true
                        );
                        $document->save();
                    }
                    //Lưu thông tin controls mở rộng
                    $controls = $fields['controls'];

                    for ($i = 0; $i < count($controls); $i++) {
                        $control = $controls[$i];
                        $dataobject = $wl->wldataobjects->where('refer_id', $control['id'])->first(); // Wldataobject::find($control['id']);

                        if ($dataobject) {
                            switch ($control['type']) {
                                case Ultils::$CONTROL_TYPE['NUMBER']:
                                case Ultils::$CONTROL_TYPE['USER']:
                                    $dataobject->value_num = $control['value'];
                                    $dataobject->save();
                                    break;

                                case Ultils::$CONTROL_TYPE['USERS']:
                                    $list = $control['value_list'];
                                    $order = 0;
                                    foreach ($list  as $value) {
                                        $dataobjectItem = new WldataobjectItms;
                                        $dataobjectItem->name = $dataobject->name . '' . $value;
                                        $dataobjectItem->wldataobject_id = $dataobject->id;
                                        $dataobjectItem->order = $order++;
                                        $dataobjectItem->value = $value;
                                        $dataobjectItem->refer_id = $control['id'];
                                        $dataobjectItem->select = 'X';
                                        $dataobject->items()->save($dataobjectItem);
                                    }
                                    break;
                                case Ultils::$CONTROL_TYPE['COMBOBOX']:
                                    $mapping_value = $control['value'];
                                    foreach ($dataobject->items as $new_item) {
                                        if ($new_item->refer_id == $mapping_value) {
                                            $mapping_value = $new_item->id;
                                            break;
                                        }
                                    }
                                    $dataobject->value = $mapping_value;
                                    $dataobject->save();
                                    break;

                                case Ultils::$CONTROL_TYPE['LIST']:
                                    $list = $control['value_list'];
                                    foreach ($list  as  $value) {

                                        $dataobjectItem = $dataobject->items->where('refer_id', $value)->first(); // WldataobjectItms::find($value);

                                        $dataobjectItem->select = 'X';
                                        $dataobjectItem->save();
                                    }
                                    break;
                                case Ultils::$CONTROL_TYPE['DATE']:
                                    $dataobject->value_date = $control['value'];
                                    $dataobject->save();
                                    break;
                                case Ultils::$CONTROL_TYPE['TIME']:
                                    $dataobject->value_time = $control['value'];
                                    $dataobject->save();
                                    break;
                                case Ultils::$CONTROL_TYPE['FILE']:
                                    $attachment_file = $control['attachment_file'];
                                    //  dd($fields);
                                    // dd($attachment_file);

                                    for ($j = 0; $j < count($attachment_file); $j++) {

                                        //chỉ lưu  các file mới
                                        if (!isset($attachment_file[$j]["id"])) {
                                            $file = new File();

                                            //    $file->name = $attachment_file[$j]["name"];
                                            //    //$ext = end(explode('.', $filename));
                                            //    // $file->ext = $attachment_file[$i]["ext"];
                                            //    $file->size = $attachment_file[$j]["size"];
                                            //    $file->user_id = $user->id;
                                            //    $strDate = date("Y"). "/" .date("m"). "/"  .date("d");
                                            //    $ext = substr($attachment_file[$j]["name"], strrpos($attachment_file[$j]["name"], '.') + 1);
                                            //    $name = "public/work/" .$strDate. "/" . uniqid() . '.' . $ext;

                                            //    $file->ext = $ext;
                                            //    $file->url = $name;
                                            //    $dataobject->attachment_file()->save($file);
                                            //    //dd($file);

                                            //    //$name = "/avatars/" . $user->username . '.' . $this->request->file['ext'];
                                            //    $base64_str = substr($attachment_file[$j]['base64'], strpos($attachment_file[$j]['base64'], ",") + 1);
                                            //    $image = base64_decode($base64_str);
                                            //    //file_put_contents(public_path().$name,  $image );
                                            //    Storage::disk('local')->put($name, $image);
                                            //    FacadesFileVault::encrypt($name);
                                            $file->name = $attachment_file[$j]["name"];

                                            $file->size = $attachment_file[$j]["size"];
                                            $file->user_id = $user->id;
                                            $strDate = date("Y") . "/" . date("m") . "/"  . date("d");
                                            $ext = substr($attachment_file[$j]["name"], strrpos($attachment_file[$j]["name"], '.') + 1);
                                            $name = "public/work/" . $strDate . "/" . uniqid() . '.' . $ext;

                                            $file->ext = $ext;
                                            $file->url = $name;
                                            $dataobject->attachment_file()->save($file);
                                            //dd($file);

                                            //$name = "/avatars/" . $user->username . '.' . $this->request->file['ext'];
                                            $base64_str = substr($attachment_file[$j]['base64'], strpos($attachment_file[$j]['base64'], ",") + 1);
                                            $image = base64_decode($base64_str);
                                            //file_put_contents(public_path().$name,  $image );
                                            Storage::disk('local')->put($name, $image);
                                            FacadesFileVault::encrypt($name);
                                        }
                                    }
                                    break;
                                default:
                                    $dataobject->value = $control['value'];
                                    $dataobject->save();
                                    break;
                            }
                            // if ($control['type'] == '6') {
                            //     $list = $control['value_list'];
                            //    foreach ($list  as  $value) {

                            //        $dataobjectItem = $dataobject->items->where('refer_id',$value)->first();// WldataobjectItms::find($value);
                            //        $dataobjectItem->select = 'X';
                            //        $dataobjectItem->save();
                            //    }
                            // }else {
                            //     //dd($control['id']);
                            //     //dd($wl->wldataobjects->where('refer_id',$control['id']));

                            //    //dd($dataobject);
                            //    $dataobject->value = $control['value'];
                            //    $dataobject->save();
                            // }
                        }
                    }

                    $shared_groups = $fields['shared_groups'];

                    for ($i = 0; $i < count($shared_groups); $i++) {
                        $shared = new Shared();
                        $shared->user_id =  $user->id;
                        $shared->object_id =  $shared_groups[$i];
                        $shared->type = 0;
                        $document->shareds()->save($shared);
                    }
                    $this->addTimeline($document, 'Tạo mới quy trình', 'WORKFLOWCREATE', $document->title, auth()->user());

                    if (!isset($fields['team_id'])) {
                        $documentBase = new WorkflowBase(null);
                        $documentBase->updatePhaseStatus($wl->current_phase, true);
                        $document->locked = 1;
                        $document->status = 2;
                        $document->send_date = date('Y-m-d h:i:s');
                        $document->save();
                    }
                } catch (\Exception $e1) {

                    $validator->errors()->add('serial_number', $e1->getMessage());
                    $this->errors = $validator->errors();

                    return null;
                }
                //$this->store_sub_data($document);

                DB::commit();
                return $document;
            } catch (\Exception $e) {
                DB::rollback();

                $this->errors = $e->getMessage();
                return null;
            }
        }
        return null;
    }

    public function show($id)
    {
        $current_user = Auth()->user();
        $document = WlworkflowDoc::with('user', 'document_type', 'company', 'department',  'team', 'team.users', 'approveds',  'attachment_file', 'timelines', 'shareds', 'shareds.group',  'reminders')->find($id);
        $this->wlworkflow =  Wlworkflow::find($document->wlworkflow_id);

        $document->controls = $this->getInitialControls();
        $document->jobs = $this->getJobReports($this->wlworkflow->current_phase, $current_user->id);
        $document->configs = $this->wlworkflow->configs;
        if ($this->wlworkflow->scope) {
            $document->scope = json_decode($this->wlworkflow->scope->json_value);
        }

        $document->activeConfig = $this->wlworkflow->activeConfig;
        $this->wlworkflow->currentPhase->load('members', 'configs');
        $document->currentPhase = $this->wlworkflow->currentPhase;
        $document->is_ready_to_complete_phase = $this->isReadyToCompletePhase($this->wlworkflow->currentPhase->id);
        $document->wlworkflow = $this->wlworkflow;

        $phases = array();
        $listPhases =  $this->wlworkflow->wlphase->sortBy('index');
        foreach ($listPhases as $phase) {
            $temp = Wlphase::find($phase->id);
            $temp->jobs = $this->getJobReports($phase->id, $current_user->id);
            array_push($phases, $temp);
        }
        $document->phases = $phases;

        if ($this->wlworkflow->approve_type == 1 || $this->wlworkflow->approve_type == 2) {
            if ($document->team !== null) {
                $document['has_approve_list'] = true;
            }
        }

        $role = User::find(auth()->user()->id);
        if ($role->hasRole('Administrator')) {
            $document['configurable'] = true;
        }
        $document['editable'] = auth()->user()->id == $document->user_id && $document->locked != 1;

        foreach ($document->shareds as  $shared) {
            switch ($shared->type) {
                case '0':
                    $shared->group;
                    break;
                case '1':
                    $shared->viewer;
                    break;
                default:
                    # code...
                    break;
            }
        }



        return $document;
    }

    public function edit($id)
    {
        $document = WlworkflowDoc::with('user', 'document_type', 'company', 'department',  'team', 'team.users', 'approveds', 'attachment_file', 'timelines', 'shareds', 'attachment_file')->find($id);
        $this->wlworkflow =  Wlworkflow::find($document->wlworkflow_id);

        ////ẩn Xử lý duyệt trên phase
        // $phaseInit = $this->wlworkflow->wlphase->where('index',-999)->first();       
        // if(($phaseInit->approve_type === 1 || $phaseInit->approve_type === 2) && $phaseInit->approveds && count($phaseInit->approveds) > 0 ){
        //    $wlworkflow_appdoc =  $phaseInit->approveds[count($phaseInit->approveds)-1];
        //    $document->team_init = $wlworkflow_appdoc->team;
        //    $document->team_init->load('users');

        // }
        $document->controls = $this->getInitialControls();

        return $document;
    }
    public function cancelWorkflow()
    {

        $document = WlworkflowDoc::find($this->request->id);

        if ($document->status == 2) { //Đã duyệt hoàn tất thì không thể huỷ
            return false;
        }
        $document->status = -1; //Đã huỷ
        if ($document->save()) {
            $this->addTimeline($document, 'Hủy quy trình', 'WORKFLOWCANCEL', $document->title, auth()->user());
            return true;
        }
        return false;
    }
    // protected function getTreeObj($root, &$list){


    //     if (count($root->childs) > 0) {

    //         foreach ($root->childs as $item) {
    //             $item->load('items');
    //             array_push($list,$item);
    //             if(count($item->childs) > 0 ){
    //                  $this->getTreeObj( $item,$list );
    //             }
    //         }
    //     }

    // }
    // //Kiểm tra node root thay đổi: không cho trỏ và các node con của nó
    // protected function validateRootChange($id,$index_after_by){

    //     $node = Wldataobject::find($id);
    //     if ($node == null ) {
    //         return true;
    //     }
    //     if ($id == $index_after_by) {
    //         return false;
    //     }
    //     $list = array();
    //     $this->getTreeObj( $node,$list );
    //     foreach ($list as   $value) {

    //         if ($index_after_by == $value->id) {
    //             return false;
    //         }
    //     }

    //     return true;


    // }
    protected function orderDataObjectList($object_list)
    {
        $data_object_process = new DataObjectProcess(null);

        $ordered_object_list = array();
        foreach ($object_list as $object) {
            if ($object->index_after_by == null || $object->index_after_by == "") {
                array_push($ordered_object_list, $object);
                $data_object_process->getTreeObj($object, $ordered_object_list);
            }
        }

        return $ordered_object_list;
    }
    public function update($id)
    {
        date_default_timezone_set("Asia/Ho_Chi_Minh");

        $validator = $this->validate_update($id);
        $failed = $validator->fails();
        $fields = $this->request->all();
        //kiểm tra thông tin hợp đồng nếu có , validate thêm
        $document = WlworkflowDoc::findOrFail($id);

        if ($failed) {
            $this->errors = $validator->errors();
        } else {

            try {

                if ($document) {
                    DB::beginTransaction();
                    $user = new User();
                    $user = auth()->user();
                    $fields['user_id'] = $user->id;

                    $group = Group::find($fields['group_id']);

                    $fields['company_id'] =   $group->company_id;
                    $fields['department_id'] = $group->department->id;

                    $fields['team_id'] = $this->getTeam($document);

                    $document->fill($fields);
                    $document->save();

                    //Lưu thông tin controls mở rộng

                    $controls = $fields['controls'];
                    for ($i = 0; $i < count($controls); $i++) {
                        $control = $controls[$i];
                        $dataobject = Wldataobject::find($control['id']);
                        switch ($control['type']) {

                            case Ultils::$CONTROL_TYPE['USERS']:
                                foreach ($dataobject->items as $item) {
                                    $item->delete();
                                }

                                $list = $control['value_list'];
                                $order = 0;
                                foreach ($list  as $value) {
                                    $dataobjectItem = new WldataobjectItms;
                                    $dataobjectItem->name = $dataobject->name . '' . $value;
                                    $dataobjectItem->wldataobject_id = $dataobject->id;
                                    $dataobjectItem->order = $order++;
                                    $dataobjectItem->value = $value;
                                    $dataobjectItem->refer_id = $control['id'];
                                    $dataobjectItem->select = 'X';
                                    $dataobject->items()->save($dataobjectItem);
                                }
                                break;

                            case Ultils::$CONTROL_TYPE['LIST']:
                                foreach ($dataobject->items as $item) {
                                    $item->select = '';
                                    $item->save();
                                }
                                $list = $control['value_list'];

                                foreach ($list  as  $value) {
                                    $dataobjectItem = WldataobjectItms::find($value);
                                    $dataobjectItem->select = 'X';
                                    $dataobjectItem->save();
                                }
                                break;
                            case Ultils::$CONTROL_TYPE['DATE']:
                                $dataobject->value_date = $control['value'];
                                $dataobject->save();
                                break;
                            case Ultils::$CONTROL_TYPE['TIME']:
                                $dataobject->value_time = $control['value'];
                                $dataobject->save();
                                break;

                            case Ultils::$CONTROL_TYPE['NUMBER']:
                            case Ultils::$CONTROL_TYPE['USER']:
                                $dataobject->value_num = $control['value'];
                                $dataobject->save();
                                break;

                            case Ultils::$CONTROL_TYPE['FILE']:
                                $attachment_file = $control['attachment_file'];
                                $attachment_file_removed = $control['attachment_file_removed'];
                                //dd($attachment_file_removed);
                                for ($j = 0; $j < count($attachment_file); $j++) {

                                    //chỉ lưu  các file mới
                                    if (!isset($attachment_file[$j]["id"])) {
                                        $file = new File();

                                        $file->name = $attachment_file[$j]["name"];
                                        //$ext = end(explode('.', $filename));
                                        // $file->ext = $attachment_file[$i]["ext"];
                                        $file->size = $attachment_file[$j]["size"];
                                        $file->user_id = $user->id;

                                        $strDate = date("Y") . "/" . date("m") . "/"  . date("d");
                                        $ext = substr($attachment_file[$j]["name"], strrpos($attachment_file[$j]["name"], '.') + 1);
                                        $name = "public/work/" . $strDate . "/" . uniqid() . '.' . $ext;

                                        $file->ext = $ext;
                                        $file->url = $name;
                                        $dataobject->attachment_file()->save($file);

                                        //$name = "/avatars/" . $user->username . '.' . $request->file['ext'];
                                        $base64_str = substr($attachment_file[$j]['base64'], strpos($attachment_file[$j]['base64'], ",") + 1);
                                        $image = base64_decode($base64_str);
                                        //file_put_contents(public_path().$name,  $image );
                                        Storage::disk('local')->put($name, $image);
                                        FacadesFileVault::encrypt($name);
                                    }
                                }
                                //xoá các file trong mảng del

                                for ($k = 0; $k < count($attachment_file_removed); $k++) {
                                    if (isset($attachment_file_removed[$k]["id"])) {
                                        $file = File::findOrFail($attachment_file_removed[$k]["id"]);
                                        if ($file) {

                                            Storage::delete($file->url . ".enc");
                                            $file->delete();
                                        }
                                    }
                                }

                            default:
                                $dataobject->value = $control['value'];
                                $dataobject->save();
                                break;
                        }
                    }

                    //Xoá
                    //save file
                    $attachment_file = $fields['attachment_file'];
                    $attachment_file_removed = $fields['attachment_file_removed'];
                    // dd($attachment_file);
                    for ($j = 0; $j < count($attachment_file); $j++) {

                        //chỉ lưu  các file mới
                        if (!isset($attachment_file[$j]["id"])) {
                            $file = new File();

                            $file->name = $attachment_file[$j]["name"];
                            //$ext = end(explode('.', $filename));
                            // $file->ext = $attachment_file[$i]["ext"];
                            $file->size = $attachment_file[$j]["size"];
                            $file->user_id = $user->id;

                            $strDate = date("Y") . "/" . date("m") . "/"  . date("d");
                            $ext = substr($attachment_file[$j]["name"], strrpos($attachment_file[$j]["name"], '.') + 1);
                            $name = "public/work/" . $strDate . "/" . uniqid() . '.' . $ext;

                            $file->ext = $ext;
                            $file->url = $name;
                            $document->attachment_file()->save($file);

                            //$name = "/avatars/" . $user->username . '.' . $request->file['ext'];
                            $base64_str = substr($attachment_file[$j]['base64'], strpos($attachment_file[$j]['base64'], ",") + 1);
                            $image = base64_decode($base64_str);
                            //file_put_contents(public_path().$name,  $image );
                            Storage::disk('local')->put($name, $image);
                            FacadesFileVault::encrypt($name);
                        }
                    }
                    //xoá các file trong mảng del

                    for ($k = 0; $k < count($attachment_file_removed); $k++) {
                        if (isset($attachment_file_removed[$k]["id"])) {
                            $file = File::findOrFail($attachment_file_removed[$k]["id"]);
                            if ($file) {

                                Storage::delete($file->url . ".enc");
                                $file->delete();
                            }
                        }
                    }
                    if ($document->shareds) {
                        foreach ($document->shareds as $shared) {
                            if ($shared->type == 0) {
                                $shared->delete();
                            }
                        }
                    }
                    $shared_groups = $fields['shared_groups'];
                    // dd($shareds);
                    for ($i = 0; $i < count($shared_groups); $i++) {
                        $shared = new Shared();
                        $shared->user_id =  $user->id;
                        $shared->object_id =  $shared_groups[$i];
                        $shared->type = 0;
                        $document->shareds()->save($shared);
                    }

                    // $this->update_sub_data($document);
                    DB::commit();

                    return $document;
                }
            } catch (\Exception $e) {
                DB::rollback();
                $this->errors = $e->getMessage();
            }
        }

        return null;
    }
    protected function validate_update($id)
    {

        $validator = Validator::make(
            $this->request->all(),
            [
                'document_type_id' => 'required',
                'group_id' => 'required',

                'title' => 'required|max:150'
            ],
            [
                'document_type_id.required' => "Loại chứng từ không được rỗng",
                'group_id.required' => "Nhóm không được rỗng",
                'title.required' => "Tiêu đề không được rỗng",
                'title.max' => "Tiêu đề tối đa 150 kí tự",
            ]
        );

        $fields = $this->request->all();

        // dd( $this->request->amount);
        $document = WlworkflowDoc::findOrFail($id);

        if ($document->status == 2) {
            $validator->after(function ($validator) {
                $validator->errors()->add('chungtuhoantat', 'Chứng từ đã hoàn tất. Vui lòng không cập nhật.');
            });
        }
        if ($document->status == -1) {
            $validator->after(function ($validator) {
                $validator->errors()->add('chungtuhuy', 'Chứng từ đã huỷ. Vui lòng không cập nhật.');
            });
        }

        if ($document->status == 2) {

            $validator->after(function ($validator) {
                $validator->errors()->add('chungtuhoantat', 'Chứng từ đã hoàn tất. Vui lòng không cập nhật.');
            });
        }
        if ($document->status == -1) {

            $validator->after(function ($validator) {
                $validator->errors()->add('chungtuhuy', 'Chứng từ đã huỷ. Vui lòng không cập nhật.');
            });
        }
        if ($document->locked == 1) {

            $validator->after(function ($validator) {
                $validator->errors()->add('locked', 'Chứng từ đang bị khoá. Vui lòng không cập nhật.');
            });
        }
        $user = new User();
        $user = auth()->user();

        $documentType = DocumentType::find($this->request->document_type_id);

        if ($documentType->approve_manual == '1') {
            //$this->request['team_id'] =  $this->createTeam();
            $is_sign = false;
            if ($this->request->team_users == null) {
                $validator->after(function ($validator) {
                    $validator->errors()->add('sign', 'Vui lòng chọn người phê duyệt.');
                });
            } else {
                foreach ($this->request->team_users as  $value) {
                    if ($value['user_id'] != '' && $value['step'] == '3' && $value['sign'] == '1') {
                        $is_sign = true;
                    }
                }
                if (!$is_sign) {
                    $validator->after(function ($validator) {
                        $validator->errors()->add('sign', 'Vui lòng chọn người phê duyệt.');
                    });
                } else {
                    // $this->assign_user($document->team_id,$this->request->team_users);
                }
            }
        } else {

            // $group = Group::find($fields['group_id']);

            // if($group->company){
            //     $company_id =  $group->company->id;

            //     if ($group->company->id != $document->company_id ) {
            //         $validator->after(function ($validator) use($company_id,$document) {
            //                $validator->errors()->add('phantuyen', 'Số chứng từ đã phát sinh cho công ty '.$document->company_id.' vui lòng không chuyển chứng từ sang công ty '. $company_id);
            //           });
            //   }


            // }
        }



        return $validator;
    }
    public function destroy($id)
    {
    }
    public function save_report_validate()
    {
        $fields = $this->request->all();

        $validator = Validator::make($this->request->all(), [], []);
        $controls = $fields['reports'];

        foreach ($controls as  $obj) {

            $wlobj = Wldataobject::find($obj['id']);
            if (!$wlobj) {
                $validator->after(function ($validator) use ($obj) {
                    $validator->errors()->add($obj['control_id'], $obj['custom_label_text'] . ' chưa được cấu hình');
                });
            } else {

                if ($obj['require_validation']) {

                    switch ($obj['type']) {
                        case Ultils::$CONTROL_TYPE['LIST']:
                            $value_list = $obj['value_list'];

                            if (count($value_list) == 0) {
                                $validator->after(function ($validator) use ($obj) {
                                    $validator->errors()->add($obj['control_id'], $obj['custom_label_text'] . ' không được rỗng');
                                });
                            }
                            break;
                        case Ultils::$CONTROL_TYPE['NUMBER']:
                            if ($obj['value'] == null || $obj['value'] = "" || $obj['value'] = "0" || !is_numeric($obj['value'])) {
                                $validator->after(function ($validator) use ($obj) {
                                    $validator->errors()->add($obj['control_id'], $obj['custom_label_text'] . ' không được rỗng');
                                });
                            }
                            break;

                        case Ultils::$CONTROL_TYPE['DATE']:
                            if ($obj['value'] == null || $obj['value'] = "" || !Ultils::isDate($obj['value'])) {
                                $validator->after(function ($validator) use ($obj) {
                                    $validator->errors()->add($obj['control_id'], $obj['custom_label_text'] . ' không được rỗng');
                                });
                            }
                            break;
                        case Ultils::$CONTROL_TYPE['TIME']:
                            if ($obj['value'] == null || $obj['value'] = "" || !Ultils::isDate($obj['value'])) {
                                $validator->after(function ($validator) use ($obj) {
                                    $validator->errors()->add($obj['control_id'], $obj['custom_label_text'] . ' không được rỗng');
                                });
                            }
                            break;
                        case Ultils::$CONTROL_TYPE['FILE']:

                            $attachment_file = $obj['attachment_file'];

                            if (count($attachment_file) == 0) {
                                $validator->after(function ($validator) use ($obj) {
                                    $validator->errors()->add($obj['control_id'], $obj['custom_label_text'] . ' không được rỗng');
                                });
                            }

                            break;

                        default:

                            if (!isset($obj['value']) || $obj['value'] == null || $obj['value'] = "") {

                                $validator->after(function ($validator) use ($obj) {
                                    $validator->errors()->add($obj['control_id'], $obj['custom_label_text'] . ' không được rỗng');
                                });
                            }
                            break;
                    }
                }
            }
        }

        return $validator;
    }

    public function submitReport($id, $request)
    {
        $obj = Wldataobject::find($id);
        if ($request instanceof Request) {
            $fields = $request->all();
        } else {
            $fields = $request;
        }
        $user = Auth()->user();

        // dd($obj->wldatatype_id);
        switch ($obj->wldatatype_id) {
            case Ultils::$CONTROL_TYPE['FILE']:
                $attachment_file = $fields['attachment_file'];
                $attachment_file_removed = $fields['attachment_file_removed'];

                for ($j = 0; $j < count($attachment_file); $j++) {

                    //chỉ lưu  các file mới
                    if (!isset($attachment_file[$j]["id"])) {
                        $file = new File();

                        $file->name = $attachment_file[$j]["name"];
                        $file->size = $attachment_file[$j]["size"];
                        $file->user_id = $user->id;

                        $strDate = date("Y") . "/" . date("m") . "/"  . date("d");
                        $ext = substr($attachment_file[$j]["name"], strrpos($attachment_file[$j]["name"], '.') + 1);
                        $name = "public/work/" . $strDate . "/" . uniqid() . '.' . $ext;

                        $file->ext = $ext;
                        $file->url = $name;

                        $obj->attachment_file()->save($file);

                        $base64_str = substr($attachment_file[$j]['base64'], strpos($attachment_file[$j]['base64'], ",") + 1);
                        $image = base64_decode($base64_str);
                        Storage::disk('local')->put($name, $image);
                        FacadesFileVault::encrypt($name);
                        $obj->load('attachment_file');
                    }
                }
                for ($k = 0; $k < count($attachment_file_removed); $k++) {

                    if (isset($attachment_file_removed[$k]["id"])) {

                        $file = File::find($attachment_file_removed[$k]["id"]);

                        if ($file) {

                            Storage::delete($file->url . ".enc");
                            $file->delete();
                        }
                    }
                }
                break;
            case Ultils::$CONTROL_TYPE['USERS']:
            case Ultils::$CONTROL_TYPE['LIST']:
                $items = $obj->items;
                $list = $fields['selecteds'] ?? $fields['value_list'];

                foreach ($items  as  $item) {
                    $item->select = '';
                    foreach ($list as   $value) {
                        if ($value == $item->id) {
                            $item->select = 'X';
                        }
                    }

                    $item->save();
                }
                break;
            case Ultils::$CONTROL_TYPE['NUMBER']:
            case Ultils::$CONTROL_TYPE['USER']:
                $obj->value_num = $fields['value'];
                $obj->save();
                break;
            case Ultils::$CONTROL_TYPE['DATE']:
                $obj->value_date = $fields['value'];
                $obj->save();
                break;
            case Ultils::$CONTROL_TYPE['TIME']:
                $obj->value_time =  $fields['value'];
                $obj->save();
                break;
            case Ultils::$CONTROL_TYPE['BOOLEAN']:
                $obj->value_boolean =  $fields['value'];
                $obj->save();
                break;
            case Ultils::$CONTROL_TYPE['APPROVE']:
                $obj->value_boolean = $fields['value']; // $request->control_value;
                $obj->value_date = date('Y-m-d');
                $obj->value_time = date('H:i:s');
                $obj->save();
                break;
            default:
                $obj->value = $fields['value']; // $request->control_value;
                $obj->save();
                break;
        }

        return $obj;
    }

    public function submitReports()
    {

        $validator = $this->save_report_validate();
        $failed = $validator->fails();

        $data = $this->request->all();
        if ($failed) {
            $this->errors = $validator->errors();
        } else {
            $current_user = Auth()->user();

            $job = Wljob::findOrFail($data['id']);
            if ($job) {
                if (!$job->is_completed) {
                    $job->is_completed = true;
                    $job->date_finished = date('Y-m-d h:i:s');
                } else {
                    $job->is_completed = false;
                    $job->date_finished = null;

                    foreach ($job->navigates as $navigate_job) {
                        $navigating_job = Wljob::find($navigate_job->navigate_job_id);
                        $navigating_job->is_navigated = null;
                        $navigating_job->save();
                    }
                }
                $job->save();

                $reportControls = array();
                foreach ($data['reports'] as $key => $value) {

                    $object = $this->submitReport($value['id'], $value);
                    array_push($reportControls, $object);
                }
                $job->reports = $reportControls;

                $wlphase = Wlphase::find($job->wlphase_id);
                $wlworkflow = Wlworkflow::find($wlphase->wlworkflow_id);
                $wlworkflowDoc = WlworkflowDoc::where('wlworkflow_id', $wlphase->wlworkflow_id)->first();

                $documentBase = new  WorkflowBase(new Request());
                $documentBase->wlworkflow = $wlworkflow;
                //Trả lại đúng định dạng của job
                $returnJobs = array();

                $jobs = $documentBase->getJobReports($job->wlphase_id, $current_user->id);
                for ($i = 0; $i < count($jobs); $i++) {
                    if ($jobs[$i]->id == $job->id) {
                        $job = $jobs[$i];
                        array_push($returnJobs, $job);
                    } else {
                        // Cập nhật lại các job điều hướng job này
                        foreach ($jobs[$i]->navigates as $navigate_job) {
                            if ($navigate_job->navigate_job_id == $job->id) {
                                array_push($returnJobs, $jobs[$i]);
                            }
                        }

                        // Cập nhật lại các job phụ thuộc job này
                        foreach ($jobs[$i]->dependencies as $depend_job) {
                            if ($depend_job->dependjobid == $job->id) {
                                array_push($returnJobs, $jobs[$i]);
                            }
                        }
                    }
                }
                $actionUser = auth()->user();
                $title = '';
                $icon = '';
                if ($job->is_completed) {
                    $title = auth()->user()->name;
                    $icon = 'REPORTSUBMIT';
                } else {
                    $title = auth()->user()->name;
                    $icon = 'REPORTUNSUBMIT';
                }
                $this->addTimeline($wlworkflowDoc, $title, $icon, $job->name, $actionUser);


                $original_job = Wljob::findOrFail($data['id']);
                $reminder = new RemindJobsAfterCompleteJob($wlworkflowDoc, $original_job, $actionUser);
                dispatch($reminder);

                return $returnJobs;
            }
        }

        return null;
    }
    public function submitApprovements()
    {
        $data = $this->request->all();

        $current_user = auth()->user();
        $job = Wljob::findOrFail($data['id']);

        if ($job) {
            if (!$job->is_completed) {
                $is_all_accepted = true;
                $modified_jobs = array();

                foreach ($data['responses'] as $response) {
                    if ($response['is_accepted'] == false) {
                        $is_all_accepted = false;

                        $depend_job = WlJob::findOrFail($response['job_id']);

                        if ($depend_job) {
                            // Kiểm tra xem job được phản hồi có đúng là phụ thuộc với job hiện tại không
                            $is_depended = WljobDependency::where('dependjobid', $depend_job->id)->where('jobid', $job->id)->first();
                            if ($is_depended) {
                                // Nếu job đã hoàn thành thì thu hồi trở về
                                if ($depend_job->is_completed) {
                                    $depend_job->is_completed = false;
                                    $depend_job->date_finished = null;

                                    $feedback = new WlworkflowJobDetail();
                                    $feedback->user_id =  $current_user->id;
                                    $feedback->content =  $response['feedback'];
                                    $depend_job->details()->save($feedback);
                                    $depend_job->save();

                                    array_push($modified_jobs, $depend_job->id);
                                }
                            }
                        }
                    }
                }
                if ($is_all_accepted) {
                    $job->is_completed = true;
                    $job->is_denied = false;
                    $job->date_finished = date('Y-m-d h:i:s');
                    $job->save();
                } else {
                    $job->is_denied = true;
                    $job->save();
                }

                $wlphase = Wlphase::find($job->wlphase_id);
                $wlworkflow = Wlworkflow::find($wlphase->wlworkflow_id);
                $wlworkflowDoc = WlworkflowDoc::where('wlworkflow_id', $wlphase->wlworkflow_id)->first();

                $documentBase = new  WorkflowBase(new Request());
                $documentBase->wlworkflow = $wlworkflow;
                //Trả lại đúng định dạng của job
                $updated_jobs = array();

                $jobs = $documentBase->getJobReports($job->wlphase_id, $current_user->id);
                for ($i = 0; $i < count($jobs); $i++) {
                    $current_job = $jobs[$i];
                    if ($current_job->id == $job->id || in_array($current_job->id, $modified_jobs)) {
                        $job = $current_job;
                        array_push($updated_jobs, $job);
                    } else {
                        foreach ($current_job->dependencies as $depend_job) {
                            if ($depend_job->dependjobid == $job->id) {
                                array_push($updated_jobs, $current_job);
                            }
                        }
                    }
                }


                $original_job = Wljob::findOrFail($data['id']);
                $reminder = new RemindJobsAfterCompleteJob($wlworkflowDoc, $original_job, $current_user);
                dispatch($reminder);

                return $updated_jobs;
            }
        }
    }
    public function sendApprovement($is_accepted)
    {
        $data = $this->request->all();

        $job = Wljob::findOrFail($data['job_id']);

        if ($job) {
            $current_user = Auth()->user();
            if (!$job->is_completed) {
                if ($is_accepted) {
                    $job->is_denied = false;
                    $job->date_finished = date('Y-m-d h:i:s');

                    $job->save();
                } else {
                    $job->is_denied = true;
                    $job->is_completed = true;
                    $job->date_finished = date('Y-m-d h:i:s');

                    $user = auth()->user();
                    $feedback = new WlworkflowJobDetail();
                    $feedback->user_id = $user->id;
                    $feedback->content =  $data['note'];
                    $job->details()->save($feedback);
                    $job->save();
                }

                $wlphase = Wlphase::find($job->wlphase_id);
                $wlworkflow = Wlworkflow::find($wlphase->wlworkflow_id);

                $documentBase = new  WorkflowBase(new Request());
                $documentBase->wlworkflow = $wlworkflow;
                //Trả lại đúng định dạng của job
                $updated_jobs = array();

                $jobs = $documentBase->getJobReports($job->wlphase_id, $current_user->id);
                for ($i = 0; $i < count($jobs); $i++) {
                    $current_job = $jobs[$i];
                    if ($current_job->id == $job->id) {
                        $job = $current_job;
                        array_push($updated_jobs, $job);
                    } else {
                        foreach ($current_job->dependencies as $depend_job) {
                            if ($depend_job->dependjobid == $job->id) {
                                array_push($updated_jobs, $current_job);
                            }
                        }
                    }
                }
                return $updated_jobs;
            }
        }
    }
    public function navigatingJobs()
    {
        try {
            $data = $this->request->all();

            $original_job = Wljob::findOrFail($data['job_id']);
            $job = Wljob::findOrFail($data['job_id']);

            if ($job) {
                $current_user = Auth()->user();
                $navigated_jobs = array();

                foreach ($data['navigate_jobs'] as $navigating_job) {
                    $navigate_job = WlJob::findOrFail($navigating_job['job_id']);

                    if ($navigate_job) {
                        // Kiểm tra xem job được điều phối có đúng là được điều phối bằng job hiện tại không
                        $can_be_navigated = WlworkflowJobNavigate::where('navigate_job_id', $navigate_job->id)->where('job_id', $job->id)->first();
                        if ($can_be_navigated) {
                            $navigate_job->is_navigated = $navigating_job['is_navigated'];
                            $navigate_job->save();

                            array_push($navigated_jobs, $navigate_job->id);
                        }
                    }
                }

                $job->is_completed = true;
                $job->date_finished = date('Y-m-d h:i:s');
                $job->save();

                $wlphase = Wlphase::find($job->wlphase_id);
                $wlworkflow = Wlworkflow::find($wlphase->wlworkflow_id);
                $wlworkflowDoc = WlworkflowDoc::where('wlworkflow_id', $wlphase->wlworkflow_id)->first();

                $documentBase = new  WorkflowBase(new Request());
                $documentBase->wlworkflow = $wlworkflow;
                //Trả lại đúng định dạng của job
                $updated_jobs = array();

                $jobs = $documentBase->getJobReports($job->wlphase_id, $current_user->id);
                for ($i = 0; $i < count($jobs); $i++) {
                    $current_job = $jobs[$i];
                    if ($current_job->id == $job->id || in_array($current_job->id, $navigated_jobs)) {
                        $job = $current_job;
                        array_push($updated_jobs, $job);
                    } else {
                        foreach ($current_job->dependencies as $depend_job) {
                            if ($depend_job->dependjobid == $job->id) {
                                array_push($updated_jobs, $current_job);
                            }
                        }
                    }
                }


                $original_job = Wljob::findOrFail($data['job_id']);
                $reminder = new RemindJobsAfterCompleteJob($wlworkflowDoc, $original_job, $current_user);
                dispatch($reminder);

                return $updated_jobs;
            }
        } catch (\Throwable $th) {
            dd($th);
        }
    }

    public function validate_create_job()
    {
        $fields = $this->request->all();

        $validator = Validator::make($this->request->all());
        $controls = $fields['reports'];

        foreach ($controls as  $obj) {

            $wlobj = Wldataobject::find($obj['id']);
            if (!$wlobj) {
                $validator->after(function ($validator) use ($obj) {
                    $validator->errors()->add($obj['control_id'], $obj['custom_label_text'] . ' chưa được cấu hình');
                });
            } else {

                if ($obj['require_validation']) {

                    switch ($obj['type']) {
                        case Ultils::$CONTROL_TYPE['LIST']:
                            $value_list = $obj['value_list'];

                            if (count($value_list) == 0) {
                                $validator->after(function ($validator) use ($obj) {
                                    $validator->errors()->add($obj['control_id'], $obj['custom_label_text'] . ' không được rỗng');
                                });
                            }
                            break;
                        case Ultils::$CONTROL_TYPE['NUMBER']:
                            if ($obj['value'] == null || $obj['value'] = "" || $obj['value'] = "0" || !is_numeric($obj['value'])) {
                                $validator->after(function ($validator) use ($obj) {
                                    $validator->errors()->add($obj['control_id'], $obj['custom_label_text'] . ' không được rỗng');
                                });
                            }
                            break;

                        case Ultils::$CONTROL_TYPE['DATE']:
                            if ($obj['value'] == null || $obj['value'] = "" || !Ultils::isDate($obj['value'])) {
                                $validator->after(function ($validator) use ($obj) {
                                    $validator->errors()->add($obj['control_id'], $obj['custom_label_text'] . ' không được rỗng');
                                });
                            }
                            break;
                        case Ultils::$CONTROL_TYPE['TIME']:
                            if ($obj['value'] == null || $obj['value'] = "" || !Ultils::isDate($obj['value'])) {
                                $validator->after(function ($validator) use ($obj) {
                                    $validator->errors()->add($obj['control_id'], $obj['custom_label_text'] . ' không được rỗng');
                                });
                            }
                            break;
                        case Ultils::$CONTROL_TYPE['FILE']:

                            $attachment_file = $obj['attachment_file'];

                            if (count($attachment_file) == 0) {
                                $validator->after(function ($validator) use ($obj) {
                                    $validator->errors()->add($obj['control_id'], $obj['custom_label_text'] . ' không được rỗng');
                                });
                            }

                            break;

                        default:

                            if (!isset($obj['value']) || $obj['value'] == null || $obj['value'] = "") {

                                $validator->after(function ($validator) use ($obj) {
                                    $validator->errors()->add($obj['control_id'], $obj['custom_label_text'] . ' không được rỗng');
                                });
                            }
                            break;
                    }
                }
            }
        }

        return $validator;
    }

    public function createNewJob()
    {
        $data = $this->request->all();
        //$validator = $this->validate_create_job();
        $failed = false; // || $validator->fails();

        if ($failed) {
            //$this->errors = $validator->errors();
        } else {
            $current_user = Auth()->user();
            try {
                DB::beginTransaction();
                $data['unique_name'] = Ultils::name_to_unique_name($data['name']);
                $data['assign_user'] = auth()->user()->id;

                $job = Wljob::create($data);
                if ($job) {
                    $new_dependencies = $data['dependencies'];

                    foreach ($new_dependencies as $new_dependency_id) {
                        $new_dependency = array();
                        $new_dependency['jobid'] = $job->id;
                        $new_dependency['dependjobid'] = $new_dependency_id;
                        WljobDependency::create($new_dependency);
                    }

                    if ($data['job_type_id'] == 1) {
                        $current_phase = Wlphase::find($job->wlphase_id);
                        if ($current_phase) {
                            $report_field = Wldataobject::create([
                                'name' => 'Nội dung',
                                'unique_name' => Ultils::name_to_unique_name('Nội dung'),
                                'wlworkflow_id' => $current_phase->wlworkflow_id,
                                'wlphase_id' =>  $job->wlphase_id,
                                'wljob_id' => $job->id,
                                'wldatatype_id' => Ultils::$CONTROL_TYPE['TEXTAREA'],
                                'descrption' => 'Nhập nội dung báo cáo',
                                'order' => 0,
                                'require' => true
                            ]);

                            $attachment_field = Wldataobject::create([
                                'name' => 'File đính kèm',
                                'unique_name' => Ultils::name_to_unique_name('File đính kèm'),
                                'wlworkflow_id' => $current_phase->wlworkflow_id,
                                'wlphase_id' =>  $job->wlphase_id,
                                'wljob_id' => $job->id,
                                'wldatatype_id' => Ultils::$CONTROL_TYPE['FILE'],
                                'descrption' => 'Tải lên file đính kèm',
                                'order' => 1,
                                'require' => false
                            ]);
                        }
                    }

                    $document = WlworkflowDoc::where('wlworkflow_id', $job->wlphase->wlworkflow_id)->first();
                    WorkflowEmailGate::sendNewJobNotice($document, $job, []);
                }

                DB::commit();

                return $this->getJobFullData($job, $current_user->id);
            } catch (\Throwable $th) {
                DB::rollback();

                $this->errors = $th->getMessage();
                return null;
            }
        }
    }

    public function sendNewResponse()
    {
        $data = $this->request->all();
        $failed = false; // || $validator->fails();

        if ($failed) {
            //$this->errors = $validator->errors();
        } else {
            $current_user = Auth()->user();
            try {
                DB::beginTransaction();
                $data['unique_name'] = Ultils::name_to_unique_name($data['name']);
                $data['action_user'] = auth()->user()->id;
                $data['is_completed'] = true;
                $data['date_finished'] = date('Y-m-d h:i:s');

                $job = Wljob::create($data);
                if ($job) {
                    $email_to = $data['email_to'];

                    $email_ccs = isset($data['email_ccs']) ? $data['email_ccs'] : [];

                    $document = WlworkflowDoc::where('wlworkflow_id', $job->wlphase->wlworkflow_id)->first();
                    WorkflowEmailGate::sendNewResponse($document, $job, $email_to, $email_ccs);
                }

                DB::commit();

                return $this->getJobFullData($job, $current_user->id);
            } catch (\Throwable $th) {
                DB::rollback();

                $this->errors = $th->getMessage();
                return null;
            }
        }
    }

    public static function getDateDue($wljob_id)
    {
        //Ngày tới hạn sẽ tính từ lúc nào ?
        //1. Ngày qui trình bắt đầu
        //2. Ngày giai đoạn bắt đầu
        //3. Ngày tạo job

        $wljob = Wljob::find($wljob_id);

        $str = null;
        if ($wljob->date_expected != null) {
            $date = date_create($wljob->date_expected);


            // if($wljob->time_execution != null ){
            //    date_add($date,date_interval_create_from_date_string($wljob->time_execution. ' hours'));
            // }
            $str = date_format($date, "Y-m-d H:i:s");
            $str = str_replace("00:00:00", "", $str);
        }
        return  $str;
    }

    public function addTimeline($document, $title, $icon, $content, $user)
    {
        $document->timelines()->save(
            new Timeline([
                'title' => $title,
                'icon' => Ultils::$WORKFLOW_ICONS[$icon],
                'content' => $content,
                'user_id' => $user->id
            ])
        );
    }

    abstract protected function getTeam($obj);
    abstract protected function getInitialControls();
    abstract protected function getJobReports($phase_id, $user_id);
    abstract protected function updatePhaseStatus($wlphase_id, $is_finished);
    abstract protected function changeWorkflowPhase($wlphase_id);
    abstract protected function assignJob($wljob_id, $user_id, $note, $emails_to);
    abstract protected function takeJob($wljob_id);
    abstract protected function abandonJob($wljob_id);
    abstract protected function isReadyToCompletePhase($wlphase_id);
    abstract protected function isFinishedPhaseReport($report);
    abstract protected function convertObjectToControlValue($object, $get_reference_value);
    abstract protected function getDependencyUndoneJobs($wljob);
    abstract protected function hasAnyJobCompletedDependenciesOn($wljob);
    abstract protected function checkJobPrivilegesStatus($job, $interacting_user_id);
    abstract protected function getAllObjectsByJob($job_id);
    abstract protected function getJobFullData($job, $user_id);
    abstract protected function convertObjectsToControls($objectList, $get_reference_value);
    abstract protected function updateWorkflowApproveSetting($workflowid, $approve_type, $approve_teamid, $approve_teamusers, $sub_approve_type, $sub_approve_value, $default_group);
}
