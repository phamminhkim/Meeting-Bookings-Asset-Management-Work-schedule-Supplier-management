<?php

namespace App\Policies;

use App\Models\work\workflow\Wlworkflow;
use App\Models\work\workflow\runtime\WlworkflowDoc;
use App\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Access\HandlesAuthorization;

class WorkflowPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $interacting_user, $workflow_code)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\WlworkflowDoc  $wlworkflowDoc
     * @return mixed
     */
    public function view(User $interacting_user, WlworkflowDoc $document, $workflow_code = null)
    {
        $can_view = false;
        //Kiểm tra nếu chứng từ cụ thể

        if ($document && $document->id != null) {
            $interacting_user = User::find(auth()->user()->id);
            $workflow_code = $document->wlworkflow_type_id;

            // Xem toàn bộ quy trình thuộc bộ workflow_code đó
            
            if ($interacting_user->hasWorkflowPermission($workflow_code, 'review_all')) {
                $can_view = true;
            }

            // Xem toàn bộ quy trình mà người trong công ty đó tạo
            if ($interacting_user->hasWorkflowPermission($workflow_code, 'review_company')) {
                $company_id = $interacting_user->company_id;
                if ($document->company_id == $company_id) {
                    $can_view = true;
                }
            }

            // Những người trong cùng nhóm có thể xem quy trình
            $group_ids = $interacting_user->groups->pluck('id')->flatten();
            if ($group_ids->contains($document->group_id)) {
                $can_view = true;
            }
            // Những người trong cây duyệt hiện tại có thể xem quy trình
            $team_ids = $interacting_user->teams->pluck('id')->flatten();
            if ($team_ids->contains($document->team_id)) {
                $can_view = true;
            }
            // Những người trong nhóm share có thể xem quy trình
            foreach ($document->shareds as $shared) {
                if ($shared->type == 0 && $group_ids->contains($shared->object_id)) {
                    $can_view = true;
                    break;
                }
                if ($shared->type == 1 && $shared->object_id == $interacting_user->id) {
                    $can_view = true;
                    break;
                }
            }

            // Những người từng duyệt có thể xem quy trình
            $approved_ids = $document->approveds->pluck('user_id')->flatten(); //pluck('id')->flatten();
            if ($approved_ids->contains($interacting_user->id)) {
                $can_view = true;
            }

            // Những người trong danh sách Admins Quy trình được xem
            $wlworkflow =  Wlworkflow::where('id', $document->wlworkflow_id)->with(['admins', 'members', 'follows'])->first();
            if ($wlworkflow) {
                $wl_admins = $wlworkflow->admins->pluck('user_id')->flatten();
                $wl_members = $wlworkflow->members->pluck('user_id')->flatten();
                $wl_follows = $wlworkflow->follows->pluck('user_id')->flatten();
                if (
                    $wl_admins->contains($interacting_user->id) ||
                    $wl_members->contains($interacting_user->id) ||
                    $wl_follows->contains($interacting_user->id)
                ) {
                    $can_view = true;
                }
            }

            // Quy trình này chưa được gửi - chưa sinh số chứng từ
            if ($document->serial_num == null) {
                $can_view = false;
            }

            // Người tạo quy trình luôn được xem
            if ($interacting_user->id == $document->user_id) {
                $can_view = true;
            }
        } else {
            $can_view = $interacting_user->hasWorkflowPermission($workflow_code, 'review');
        }

        return $can_view;
    }

    public function create(User $interacting_user, WlworkflowDoc $document, $workflow_code)
    {
        return $interacting_user && $interacting_user->hasWorkflowPermission($workflow_code, 'create');
    }

    public function update(User $interacting_user, WlworkflowDoc $document)
    {
        return ($interacting_user && ($interacting_user->id == $document->user_id) && $document->locked != 1);
    }

    public function delete(User $interacting_user, WlworkflowDoc $document)
    {
        //
    }
}
