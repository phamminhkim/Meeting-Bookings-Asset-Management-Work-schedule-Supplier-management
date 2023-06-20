<?php

namespace  App\Repositories\work\workflow\runtime;

use App\Mail\Workflows\EmailWorkflowBase;
use App\Models\work\workflow\runtime\WlworkflowDoc;
use App\Models\work\workflow\Wljob;
use App\Models\work\workflow\WljobDependency;
use App\Models\work\workflow\WlworkflowJobType;
use App\Notifications\NotiBaseModel;
use App\Notifications\WorkflowNotification;
use App\Ultils\Ultils;
use App\User;
use Exception;
use Illuminate\Database\Eloquent\Builder;

class WorkflowJobGate
{
    public static function getPendingWorkflowsCount($workflow_code)
    {
        try {
            $pending_approveds = WlworkflowDoc::where('finished', false)
                ->where('wlworkflow_type_id', $workflow_code)
                ->whereHas('approveds', function (Builder $query) {
                    $query->where('user_id', auth()->user()->id)
                        ->whereIn('release', [0, 1])
                        ->where('online', 'X');
                })->orWhereHas('wlworkflow', function (Builder $query) {
                    $query->whereHas('currentPhase', function (Builder $query) {
                        $query->whereHas('wljobs', function (Builder $query) {
                            $query->where('action_user', auth()->user()->id)
                                ->where('is_completed', false)
                                ->WhereDoesntHave('navigated_by_job')
                                ->orWhereHas('navigated_by_job', function (Builder $query) {
                                    //$query->where('is_navigated', true);
                                })
                                ->where('is_navigated', true);
                          //  $job->navigated_by_job && !$job->is_navigated;
                        });
                    });
                });
            return $pending_approveds->count();
        } catch (\Throwable $th) {
            return 0;
        }
    }
}
