<?php

namespace App\Repositories\payment;

use App\Models\payment\contract\Contract;
use App\Models\payment\contract\ContractTerm;
use App\Models\payment\PaymentAdvanceMoney;
use App\Models\payment\PaymentAttached;
use App\Models\payment\PaymentVoucher;
use App\Models\payment\Payrequest;
use App\Models\shared\Approved;
use App\Models\shared\ApprovedRouting;
use App\Models\shared\DocumentType;
use App\Models\shared\File;
use App\Models\shared\Group;
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

abstract class PayrquestAbs
{
    protected $payRequest;
    protected $request;
    protected $errors;
    protected $message;
    protected $layout;
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
        //DB::enableQueryLog();

        $result = array();
        $result['data'] = array();
        $result['success'] = "0";
        $query = Payrequest::query();
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
            // $query = $query->whereIn('status',  $status);
            //Lấy theo loại chứng từ

            if ($this->request->filled('document_type_id') && $this->request->document_type_id != "undefined" && $this->request->document_type_id != "null") {
                $query = $query->whereIn('document_type_id', explode(",", $this->request->document_type_id));
            }

            // $query = $query->where('company_id', $this->request->company_id);
            if ($this->request->filled('group_id') && $this->request->group_id != "undefined" &&  $this->request->group_id != "null") {
                $query = $query->whereIn('group_id', explode(",", $this->request->group_id));
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
            if ($this->request->filled('vendor_id')) {
                $query = $query->where('vendor_id', $this->request->vendor_id);
            }
            if ($this->request->filled('method_payment')) {
                $query = $query->where('method_payment', $this->request->method_payment);
            }
            if ($this->request->filled('miss_invoice')) {
                $query = $query->where('miss_invoice', $this->request->miss_invoice);
            }

            // dd($this->request->visibility);
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

            if ($this->request->filled('contract_num') || $this->request->filled('contract_type')) {

                $contract_query = Contract::query();

                if ($this->request->filled('company_id')) {
                    $contract_query = $contract_query->where('company_id', $this->request->company_id);
                }

                if ($this->request->filled('department_id')) {
                    $contract_query = $contract_query->where('department_id', $this->request->department_id);
                }
                if ($this->request->filled('contract_num')) {
                    $contract_query = $contract_query->where('contract_num', $this->request->contract_num);
                }

                if ($this->request->filled('contract_type')) {
                    $contract_query = $contract_query->where('contract_type', $this->request->contract_type);
                }

                // if ($this->request->filled('vendor_id')) {
                //     $contract_query = $contract_query->where('vendor_id', $this->request->vendor_id);
                // }

                $contractlist = $contract_query->get()->pluck('id')->flatten();
                //dd($contractlist);
                $query = $query->whereIn('contract_id', $contractlist);
            }
            //Tìm theo Chứng từ tham chiếu
            if ($this->request->filled('doc_reference')) {
                $query = $query->where('doc_reference', 'like', '%' . $this->request->doc_reference . '%');
            }
            if ($this->request->filled('payment_voucher_doc_num')) {

                $payment_voucher_query = PaymentVoucher::query();
                $payment_voucher_query = $payment_voucher_query->where('doc_num', 'like', '%' . $this->request->payment_voucher_doc_num . '%');
                $payrequest_ids = $payment_voucher_query->get()->pluck('payrequest_id')->flatten();
                //dd($payrequest_ids);
                $query = $query->whereIn('id', $payrequest_ids);
                // dd($payrequest_ids);
            }
            if ($this->request->filled('payment_voucher_costcenter')) {

                $payment_voucher_query = PaymentVoucher::query();
                $payment_voucher_query = $payment_voucher_query->where('costcenter', 'like', '%' . $this->request->payment_voucher_costcenter . '%');
                $payrequest_ids = $payment_voucher_query->get()->pluck('payrequest_id')->flatten();
                //dd($payrequest_ids);
                $query = $query->whereIn('id', $payrequest_ids);
                // dd($payrequest_ids);
            }
            if ($this->request->filled('payment_voucher_prpo_num')) {

                $payment_voucher_query = PaymentVoucher::query();
                $payment_voucher_query = $payment_voucher_query->where('prpo_num', 'like', '%' . $this->request->payment_voucher_prpo_num . '%');
                $payrequest_ids = $payment_voucher_query->get()->pluck('payrequest_id')->flatten();
                //dd($payrequest_ids);
                $query = $query->whereIn('id', $payrequest_ids);
                // dd($payrequest_ids);
            }


            if ($this->request->filled('payment_voucher_doc_date_from')) {
                if (!$this->request->filled('payment_voucher_doc_date_to')) {
                    $this->request->payment_voucher_doc_date_to = $this->request->payment_voucher_doc_date_from;
                }
                $start_date = Carbon::create($this->request->payment_voucher_doc_date_from);
                $end_date = Carbon::create($this->request->payment_voucher_doc_date_to);

                $payment_voucher_query = PaymentVoucher::query();
                $payment_voucher_query = $payment_voucher_query->whereBetween('doc_date', [$start_date, $end_date]);
                $payrequest_ids = $payment_voucher_query->get()->pluck('payrequest_id')->flatten();
                //dd($payrequest_ids);
                $query = $query->whereIn('id', $payrequest_ids);
            }
            if ($this->request->feedback === "true") {
                $query = $query->whereHas('approveds', function (Builder $query) {
                    $query->where('note', '<>', null);
                });
            }
            $is_excel = false;
            if ($this->request->filled('is_excel')) {

                $is_excel = true;
            }
        } catch (Exception $e) {
            $this->errors = $e->getMessage();
        }
        $user = new User();
        $user = User::find(auth()->user()->id);

        $withModel = ['approveds', 'user', 'reminders'];
        if ($user->hasPermission('review_all_yctt')) {
            $payRequest = $query->orderBy('id', 'desc')->with($withModel)->get();
        } else if ($user->hasPermission('review_company_yctt')) {
            //vd: nhân viên kế toán  ở TLG thì thấy các chứng từ TLG hoặc các chứng từ mà nhân viên này được có trong group ở công ty khác
            $company_id = $user->company_id;
            // $payRequest = $query->where('company_id',$company_id)->orderBy('id', 'desc')->with($withModel)->withCount('approveds')->get();
            $group_ids = $user->groups->pluck('id')->flatten(); //pluck('id')->flatten();
            // $payRequest = $query->orWhereIn('group_id', $group_ids)->orderBy('id', 'desc')->with($withModel)->withCount('approveds')->get();
            $payRequest = $query->where(function ($q) use ($company_id, $group_ids) {
                $q->where('company_id', $company_id)
                    ->orWhereIn('group_id', $group_ids);
            });
            $payRequest = $payRequest->orderBy('id', 'desc')->with($withModel)->get();
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


            $payRequest = $query->whereIn('group_id', $group_ids)->orderBy('id', 'desc')->with($withModel)->get();
        }


        foreach ($payRequest as $currentRequest) {
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
            if($is_excel){
                $currentRequest->gl_accounts = "";
                $gl_accounts = $currentRequest->payment_vouchers->pluck('gl_account')->flatten();
                $currentRequest->gl_accounts = implode(", ",$gl_accounts->toArray());
                unset($currentRequest['payment_vouchers']);
            }
        }

        return $payRequest;
    }
    public function check_advance_money($payment_advance_moneys)
    {


        //Kiểm tra chứng từ này đã được tạm ứng chưa
        $check = true;

        // dd($payment_advance_moneys);
        for ($i = 0; $i < count($payment_advance_moneys); $i++) {
            $advance_money_id = "";
            if (isset($payment_advance_moneys[$i]['advance_money_id'])) {
                $advance_money_id = $payment_advance_moneys[$i]['advance_money_id'];
            }
            // $advance_money_id = $payment_advance_moneys[$i]['advance_money_id'];

            //Lấy chứng từ tạm ứng
            $payRequest = Payrequest::find($advance_money_id);
            //kiểm tra chứng từ vào có phải là chứng từ tạm ứng hay không

            if (isset($payRequest->document_type) && $payRequest->document_type && $payRequest->document_type->code != Ultils::$MODULE_PAYMENT_DNTU) {
                $check = false;
            } else {
                //Kiểm tra tồn tại trong bảng hoàn ứng

                $payment_advance_money = PaymentAdvanceMoney::where('advance_money_id', $advance_money_id)->first();

                if ($payment_advance_money) {
                    //Nếu đã tồn tại nhưng không phải là cập nhật

                    if (!isset($payment_advance_moneys[$i]['id']) || $payment_advance_moneys[$i]['id'] != $payment_advance_money->id) {
                        $check = false;
                    }
                }
            }
        }
        return $check;
    }
    //Lấy chứng từ quyết toán
    public function get_settlement_doc($payrequest_id)
    {


        $result = array();
        $result['data'] = array();
        $result['success'] = "0";


        $advance_money = PaymentAdvanceMoney::where('advance_money_id', $payrequest_id)->first();
        if ($advance_money) {
            $payrequest = Payrequest::find($advance_money->payrequest_id);
            return $payrequest;
        } else {
            return null;
        }
    }
    //Lấy danh sách tạm ứng đã được duyệt
    public function get_advance_money()
    {

        $result = array();
        $result['data'] = array();
        $result['success'] = "0";
        $query = Payrequest::query();
        $user = new User();
        $user = User::find(auth()->user()->id);
        $document_type = DocumentType::where('code', Ultils::$MODULE_PAYMENT_DNTU)->first();

        $document_type_id = 0;
        if ($document_type) {
            $document_type_id = $document_type->id;
        }

        $query = $query->where('user_id', $user->id)->where('document_type_id', $document_type_id);

        $query = $query->whereHas('approveds', function (Builder $query) {
            $query->where('finished', '=', '1');
            $query->where('online', '=', 'X');
        });
        $query = $query->whereNotIn('id', DB::table('payment_advance_money')->pluck('advance_money_id'));
        if ($this->request->filled('serial_num')) {
            $query = $query->where('serial_num', $this->request->serial_num);
        }
        $payRequest = $query->orderBy('id', 'desc')->get();
        //   dd($payRequest );
        return $payRequest;
    }
    protected function validate_store()
    {

        $validator = Validator::make($this->request->all(), [
            'amount' => 'required',
            'content' => 'required|max:255',
            'proposer_payment' => 'required',
            'group_id' => 'required',
            'budget_type' => 'required',
            'payment_type_id' => 'required',

            'method_payment' => 'required',
            // 'finish_date' => 'sometimes|date|min:10|max:10',
            'has_contract' => 'required|bool',
            'contract_id' => 'exclude_if:has_contract,false|exists:contracts,id',
            //kiểm tra điều khoản có thuộc về hợp đồng không?
            'contract_term_id' => 'exclude_if:has_contract,false|exists:contract_terms,id,contract_id,' . $this->request->contract_id,
            'payment_attacheds.*.name' => 'required|max:50',
            'payment_attacheds.*.note' => 'sometimes|max:50',

        ], [
            'payment_attacheds.*.name.required' => 'Tab chứng từ kèm không được rỗng',
            'payment_attacheds.*.name.max' => 'Tab chứng từ kèm : tên tối đa 50 kí tự',             
            'payment_attacheds.*.note.max' => 'Tab chứng từ kèm : ghi chú tối đa 50 kí tự',
            'amount.required' => 'Số tiền đề nghị không được rỗng',
            'content.required' => 'Mục đích không được rỗng',
            'proposer_payment.required' => 'Người đề nghị không được rỗng',
            'group_id.required' => 'Nhóm không được rỗng',
            'payment_type_id.required' => 'Loại thanh toán không được rỗng',
            'budget_type.required' => 'Ngân sách không được rỗng',
            'method_payment.required' => 'Hình thức thanh toán không được rỗng',
            'finish_date.max' => 'Thời hạn thanh toán : Tối đa 10 kí tự',
        ]);
        $fields = $this->request->all();
        // $validator->after(function($validator) {
        //     $validator->errors()->add('dieukhoanhopdong', 'Điều khoản này đã được thanh toán');

        // });
        //  dd($fields['method_payment']);
        if (isset($fields['finish_date']) && $fields['finish_date'] != '' && $fields['method_payment'] == '1') {
            $time = Carbon::parse($fields['finish_date']);
            if (Ultils::is_weekend($time->year, $time->month, $time->day)) {
                $validator->after(function ($validator) {
                    $validator->errors()->add('finish_date', 'Thời hạn thanh toán: Vui lòng không chọn ngày thứ 7, CN đối với chuyển khoản');
                });
            }
        }
        if (isset($fields['contract_term_id']) && $fields['contract_term_id'] != '') {

            // dd($fields['contract_term_id']);
            $contract_term = ContractTerm::find($fields['contract_term_id']);
            if (
                $contract_term && $contract_term->contract
                && $contract_term->contract->contract_type == 1
                && $contract_term->status == 2
            ) {
                $validator->after(function ($validator) {
                    $validator->errors()->add('dieukhoanhopdong', 'Điều khoản này đã được thanh toán');
                });
            }
        }



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
        $group = Group::find($fields['group_id']);
        if ($group->company) {

            $team_id = ApproveRoutingHelper::get_team(
                $group->company->id,
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
        $doc_type = DocumentType::find( $fields['document_type_id']);
        if($doc_type && $doc_type->code != 'DNTU' && $doc_type->code != 'QTTU'){
            if(!$this->checkTotal($fields))
            {
                $validator->after(function ($validator) {
                    $validator->errors()->add('chenhlech', 'Số tiền đề nghị và tổng tiền của chứng từ thanh toán chưa khớp.');
    
                });
            }
        }
        


        return $validator;
    }

    /**
     * Return : Payrequest
     */
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

        //dd($failed);
        if ($failed) {
            $this->errors = $validator->errors();
        } else {

            try {

                DB::beginTransaction();
                $user = new User();
                $user = auth()->user();
                $fields['user_id'] = $user->id;
                // $fields['company_id'] = $user->company->id;
                // $fields['department_id'] = $user->department->id;

                $group = Group::find($fields['group_id']);

                $fields['company_id'] =   $group->company_id;
                $fields['department_id'] = $group->department->id;

                $team_id = ApproveRoutingHelper::get_team(
                    $fields['company_id'],
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
                //dd(  $fields['team_id'] );
                $payRequest = Payrequest::create($fields);
                // dd($payRequest);
                if ($payRequest) {

                    //Lưu tạm ứng
                    $payment_advance_moneys = $fields['payment_advance_moneys'];
                    for ($i = 0; $i < count($payment_advance_moneys); $i++) {
                        $payment_advance_moneys[$i]['payrequest_id'] = $payRequest->id;
                        if (isset($payment_advance_moneys[$i]['id']) && $payment_advance_moneys[$i]['id'] != 0) {
                            $payment_advance_money = PaymentAdvanceMoney::find($payment_advance_moneys[$i]['id']);
                            //dd($payment_advance_moneys[$i]);
                            $payment_advance_money->fill($payment_advance_moneys[$i]);
                            $payment_advance_money->save();
                        } else {

                            $payment_advance_money = PaymentAdvanceMoney::create($payment_advance_moneys[$i]);
                        }
                    }

                    $payment_vouchers = $fields['payment_vouchers'];

                    for ($i = 0; $i < count($payment_vouchers); $i++) {
                        $payment_vouchers[$i]['payrequest_id'] = $payRequest->id;
                        if (isset($payment_vouchers[$i]['id']) && $payment_vouchers[$i]['id'] != 0) {
                            $payment_voucher = PaymentVoucher::find($payment_vouchers[$i]['id']);
                            $payment_voucher->fill($payment_vouchers[$i]);
                            $payment_voucher->save();
                        } else {

                            $payment_voucher = PaymentVoucher::create($payment_vouchers[$i]);
                        }
                        $attachment_file = $payment_vouchers[$i]['attachment_file'];

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

                                $ext = substr($attachment_file[$j]["name"], strrpos($attachment_file[$j]["name"], '.') + 1);
                                $name = "public/payment/" . $user->username . "/" . uniqid() . '.' . $ext;

                                $file->ext = $ext;
                                $file->url = $name;
                                $payment_voucher->attachment_file()->save($file);

                                //$name = "/avatars/" . $user->username . '.' . $this->request->file['ext'];
                                $base64_str = substr($attachment_file[$j]['base64'], strpos($attachment_file[$j]['base64'], ",") + 1);
                                $image = base64_decode($base64_str);
                                //file_put_contents(public_path().$name,  $image );
                                Storage::disk('local')->put($name, $image);
                                FacadesFileVault::encrypt($name);
                            }
                        }
                    }

                    $paycatset_id = $fields['paycatset_id'];
                    //
                    $payment_attacheds = $fields['payment_attacheds'];

                    for ($i = 0; $i < count($payment_attacheds); $i++) {

                        $payment_attacheds[$i]['payrequest_id'] = $payRequest->id;
                        $payment_attacheds[$i]['paycatset_id'] = $paycatset_id;

                        $paymentAttached = PaymentAttached::create($payment_attacheds[$i]);

                        //save file
                        $attachment_file = $payment_attacheds[$i]['attachment_file'];

                        for ($j = 0; $j < count($attachment_file); $j++) {

                            $file = new File();

                            $file->name = $attachment_file[$j]["name"];
                            //$ext = end(explode('.', $filename));
                            // $file->ext = $attachment_file[$i]["ext"];
                            $file->size = $attachment_file[$j]["size"];
                            $file->user_id = $user->id;

                            $ext = substr($attachment_file[$j]["name"], strrpos($attachment_file[$j]["name"], '.') + 1);
                            $name = "public/payment/" . $user->username . "/" . uniqid() . '.' . $ext; // $attachment_file[$i]["ext"];

                            $file->ext = $ext;
                            $file->url = $name;
                            $paymentAttached->attachment_file()->save($file);

                            //$name = "/avatars/" . $user->username . '.' . $this->request->file['ext'];
                            $base64_str = substr($attachment_file[$j]['base64'], strpos($attachment_file[$j]['base64'], ",") + 1);
                            $image = base64_decode($base64_str);
                            //file_put_contents(public_path().$name,  $image );
                            Storage::disk('local')->put($name, $image);
                            FacadesFileVault::encrypt($name);
                        }
                    }
                }
                //cập nhật lại trạng thái thông tin hợp đồng
                $contract = Contract::find($payRequest->contract_id);
                if ($contract) {
                    $contract->payment_status = 2; //Đang thanh  toán
                    $contract->status = 2; //Tình trạng đang xử lý
                    $contract->save();
                }
                //cập nhật lại trạng thái điều khoản hơp đồng
                $contract_term = ContractTerm::find($payRequest->contract_term_id);
                if ($contract_term) {
                    $contract_term->status = 1;
                    $contract_term->save();
                }

                //Cấp dãy số sau khi lưu thành công -> thay đổi: sau khi gửi thành công nó sẽ cấp số
                try {
                    $documentType = DocumentType::find($payRequest->document_type_id);
                    if ($documentType) {
                        $payRequest->serial_num = DocumentSerials::getSerial(
                            Ultils::$MODULE_PAYMENT,
                            $documentType->code,
                            $payRequest->company_id,
                            Ultils::$MODULE_PAYMENT_FORMAT_SERIAL_NUMBER,
                            true
                        );
                        $payRequest->save();
                    }
                } catch (\Exception $e1) {

                    $validator->errors()->add('serial_number', $e1->getMessage());
                    $this->errors = $validator->errors();
                    return null;
                }

                DB::commit();
                return $payRequest;
            } catch (\Exception $e) {
                DB::rollback();

                $this->errors = $e->getMessage();
                return null;
            }
        }
        return null;
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
                    if (isset($result['object_type']) && $result['object_type'] == Payrequest::class && $result['object_id'] == $id) {
                        $noti->markAsRead();
                    }
                } catch (\Throwable $th) {
                    Log::error($th->getMessage());
                }
            });
        }
        //  dd("tesst");
    }
    public function show($id)
    {

        $payRequest = Payrequest::with(
            'contract',
            'contract.contract_terms',
            'payment_attacheds',
            'payment_attacheds.attachment_file',
            'payment_vouchers',
            'payment_vouchers.attachment_file',
            'user',
            'proposer_payment',
            'company',
            'department',
            'group',
            'payrequest_type',
            'bank',
            'vendor',
            'contract_term',
            'team',
            'team.users',
            'approveds',
            'payment_advance_moneys',
            'payment_advance_moneys.refer',
            'reminders',
            'shareds',
            'payment_type',
            'budgetTypeObj',
            'timelines'
        )->find($id);
        foreach ($payRequest->shareds as  $shared) {
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
        return $payRequest;
    }

    public function edit($id)
    {
        $payRequest = Payrequest::with(
            'contract',
            'contract.contract_terms',
            'payment_attacheds',
            'payment_attacheds.attachment_file',
            'payment_vouchers.attachment_file',
            'contract_term',
            'payment_vouchers',
            'payment_advance_moneys',
            'payment_advance_moneys.refer',
            'payment_type'
        )->find($id);

        return $payRequest;
    }


    protected function validate_update($id)
    {

        $validator = Validator::make(
            $this->request->all(),
            [
                'amount' => 'required',
                'payment_type_id' => 'required',
                'content' => 'required|max:255',
                'group_id' => 'required',
                'proposer_payment' => 'required',
                'method_payment' => 'required',
                'has_contract' => 'required|bool',
                'contract_id' => 'exclude_if:has_contract,false|exists:contracts,id',
                //kiểm tra điều khoản có thuộc về hợp đồng không?
                'contract_term_id' => 'exclude_if:has_contract,false|exists:contract_terms,id,contract_id,' . $this->request->contract_id,
                'payment_attacheds.*.name' => 'required|max:50',
                'payment_attacheds.*.note' => 'sometimes|max:50',
            ],
            [
                'payment_attacheds.*.name.required' => 'Tab chứng từ kèm : tên rỗng',
                'payment_attacheds.*.name.max' => 'Tab chứng từ kèm : tên tối đa 50 kí tự',                
                'payment_attacheds.*.note.max' => 'Tab chứng từ kèm : ghi chú tối đa 50 kí tự',
                'amount.required' => 'Số tiền đề nghị không được rỗng',
                'content.required' => 'Mục đích không được rỗng',
                'proposer_payment.required' => 'Người đề nghị không được rỗng',
                'group_id.required' => 'Nhóm không được rỗng',
                'budget_type.required' => 'Ngân sách không được rỗng',
                'method_payment.required' => 'Hình thức thanh toán không được rỗng',
                'finish_date.required' => 'Thời hạn thanh toán không được rỗng',
                'finish_date.max' => 'Thời hạn thanh toán : Tối đa 10 kí tự',

            ]
        );
        $fields = $this->request->all();

        if (isset($fields['contract_term_id']) && $fields['contract_term_id'] != '') {

            // dd($fields['contract_term_id']);
            $contract_term = ContractTerm::find($fields['contract_term_id']);
            if ($contract_term->status == 2) {

                $validator->after(function ($validator) {
                    $validator->errors()->add('dieukhoanhopdong', 'Điều khoản này đã được thanh toán');
                });
            }
        }
        $payRequest = Payrequest::findOrFail($id);
        if ($payRequest->status == 2) {

            $validator->after(function ($validator) {
                $validator->errors()->add('chungtuhoantat', 'Chứng từ đã hoàn tất. Vui lòng không cập nhật.');
            });
        }
        if ($payRequest->status == -1) {

            $validator->after(function ($validator) {
                $validator->errors()->add('chungtuhuy', 'Chứng từ đã huỷ. Vui lòng không cập nhật.');
            });
        }
        if ($payRequest->locked == 1) {

            $validator->after(function ($validator) {
                $validator->errors()->add('locked', 'Chứng từ đang bị khoá. Vui lòng không cập nhật.');
            });
        }
        // dd($fields['method_payment']);
        if (isset($fields['finish_date']) && $fields['finish_date'] != '' && $fields['method_payment'] == '1') {
            $time = Carbon::parse($fields['finish_date']);
            if (Ultils::is_weekend($time->year, $time->month, $time->day)) {
                $validator->after(function ($validator) {
                    $validator->errors()->add('finish_date', 'Thời hạn thanh toán: Vui lòng không chọn ngày thứ 7, CN đối với chuyển khoản');
                });
            }
        }


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
        $group = Group::find($fields['group_id']);
        if ($group->company) {
            $company_id =  $group->company->id;
            $team_id = ApproveRoutingHelper::get_team(
                $group->company->id,
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
            if ($group->company->id != $payRequest->company_id) {
                $validator->after(function ($validator) use ($company_id, $payRequest) {
                    $validator->errors()->add('phantuyen', 'Số chứng từ đã phát sinh cho công ty ' . $payRequest->company_id . ' vui lòng không chuyển chứng từ sang công ty ' . $company_id);
                });
            }
            //Kiểm tra Cập nhật Công ty khác


            //   if($payRequest->team_id <> $team_id){
            //     foreach ( $payRequest->approveds as  $approve) {

            //         if($approve->online == 'X' && $approve->release <> '1'){
            //             $validator->after(function ($validator) {
            //                 $validator->errors()->add('phantuyen', 'Chứng từ đã gửi không thể phân tuyến lại.');
            //             });
            //         }

            //     }

            //  }
        }
        $doc_type = DocumentType::find( $fields['document_type_id']);
        if($doc_type && $doc_type->code != 'DNTU' && $doc_type->code != 'QTTU'){
            if(!$this->checkTotal($fields))
            {
                $validator->after(function ($validator) {
                    $validator->errors()->add('chenhlech', 'Số tiền đề nghị và tổng tiền của chứng từ thanh toán chưa khớp.');
    
                });
            }
        }





        // dd("test");
        return $validator;
    }
    public function update_paid()
    {
        $ids = explode(',', $this->request->ids);
        $doc_paids = array();
        $payRequests = Payrequest::whereIn('id', $ids)->where('status', 2)->get();
        try {
            DB::beginTransaction();
            foreach ($payRequests as $doc) {
                $doc->status = 3; //Đã thanh toán
                if ($doc->save()) {
                    array_push($doc_paids, $doc->id);
                    $doc->timelines()->save(new Timeline(['title' => "form.paid", 'icon' => 'far fa-credit-card', 'user_id' => auth()->user()->id]));
                }
            }

            DB::commit();
            return $doc_paids;
        } catch (\Throwable $th) {
            DB::rollBack();
            return null;
        }


        // if($payRequest->status == 2){ // sau khi duyệt xong
        //     $payRequest->status = 3;//Đã thanh toán
        //     if($payRequest->save()){

        //         $payRequest->timelines()->save(new Timeline(['title'=>"form.paid",'icon'=>'far fa-credit-card','user_id'=>auth()->user()->id]));
        //         return true;
        //     }
        // }
        return null;
    }
    public function update_cancel()
    {
        $user = User::find(Auth::user()->id);

        $payRequest = Payrequest::find($this->request->id);
        //dd($payRequest->status);
        //User hủy khi được trả lại
        if ($user && $user->id == $payRequest->user_id) {
            if ($payRequest->status != null || $payRequest->approveds) {
                $last = $payRequest->approveds->last();
                if ($last && ($last->release != '1' || $last->online != 'X')) {
                    return false;
                }
            }
        }

        if ($payRequest->status == 3) { //Đã thanh toán thì không thể huỷ
            return false;
        }
        $payRequest->status = -1; //Đã huỷ
        if ($payRequest->save()) {
            $payRequest->timelines()->save(new Timeline(['title' => "form.document_cancel", 'icon' => 'fas fa-window-close bg-danger', 'user_id' => auth()->user()->id]));
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
        $payRequest = Payrequest::findOrFail($id);

        if ($failed) {
            $this->errors = $validator->errors();
        } else {

            try {

                if ($payRequest) {
                    DB::beginTransaction();

                    //$fields = $request->all();
                    $user = new User();
                    $user = auth()->user();
                    $fields['user_id'] = $user->id;
                    //dd("dd");
                    // $group = Group::find($fields['group_id']);

                    // $fields['company_id'] =   $group->company_id;
                    // $fields['department_id'] = $group->department->id;

                    // $fields['team_id'] = '1';
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
                    $teamOld = Team::find($payRequest->team_id);
                    if ($teamOld && $teamOld->name == '_AUTO') {
                        //cẩn thận - không xóa các team cấu hình - chỉ xóa các team auto
                        $teamOld->delete();
                    }

                    // $fields['team_id'] = $team_id;
                    //   dd("new id " . $team_id . " - old :". $payRequest->team_id);

                    $fields['locked'] = $payRequest->locked;
                    //dd( $fields['locked']);
                    $payRequest->fill($fields);

                    $payRequest->save();
                    //  dd($payRequest);
                    if ($payRequest) {

                        //Lưu tạm ứng
                        $payment_advance_moneys = $fields['payment_advance_moneys'];
                        for ($i = 0; $i < count($payment_advance_moneys); $i++) {
                            $payment_advance_moneys[$i]['payrequest_id'] = $payRequest->id;
                            if (isset($payment_advance_moneys[$i]['id']) && $payment_advance_moneys[$i]['id'] != 0) {
                                $payment_advance_money = PaymentAdvanceMoney::find($payment_advance_moneys[$i]['id']);
                                //dd($payment_advance_moneys[$i]);
                                $payment_advance_money->fill($payment_advance_moneys[$i]);
                                $payment_advance_money->save();
                            } else {

                                $payment_advance_money = PaymentAdvanceMoney::create($payment_advance_moneys[$i]);
                            }
                        }
                        //các  tạm ứng bị xoá
                        $payment_advance_moneys_del = $fields['payment_advance_moneys_del'];
                        for ($i = 0; $i < count($payment_advance_moneys_del); $i++) {

                            $payment_advance_money = PaymentAdvanceMoney::find($payment_advance_moneys_del[$i]['id']);
                            if ($payment_advance_money) {
                                $payment_advance_money->delete();
                            }
                        }

                        $payment_vouchers = $fields['payment_vouchers'];
                        for ($i = 0; $i < count($payment_vouchers); $i++) {
                            $payment_vouchers[$i]['payrequest_id'] = $payRequest->id;
                            if (isset($payment_vouchers[$i]['id']) && $payment_vouchers[$i]['id'] != 0) {
                                $payment_voucher = PaymentVoucher::find($payment_vouchers[$i]['id']);
                                $payment_voucher->fill($payment_vouchers[$i]);
                                $payment_voucher->save();
                            } else {

                                $payment_voucher = PaymentVoucher::create($payment_vouchers[$i]);
                            }
                            //save file
                            $attachment_file = $payment_vouchers[$i]['attachment_file'];
                            $attachment_file_removed = $payment_vouchers[$i]['attachment_file_removed'];
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

                                    $ext = substr($attachment_file[$j]["name"], strrpos($attachment_file[$j]["name"], '.') + 1);
                                    $name = "public/payment/" . $user->username . "/" . uniqid() . '.' . $ext;

                                    $file->ext = $ext;
                                    $file->url = $name;
                                    $payment_voucher->attachment_file()->save($file);

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
                        //các payment_voucher bị xoá
                        $payment_vouchers_del = $fields['payment_vouchers_del'];
                        for ($i = 0; $i < count($payment_vouchers_del); $i++) {

                            $payment_voucher = PaymentVoucher::find($payment_vouchers_del[$i]['id']);
                            if ($payment_voucher) {

                                foreach ($payment_voucher->attachment_file as $file) {
                                    Storage::delete($file->url . ".enc");
                                    $file->delete();
                                }
                                $payment_voucher->delete();
                            }
                        }
                        ////Sử dụng bộ chứng từ -> không sử dụng
                        // $paycatset_id = $fields['paycatset_id'];
                        // //kiểm tra nếu user thay đổi bộ chứng từ đính kèm,
                        // //-nếu có thay đổi bộ chứng từ thì xoá bộ cũ và các file đính kèm
                        // //-nếu không có thì bỏ qua
                        // if(count($payRequest->payment_attacheds) >0){
                        //     if ($paycatset_id != $payRequest->payment_attacheds[0]->paycatset_id){
                        //         // dd('abc');
                        //          //1.xoá các chứng từ kèm các file cũ, nếu thay đổi bộ chứng từ
                        //          foreach ( $payRequest->payment_attacheds as $payment_attached) {
                        //             //xoá các file
                        //             foreach ($payment_attached->attachment_file as $file) {

                        //                  // dd($file);
                        //                   // Storage::delete($file->url.".enc");
                        //                    Storage::delete( $file->url.".enc");
                        //                   $file->delete();
                        //               }
                        //           }
                        //           //xoá payment_attached cũ
                        //           $payment_attached->delete();

                        //     }
                        // }
                        //dd('xyz'.$paycatset_id.'='.$payRequest->payment_attacheds);

                        //Lưu các payment_attached mới
                        $payment_attacheds = $fields['payment_attacheds'];
                        for ($i = 0; $i < count($payment_attacheds); $i++) {
                            $payment_attacheds[$i]['payrequest_id'] = $payRequest->id;
                            // $payment_attacheds[$i]['paycatset_id'] = $paycatset_id ;
                            if (isset($payment_attacheds[$i]['id']) && $payment_attacheds[$i]['id'] != 0) {
                                $paymentAttached = PaymentAttached::find($payment_attacheds[$i]['id']);
                                $paymentAttached->fill($payment_attacheds[$i]);
                                $paymentAttached->save();
                            } else {

                                $paymentAttached = PaymentAttached::create($payment_attacheds[$i]);
                            }
                            //save file
                            $attachment_file = $payment_attacheds[$i]['attachment_file'];
                            $attachment_file_removed = $payment_attacheds[$i]['attachment_file_removed'];
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
                                    $name = "public/payment/" . $user->username . "/" . uniqid() . '.' . $ext;

                                    $file->ext = $ext;
                                    $file->url = $name;
                                    $paymentAttached->attachment_file()->save($file);

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
                        $payment_attacheds_del = $fields['payment_attacheds_del'];
                        //dd($payment_attacheds_del);
                        for ($i = 0; $i < count($payment_attacheds_del); $i++) {

                            $paymentAttached = PaymentAttached::find($payment_attacheds_del[$i]['id']);
                            if ($paymentAttached) {

                                foreach ($paymentAttached->attachment_file as $file) {
                                    Storage::delete($file->url . ".enc");
                                    $file->delete();
                                }
                                $paymentAttached->delete();
                            }
                        }
                    }

                    //cập nhật lại trạng thái thông tin hợp đồng
                    $contract = Contract::find($payRequest->contract_id);
                    if ($contract) {
                        $contract->payment_status = 1;
                        $contract->save();
                    }
                    //cập nhật lại trạng thái điều khoản hơp đồng
                    $contract_term = ContractTerm::find($payRequest->contract_term_id);
                    if ($contract_term) {

                        $contract_term->status = 1;
                        $contract_term->save();
                    }

                    DB::commit();

                    return $payRequest;
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

        $payRequest = Payrequest::findOrFail($id);

        if (!$payRequest) {
            abort(404);
        }
        if ($payRequest->status == 2) {
            $this->message = "Vui lòng không xoá chứng từ đã hoàn tất!";
        } else {
            try {
                // dd($contract->attachment_file());
                DB::beginTransaction();
                if (count($payRequest->payment_attacheds) > 0) {

                    foreach ($payRequest->payment_attacheds as $payment_attached) {
                        //xoá các file
                        foreach ($payment_attached->attachment_file as $file) {
                            Storage::delete($file->url . ".enc");
                            $file->delete();
                        }
                        //xoá payment_attached
                        $payment_attached->delete();
                    }
                }

                foreach ($payRequest->payment_vouchers as $payment_voucher) {

                    //xoá các file
                    foreach ($payment_voucher->attachment_file as $file) {
                        Storage::delete($file->url . ".enc");
                        $file->delete();
                    }
                    $payment_voucher->delete();
                }

                foreach ($payRequest->approveds as $key => $approved) {
                    $approved->delete();
                }
                foreach ($payRequest->payment_advance_moneys as $key => $advance_money) {
                    $advance_money->delete();
                }

                $payRequest->delete();
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
    public function payment_attached_check()
    {

        $payment_att = PaymentAttached::find($this->request->payment_attached_id);
        //dd("acheck".$payment_att);
        if ($payment_att) {
            $payment_att->checked = $this->request->status;

            if ($payment_att->save()) {
                return true;
            }
        }

        return false;
    }
    public function miss_invoice_check()
    {

        $payrequest = Payrequest::find($this->request->id);
        //dd("acheck".$payment_att);
        if ($payrequest) {
            $payrequest->miss_invoice = $this->request->miss_invoice;

            if ($payrequest->save()) {
                return true;
            }
        }

        return false;
    }
    public function printed_check()
    {

        $payrequest = Payrequest::find($this->request->id);
        //dd("acheck".$payment_att);
        if ($payrequest) {
            $payrequest->printed = $this->request->printed;

            if ($payrequest->save()) {
                return true;
            }
        }

        return false;
    }
    public function update_hard_doc()
    {

        $payrequest = Payrequest::find($this->request->id);
        //dd("acheck".$payment_att);
        if ($payrequest) {
            $payrequest->date_receive_hard_doc = $this->request->date_receive_hard_doc;

            if ($payrequest->save()) {
                $payrequest->timelines()->save(new Timeline(['title' => "form.received_hard_doc", 'icon' => 'far fa-file-alt', 'user_id' => auth()->user()->id]));
                return true;
            }
        }

        return false;
    }
    //Người nhắc nhở
    // public function reminder()
    // {

    //     $payrequest = Payrequest::find($this->request->id);
    //     //dd("acheck".$payment_att);
    //     if ($payrequest) {
    //         $reminder = new Reminder();

    //         $reminder->url = "payment/request?type=view&id=".$this->request->id;
    //         $reminder->content = $this->request->content;
    //         $reminder->date_due = $this->request->date_due;
    //         $reminder->type = $this->request->type;
    //         $reminder->duration = $this->request->duration;
    //         $reminder->start_date = $this->request->start_date;
    //         $reminder->duration_value = $this->request->duration_value;
    //         $reminder->reminded_before_day = $this->request->reminded_before_day;
    //         $reminder->user_id  = auth()->user()->id;

    //         $payrequest->reminder()->save($reminder);
    //         return true;
    //     }

    //     return false;
    // }
    public function statistics()
    {
        //DB::enableQueryLog();

        $result = array();
        $result['data'] = array();
        $result['success'] = "0";
        $query = Payrequest::query();
        $user = Auth::user();
        //dd($this->request->all());

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
            // $query = $query->whereIn('status',  $status);
            //Lấy theo loại chứng từ
            if ($this->request->filled('document_type_id')) {
                $query = $query->where('document_type_id', $this->request->document_type_id);
            }


            // $query = $query->where('company_id', $this->request->company_id);
            if ($this->request->filled('group_id') && $this->request->group_id != "undefined" &&  $this->request->group_id != "null") {
                $query = $query->whereIn('group_id', explode(",", $this->request->group_id));
            }
            if ($this->request->filled('company_id')) {
                $query = $query->where('company_id', $this->request->company_id);
            }
            if ($this->request->filled('department_id')) {
                $query = $query->where('department_id', $this->request->department_id);
            }
            if ($this->request->filled('document_type_id')) {

                $query = $query->where('document_type_id', $this->request->document_type_id);
            }
            if ($this->request->filled('serial_num')) {
                // dd($this->request->serial_num);
                $query = $query->where('serial_num', 'like', '%' . $this->request->serial_num . '%');
            }
            if ($this->request->filled('vendor_id')) {
                $query = $query->where('vendor_id', $this->request->vendor_id);
            }
            if ($this->request->filled('method_payment')) {
                $query = $query->where('method_payment', $this->request->method_payment);
            }
            if ($this->request->filled('miss_invoice')) {
                $query = $query->where('miss_invoice', $this->request->miss_invoice);
            }

            // dd($this->request->visibility);
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

            if ($this->request->filled('contract_num') || $this->request->filled('contract_type')) {

                $contract_query = Contract::query();

                if ($this->request->filled('company_id')) {
                    $contract_query = $contract_query->where('company_id', $this->request->company_id);
                }

                if ($this->request->filled('department_id')) {
                    $contract_query = $contract_query->where('department_id', $this->request->department_id);
                }
                if ($this->request->filled('contract_num')) {
                    $contract_query = $contract_query->where('contract_num', $this->request->contract_num);
                }

                if ($this->request->filled('contract_type')) {
                    $contract_query = $contract_query->where('contract_type', $this->request->contract_type);
                }

                // if ($this->request->filled('vendor_id')) {
                //     $contract_query = $contract_query->where('vendor_id', $this->request->vendor_id);
                // }

                $contractlist = $contract_query->get()->pluck('id')->flatten();
                //dd($contractlist);
                $query = $query->whereIn('contract_id', $contractlist);
            }
            //Tìm theo Chứng từ tham chiếu
            if ($this->request->filled('doc_reference')) {
                $query = $query->where('doc_reference', 'like', '%' . $this->request->doc_reference . '%');
            }
            if ($this->request->filled('payment_voucher_doc_num')) {

                $payment_voucher_query = PaymentVoucher::query();
                $payment_voucher_query = $payment_voucher_query->where('doc_num', 'like', '%' . $this->request->payment_voucher_doc_num . '%');
                $payrequest_ids = $payment_voucher_query->get()->pluck('payrequest_id')->flatten();
                //dd($payrequest_ids);
                $query = $query->whereIn('id', $payrequest_ids);
                // dd($payrequest_ids);
            }
            if ($this->request->filled('payment_voucher_costcenter')) {

                $payment_voucher_query = PaymentVoucher::query();
                $payment_voucher_query = $payment_voucher_query->where('costcenter', 'like', '%' . $this->request->payment_voucher_costcenter . '%');
                $payrequest_ids = $payment_voucher_query->get()->pluck('payrequest_id')->flatten();
                //dd($payrequest_ids);
                $query = $query->whereIn('id', $payrequest_ids);
                // dd($payrequest_ids);
            }
            if ($this->request->filled('payment_voucher_prpo_num')) {

                $payment_voucher_query = PaymentVoucher::query();
                $payment_voucher_query = $payment_voucher_query->where('prpo_num', 'like', '%' . $this->request->payment_voucher_prpo_num . '%');
                $payrequest_ids = $payment_voucher_query->get()->pluck('payrequest_id')->flatten();
                //dd($payrequest_ids);
                $query = $query->whereIn('id', $payrequest_ids);
                // dd($payrequest_ids);
            }

            if ($this->request->filled('payment_voucher_doc_date_from')) {
                if (!$this->request->filled('payment_voucher_doc_date_to')) {
                    $this->request->payment_voucher_doc_date_to = $this->request->payment_voucher_doc_date_from;
                }
                $start_date = Carbon::create($this->request->payment_voucher_doc_date_from);
                $end_date = Carbon::create($this->request->payment_voucher_doc_date_to);

                $payment_voucher_query = PaymentVoucher::query();
                $payment_voucher_query = $payment_voucher_query->whereBetween('doc_date', [$start_date, $end_date]);
                $payrequest_ids = $payment_voucher_query->get()->pluck('payrequest_id')->flatten();
                //dd($payrequest_ids);
                $query = $query->whereIn('id', $payrequest_ids);
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

        $withModel = ['approveds', 'user', 'reminders'];
        if ($user->hasPermission('review_all_yctt')) {


            $payRequest = $query->orderBy('id', 'desc')->with($withModel)->get();
        } else if ($user->hasPermission('review_company_yctt')) {
            $company_id = $user->company_id;
            $payRequest = $query->where('company_id', $company_id)->orderBy('id', 'desc')->with($withModel)->get();
        } else {
            $group_ids = $user->groups->pluck('id')->flatten(); //pluck('id')->flatten();

            $payRequest = $query->whereIn('group_id', $group_ids)->orderBy('id', 'desc')->with($withModel)->get();
        }

        foreach ($payRequest as $currentRequest) {
            foreach ($currentRequest->approveds as $approve) {

                if ($approve->finished == 0 && $approve->release == 0 && $approve->online == "X") {
                    $currentRequest->waitingApproval = User::find($approve->user_id)->name;
                }
                if ($approve->checkout == null) {
                    $currentRequest->waitApprove = User::find($approve->user_id)->name;
                }

                if ($approve->note != null) {
                    $currentRequest->noting =  $currentRequest->noting."<br/>".$approve->note;
                }


            }
            if ($currentRequest->approveds && count($currentRequest->approveds) > 0) {

                $finalApprove = $currentRequest->approveds[count($currentRequest->approveds) - 1];
                if ($finalApprove  && $finalApprove->release == 1) {
                    $currentRequest->isRelease = $finalApprove->release;
                }
            }
            unset($currentRequest['approveds']);
        }

        return $payRequest;
    }
    //KIểm tra tổng số tiền nhập vào so với số tiền
    protected function checkTotal($fields){

        $amount_vourcher =0;
        $amount = 0;

        $payment_vouchers = $fields['payment_vouchers'];
        for ($i = 0; $i < count($payment_vouchers); $i++) {
            $amount_vourcher += $payment_vouchers[$i]['amount'];
        }
        //dd($amount_vourcher . "-" .  $fields['amount']);
        $amount = $fields['amount'];

       // dd( strcmp($amount_vourcher ,$amount)==0?true:false);
        return  strcmp($amount_vourcher ,$amount)==0?true:false;

    }
}
