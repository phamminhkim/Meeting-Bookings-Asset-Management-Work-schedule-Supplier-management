<?php

namespace  App\Repositories\work\workflow\runtime;

use App\Mail\Workflows\EmailWorkflowBase;
use App\Models\work\workflow\Wljob;
use App\Models\work\workflow\WljobDependency;
use App\Models\work\workflow\WlworkflowJobType;
use App\Notifications\NotiBaseModel;
use App\Notifications\WorkflowNotification;
use App\Ultils\Ultils;
use App\User;
use Illuminate\Support\Facades\Log;
use Throwable;

class WorkflowEmailGate
{
    public static function sendNewJobNotice($document, $job, $list_ccs)
    {
        try {
            $job->load('assigning_user', 'user');
        }
        catch (Throwable $th) {
            Log::error($job);
            Log::error($th);
        }
     
        $job_type = WlworkflowJobType::find($job->job_type_id);

        $title = $job_type->name . " " . $job->name;
        $content = '{gender} vừa nhận được ' . $job_type->name;
        $content .= ' [' . $job->name . ']';
        if ($job->assigning_user) {
            $content .=  ' từ ';
            $content .= $job->assigning_user->gender == 0 ? 'Chị ' : 'Anh ';
            $content .= $job->assigning_user->name;
            $content .= ' (' . $job->assigning_user->username . ')';
        }

        $details = [
            "Công việc" => $job->name,
        ];
        if ($job->description) {
            $details['Mô tả'] = $job->description;
        }
        if ($job->note) {
            $details['Ghi chú'] = $job->note;
        }
        if ($job->assigning_user) {
            $details['Người yêu cầu'] = $job->assigning_user->name . ' (' . $job->assigning_user->username . ')';
        }
        if ($job->date_received) {
            $details['Ngày yêu cầu'] = $job->date_received;
        }

        $mail_ccs = array();
        $list_ccs = $list_ccs ? array_unique($list_ccs) : [];
        foreach ($list_ccs as $user_id) {
            $user = User::find($user_id);
            if ($user) {
                array_push($mail_ccs, $user);
            }
        }

        WorkflowEmailGate::sendNoticeWithMail($job->user, $document, $title, $content, 'JOBASSIGNUSER', $details, 'general', $mail_ccs);
    }

    public static function checkReadyToEmail($job, $trigger_job = null, $trigger_user = null)
    {
        if ($trigger_user) {
               // Trùng người thực hiện thì không cần mail
               if ($job->action_user == $trigger_user->id) {
                //Log::debug($job->name . " trùng người thực hiện " . $this->completed_job->name);
                return false;
            }
        }
        if ($trigger_job) {
            //Job khác job hoàn thành
            if ($job->id == $trigger_job->id) {
                //Log::debug($job->name . " trùng với job hoàn thành");
                return false;
            }
            // Trùng người thực hiện thì không cần mail
            if ($job->action_user == $trigger_job->action_user) {
                //Log::debug($job->name . " trùng người thực hiện " . $this->completed_job->name);
                return false;
            }
            if (!WljobDependency::where('jobid', $job->id)->where('dependjobid', $trigger_job->id)->get()->first()) {
                //Log::debug($job->name . " không bị phụ thuộc: " . $this->completed_job->name);
                return false;
            }
            if ($job->navigated_by_job && $trigger_job->id != $job->navigated_by_job->job_id) {
                //Log::debug($job->name . " không bị điều phối bởi: " . $this->completed_job->name);
                return false;
            }
        }

        // Job không khả dụng thì bỏ qua
        if ($job->navigated_by_job && !$job->is_navigated) {
            //Log::debug($job->name . " không khả dụng " . ' ' . $job->is_navigated . ' ' . $job->navigated_by_job);
            return false;
        }
        // Job không có người thực hiện thì bỏ qua
        if (!$job->action_user) {
            //Log::debug($job->name . " chưa assign người thực hiện");
            return false;
        }
        // Job chưa mở khóa thì bỏ qua
        $workflow = new WorkflowBase(null);
        $undone_jobs = $workflow->getDependencyUndoneJobs($job);
        if (count($undone_jobs) > 0) {
            //Log::debug($job->name . " vẫn còn phụ thuộc " . json_encode($undone_jobs));
            return false;
        }

        return true;
    }

    public static function sendNewResponse($document, $job, $email_to, $email_ccs)
    {
        $job->load('user');

        $job_type = WlworkflowJobType::find($job->job_type_id);

        $title =  $job->name;
        $content = '{gender} vừa nhận được ' . $job_type->name;
        if ($job->user) {
            $content .=  ' từ ';
            $content .= $job->user->gender == 0 ? 'Chị ' : 'Anh ';
            $content .= $job->user->name;
            $content .= ' (' . $job->user->username . ')';
        }

        $details = [
            "Tiêu đề" => $job->name,
        ];
        if ($job->note) {
            $details['Nội dung'] = $job->note;
        }
        if ($job->user) {
            $details['Người phản hồi'] = $job->user->name . ' (' . $job->user->username . ')';
        }
        if ($job->date_received) {
            $details['Ngày phản hồi'] = $job->date_received;
        }

        $mail_ccs = array();
        $list_ccs = $email_ccs ? array_unique($email_ccs) : [];
        foreach ($list_ccs as $user_id) {
            $user = User::find($user_id);
            if ($user) {
                array_push($mail_ccs, $user);
            }
        }

        $user_receiver = User::find($email_to);
        WorkflowEmailGate::sendNoticeWithMail($user_receiver, $document, $title, $content, 'RESPONSE', $details, 'general', $mail_ccs);
    }

    public static function sendNoticeWithMail($recipient, $document, $title, $content, $icon, $details, $template = 'general', $users_cc = [], $users_bcc = [])
    {
        $notice = new NotiBaseModel;
        $notice->icon = Ultils::$WORKFLOW_ICONS[$icon];
        $notice->title = $title == "" ? "Thông báo" : $title;
        $notice->content = $document->serial_num;
        $notice->content_full = $content;
        $notice->url = URL('/') . '/' . "works/list/" . $document->wlworkflow_type_id . "?type=view&id=" . $document->id;
        $notice->object_type = get_class($document);
        $notice->object_id =  $document->id;

        $email = new EmailWorkflowBase($notice, $details, $recipient);
        $email->setEmailTemplate($template);
        if (count($users_cc) > 0) {
            $email->setUsersCc($users_cc);
        }
        if (count($users_bcc) > 0) {
            $email->setUsersBcc($users_bcc);
        }

        //Mail::send($email);
        $recipient->notify(new WorkflowNotification($notice, $email));
    }
}
