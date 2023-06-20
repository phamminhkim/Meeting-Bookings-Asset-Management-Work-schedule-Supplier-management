<?php

namespace App\Repositories\approve\workflowdoc;

use App\Repositories\work\workflow\runtime\WorkflowBase;
use App\Http\Controllers\frontend\document\DocumentFrontend;
use App\Http\Controllers\frontend\payment\PayRequestController;
use App\Jobs\SendEmail;
use App\Jobs\SignPdf;
use App\Mail\EmailWorkflowRequest;
use App\Mail\EmailWorkflowSuccess;
use App\Mail\EmailPayequest;
use App\Models\shared\Approved;
use App\Models\shared\ApprovedStep;
use App\Models\shared\DocumentType;
use App\Models\shared\Team;
use App\Models\shared\Timeline;
use App\Models\work\workflow\runtime\WlworkflowDoc;
use App\Models\work\workflow\Wlphase;
use App\Models\work\workflow\Wlworkflow;
use App\Notifications\ApprovedPaymentNoti;
use App\Notifications\CommondNotification;
use App\Notifications\NotiBaseModel;
use App\Repositories\approve\ApproveAbs;
use App\Repositories\DocumentSerials;
use App\Repositories\work\workflow\runtime\WorkflowEmailGate;
use App\Repositories\work\workflow\runtime\WorkflowMain;
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

class ApproveWorkflowdoc extends ApproveAbs
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
        $status  = explode(',', $request->status);
        if ($status[0] == '') {
            $status = [0, 1, 2];
        }

        // dd($status);
        try {
            $document = WlworkflowDoc::whereHas('approveds', function (Builder $query) use ($status, $request) {
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
                    ->where('online', 'X');
            });
            if ($request->filled('hide_doc_cancel')) {
                if ($request->hide_doc_cancel == "true") {
                    //dd($request->hide_doc_cancel);
                    $document = $document->where('status', '<>', '-1');
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


            $document = $document->orderBy('created_at', 'desc')->with(['approveds', 'user', 'approveds.user'])->withCount('approveds')->get();
            return $document;
        } catch (Exception $e) {
            $result['success'] = "0";
            return null;
        }
    }
    //
    public function is_cancel()
    {

        if ($this->object->status == -1) { //đã huỷ
            return true;
        }
        return false;
    }
    public function validate()
    {

        if ($this->is_cancel()) {
            return false;
        }
        return true;
    }

    public function notifycation(Approved $approved)
    {
        $document =  $approved->approvedable;

        $workflow =  $document->wlworkflow;
        $workflow_type_id = $workflow != null ? $workflow->wlworkflow_type->id : "";
       
        if ($approved->finished == 1) {
            $creator_user = $document->user;

            $title = "Duyệt hoàn tất " . Str::lower($workflow->wlworkflow_type->name);
            $content = Str::lower($workflow->wlworkflow_type->name) . ' của bạn đã được duyệt hoàn tất và đang được xử lí.';
            $details = [
                "Mã yêu cầu" => $document->serial_num,
                "Ngày duyệt xong" => $approved->updated_at
            ];

            WorkflowEmailGate::sendNoticeWithMail($creator_user, $document, $title, $content, 'REQUEST_ACCEPTED', $details);

            //Gửi thông báo  cc
            //if ($document->teamconfig_id) {
            //    $team = Team::find($document->teamconfig_id);
            //    $userccs = $team->userccs;
            //
            //    if ($userccs && count($userccs) > 0) {
            //
            //        foreach ($userccs as $cc) {
            //            //$cc->notify(new CommondNotification($data, $cc, $email));
            //        }
            //    }
            //}
        } else if ($approved->release == 1) {
            $creator_user = $document->user;

            $title = "Phản hồi duyệt " . Str::lower($workflow->wlworkflow_type->name);
            $content = '{gender} vừa nhận được phản hồi duyệt ' . ' [' .  Str::lower($workflow->wlworkflow_type->name) . '] từ ' . $approved->user->name;
            $details = [
                "Mã yêu cầu" => $document->serial_num,
                "Nội dung phản hồi" => $approved->note,
                "Ngày phản hồi" => $approved->updated_at
            ];
            
            WorkflowEmailGate::sendNoticeWithMail($creator_user, $document, $title, $content, 'REQUEST_DENIED', $details);

            $document->timelines()->save(new Timeline(['title' => "form.feedback", 'icon' => 'fas fa-comments bg-warning', 'content' => $approved->note, 'user_id' => auth()->user()->id]));
        } else {
            $creator_user = $document->user;

            $title = "Yêu cầu duyệt " . Str::lower($workflow->wlworkflow_type->name);
            $content = '{gender} vừa nhận được yêu cầu duyệt ' . ' [' .  Str::lower($workflow->wlworkflow_type->name) . '] từ ' . $creator_user->name;
            $details = [
                "Mã yêu cầu" => $document->serial_num,
                "Nội dung yêu cầu" => $document->title,
                "Ngày yêu cầu" => $document->send_date
            ];

            WorkflowEmailGate::sendNoticeWithMail($approved->user, $document, $title, $content, 'REQUEST_APPROVEMENT', $details);
        }
    }
    public function update_status_send()
    {
        $this->object->timelines()->save(new Timeline(['title' => "form.send_approve", 'icon' => 'fas fa-paper-plane bg-info', 'user_id' => auth()->user()->id]));
        //cập nhật ngày gửi
        $this->object->status = 1;
        $this->object->locked = 1;
        $this->object->send_date = now();
        $this->object->save();
        //cập nhật timeline cho workflowdoc
        // $workflow_doc  = $this->getWorkflowDoc($this->object);
        // $workflow_doc->timelines()->save(new Timeline(['title'=>"form.send_approve",'icon'=>'fas fa-paper-plane bg-info','user_id'=>auth()->user()->id]));
    }
    public function update_status_agree()
    {
        $this->object->timelines()->save(new Timeline(['title' => "form.approved", 'icon' => 'fas fa-check bg-success', 'user_id' => auth()->user()->id]));
        // $workflow_doc  = $this->getWorkflowDoc($this->object);
        // $workflow_doc->timelines()->save(new Timeline(['title'=>"form.approved",'icon'=>'fas fa-check bg-success','user_id'=>auth()->user()->id]));
    }
    public function update_status_refuse()
    {

        $this->object->locked = 0;
        $this->object->save();
    }
    public function update_status_agree_finish()
    {

        $this->object->timelines()->save(new Timeline(['title' => "form.complete_approval", 'icon' => 'fas fa-check-circle bg-success', 'user_id' => auth()->user()->id]));
        //Xác định người duyệt cuối
        $term_release_count = 0;
        $document = $this->object;

        //Lưu html chứng từ vào bảng -- chưa sử dụng
        // $con = new DocumentFrontend;
        // $html =  $con->print(new Request ,$this->object->id);
        // $this->save_printed_doc($html,$this->object);
        //end lưu html

        //  dd('test o day');
        //cập nhật lại trạng thái chứng từ
        $document->status = 2; //Duyệt xong

        $document->save();
        //Đẩy file trình ký xuống JOB, sau khi xử lý file nào sẽ gửi email báo cho user
        //     $url = URL('/').'/'. Ultils::$URL_DOCUMENT_VIEW . $document->id;
        //    $SignPdfJob = new SignPdf($document,$url);
        //    dispatch($SignPdfJob);

        // $workflow_doc  = $this->getWorkflowDoc($this->object);
        // $workflow_doc->timelines()->save(new Timeline(['title'=>"form.complete_approval",'icon'=>'fas fa-check-circle bg-success','user_id'=>auth()->user()->id]));
        //Hoàn thành giai đoạn
        $documentBase = WorkflowMain::create(new Request());
        $workflow =  $this->object->wlworkflow; // $this->getWorkflow($this->object);

        $re = $documentBase->updatePhaseStatus($workflow->currentPhase->id, true);
    }


    public function get_step_name($stepid)
    {
        $name = "";;

        $documentType = DocumentType::where('id', $this->object->document_type_id)->first();

        if ($documentType) {
            $step = ApprovedStep::where('document_type_id', $documentType->id)->where('step', $stepid)->first();
            $name =   $step ? $step->name : "";
            // dd($documentType->id);
        }
        if ($name == '') {
            $name = parent::get_step_name($stepid);
        }

        return $name;
    }
}
