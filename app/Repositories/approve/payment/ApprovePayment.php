<?php

namespace App\Repositories\approve\payment;

use App\Http\Controllers\frontend\payment\PayRequestController;
use App\Jobs\SendEmail;
use App\Mail\EmailDocumentSuccess;
use App\Mail\EmailPayequest;
use App\Mail\EmailPayrequestSuccess;

use App\Models\payment\contract\Contract;
use App\Models\payment\contract\ContractTerm;
use App\Models\payment\PaymentTermPlan;
use App\Models\payment\Payrequest;
use App\Models\shared\Approved;
use App\Models\shared\ApprovedStep;
use App\Models\shared\DocumentType;
use App\Models\shared\PrintedDoc;
use App\Models\shared\Team;
use App\Models\shared\Timeline;
use App\Notifications\CommondNotification;
use App\Notifications\NotiBaseModel;
use App\Repositories\approve\ApproveAbs ;
use App\Repositories\DocumentSerials;
use App\Ultils\Ultils;
use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
class ApprovePayment extends ApproveAbs
{

    public function __set($name, $value)
    {
        $this->$name = $value;
    }
    public function __get($name)
    {
        return $this->$name;
    }
    public function index(Request $request)
    {
        // $status = [0, 1, 2];

        // if ($request->status != null || $request->status != '') {
        //     $status = [];
        //     array_push($status, $request->status);
        // }
        // if ($request->status != null || $request->status != '') {
        //     $status = [];
        //     array_push($status, $request->status);
        // }
        $status  = explode(',',$request->status);
        if ($status[0]=='') {
            $status = [0, 1, 2];
        }
        // dd($status[0]);
        // dd($status);
        try {
            $payRequest = Payrequest::whereHas('approveds', function (Builder $query) use ($status, $request) {
                //  dd($request->filled('start_date'));
                if ($request->filled('start_date')) {
                    if (!$request->filled('end_date')) {
                        $request->end_date = $request->start_date;
                    }
                    $start_date = Carbon::create($request->start_date);
                    $end_date = Carbon::create($request->end_date);
                    $end_date->addHours(23);
                    $end_date->addMinutes(59);
                    $end_date->addSeconds(59);

                    $query->whereBetween('created_at', [$start_date, $end_date]);
                    //  dd("test");
                }
                $query->where('user_id', auth()->user()->id)
                    ->whereIn('release', $status)
                    ->where('online', 'X')
                ;
            });

             if ($request->filled('hide_doc_cancel')) {
                if ($request->hide_doc_cancel == "true") {
                    // dd($request->hide_doc_cancel);
                    $payRequest = $payRequest->where('status','<>', '-1');
                }

            }
            if ($request->filled('company_id')) {
                $payRequest = $payRequest->where('company_id', $request->company_id);
            }
            if ($request->filled('department_id')) {
                $payRequest = $payRequest->where('department_id', $request->department_id);
            }
            if ($request->filled('document_type_id')) {
                $payRequest = $payRequest->where('document_type_id', $request->document_type_id);
            }
            if ($request->filled('serial_num')) {
                $payRequest = $payRequest->where('serial_num', $request->serial_num);
            }
            if ($request->filled('vendor_id')) {
                $payRequest = $payRequest->where('vendor_id', $request->vendor_id);
            }
            if ($request->filled('contract_num') || $request->filled('contract_type') ) {

                $contract_query = Contract::query();

                if ($request->filled('contract_num')) {
                    $contract_query = $contract_query->where('contract_num', $request->contract_num);
                }

                if ($request->filled('contract_type')) {
                    $contract_query = $contract_query->where('contract_type', $request->contract_type);
                }

                // if ($request->filled('vendor_id')) {
                //     $contract_query = $contract_query->where('vendor_id', $request->vendor_id);
                // }
                //  dd('tesst');
                $contractlist = $contract_query->get()->pluck('id')->flatten();
                // dd($contractlist);
                $payRequest = $payRequest->whereIn('contract_id', $contractlist);
            }
            $withModel = [ 'approveds', 'user', 'approveds.user'];

            $payRequest = $payRequest->orderBy('created_at', 'desc')->with($withModel)->withCount('approveds')->get();


            foreach ($payRequest as $currentPayment) {
                //if (count($currentPayment->approveds) > 0) {
                    foreach ($currentPayment->approveds as $approve) {

                        if ($approve->user_id == auth()->user()->id && $approve->online == "X") {
                            $currentPayment->waitingApproval = new Approved();
                            $currentPayment->waitingApproval->checkout = $approve->checkout;
                            $currentPayment->waitingApproval->release = $approve->release;
                             }
                    }

                    $lastApprove = $currentPayment->approveds[count($currentPayment->approveds)-1];

                    $currentPayment->lastApprove = new Approved();
                    $currentPayment->lastApprove->created_at = $lastApprove->created_at ;
                    $currentPayment->lastApprove->checkout = $lastApprove->checkout ;
                  
                    if($currentPayment->money_receiver != ""){
                        $currentPayment->receiver = $currentPayment->money_receiver;
                    }else if($currentPayment->vendor){

                        $currentPayment->receiver = $currentPayment->vendor->comp_name;
                        $currentPayment->unsetRelation("vendor");
                    }
                    



                //}

                unset($currentPayment['approveds']);
            }

            return $payRequest;
        } catch (Exception $e) {
            $result['success'] = "0";
            return null;
        }

    }
    public function pending_count()
    {
        $status = [0, 1];

        try {
            $document = Payrequest::whereHas('approveds', function (Builder $query) use ($status) {
                $query->where('user_id', auth()->user()->id)
                    ->whereIn('release', $status)
                    ->where('online', 'X')
                ;
            });

            return  $document->count();
        } catch (Exception $e) {
            return 0;
        }
    }
    //
    public function is_cancel(){

        if($this->object->status == -1){ //đã huỷ
            return true;
        }
        return false;

    }
    public function validate(){

       if($this->is_cancel()){
           return false;
       }
       return true;

    }
    public function notifycation(Approved $approved)
    {
      // dd("test");
       $data = new NotiBaseModel;
       $payRequest  = new Payrequest();
       $payRequest =  $approved->approvedable;
       $documentType = DocumentType::find($payRequest->document_type_id);
       $email = null;
      // dd("test");
        if($approved->finished == 1){
            //Thông báo duyệt hoàn thành
            $data->title = "Đã duyệt " . Str::lower($documentType->name);
            $data->icon = "far fa-credit-card";
            $data->content = $payRequest->serial_num;
            $data->content_full = $documentType->name;
            $data->url =URL('/').'/'. Ultils::$URL_PAYMENT_REQUEST_VIEW . $payRequest->id;
            $data->object_type = Payrequest::class;
            $data->object_id =  $payRequest->id;
            $email = new EmailDocumentSuccess($data,$payRequest->user,$payRequest);
            //Gửi thông báo cho người tạo chứng từ
            $payRequest->user->notify(new CommondNotification($data,$payRequest->user,$email) );
            //Gửi thông báo cho kế toán kiểm soát

            if ($payRequest->teamconfig_id) {
                $team = Team::find($payRequest->teamconfig_id);
                $userccs = $team->userccs;
                //dd($userccs);
                // dd(  $userccs);
                 if($userccs && count($userccs)>0){

                     foreach ($userccs as $cc) {
                        // dd( $cc);
                         $cc->notify(new CommondNotification($data, $cc,$email) );
                     }
                 }
            }

        }else if($approved->release == 1){
            //Gửi phản hồi duyệt
            $data->title = "Phản hồi duyệt " .Str::lower($documentType->name);
            $data->icon = "far fa-credit-card";

            $data->content = $payRequest->serial_num;

            $data->url = URL('/').'/'.Ultils::$URL_PAYMENT_REQUEST_VIEW  . $payRequest->id;
            $data->object_type = Payrequest::class;
            $data->object_id =  $payRequest->id;

            $email = new EmailPayequest($data,$payRequest->user,$payRequest);
            // $sendEmailJob = new SendEmail($email ,$payRequest->user->email );
            // dispatch($sendEmailJob);
            $payRequest->timelines()->save(new Timeline(['title'=>"form.feedback",'icon'=>'fas fa-comments bg-warning','content'=>$approved->note,'user_id'=>auth()->user()->id]));
            $payRequest->user->notify(new CommondNotification($data,$payRequest->user,$email) );



        }else{
            //Gửi yêu cầu duyệt
            $data->title = "Yêu cầu duyệt " . Str::lower($documentType->name);
            $data->icon = "far fa-credit-card";
            $data->content = $payRequest->serial_num;
            $data->url =URL('/').'/'. Ultils::$URL_APPROVE_REQUEST_VIEW . $payRequest->id;
            $data->object_type = Payrequest::class;
            $data->object_id =  $payRequest->id;

            $email = new EmailPayequest($data, $approved->user,$payRequest);
           // dd($email);
            // $sendEmailJob = new SendEmail($email ,$approved->user->email );
            // dispatch($sendEmailJob);


            $approved->user->notify(new CommondNotification($data,$approved->user,$email) );

        }
       // dd("test");



    }
    public function update_status_send()
    {
        $this->object->timelines()->save(new Timeline(['title'=>"form.send_approve",'icon'=>'fas fa-paper-plane bg-info','user_id'=>auth()->user()->id]));
        //cập nhật ngày gửi
        $this->object->status = 1;
        $this->object->locked = 1;
        $this->object->send_date = now();
        //Cấp dãy số sau khi lưu thành công -> thay đổi: sau khi gửi thành công nó sẽ cấp số
        // if ( $this->object->serial_num == null) {
        //     try {
        //         $documentType = DocumentType::find($this->object->document_type_id);

        //         if ($documentType) {
        //             $this->object->serial_num = DocumentSerials::getSerial(Ultils::$MODULE_PAYMENT, $documentType->code, $this->object->company_id,
        //                 Ultils::$MODULE_PAYMENT_FORMAT_SERIAL_NUMBER, true);
        //          }

        //     } catch (\Exception $e1) {
        //         $this->errors = $e1->getMessage();
        //         //$validator->errors()->add('serial_number', $e1->getMessage());
        //        // $this->errors = $validator->errors();
        //        throw new Exception( $e1->getMessage());
        //        // return null;
        //      //  dd($this->errors);
        //     }
        // }
        //dd("test");
        $this->object->save();
    }
    public function update_status_agree()
    {
        $this->object->timelines()->save(new Timeline(['title'=>"form.approved",'icon'=>'fas fa-check bg-success','user_id'=>auth()->user()->id]));
    }
    public function update_status_refuse()
    {

        $this->object->locked = 0;
        $this->object->save();
    }
    public function update_status_agree_finish()
    {
        $this->object->timelines()->save(new Timeline(['title'=>"form.complete_approval",'icon'=>'fas fa-check-circle bg-success','user_id'=>auth()->user()->id]));
        //Xác định người duyệt cuối

        $term_release_count = 0;
        $payRequest = $this->object;

        //Lưu html chứng từ vào bảng
        $con = new PayRequestController;
        $html =  $con->print(new Request ,$this->object->id);
        $this->save_printed_doc($html,$this->object);

        //end lưu html


        $contract = Contract::find($payRequest->contract_id);

        if ($contract) {

            //1.Hợp đồng số tiền cố định thì
            if ($contract->contract_type == 1) {
                foreach ($contract->contract_terms as $key => $term) {

                    if ($term->status == 2) {
                        $term_release_count = $term_release_count + 1;
                    }
                }
                //cập nhật số tiền vào Điều Khoản
                $contract_term = ContractTerm::find($payRequest->contract_term_id);
                if ($contract_term) {
                    $contract_term->amount_paid = $contract_term->amount_paid + $payRequest->amount;
                    //tổng số tiền yêu cầu phải lớn hoặc bằng số tiền trong điều khoản thì xác nhận hoàn tất
                    if (($payRequest->amount + $contract_term->amount_paid) >= $contract_term->amount) {
                        $contract_term->status = 2; //Hoàn tất
                        if (count($contract->contract_terms) - $term_release_count == 1) {
                            // dd('cập nhật lại thông tin hợp đồng đã hoàn tất');
                            $contract->payment_status = 2;
                            $contract->save();
                            //    dd('cập nhật contract');
                        }
                    }
                    $contract_term->date_paid = now();
                    $contract_term->save();
                }
            } else { //2. Hợp đồng thanh toán định kỳ và hợp đồng nguyên tắc thì thêm vào bảng kế hoạch để thống kê

                $payment_term_plan = new PaymentTermPlan();
                //cập nhật số tiền vào Điều Khoản
                $contract_term = ContractTerm::find($payRequest->contract_term_id);
                if ($contract_term) {
                    $payment_term_plan->contract_term_id = $contract_term->id;
                    $payment_term_plan->terms_num = $contract_term->terms_num;
                    $payment_term_plan->content = $contract_term->content;
                    $payment_term_plan->date_due = $contract_term->date_due;
                    $payment_term_plan->amount = $contract_term->amount;
                    $payment_term_plan->date_paid = now();
                    $payment_term_plan->amount_paid = $payRequest->amount;
                    $payment_term_plan->status = 3;
                    $payment_term_plan->term_content = $contract_term->term_content;
                    $payment_term_plan->note = $contract_term->note;
                    $payment_term_plan->sub_contract_id = $contract_term->sub_contract_id;
                    $payment_term_plan->user_id = $payRequest->user_id; //Người yêu cầu thanh toán lập kế hoạch
                }
                $payment_term_plan->contract_id = $contract->id;

                $payment_term_plan->save();

            }

        }
        //thông báo cho kế toán khi duyệt xong

        //  dd('test o day');
        //cập nhật lại trạng thái chứng từ
        $payRequest->status = 2; //Duyệt xong

        $payRequest->save();

    }

    public function get_step_name($stepid){
      $name = "";
        ;

      $documentType = DocumentType::where('id',$this->object->document_type_id)->first();

      if($documentType){
        $step = ApprovedStep::where('document_type_id',$documentType->id)->where('step',$stepid)->first();
        $name=   $step?$step->name:"";
       // dd($documentType->id);
      }
      if($name == ''){
        $name = parent::get_step_name($stepid);
      }

        return $name;

    }

}
