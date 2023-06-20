<?php

namespace App\Jobs\Workflows;

use App\Mail\EmailDocumentRequest;
use App\Mail\EmailDocumentSuccess;
use App\Models\shared\DocumentType;
use App\Models\shared\Timeline;
use App\Models\work\workflow\Wljob;
use App\Models\work\workflow\WljobDependency;
use App\Models\work\workflow\Wlphase;
use App\Models\work\workflow\WlworkflowJobType;
use App\Notifications\CommondNotification;
use App\Notifications\NotiBaseModel;
use App\Repositories\work\workflow\runtime\WorkflowBase;
use App\Repositories\work\workflow\runtime\WorkflowEmailGate;
use App\Ultils\Pdf\ThienLongPDF;
use App\Ultils\Ultils;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use SoareCostin\FileVault\Facades\FileVault;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class RemindJobsAfterCompleteJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $document;
    protected $completed_job;
    protected $trigger_user;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($document, $completed_job, $trigger_user)
    {
        $this->document = $document;
        $this->completed_job = $completed_job;
        $this->trigger_user = $trigger_user;
    }

    private function getRelevantJobsOfCompletedJobs($completed_job)
    {
        $relevant_jobs = array();

        // Lấy danh sách các Jobs trong phase hiện tại

        $workflow = new WorkflowBase(null);
        $phase_jobs = $workflow->getJobReports($this->completed_job->wlphase_id, $this->trigger_user->id);


        foreach ($phase_jobs as $job) {

            foreach ($job->dependencies as $depend_job) {
                // Lấy danh sách các Jobs phụ thuộc vào job đã hoàn thành
                if ($depend_job->dependjobid == $completed_job->id) {
                    array_push($relevant_jobs, $job);
                    break;
                }
            }

            foreach ($job->navigates as $navigate_job) {
                // Lấy danh sách các Jobs được điều hướng bởi job đã hoàn thành
                if ($navigate_job->id == $completed_job->id) {
                    array_push($relevant_jobs, $job);
                }
            }
        }
        //Log::debug("Job liên quan: " . json_encode($relevant_jobs));
        return $relevant_jobs;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //$phase_jobs = $this->workflow_base->getJobReports($this->completed_job->wlphase_id, $this->completed_job->action_user);
        $relevant_jobs = $this->getRelevantJobsOfCompletedJobs($this->completed_job);

        foreach ($relevant_jobs as $job) {
            $ready_to_email = WorkflowEmailGate::checkReadyToEmail($job, $this->completed_job, $this->trigger_user);

            if ($ready_to_email) {
                WorkflowEmailGate::sendNewJobNotice($this->document, $job, null);
            }
        }
    }
}
