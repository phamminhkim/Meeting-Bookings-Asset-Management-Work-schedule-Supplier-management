<?php

namespace App\Repositories\managerprice;

use App\Models\managerprice\PriceProduct;
use App\Models\managerprice\PriceDetail;
use App\Models\managerprice\PriceSpecitem;
use App\Models\managerprice\PriceSpec;
use App\Models\managerprice\PriceReq;
use App\Models\shared\DocumentType;
use App\Models\shared\File;
use App\Models\shared\Group;
use App\Models\shared\OtherAttached;
use App\Models\shared\Reminder;
use App\Models\shared\Team;
use App\Models\shared\Timeline;
use App\Repositories\approve\ApproveRoutingHelper;
use App\Repositories\DocumentSerials;
use App\Ultils\Ultils;
use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use SoareCostin\FileVault\Facades\FileVault as FacadesFileVault;

abstract class PriceAppReqAbs
{

    protected $priceAppReq;
    protected $request;
    protected $errors;
    protected $message;
    protected $layout;
    protected $form_name;

    private $payment_type_id = '0';
    private $budget_type = 1;
    private $amount = 0;
    private $amount_exchanged = 0;
    private $amount_out_exchanged = 0;
    private $amount_out_budget = 0;
    private $currency = 'VND';


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
        $query = PriceReq::query();
        $user = Auth::user();
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
                $query = $query->where('serial_num', $this->request->serial_num);
            }
            if ($this->request->filled('vendor_id')) {
                $query = $query->where('vendor_id', $this->request->vendor_id);
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

            // dd( 'teddt');

        } catch (Exception $e) {
            $this->errors = $e->getMessage();
        }
        $user = new User();
        $user = User::find(auth()->user()->id);

        $withModel = ['approveds', 'user', 'reminders'];
        if ($user->hasPermission('review_all_ycdg')) {

            $priceAppRequest = $query->orderBy('id', 'desc')->with($withModel)->get();
        } else if ($user->hasPermission('review_company_ycdg')) {
            // $company_id = $user->company_id;
            // $priceAppRequest = $query->where('company_id',$company_id)->orderBy('id', 'desc')->with($withModel)->withCount('approveds')->get();

            $company_id = $user->company_id;
            $group_ids = $user->groups->pluck('id')->flatten(); //pluck('id')->flatten();
            //$priceAppRequest = $query->where('company_id',$company_id)->orderBy('id', 'desc')->with($withModel)->withCount('approveds')->get();
            $query = $query->Where(function ($q) use ($group_ids, $company_id) {
                $q = $q->where('company_id',  $company_id);
                $q = $q->orWhereIn('group_id', $group_ids);
            });
            $priceAppRequest = $query->orderBy('id', 'desc')->with($withModel)->get();
        } else {
            $group_ids = $user->groups->pluck('id')->flatten(); //pluck('id')->flatten();

            // $priceAppRequest = $query->where('user_id', $user->id)->orderBy('id', 'desc')->with($withModel)->withCount('approveds')->get();
            $priceAppRequest = $query->whereIn('group_id', $group_ids)->orderBy('id', 'desc')->with($withModel)->get();

            // $priceAppRequest = PriceReq::all();
        }

        foreach ($priceAppRequest as $currentRequest) {
            if ($currentRequest->approveds) {
                foreach ($currentRequest->approveds as $approve) {
                    $approve_user = User::find($approve->user_id);

                    if ($approve->finished == 0 && $approve->release == 0 && $approve->online == "X") {
                        if ($approve_user) {
                            $currentRequest->waitingApproval = $approve_user->name;
                        }
                    }
                    if ($approve->checkout == null) {
                        if ($approve_user) {
                            $currentRequest->waitApprove = $approve_user->name;
                        }
                    }
                }
                $approve_count = count($currentRequest->approveds);
                if ($approve_count > 0) {
                    $finalApprove = $currentRequest->approveds[$approve_count - 1];
                    if ($finalApprove  && $finalApprove->release == 1) {
                        $currentRequest->isRelease = 'X';
                    }
                }
            }
            unset($currentRequest['approveds']);
        }

        return $priceAppRequest->makeHidden(['purchase_note']);;
    }
    protected function validate_store()
    {

        $validator = Validator::make($this->request->all(), [
            // 'amount' => 'required',
            'title' => 'required|max:100',
            'proposer' => 'required',
            'group_id' => 'required',
            'vendor_id' => 'required',
            'purchase_note' => 'required',
            'material_type_name' => 'sometimes|max:100',
            'method_payment_name' => 'sometimes|max:255',
            'contract_num' => 'sometimes|max:50',
            'diff_info' => 'sometimes|max:255',
            'another_idea' => 'sometimes|max:255',



        ], [
            'title.required' => 'Tiêu đề không được rỗng',
            'title.max' => 'Tiêu đề : Tối đa 100 kí tự',
            'proposer.required' => 'Người đề nghị không được rỗng',
            'group_id.required' => 'Nhóm không được rỗng',
            // 'purchase_note.max' => 'Ghi chú mua hàng : Tối đa 255 kí tự',
            'material_type_name.max' => 'Sản phẩm (chủng loại) : Tối đa 100 kí tự',
            'method_payment_name.max' => 'Phương thức thanh toán : Tối đa 255 kí tự',
            'contract_num.max' => 'Hợp đồng nguyên tắc : Tối đa 50 kí tự',
            'diff_info.max' => 'Thông tin khác : Tối đa 255 kí tự',
            'another_idea.max' => 'Ý kiến khác : Tối đa 255 kí tự',

        ]);
        $fields = $this->request->all();

        $user = new User();
        $user = auth()->user();
        if (!$user->company) {
            $validator->after(function ($validator) {
                $validator->errors()->add('company', 'User chưa được cấu hình công ty');
            });
        }
        if (!$user->department) {

            $validator->after(function ($validator) {
                $validator->errors()->add('department', 'User chưa được cấu hình phòng ban');
            });
        }

        if ($user->company) {


            $fields['payment_type_id'] = $this->payment_type_id;
            $fields['budget_type'] = $this->budget_type;
            $fields['amount'] = $this->amount;
            $fields['amount_exchanged'] = $this->amount_exchanged;
            $fields['amount_out_exchanged'] = $this->amount_out_exchanged;
            $fields['amount_out_budget'] = $this->amount_out_budget;
            // $fields['currency'] = $this->request->currency;
            $team_id = ApproveRoutingHelper::get_team(
                $user->company->id,
                $fields['document_type_id'],
                $fields['group_id'],
                $fields['budget_type'],
                $fields['amount'],
                $fields['amount_exchanged'],
                $fields['currency'],
                $fields['payment_type_id'],
                $fields['amount_out_budget'],
                $fields['amount_out_exchanged']
            );
            if ($team_id == "") {
                $validator->after(function ($validator) {
                    $validator->errors()->add('team_id', 'Chưa phân tuyến duyệt. Vui lòng liên hệ IT- Admin hệ thống');
                });
            }
        }


        return $validator;
    }
    public function store()
    {

        $result = array();
        $result['data'] = array();
        $result['data']['success'] = 0;
        date_default_timezone_set("Asia/Ho_Chi_Minh");
        $team_id = "";

        $fields = $this->request->all();
        // dd($fields);

        $validator = $this->validate_store();
        $failed = $validator->fails();


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
                $fields['payment_type_id'] =  $this->payment_type_id;
                $fields['budget_type'] = $this->budget_type;
                $fields['amount'] = $this->amount;
                $fields['amount_exchanged'] = $this->amount_exchanged;
                $fields['amount_out_exchanged'] = $this->amount_out_exchanged;
                $fields['amount_out_budget'] = $this->amount_out_budget;
                // $fields['currency'] = $this->currency;

                $team_id = ApproveRoutingHelper::get_team(
                    $user->company->id,
                    $fields['document_type_id'],
                    $fields['group_id'],
                    $fields['budget_type'],
                    $fields['amount'],
                    $fields['amount_exchanged'],
                    $fields['currency'],
                    $fields['payment_type_id'],
                    $fields['amount_out_budget'],
                    $fields['amount_out_exchanged']
                );

                $fields['teamconfig_id'] = $team_id;
                $fields['team_id'] = ApproveRoutingHelper::createTeamFrom($team_id);

                $priceRequest = PriceReq::create($fields);

                if ($priceRequest) {
                    //Nhập giá
                    $products = $fields['products'];

                    for ($i = 0; $i < count($products); $i++) {
                        $products[$i]['price_req_id'] = $priceRequest->id;

                        if (isset($products[$i]['id']) && $products[$i]['id'] != 0) {
                            $product = PriceProduct::find($products[$i]['id']);
                            $product->fill($products[$i]);
                            $product->save();
                        } else {
                            // dd($products[$i]);
                            $product = PriceProduct::create($products[$i]);
                        }

                        $specs = $products[$i]['specs'];
                        for ($j = 0; $j < count($specs); $j++) {
                            $specs[$j]['price_product_id'] = $product->id;
                            if (isset($specs[$j]['id']) && $specs[$j]['id'] != 0) {
                                $spec = PriceSpec::find($specs[$j]['id']);
                                $spec->fill($specs[$j]);
                                $spec->save();
                            } else {

                                $spec = PriceSpec::create($specs[$j]);
                            }
                            $others = $specs[$j]['others'];

                            for ($h = 0; $h < count($others); $h++) {

                                $others[$h]['price_product_id'] = $spec->price_product_id;
                                $others[$h]['price_spec_id'] =  $spec->id;

                                if (isset($others[$h]['id']) && $others[$h]['id'] != 0) {
                                    $other = PriceSpecitem::find($others[$h]['id']);
                                    $other->fill($others[$h]);
                                    $other->save();
                                } else {

                                    $other = PriceSpecitem::create($others[$h]);
                                }
                                //dd($detail);
                            }
                        }

                        //Nhập giá chi tiết
                        $details = $products[$i]['details'];

                        for ($k = 0; $k < count($details); $k++) {

                            $details[$k]['price_product_id'] = $product->id;

                            if (isset($details[$k]['id']) && $details[$k]['id'] != 0) {
                                $detail = PriceDetail::find($details[$k]['id']);
                                $detail->fill($details[$k]);
                                $detail->save();
                            } else {

                                $detail = PriceDetail::create($details[$k]);
                            }
                            //dd($detail);
                        }
                    }


                    //File duyệt giá
                    $list_file_duyet_gia = $fields['attachment_file'];
                    for ($i = 0; $i < count($list_file_duyet_gia); $i++) {

                        $file = new File();
                        $file->name = $list_file_duyet_gia[$i]["name"];
                        //$ext = end(explode('.', $filename));
                        // $file->ext = $attachment_file[$i]["ext"];
                        $file->size = $list_file_duyet_gia[$i]["size"];
                        $file->user_id = $user->id;

                        $ext = substr($list_file_duyet_gia[$i]["name"], strrpos($list_file_duyet_gia[$i]["name"], '.') + 1);
                        $name = "public/price/" . $user->username . "/" . uniqid() . '.' . $ext; // $attachment_file[$i]["ext"];

                        $file->ext = $ext;
                        $file->url = $name;
                        $priceRequest->attachment_file()->save($file);

                        //$name = "/avatars/" . $user->username . '.' . $this->request->file['ext'];
                        $base64_str = substr($list_file_duyet_gia[$i]['base64'], strpos($list_file_duyet_gia[$i]['base64'], ",") + 1);
                        $image = base64_decode($base64_str);
                        //file_put_contents(public_path().$name,  $image );
                        Storage::disk('local')->put($name, $image);
                        FacadesFileVault::encrypt($name);
                    }
                    //File khác
                    $other_attacheds = $fields['other_attacheds'];
                    for ($i = 0; $i < count($other_attacheds); $i++) {
                        $otherAttached = new OtherAttached($other_attacheds[$i]);
                        $priceRequest->other_attacheds()->save($otherAttached);

                        //save file
                        $attachment_file = $other_attacheds[$i]['attachment_file'];

                        for ($j = 0; $j < count($attachment_file); $j++) {

                            $file = new File();

                            $file->name = $attachment_file[$j]["name"];
                            //$ext = end(explode('.', $filename));
                            // $file->ext = $attachment_file[$i]["ext"];
                            $file->size = $attachment_file[$j]["size"];
                            $file->user_id = $user->id;

                            $ext = substr($attachment_file[$j]["name"], strrpos($attachment_file[$j]["name"], '.') + 1);
                            $name = "public/price/" . $user->username . "/" . uniqid() . '.' . $ext; // $attachment_file[$i]["ext"];

                            $file->ext = $ext;
                            $file->url = $name;
                            $otherAttached->attachment_file()->save($file);

                            //$name = "/avatars/" . $user->username . '.' . $this->request->file['ext'];
                            $base64_str = substr($attachment_file[$j]['base64'], strpos($attachment_file[$j]['base64'], ",") + 1);
                            $image = base64_decode($base64_str);
                            //file_put_contents(public_path().$name,  $image );
                            Storage::disk('local')->put($name, $image);
                            FacadesFileVault::encrypt($name);
                        }
                    }
                }

                // //Cấp dãy số sau khi lưu thành công -> thay đổi: sau khi gửi thành công nó sẽ cấp số
                try {
                    $documentType = DocumentType::find($priceRequest->document_type_id);
                    if ($documentType) {
                        $priceRequest->serial_num = DocumentSerials::getSerial(
                            Ultils::$MODULE_PRICE,
                            $documentType->code,
                            $priceRequest->company_id,
                            Ultils::$MODULE_PAYMENT_FORMAT_SERIAL_NUMBER,
                            true
                        );


                        $priceRequest->save();
                    }
                } catch (\Exception $e1) {

                    $validator->errors()->add('serial_number', $e1->getMessage());
                    $this->errors = $validator->errors();
                    return null;
                }


                DB::commit();
                return $priceRequest;
            } catch (\Exception $e) {
                DB::rollback();

                $this->errors = $e->getMessage();
                return null;
            }
        }
        return null;
    }
    public function edit($id)
    {
        $PriceReq = PriceReq::with('other_attacheds', 'other_attacheds.attachment_file', 'attachment_file', 'products')->find($id);

        return $PriceReq;
    }
    public function show($id)
    {

        $PriceReq = PriceReq::with(
            'vendor',
            'department',
            'proposer',
            'group',
            'reminders',
            'approveds',
            'company',
            'other_attacheds',
            'other_attacheds.attachment_file',
            'attachment_file',
            'products',
            'timelines',
            'shareds',
            'shareds.group'
        )->find($id);
        foreach ($PriceReq->shareds as  $shared) {
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
        return $PriceReq;
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
                    if (isset($result['object_type']) &&  $result['object_type'] == PriceReq::class && $result['object_id'] == $id) {
                        $noti->markAsRead();
                    }
                } catch (\Throwable $th) {
                    Log::error($th->getMessage());
                }
            });
        }
        //  dd("tesst");
    }
    protected function validate_update($id)
    {

        $validator = Validator::make($this->request->all(), [
            // 'amount' => 'required',
            'title' => 'required|max:100',
            'proposer' => 'required',
            'group_id' => 'required',
            'vendor_id' => 'required',
            'purchase_note' => 'required',
            'material_type_name' => 'sometimes|max:100',
            'method_payment_name' => 'sometimes|max:255',
            'contract_num' => 'sometimes|max:50',
            'diff_info' => 'sometimes|max:255',
            'another_idea' => 'sometimes|max:255',



        ], [

            'title.required' => 'Tiêu đề không được rỗng',
            'title.max' => 'Tiêu đề : Tối đa 100 kí tự',
            'proposer.required' => 'Người đề nghị không được rỗng',
            'group_id.required' => 'Nhóm không được rỗng',
            // 'purchase_note.max' => 'Ghi chú mua hàng : Tối đa 255 kí tự',
            'material_type_name.max' => 'Sản phẩm (chủng loại) : Tối đa 100 kí tự',
            'method_payment_name.max' => 'Phương thức thanh toán : Tối đa 255 kí tự',
            'contract_num.max' => 'Hợp đồng nguyên tắc : Tối đa 50 kí tự',
            'diff_info.max' => 'Thông tin khác : Tối đa 255 kí tự',
            'another_idea.max' => 'Ý kiến khác : Tối đa 255 kí tự',

        ]);
        $fields = $this->request->all();


        $priceRequest = PriceReq::findOrFail($id);
        if ($priceRequest->status == 2) {

            $validator->after(function ($validator) {
                $validator->errors()->add('chungtuhoantat', 'Chứng từ đã hoàn tất. Vui lòng không cập nhật.');
            });
        }
        if ($priceRequest->status == -1) {

            $validator->after(function ($validator) {
                $validator->errors()->add('chungtuhuy', 'Chứng từ đã huỷ. Vui lòng không cập nhật.');
            });
        }
        if ($priceRequest->locked == 1) {

            $validator->after(function ($validator) {
                $validator->errors()->add('locked', 'Chứng từ đang bị khoá. Vui lòng không cập nhật.');
            });
        }

        $user = new User();
        $user = auth()->user();
        if (!$user->company) {


            $validator->after(function ($validator) {
                $validator->errors()->add('company', 'User chưa được cấu hình công ty');
            });
        }
        if (!$user->department) {


            $validator->after(function ($validator) {
                $validator->errors()->add('department', 'User chưa được cấu hình phòng ban');
            });
        }

        if ($user->company) {
            $fields['payment_type_id'] = $this->payment_type_id;
            $fields['budget_type'] = $this->budget_type;
            $fields['amount'] = $this->amount;
            $fields['amount_exchanged'] = $this->amount_exchanged;
            $fields['amount_out_exchanged'] = $this->amount_out_exchanged;
            $fields['amount_out_budget'] = $this->amount_out_budget;
            // $fields['currency'] = $this->currency;
            $team_id = ApproveRoutingHelper::get_team(
                $user->company->id,
                $fields['document_type_id'],
                $fields['group_id'],
                $fields['budget_type'],
                $fields['amount'],
                $fields['amount_exchanged'],
                $fields['currency'],
                $fields['payment_type_id'],
                $fields['amount_out_budget'],
                $fields['amount_out_exchanged']
            );

            if ($team_id == "") {
                $validator->after(function ($validator) {
                    $validator->errors()->add('team_id', 'Chưa phân tuyến duyệt. Vui lòng liên hệ IT- Admin hệ thống');
                });
            }

            $group = Group::find($fields['group_id']);

            $company_id =  $group->company->id;
            // dd($company_id );
            if ($group->company->id != $priceRequest->company_id) {
                $validator->after(function ($validator) use ($company_id, $priceRequest) {
                    $validator->errors()->add('phantuyen', 'Số chứng từ đã phát sinh cho công ty ' . $priceRequest->company_id . ' vui lòng không chuyển chứng từ sang công ty ' . $company_id);
                });
            }


            //   if($priceRequest->team_id <> $team_id){
            //     foreach ( $priceRequest->approveds as  $approve) {

            //         if($approve->online == 'X' && $approve->release <> '1'){
            //             $validator->after(function ($validator) {
            //                 $validator->errors()->add('phantuyen', 'Chứng từ đã gửi không thể phân tuyến lại.');
            //             });
            //         }

            //     }

            //  }
        }
        // dd("test");
        return $validator;
    }
    public function update_cancel()
    {

        $priceRequest = PriceReq::find($this->request->id);
        $priceRequest->status = -1; //Đã huỷ
        if ($priceRequest->status == 2) { //Đã duyệt hoàn tất thì không thể huỷ
            return false;
        }
        if ($priceRequest->save()) {

            $priceRequest->timelines()->save(new Timeline(['title' => "form.document_cancel", 'icon' => 'fas fa-window-close bg-danger', 'user_id' => auth()->user()->id]));
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

        $priceRequest = PriceReq::findOrFail($id);

        if ($failed) {
            $this->errors = $validator->errors();
        } else {

            try {

                if ($priceRequest) {
                    DB::beginTransaction();

                    //$fields = $request->all();
                    $user = new User();
                    $user = auth()->user();
                    $fields['user_id'] = $user->id;
                    $group = Group::find($fields['group_id']);

                    $fields['company_id'] =   $group->company_id;
                    $fields['department_id'] = $group->department->id;

                    $fields['payment_type_id'] = $this->payment_type_id;
                    $fields['budget_type'] = $this->budget_type;
                    $fields['amount'] = $this->amount;
                    $fields['amount_exchanged'] = $this->amount_exchanged;
                    $fields['amount_out_exchanged'] = $this->amount_out_exchanged;
                    $fields['amount_out_budget'] = $this->amount_out_budget;
                    // $fields['currency'] = $this->currency;
                    $team_id = ApproveRoutingHelper::get_team(
                        $user->company->id,
                        $fields['document_type_id'],
                        $fields['group_id'],
                        $fields['budget_type'],
                        $fields['amount'],
                        $fields['amount_exchanged'],
                        $fields['currency'],
                        $fields['payment_type_id'],
                        $fields['amount_out_budget'],
                        $fields['amount_out_exchanged']
                    );

                    $fields['teamconfig_id'] = $team_id;
                    $fields['team_id'] = ApproveRoutingHelper::createTeamFrom($team_id);
                    $teamOld = Team::find($priceRequest->team_id);
                    if ($teamOld && $teamOld->name == '_AUTO') {
                        //cẩn thận - không xóa các team cấu hình - chỉ xóa các team auto
                        $teamOld->delete();
                    }

                    $fields['locked'] = $priceRequest->locked;
                    //dd( $fields['locked']);
                    $priceRequest->fill($fields);

                    $priceRequest->save();

                    //  dd($priceRequest);
                    if ($priceRequest) {

                        //Nhập giá
                        $products = $fields['products'];

                        for ($i = 0; $i < count($products); $i++) {
                            $products[$i]['price_req_id'] = $priceRequest->id;

                            if (isset($products[$i]['id']) && $products[$i]['id'] != 0) {
                                $product = PriceProduct::find($products[$i]['id']);
                                $product->fill($products[$i]);
                                $product->save();
                            } else {
                                // dd($products[$i]);
                                $product = PriceProduct::create($products[$i]);
                            }
                            $specs = $products[$i]['specs'];
                            for ($j = 0; $j < count($specs); $j++) {
                                $specs[$j]['price_product_id'] = $product->id;
                                if (isset($specs[$j]['id']) && $specs[$j]['id'] != 0) {
                                    $spec = PriceSpec::find($specs[$j]['id']);
                                    $spec->fill($specs[$j]);
                                    $spec->save();
                                } else {

                                    $spec = PriceSpec::create($specs[$j]);
                                }
                                $others = $specs[$j]['others'];

                                for ($h = 0; $h < count($others); $h++) {

                                    $others[$h]['price_product_id'] = $spec->price_product_id;
                                    $others[$h]['price_spec_id'] =  $spec->id;

                                    if (isset($others[$h]['id']) && $others[$h]['id'] != 0) {
                                        $other = PriceSpecitem::find($others[$h]['id']);
                                        $other->fill($others[$h]);
                                        $other->save();
                                    } else {

                                        $other = PriceSpecitem::create($others[$h]);
                                    }
                                }
                                //xóa các other_del
                                $others_del = $specs[$j]['others_del'];
                                for ($h = 0; $h < count($others_del); $h++) {

                                    if (isset($others_del[$h]['id']) && $others_del[$h]['id'] != 0) {
                                        $other = PriceSpecitem::find($others_del[$h]['id']);
                                        $other->delete();
                                    }
                                }
                            }

                            //xóa các spec items
                            $specs_del = $products[$i]['specs_del'];

                            for ($h = 0; $h < count($specs_del); $h++) {

                                if (isset($specs_del[$h]['id']) && $specs_del[$h]['id'] != 0) {
                                    $spec = PriceSpec::find($specs_del[$h]['id']);
                                    $spec->delete();
                                }
                            }

                            //Nhập giá chi tiết
                            $details = $products[$i]['details'];
                            for ($k = 0; $k < count($details); $k++) {
                                $details[$k]['price_product_id'] = $product->id;
                                if (isset($details[$k]['id']) && $details[$k]['id'] != 0) {
                                    $detail = PriceDetail::find($details[$k]['id']);
                                    $detail->fill($details[$k]);
                                    $detail->save();
                                } else {
                                    $detail = PriceDetail::create($details[$k]);
                                }
                                //dd($detail);
                            }
                            $details_del = $products[$i]['details_del'];
                            for ($k = 0; $k < count($details_del); $k++) {

                                if (isset($details_del[$k]['id']) && $details_del[$k]['id'] != 0) {
                                    $detail = PriceDetail::find($details_del[$k]['id']);
                                    $detail->delete();
                                }
                                //dd($detail);
                            }
                        }
                        $products_del = $fields['products_del'];
                        for ($k = 0; $k < count($products_del); $k++) {

                            if (isset($products_del[$k]['id']) && $products_del[$k]['id'] != 0) {
                                $product = PriceProduct::find($products_del[$k]['id']);
                                $product->delete();
                            }
                            //dd($detail);
                        }
                        //File duyệt giá
                        $list_file_duyet_gia = $fields['attachment_file'];
                        for ($i = 0; $i < count($list_file_duyet_gia); $i++) {

                            //Chỉ upload file mới
                            if (!isset($list_file_duyet_gia[$i]['id']) || $list_file_duyet_gia[$i]['id'] == 0) {
                                $file = new File();
                                $file->name = $list_file_duyet_gia[$i]["name"];
                                //$ext = end(explode('.', $filename));
                                // $file->ext = $attachment_file[$i]["ext"];
                                $file->size = $list_file_duyet_gia[$i]["size"];
                                $file->user_id = $user->id;

                                $ext = substr($list_file_duyet_gia[$i]["name"], strrpos($list_file_duyet_gia[$i]["name"], '.') + 1);
                                $name = "public/price/" . $user->username . "/" . uniqid() . '.' . $ext; // $attachment_file[$i]["ext"];

                                $file->ext = $ext;
                                $file->url = $name;
                                $priceRequest->attachment_file()->save($file);

                                //$name = "/avatars/" . $user->username . '.' . $this->request->file['ext'];
                                $base64_str = substr($list_file_duyet_gia[$i]['base64'], strpos($list_file_duyet_gia[$i]['base64'], ",") + 1);
                                $image = base64_decode($base64_str);
                                //file_put_contents(public_path().$name,  $image );
                                Storage::disk('local')->put($name, $image);
                                FacadesFileVault::encrypt($name);
                            }


                    }
                    //delete các file duyệt giá bị xóa
                        $list_file_duyet_gia_del = $fields['attachment_file_removed'];

                        for ($i = 0; $i < count($list_file_duyet_gia_del); $i++) {
                            //Chỉ delete các  file đã được upload
                            if (isset($list_file_duyet_gia_del[$i]['id']) &&  $list_file_duyet_gia_del[$i]['id'] != 0) {
                                $file = File::findOrFail($list_file_duyet_gia_del[$i]["id"]);
                                if ($file) {

                                    Storage::delete($file->url . ".enc");
                                    $file->delete();
                                }
                            }
                        }
                        //Lưu các other_attached mới
                        $other_attacheds = $fields['other_attacheds'];
                        for ($i = 0; $i < count($other_attacheds); $i++) {
                            // $other_attacheds[$i]['priceRequest_id'] = $priceRequest->id;

                            // $other_attacheds[$i]['paycatset_id'] = $paycatset_id ;
                            if (isset($other_attacheds[$i]['id']) && $other_attacheds[$i]['id'] != 0) {
                                $otherAttached = OtherAttached::find($other_attacheds[$i]['id']);
                                $otherAttached->fill($other_attacheds[$i]);
                                $otherAttached->save();
                            } else {

                                $otherAttached = new OtherAttached($other_attacheds[$i]);
                                $priceRequest->other_attacheds()->save($otherAttached);
                            }

                            //save file
                            $attachment_file = $other_attacheds[$i]['attachment_file'];
                            $attachment_file_removed = $other_attacheds[$i]['attachment_file_removed'];
                            //  dd($attachment_file);
                            for ($j = 0; $j < count($attachment_file); $j++) {

                                //chỉ lưu  các file mới
                                if (!isset($attachment_file[$j]["id"])) {
                                    $file = new File();

                                    $file->name = $attachment_file[$j]["name"];
                                    //$ext = end(explode('.', $filename));
                                    // $file->ext = $attachment_file[$i]["ext"];
                                    $file->size = $attachment_file[$j]["size"];
                                    $file->user_id = $user->id;

                                    $ext = substr($attachment_file[$j]["name"], strrpos($attachment_file[$j]["name"], '.') + 1);
                                    $name = "public/price/" . $user->username . "/" . uniqid() . '.' . $ext;

                                    $file->ext = $ext;
                                    $file->url = $name;

                                    $otherAttached->attachment_file()->save($file);
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
                        }

                        //Xoá  payment_attached_del
                        $other_attacheds_del = $fields['other_attacheds_del'];
                        //dd($other_attacheds_del);
                        for ($i = 0; $i < count($other_attacheds_del); $i++) {

                            $otherAttached = OtherAttached::find($other_attacheds_del[$i]['id']);


                            if ($otherAttached) {

                                foreach ($otherAttached->attachment_file as $file) {
                                    Storage::delete($file->url . ".enc");
                                    $file->delete();
                                }
                                $otherAttached->delete();
                            }
                        }
                    }



                    DB::commit();

                    return $priceRequest;
                }
            } catch (\Exception $e) {
                DB::rollback();
                $this->errors = $e->getMessage();
            }
        }

        return null;
    }
}
