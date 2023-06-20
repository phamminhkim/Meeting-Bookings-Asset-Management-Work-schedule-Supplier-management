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

class RemindJobsAfterCompletePhase implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $document;
    protected $next_phase;
    protected $trigger_user;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($document, $next_phase, $trigger_user)
    {
        $this->document = $document;
        $this->next_phase = $next_phase;
        $this->trigger_user = $trigger_user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->next_phase->load('wljobs');
    
        foreach ($this->next_phase->wljobs as $job) {
            $ready_to_email = WorkflowEmailGate::checkReadyToEmail($job, null, $this->trigger_user);

            if ($ready_to_email) {
                WorkflowEmailGate::sendNewJobNotice($this->document, $job, null);
            }
        }
    }
}
