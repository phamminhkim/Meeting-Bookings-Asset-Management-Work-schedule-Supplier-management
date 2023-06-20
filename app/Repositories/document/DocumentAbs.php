<?php

namespace App\Repositories\document;

use App\Models\document\Document;
use App\Models\shared\Approved;
use App\Models\shared\DocumentType;
use App\Models\shared\File;
use App\Models\shared\Filesign;
use App\Models\shared\Filesigninfo;
use App\Models\shared\Group;
use App\Models\shared\Reminder;
use App\Models\shared\Shared;
use App\Models\shared\Team;
use App\Models\shared\Timeline;
use App\Repositories\approve\ApproveRoutingHelper;
use App\Repositories\DocumentSerials;
use App\Traits\HasTeamManual;
use App\Ultils\Ultils;
use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use PhpParser\Node\Stmt\TryCatch;
use SoareCostin\FileVault\Facades\FileVault as FacadesFileVault;

abstract class DocumentAbs
{
    use HasTeamManual;

    protected $document;
    protected $request;
    protected $errors;
    protected $message;
    protected $layout;
    protected $form_name; //fix

    protected $documentType;
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
        $result = array();
        $result['data'] = array();
        $result['success'] = "0";
        $query = Document::query();
        $user = new User();
        $user = Auth()->user();
        try {

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

            $status  = explode(',', $this->request->status);
            $status_null = false;
            for ($i = 0; $i < count($status); $i++) {
                if ($status[$i] == 0) {
                    $status_null = true;
                }
            }

            if ($this->request->filled('status')) {
                // if ($this->request->status == 0) {
                //     $query = $query->where('status', null);
                // } else {
                //     $query = $query->whereIn('status',  $status);
                // }
                // dd($status );
                if ($status_null) {
                    $query = $query->Where(function ($q) use ($status) {
                        $q = $q->whereIn('status',  $status);
                        $q = $q->orWhereNull('status');
                    });
                } else {
                    $query = $query->whereIn('status',  $status);
                }

                //  dd( $query);
            }
            //Lấy theo loại chứng từ
            if ($this->request->filled('document_type_id') && $this->request->document_type_id != "undefined" && $this->request->document_type_id != "null") {
                $query = $query->whereIn('document_type_id', explode(",", $this->request->document_type_id));
            }
            if ($this->request->filled('company_id')) {
                $query = $query->where('company_id', $this->request->company_id);
            }
            if ($this->request->filled('department_id')) {
                $query = $query->where('department_id', $this->request->department_id);
            }
            if ($this->request->filled('serial_num')) {
                // dd($this->request->serial_num);
                $query = $query->where('serial_num', 'like', '%' . $this->request->serial_num . '%');
            }
            if ($this->request->filled('docbrowser_type_id')) {

                $query = $query->whereIn('docbrowser_type_id',  explode(",", $this->request->docbrowser_type_id));
            }
            //lọc theo phạm vi: của tôi, theo nhóm, .....
            if ($this->request->filled('visibility')) {
                switch ($this->request->visibility) {
                    case '0': //owner
                        $query = $query->where('user_id', $user->id);
                        break;
                        // case '1'://group
                        //     $query = $query->whereNotNull('serial_num');
                        //     break;
                }
            } else {
                //Lấy những chứng từ của nhóm ( not nul Serial_num) và của tôi ( bao gồm cả null Serial_num)
                $query = $query->where(function ($q) use ($user) {
                    $q = $q->whereNotNull('serial_num');
                    $q = $q->orWhere(function ($q1) use ($user) {
                        $q1 = $q1->whereNull('serial_num');
                        $q1 = $q1->where('user_id', $user->id);
                    });
                    // $q = $q->where('user_id',$user->id);
                });
            }

            if ($this->request->feedback === "true") {
                $query = $query->whereHas('approveds', function (Builder $query) {
                    $query->where('note', '<>', null);
                });
            }
        } catch (Exception $e) {
            $this->errors = $e->getMessage();
        }


        $user = new User();
        $user = User::find(auth()->user()->id);

        $all_columns = Schema::getColumnListing('documents');
        $exclude_columns = ['content'];
        $get_columns = array_diff($all_columns, $exclude_columns);

        $query = $query->select($get_columns);

        $withModel = ['approveds', 'user', 'reminders', 'docbrowser_type'];

        if ($user->hasPermission('review_all_document')) {
        } else if ($user->hasPermission('review_company_document')) {

            $company_id = $user->company_id;
            $group_ids = $user->groups->pluck('id')->flatten();
            $query = $query->where(function ($q) use ($company_id, $group_ids) {
                $q->where('company_id', $company_id)
                    ->orWhereIn('group_id', $group_ids);
            });
            // $company_id = $user->company_id;
            // $document = $query->where('company_id',$company_id)->orderBy('id', 'desc')->with($withModel)->withCount('approveds')->get();
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

            $group_ids = $user->groups->pluck('id')->flatten(); //pluck('id')->flatten();
            $user_id = $user->id;
            //Lấy các chứng từ shared group
            $query = $query->where(function ($q) use ($group_ids, $user_id) {
                $q->whereIn('group_id', $group_ids)
                    ->orWhereHas('shareds', function ($q) use ($group_ids, $user_id) {
                        //$q->whereIn('object_id',$group_ids)->where('type',0);

                        $q->where(function ($qu) use ($group_ids) {
                            $qu->whereIn('object_id', $group_ids)->where('type', 0);
                        })->orwhere(function ($qu) use ($user_id) {
                            $qu->where('object_id', $user_id)->where('type', 1);
                        });
                    });
            });
            // $document = $query->where('user_id', $user->id)->orderBy('id', 'desc')->with($withModel)->withCount('approveds')->get();
            // $document = $query->whereIn('group_id', $group_ids)->orderBy('id', 'desc')->with($withModel)->withCount('approveds')->get();
        }

        $query = $query->orderBy('id', 'desc')->with($withModel);

        $document = $query->get();

        foreach ($document as $currentDocument) {
            foreach ($currentDocument->approveds as $approve) {
                $approve_user = User::find($approve->user_id);

                if ($approve->finished == 0 && $approve->release == 0 && $approve->online == "X") {
                    if ($approve_user) {
                        $currentDocument->waitingApproval = $approve_user->name;
                    }
                }
                if ($approve->checkout == null) {
                    if ($approve_user) {
                        $currentDocument->waitApprove = $approve_user->name;
                    }
                }

                if ($approve->note != null) {
                    $currentDocument->noting =  $currentDocument->noting . "<br/>" . $approve->note;
                }
            }
            if ($currentDocument->approveds && count($currentDocument->approveds) > 0) {

                $finalApprove = $currentDocument->approveds[count($currentDocument->approveds) - 1];
                if ($finalApprove  && $finalApprove->release == 1) {
                    $currentDocument->isRelease = 'X';
                }
            }
            unset($currentDocument['approveds']);
        }

        return $document;
    }
    protected function validate_store()
    {

        $validator = Validator::make(
            $this->request->all(),
            [

                'document_type_id' => 'required',
                'group_id' => 'required',
                'amount' => 'required',
                'content' => 'required',
                'title' => 'required|max:150',

            ],
            [
                'document_type_id.required' => "Loại chứng từ không được rỗng",
                'group_id.required' => "Nhóm không được rỗng",
                'amount.required' => "Số tiền không được rỗng",
                'content.required' => "Nội dung không được rỗng",
                'title.required' => "Tiêu đề không được rỗng",
                'title.max' => "Tiêu đề tối đa 150 kí tự",
            ]
        );

        $fields = $this->request->all();
        //dd( $fields);
        // dd($fields["attachment_file"]);
        $user = new User();
        $user = auth()->user();
        // if (!$user->company) {
        //     $validator->after(function ($validator) {
        //         $validator->errors()->add('company', 'User chưa được cấu hình công ty');

        //     });

        // }

        // if (!$user->department) {

        //     $validator->after(function ($validator) {
        //         $validator->errors()->add('department', 'User chưa được cấu hình phòng ban');

        //     });
        // }

        $documentType = DocumentType::find($this->request->document_type_id);
        if ($documentType->approve_manual == '1') {
            $this->request['team_id'] =  $this->createTeam();
            $this->assign_user($this->request['team_id'], $this->request->team_users);

            if ($this->request['team_id'] == '') {
                $validator->after(function ($validator) {
                    $validator->errors()->add('team_id', 'Tạo Team bị lỗi. Vui lòng liên hệ IT- Admin hệ thống');
                });
            }
        } else {
            $group = Group::find($fields['group_id']);

            if ($group->company) {

                //dd($fields['amount']);
                $team_id = ApproveRoutingHelper::get_team($group->company->id, $fields['document_type_id'], $fields['group_id'], $fields['budget_type'], $fields['amount'], $fields['amount'], $fields['currency'], $fields['payment_type_id'], 0, 0);
                // dd ($group->company->id);
                //   dd("test");
                if ($team_id == "") {
                    $validator->after(function ($validator) {
                        $validator->errors()->add('team_id', 'Chưa phân tuyến duyệt. Vui lòng liên hệ IT- Admin hệ thống');
                    });
                }
            }
        }

        $filesigns = $fields['filesigns'];

        for ($i = 0; $i < count($filesigns); $i++) {
            $filesigns[$i]['user_id'] = $user->id;
            // $fileSign = new Filesign($filesigns[$i]);

            $attachment_file = $filesigns[$i]['attachment_file'];

            for ($j = 0; $j < count($attachment_file); $j++) {
                $filename = $attachment_file[$j]["name"];
                if (isset($attachment_file[$j]["signs"])) {
                    $signs =  $attachment_file[$j]["signs"];
                    $listSign = [];
                    for ($k = 0; $k < count($signs); $k++) {
                        $filesign = $this->get_signinfo($signs[$k]); // new Filesign();
                        array_push($listSign, $filesign->sign);
                    }
                    //check duplicate
                    $arrCount = array_count_values($listSign);
                    foreach ($arrCount  as $key => $value) {
                        if ($value > 1) {
                            $validator->after(function ($validator) use ($filename, $key) {
                                $validator->errors()->add('filesign', 'File trình ký: ' . $filename . " bị trùng chữ ký " . ($key == 0 ? "người tạo" : ("thứ " . $key)));
                            });
                        }
                    }
                }
            }
        }

        return $validator;
    }
    public function show($id)
    {

        $document = Document::with('user', 'document_type', 'company', 'department',  'team', 'team.users', 'approveds', 'payment_type', 'budgetTypeObj', 'attachment_file', 'timelines', 'doc_type', 'shareds', 'shareds.group', 'docbrowser_type', 'filesigns', 'filesigns.attachment_file', 'filesigns.attachment_file.signs', 'reminders')->find($id);
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
        $this->check_read_noti($id);
        return $document;
    }

    public function edit($id)
    {
        $document = Document::with('user', 'document_type', 'company', 'department',  'team', 'team.users', 'approveds', 'attachment_file', 'timelines', 'shareds', 'filesigns', 'filesigns.attachment_file', 'filesigns.attachment_file.signs', 'attachment_file')->find($id);

        return $document;
    }
    public function check_read_noti($id)
    {
        $request = new Request();
        $request = $this->request;

        $user = User::findOrFail(auth()->user()->id);
        //   dd( $this->request->notification_id);
        if ($this->request->filled('notification_id')) {
            // dd($request->get('notification_id'));

            $user->unreadNotifications->map(function ($noti) use ($request) {
                if ($noti->id == $request->get('notification_id')) {
                    $noti->markAsRead();
                }
            });
        } else {
            $user->unreadNotifications->map(function ($noti) use ($id) {

                try {
                    $result = $noti->data['data'];
                    if (isset($result['object_type']) &&  $result['object_type'] == Document::class && $result['object_id'] == $id) {
                        $noti->markAsRead();
                    }
                } catch (\Throwable $th) {
                    Log::error($th->getMessage());
                }
            });
        }
        //  dd("tesst");
    }
    public function store()
    {

        $result = array();
        $result['data'] = array();
        $result['data']['success'] = 0;
        date_default_timezone_set("Asia/Ho_Chi_Minh");
        $team_id = "";


        //dd("test");
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

                // $fields['company_id'] = $user->company->id;
                // $fields['department_id'] = $user->department->id;
                $teamconfig_id = $this->getTeamConfig();
                if ($teamconfig_id != "") {
                    $fields['teamconfig_id'] = $teamconfig_id;
                }

                $fields['team_id'] =  $this->getTeam(null);
                // dd( $fields['team_id'] );
                //dd(  $fields['team_id'] );


                //Cấp dãy số sau khi lưu thành công
                try {

                    $document = Document::create($fields);

                    $documentType = DocumentType::find($document->document_type_id);
                    if ($documentType) {
                        $document->serial_num = DocumentSerials::getSerial(
                            Ultils::$MODULE_REPORT,
                            $documentType->code,
                            $document->company_id,
                            Ultils::$MODULE_FORMAT_SERIAL_NUMBER,
                            true
                        );
                        $document->save();
                    }

                    //File trình ký
                    $filesigns = $fields['filesigns'];
                    for ($i = 0; $i < count($filesigns); $i++) {
                        $filesigns[$i]['user_id'] = $user->id;
                        $fileSign = new Filesign($filesigns[$i]);
                        $document->filesigns()->save($fileSign);

                        //save file
                        $attachment_file = $filesigns[$i]['attachment_file'];

                        for ($j = 0; $j < count($attachment_file); $j++) {

                            $file = new File();

                            $file->name = $attachment_file[$j]["name"];
                            $file->size = $attachment_file[$j]["size"];
                            $file->user_id = $user->id;
                            $strDate = date("Y") . "/" . date("m") . "/"  . date("d");
                            $ext = substr($attachment_file[$j]["name"], strrpos($attachment_file[$j]["name"], '.') + 1);
                            $name = "public/document/" . $strDate . "/" . uniqid() . '.' . $ext;
                            $file->ext = $ext;
                            $file->url = $name;
                            $fileSign->attachment_file()->save($file);

                            if (isset($attachment_file[$j]["signs"])) {
                                $signs =  $attachment_file[$j]["signs"];
                                for ($k = 0; $k < count($signs); $k++) {
                                    $filesign = $this->get_signinfo($signs[$k]); // new Filesign();
                                    $filesign->file_id = $file->id;
                                    $file->signs()->save($filesign);
                                }
                            }

                            $base64_str = substr($attachment_file[$j]['base64'], strpos($attachment_file[$j]['base64'], ",") + 1);
                            $image = base64_decode($base64_str);

                            Storage::disk('local')->put($name, $image);
                            FacadesFileVault::encrypt($name);
                        }
                    }


                    $attachment_file = $fields['attachment_file'];
                    //  dd($fields);
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
                            $name = "public/document/" . $strDate . "/" . uniqid() . '.' . $ext;

                            $file->ext = $ext;
                            $file->url = $name;
                            $document->attachment_file()->save($file);
                            //dd($file);

                            //$name = "/avatars/" . $user->username . '.' . $this->request->file['ext'];
                            $base64_str = substr($attachment_file[$j]['base64'], strpos($attachment_file[$j]['base64'], ",") + 1);
                            $image = base64_decode($base64_str);
                            //file_put_contents(public_path().$name,  $image );
                            Storage::disk('local')->put($name, $image);
                            FacadesFileVault::encrypt($name);
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
                    // $shared_users = $fields['shared_users'];
                    // // dd($shareds);
                    //  for ($i = 0; $i < count($shared_users); $i++) {
                    //          $shared = new Shared();
                    //          $shared->user_id =  $user->id;
                    //          $shared->view_user_id =  $shared_users[$i];
                    //          $document->shareds()->save($shared);
                    //  }
                    //shared_users


                } catch (\Exception $e1) {

                    $validator->errors()->add('serial_number', $e1->getMessage());
                    $this->errors = $validator->errors();

                    return null;
                }
                $this->store_sub_data($document);
                $document->timelines()->save(new Timeline(['title' => "form.create", 'icon' => 'fas fa-file-alt', 'user_id' => auth()->user()->id]));
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
    /**
     * Tạo đối tượng Filesign
     */
    protected function get_signinfo($sign)
    {
        $filesigninfo = new Filesigninfo();
        $filesigninfo->show_sign = $sign['show_sign'];
        $filesigninfo->sign = $sign['sign'];
        $filesigninfo->x = $sign['x'];
        $filesigninfo->y = $sign['y'];
        $filesigninfo->page = $sign['page'];
        $filesigninfo->width = $sign['width'];
        $filesigninfo->height = $sign['height'];
        $filesigninfo->width_c = $sign['width_c'];
        $filesigninfo->height_c = $sign['height_c'];
        $filesigninfo->cx = $sign['cx'];
        $filesigninfo->cy = $sign['cy'];

        return $filesigninfo;
    }
    protected function validate_update($id)
    {

        $validator = Validator::make(
            $this->request->all(),
            [
                'document_type_id' => 'required',
                'group_id' => 'required',
                'amount' => 'required',
                'title' => 'required|max:150'
            ],
            [
                'document_type_id.required' => "Loại chứng từ không được rỗng",
                'group_id.required' => "Nhóm không được rỗng",
                'amount.required' => "Số tiền không được rỗng",
                'title.required' => "Tiêu đề không được rỗng",
                'title.max' => "Tiêu đề tối đa 150 kí tự",
            ]
        );
        $fields = $this->request->all();

        // dd( $this->request->amount);
        $document = Document::findOrFail($id);

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

        if (!$validator->fails()) {
            $documentType = DocumentType::find($this->request->document_type_id);
            if ($documentType->approve_manual == '1') {
                //$this->request['team_id'] =  $this->createTeam();
                // dd($document);
                $is_sign = false;
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
                    $this->assign_user($document->team_id, $this->request->team_users);
                }

                // if ($this->request['team_id']=='') {
                //     $validator->after(function ($validator) {
                //         $validator->errors()->add('team_id', 'Tạo Team bị lỗi. Vui lòng liên hệ IT- Admin hệ thống');
                //     });
                // }
            } else {
                $group = Group::find($fields['group_id']);
                if ($group->company) {
                    $company_id =  $group->company->id;
                    $team_id = ApproveRoutingHelper::get_team($group->company->id, $fields['document_type_id'], $fields['group_id'], $fields['budget_type'], $fields['amount'], $fields['amount'], $fields['currency'], $fields['payment_type_id'], 0, 0);

                    if ($team_id == "") {
                        $validator->after(function ($validator) {
                            $validator->errors()->add('team_id', 'Chưa phân tuyến duyệt. Vui lòng liên hệ IT- Admin hệ thống');
                        });
                    }

                    if ($group->company->id != $document->company_id) {
                        $validator->after(function ($validator) use ($company_id, $document) {
                            $validator->errors()->add('phantuyen', 'Số chứng từ đã phát sinh cho công ty ' . $document->company_id . ' vui lòng không chuyển chứng từ sang công ty ' . $company_id);
                        });
                    }
                }
            }

            $filesigns = $fields['filesigns'];

            for ($i = 0; $i < count($filesigns); $i++) {
                $filesigns[$i]['user_id'] = $user->id;
                // $fileSign = new Filesign($filesigns[$i]);

                $attachment_file = $filesigns[$i]['attachment_file'];

                for ($j = 0; $j < count($attachment_file); $j++) {
                    $filename = $attachment_file[$j]["name"];
                    if (isset($attachment_file[$j]["signs"])) {
                        $signs =  $attachment_file[$j]["signs"];
                        $listSign = [];
                        for ($k = 0; $k < count($signs); $k++) {
                            $filesign = $this->get_signinfo($signs[$k]); // new Filesign();
                            array_push($listSign, $filesign->sign);
                        }
                        //check duplicate
                        $arrCount = array_count_values($listSign);
                        foreach ($arrCount  as $key => $value) {
                            if ($value > 1) {
                                $validator->after(function ($validator) use ($filename, $key) {
                                    $validator->errors()->add('filesign', 'File trình ký: ' . $filename . " bị trùng chữ ký " . ($key == 0 ? "người tạo" : ("thứ " . $key)));
                                });
                            }
                        }
                    }
                }
            }
        }



        return $validator;
    }

    public function update_cancel()
    {
        $user = User::find(Auth::user()->id);
        $document = Document::find($this->request->id);
        //User hủy khi được trả lại

        if ($user && $user->id == $document->user_id) {
            if ($document->status != null || $document->approveds) {
                $last = $document->approveds->last();
                if ($last && ($last->release != '1' || $last->online != 'X')) {
                    return false;
                }
            }
        }
        //  dd("tes");

        // if($document->status == 2){ //Đã duyệt hoàn tất thì không thể huỷ
        //     return false;
        // }
        $document->status = -1; //Đã huỷ
        if ($document->save()) {
            $document->timelines()->save(new Timeline(['title' => "form.document_cancel", 'icon' => 'fas fa-window-close bg-danger', 'user_id' => auth()->user()->id]));
            return true;
        }
        return false;
    }
    public function update($id)
    {
        date_default_timezone_set("Asia/Ho_Chi_Minh");

        $validator = $this->validate_update($id);
        $failed = $validator->fails();
        $fields = $this->request->all();
        //kiểm tra thông tin hợp đồng nếu có , validate thêm
        $document = Document::findOrFail($id);

        if ($failed) {
            $this->errors = $validator->errors();
        } else {

            try {

                if ($document) {
                    DB::beginTransaction();
                    $user = new User();
                    $user = auth()->user();
                    $fields['user_id'] = $user->id;
                    $fields['team_id'] = $this->getTeam($document);

                    $teamconfig_id = $this->getTeamConfig();
                    if ($teamconfig_id != "") {
                        $fields['teamconfig_id'] = $teamconfig_id;
                    }

                    $group = Group::find($fields['group_id']);

                    $fields['company_id'] =   $group->company_id;
                    $fields['department_id'] = $group->department->id;
                    //dd("day". $fields['team_id'] );
                    $document->fill($fields);
                    $document->save();
                    //File trình ký
                    $filesigns = $fields['filesigns'];

                    for ($i = 0; $i < count($filesigns); $i++) {
                        $filesigns[$i]['user_id'] = $user->id;

                        // $other_attacheds[$i]['paycatset_id'] = $paycatset_id ;
                        if (isset($filesigns[$i]['id']) && $filesigns[$i]['id'] != 0) {
                            $fileSign = Filesign::find($filesigns[$i]['id']);

                            $fileSign->fill($filesigns[$i]);
                            $fileSign->save();
                        } else {


                            $fileSign = new Filesign($filesigns[$i]);
                            $document->filesigns()->save($fileSign);
                        }

                        //save file
                        $attachment_file = $filesigns[$i]['attachment_file'];

                        for ($j = 0; $j < count($attachment_file); $j++) {
                            if (!isset($attachment_file[$j]["id"])) {

                                $file = new File();
                                $file->name = $attachment_file[$j]["name"];
                                $file->size = $attachment_file[$j]["size"];
                                $file->user_id = $user->id;
                                $strDate = date("Y") . "/" . date("m") . "/"  . date("d");
                                $ext = substr($attachment_file[$j]["name"], strrpos($attachment_file[$j]["name"], '.') + 1);
                                $name = "public/document/" . $strDate . "/" . uniqid() . '.' . $ext;
                                $file->ext = $ext;
                                $file->url = $name;

                                $fileSign->attachment_file()->save($file);
                                if (isset($attachment_file[$j]["signs"])) {

                                    $signs =  $attachment_file[$j]["signs"];
                                    for ($k = 0; $k < count($signs); $k++) {
                                        $sign = $this->get_signinfo($signs[$k]); // new Filesign();
                                        $sign->file_id = $file->id;
                                        $file->signs()->save($sign);
                                    }
                                }
                                $base64_str = substr($attachment_file[$j]['base64'], strpos($attachment_file[$j]['base64'], ",") + 1);
                                $image = base64_decode($base64_str);

                                Storage::disk('local')->put($name, $image);
                                FacadesFileVault::encrypt($name);
                            } else {

                                $file = File::find($attachment_file[$j]["id"]);
                                foreach ($file->signs as   $sign) {
                                    $sign->delete();
                                }
                                if (isset($attachment_file[$j]["signs"])) {
                                    $signs =  $attachment_file[$j]["signs"];

                                    for ($k = 0; $k < count($signs); $k++) {
                                        $sign = $this->get_signinfo($signs[$k]); // new Filesign();
                                        $sign->file_id = $file->id;
                                        $file->signs()->save($sign);
                                    }
                                }
                            }
                        }
                    }
                    //Xoá

                    $filesigns_del = $fields['filesigns_del'];

                    //dd($other_attacheds_del);
                    for ($i = 0; $i < count($filesigns_del); $i++) {

                        if (isset($filesigns_del[$i]['id'])) {
                            $FileSign = Filesign::find($filesigns_del[$i]['id']);
                            if ($FileSign) {
                                foreach ($FileSign->attachment_file as $file) {
                                    foreach ($file->signs as  $sign) {
                                        $sign->delete();
                                    }
                                    Storage::delete($file->url . ".enc");
                                    $file->delete();
                                }
                                $FileSign->delete();
                            }
                        }
                    }

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
                            $name = "public/document/" . $strDate . "/" . uniqid() . '.' . $ext;

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

                    $this->update_sub_data($document);
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

    public function destroy($id)
    {

        $document = Document::findOrFail($id);

        if (!$document) {
            abort(404);
        }
        if ($document->status == 2) {
            $this->message = "Vui lòng không xoá chứng từ đã hoàn tất!";
        } else {
            try {
                // dd($contract->attachment_file());
                DB::beginTransaction();
                $this->destroy_sub_data($document);
                $document->delete();
                DB::commit();
                return true;
            } catch (\Exception $e) {
                DB::rollback();
                $this->errors = $e->getMessage();
                return false;
            }
        }

        return false;
    }
    protected function getTeamConfig()
    {
        $user = new User();
        $user = auth()->user();
        $fields = $this->request->all();

        $group = Group::find($fields['group_id']);
        //$fields['payment_type_id'] = "0";

        $team_id = ApproveRoutingHelper::get_team($group->company->id, $fields['document_type_id'], $fields['group_id'], $fields['budget_type'], $fields['amount'], $fields['amount'], $fields['currency'], $fields['payment_type_id'], 0, 0);

        return $team_id;
    }
    // protected function createTeam(){
    //     //  protected $fillable = ['id', 'name','description','module','active','created_at','updated_at'];

    //     $documentType = DocumentType::find($this->request->document_type_id);
    //     $team = new Team();
    //     $team->name = $documentType->name .'_AUTO';
    //     $team->description = $documentType->name .'_AUTO';
    //     $team->module = $documentType->module;
    //     $team->active = '1';
    //     if($team->save()){

    //         $this->assign_user($team->id,$this->request->team_users);
    //         return $team->id;
    //     }

    //     return "";
    //   }
    //   public function assign_user($team_id,$users){

    //         try {

    //             $team = Team::findOrFail($team_id);


    //             if ($team) {
    //                 foreach ($team->users as $key => $u) {
    //                          dd( $u['id']);
    //                          $user = User::find($u['id']);
    //                          $team->users()->detach($user);

    //                      }
    //                 $level = 1;
    //                 foreach ($users as $key => $u) {

    //                     $user = User::find($u['user_id']);
    //                     $level =  $level++;
    //                     $step = isset($u['step'])? $u['step']:"1";
    //                     $review = isset($u['review'])? $u['review']:"";
    //                      $sign = isset($u['sign'])? $u['sign']:null;
    //                    // $team->users()->detach($user);
    //                      $team->users()->attach($user,['level'=>$level,'step'=>$step,'review'=>$review,'sign'=>$sign]);
    //                 }
    //                  //$result['data']['success']  = 1;
    //                  return true;
    //             }
    //         } catch (\Exception $e) {
    //            // $result['data']['errors']  =  $e->getMessage();
    //            //dd( "loi");
    //            return false;
    //         }
    //         return false;


    //    // return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);


    // }

    //Hàm trả về Team duyệt
    abstract protected function getTeam($obj);
    //các hàm này không sử dụng commit
    abstract protected function store_sub_data($obj);
    abstract protected function update_sub_data($obj);
    abstract protected function destroy_sub_data($obj);

    public function test_performance()
    {
        $result = array();
        $result['data'] = array();
        $result['success'] = "0";
        $query = Document::query();
        $user = new User();
        $user = Auth()->user();
        try {

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

            $status  = explode(',', $this->request->status);
            $status_null = false;
            for ($i = 0; $i < count($status); $i++) {
                if ($status[$i] == 0) {
                    $status_null = true;
                }
            }

            if ($this->request->filled('status')) {
                // if ($this->request->status == 0) {
                //     $query = $query->where('status', null);
                // } else {
                //     $query = $query->whereIn('status',  $status);
                // }
                // dd($status );
                if ($status_null) {
                    $query = $query->Where(function ($q) use ($status) {
                        $q = $q->whereIn('status',  $status);
                        $q = $q->orWhereNull('status');
                    });
                } else {
                    $query = $query->whereIn('status',  $status);
                }

                //  dd( $query);
            }
            //Lấy theo loại chứng từ
            if ($this->request->filled('document_type_id') && $this->request->document_type_id != "undefined" && $this->request->document_type_id != "null") {
                $query = $query->whereIn('document_type_id', explode(",", $this->request->document_type_id));
            }
            if ($this->request->filled('company_id')) {
                $query = $query->where('company_id', $this->request->company_id);
            }
            if ($this->request->filled('department_id')) {
                $query = $query->where('department_id', $this->request->department_id);
            }
            if ($this->request->filled('serial_num')) {
                // dd($this->request->serial_num);
                $query = $query->where('serial_num', 'like', '%' . $this->request->serial_num . '%');
            }
            if ($this->request->filled('docbrowser_type_id')) {

                $query = $query->whereIn('docbrowser_type_id',  explode(",", $this->request->docbrowser_type_id));
            }
            //lọc theo phạm vi: của tôi, theo nhóm, .....
            if ($this->request->filled('visibility')) {
                switch ($this->request->visibility) {
                    case '0': //owner
                        $query = $query->where('user_id', $user->id);
                        break;
                        // case '1'://group
                        //     $query = $query->whereNotNull('serial_num');
                        //     break;
                }
            } else {
                //Lấy những chứng từ của nhóm ( not nul Serial_num) và của tôi ( bao gồm cả null Serial_num)
                $query = $query->where(function ($q) use ($user) {
                    $q = $q->whereNotNull('serial_num');
                    $q = $q->orWhere(function ($q1) use ($user) {
                        $q1 = $q1->whereNull('serial_num');
                        $q1 = $q1->where('user_id', $user->id);
                    });
                    // $q = $q->where('user_id',$user->id);
                });
            }

            if ($this->request->feedback === "true") {
                $query = $query->whereHas('approveds', function (Builder $query) {
                    $query->where('note', '<>', null);
                });
            }
        } catch (Exception $e) {
            $this->errors = $e->getMessage();
        }


        $user = new User();
        $user = User::find(auth()->user()->id);

        $all_columns = Schema::getColumnListing('documents');
        $exclude_columns = ['content'];
        $get_columns = array_diff($all_columns, $exclude_columns);

        $query = $query->select($get_columns);

        $withModel = ['approveds', 'user', 'reminders', 'docbrowser_type'];

        if ($this->request->filled('test')) {
            if ($this->request->test == 1) {
                $withModel =  ['approveds', 'user', 'reminders'];
            }
            if ($this->request->test == 2) {
                $withModel =  ['approveds', 'reminders', 'docbrowser_type'];
            }
            if ($this->request->test == 3) {
                $withModel =  ['approveds', 'reminders'];
            }
        }


        if ($user->hasPermission('review_all_document')) {
        } else if ($user->hasPermission('review_company_document')) {

            $company_id = $user->company_id;
            $group_ids = $user->groups->pluck('id')->flatten();
            $query = $query->where(function ($q) use ($company_id, $group_ids) {
                $q->where('company_id', $company_id)
                    ->orWhereIn('group_id', $group_ids);
            });
            // $company_id = $user->company_id;
            // $document = $query->where('company_id',$company_id)->orderBy('id', 'desc')->with($withModel)->withCount('approveds')->get();
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

            $group_ids = $user->groups->pluck('id')->flatten(); //pluck('id')->flatten();
            $user_id = $user->id;
            //Lấy các chứng từ shared group
            $query = $query->where(function ($q) use ($group_ids, $user_id) {
                $q->whereIn('group_id', $group_ids)
                    ->orWhereHas('shareds', function ($q) use ($group_ids, $user_id) {
                        //$q->whereIn('object_id',$group_ids)->where('type',0);

                        $q->where(function ($qu) use ($group_ids) {
                            $qu->whereIn('object_id', $group_ids)->where('type', 0);
                        })->orwhere(function ($qu) use ($user_id) {
                            $qu->where('object_id', $user_id)->where('type', 1);
                        });
                    });
            });
            // $document = $query->where('user_id', $user->id)->orderBy('id', 'desc')->with($withModel)->withCount('approveds')->get();
            // $document = $query->whereIn('group_id', $group_ids)->orderBy('id', 'desc')->with($withModel)->withCount('approveds')->get();
        }

        $query = $query->orderBy('id', 'desc')->with($withModel);

        $document = $query->get();

        foreach ($document as $currentDocument) {
            foreach ($currentDocument->approveds as $approve) {
                $approve_user = User::find($approve->user_id);

                if ($approve->finished == 0 && $approve->release == 0 && $approve->online == "X") {
                    if ($approve_user) {
                        $currentDocument->waitingApproval = $approve_user->name;
                    }
                }
                if ($approve->checkout == null) {
                    if ($approve_user) {
                        $currentDocument->waitApprove = $approve_user->name;
                    }
                }

                if ($approve->note != null) {
                    $currentDocument->noting =  $currentDocument->noting . "<br/>" . $approve->note;
                }
            }
            if ($currentDocument->approveds) {
                $approved_count = count($currentDocument->approveds);

                if ($approved_count > 0) {
                    $finalApprove = $currentDocument->approveds[count($currentDocument->approveds) - 1];
                    if ($finalApprove  && $finalApprove->release == 1) {
                        $currentDocument->isRelease = 'X';
                    }
                }
            }
            unset($currentDocument['approveds']);
        }

        return $document;
    }
}
