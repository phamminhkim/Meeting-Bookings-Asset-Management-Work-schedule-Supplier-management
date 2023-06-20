<?php

namespace App\Repositories\approve\document;

use App\Http\Controllers\frontend\document\DocumentFrontend;
use App\Http\Controllers\frontend\payment\PayRequestController;
use App\Jobs\SendEmail;
use App\Jobs\SignPdf;
use App\Mail\EmailDocumentRequest;
use App\Mail\EmailDocumentSuccess;
use App\Mail\EmailPayequest;
use App\Models\document\Document;

use App\Models\shared\Approved;
use App\Models\shared\ApprovedStep;
use App\Models\shared\DocumentType;
use App\Models\shared\Team;
use App\Models\shared\Timeline;
use App\Notifications\ApprovedPaymentNoti;
use App\Notifications\CommondNotification;
use App\Notifications\NotiBaseModel;
use App\Repositories\approve\ApproveAbs ;
use App\Repositories\DocumentSerials;
use App\Ultils\Pdf\ThienLongPDF;
use App\Ultils\Ultils;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use SoareCostin\FileVault\Facades\FileVault;
use SoareCostin\FileVault\FileVault as FileVaultFileVault;

class ApproveDocument extends ApproveAbs
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
        $status  = explode(',',$request->status);
        if ($status[0]=='') {
            $status = [0, 1, 2];
        }

        // dd($status);
        try {
            $document = Document::whereHas('approveds', function (Builder $query) use ($status, $request) {
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
                     //dd($request->hide_doc_cancel);
                    $document = $document->where('status','<>', '-1');
                }

            }
            if ($request->filled('company_id')) {
                $document = $document->where('company_id', $request->company_id);
            }
            if ($request->filled('department_id')) {
                $document = $document->where('department_id', $request->department_id);
            }
            if ($request->filled('document_type_id')) {
                $document = $document->where('document_type_id', $request->document_type_id);
            }
            if ($request->filled('serial_num')) {
                $document = $document->where('serial_num', $request->serial_num);
            }
            if ($request->filled('docbrowser_type_id')) {
                
                $document = $document->whereIn('docbrowser_type_id', explode(",", $request->docbrowser_type_id) );
             }
            $withModel = [ 'approveds', 'user', 'approveds.user','docbrowser_type'];

            $document = $document->orderBy('created_at', 'desc')->with($withModel)->withCount('approveds')->get();

            foreach ($document as $currentDoc) {
                //if (count($currentPayment->approveds) > 0) {
                    foreach ($currentDoc->approveds as $approve) {
                        if ($approve->user_id == auth()->user()->id && $approve->online == "X") {
                            $currentDoc->waitingApproval = new Approved();
                            $currentDoc->waitingApproval->checkout = $approve->checkout;
                            $currentDoc->waitingApproval->release = $approve->release;
                             }
                    }
                    $lastApprove = $currentDoc->approveds[count($currentDoc->approveds)-1];
                    $currentDoc->lastApprove = new Approved();
                    $currentDoc->lastApprove->created_at = $lastApprove->created_at ;
                    $currentDoc->lastApprove->checkout = $lastApprove->checkout ;
                //}
                unset($currentDoc['approveds']);
            }

            return $document->makeHidden(['content']);;
        } catch (Exception $e) {
            $result['success'] = "0";
            return null;
        }

    }
    public function pending_count()
    {
        $status = [0, 1];

        try {
            $document = Document::whereHas('approveds', function (Builder $query) use ($status) {
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

       $data = new NotiBaseModel;
       $document  = new document();
       $document =  $approved->approvedable;
       $documentType = DocumentType::find($document->document_type_id);

        if($approved->finished == 1){
            $data->title = "Đã duyệt " . Str::lower($documentType->name);
            $data->icon = "fas fa-briefcase";
            $data->content = $document->serial_num;
            $data->content_full = $documentType->name;
            $data->url = URL('/').'/'. Ultils::$URL_DOCUMENT_VIEW . $document->id;
            $data->object_type = Document::class;
            $data->object_id =  $document->id;
            //$approved->user = $document->user;
            // $email = new EmailPayequest($data, $document->user);
            // $sendEmailJob = new SendEmail($email ,$document->user->email );
            // dispatch($sendEmailJob);
            $email = new EmailDocumentSuccess($data,$document->user,$document);
            $document->user->notify(new CommondNotification($data,$document->user,$email) );
              //Gửi thông báo  cc

              if ($document->teamconfig_id) {
               // dd("NO");
                $team = Team::find($document->teamconfig_id);
                $userccs = $team->userccs;
               // dd($userccs);
                // dd(  $userccs);
                 if($userccs && count($userccs)>0){

                     foreach ($userccs as $cc) {
                         $cc->notify(new CommondNotification($data, $cc,$email) );
                     }
                 }

              }

        }else if($approved->release == 1){

            $data->title = "Phản hồi duyệt " . Str::lower($documentType->name);
            $data->icon = "fas fa-briefcase";

            $data->content = $document->serial_num;

            $data->url = URL('/').'/'. Ultils::$URL_DOCUMENT_VIEW  . $document->id;
            $data->object_type = Document::class;
            $data->object_id =  $document->id;
            // $email = new EmailPayequest($data, $document->user);
            // $sendEmailJob = new SendEmail($email ,$document->user->email );
            // dispatch($sendEmailJob);
            $email = new EmailDocumentRequest($data,$document->user,$document);
            $document->timelines()->save(new Timeline(['title'=>"form.feedback",'icon'=>'fas fa-comments bg-warning','content'=>$approved->note,'user_id'=>auth()->user()->id]));
            $document->user->notify(new CommondNotification($data,$document->user,$email) );


        }else{

            $data->title = "Yêu cầu duyệt " .Str::lower($documentType->name);
            $data->icon = "fas fa-briefcase";
            $data->content = $document->serial_num;
            $data->url = URL('/').'/'.Ultils::$URL_APPROVE_DOCUMENT_VIEW . $document->id;
            $data->object_type = Document::class;
            $data->object_id =  $document->id;

            $email = new EmailDocumentRequest($data, $approved->user,$document);
            // $email = new EmailPayequest($data, $approved->user);
            // $sendEmailJob = new SendEmail($email ,$approved->user->email );
            // dispatch($sendEmailJob);

            $approved->user->notify(new CommondNotification($data,$approved->user,$email) );

        }



    }
    public function update_status_send()
    {
        $this->object->timelines()->save(new Timeline(['title'=>"form.send_approve",'icon'=>'fas fa-paper-plane bg-info','user_id'=>auth()->user()->id]));
        //cập nhật ngày gửi
        $this->object->status = 1;
        $this->object->locked = 1;
        $this->object->send_date = now();
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
        $document = $this->object;

        //Lưu html chứng từ vào bảng
        $con = new DocumentFrontend;
        $html =  $con->print(new Request ,$this->object->id);
        $this->save_printed_doc($html,$this->object);
        //end lưu html

        //  dd('test o day');
        //cập nhật lại trạng thái chứng từ
        $document->status = 2; //Duyệt xong

        $document->save();
        //Đẩy file trình ký xuống JOB, sau khi xử lý file nào sẽ gửi email báo cho user
        $url = URL('/').'/'. Ultils::$URL_DOCUMENT_VIEW . $document->id;
       $SignPdfJob = new SignPdf($document,$url);
       dispatch($SignPdfJob);

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
