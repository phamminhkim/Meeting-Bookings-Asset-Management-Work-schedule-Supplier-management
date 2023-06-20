<?php

namespace App\Policies;

use App\Models\document\Document;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DocumentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\Document  $document
     * @return mixed
     */
    public function view(User $user, Document $document)
    {


        //return($user && ($user->id == $document->user_id ||  $user->hasPermission('review_document')));
        $is_view = false;
        //Kiểm tra nếu chứng từ cụ thể
        if ($document && $document->id != null) {

            $user = new User();
            $user = User::find(auth()->user()->id);

            //dd($user->hasPermission('review_company_yctt'));
            if ($user->hasPermission('review_all_document')) {
                //OK
                $is_view = true;
            }
            if ($user->hasPermission('review_company_document')) {
                $company_id = $user->company_id;
                if ($document->company_id == $company_id) {
                    //OK
                    $is_view = true;
                }
            }
            //user trong nhóm có thể xem chứng từ
            $group_ids = $user->groups->pluck('id')->flatten(); //pluck('id')->flatten();
            if ( $group_ids->contains($document->group_id)) {
                //OK
                $is_view = true;
            }
             //user trong nhóm shared có thể xem chứng từ
             foreach ($document->shareds as $shared) {
                if ($shared->type == 0 && $group_ids->contains($shared->object_id)) {
                    //OK
                    $is_view = true;
                    break;
                }
                if ($shared->type == 1 && $shared->object_id ==  $user->id) {
                    //OK
                    $is_view = true;
                    break;
                }
             }

            //User đang ở team duyệt chứng từ
            $team_ids = $user->teams->pluck('id')->flatten(); //pluck('id')->flatten();
            if ( $team_ids->contains($document->team_id)) {
                //OK
                $is_view = true;
            }
            //hoặc user có thể xem lại các chứng từ cũ mình đã duyệt

            $approved_ids = $document->approveds->pluck('user_id')->flatten(); //pluck('id')->flatten();
            if ( $approved_ids->contains($user->id)) {
                //OK
                $is_view = true;
            }

            //Chứng từ này chưa được gửi - chưa sinh số chứng từ
            if ($document->serial_num ==  null) {
                $is_view = false;
            }
        } else {
            //Trường hợp có quyền xem danh sách chứng từ
            if ($user->hasPermission('review_document')) {
                $is_view = true;
            }
        }


        return ($user && ($user->id == $document->user_id  ||  $is_view));


    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return ($user &&  $user->hasPermission('create_document'));
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Document  $document
     * @return mixed
     */
    public function update(User $user, Document $document)
    {
        return ($user && ($user->id == $document->user_id ));
    }
 //cập nhật đã huỷ
    public function update_cancel_document(User $user, Document $document)
    {

       // return ($user && ($user->id == $document->user_id ));
       //dd(($user && ($user->id == $document->user_id || $user->hasPermission('update_cancel_document'))));
        return ($user && ($user->id == $document->user_id || $user->hasPermission('update_cancel_document')));
    }
    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Document  $document
     * @return mixed
     */
    public function delete(User $user, Document $document)
    {
        return ($user && ($user->id == $document->user_id ));
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Document  $document
     * @return mixed
     */
    public function restore(User $user, Document $document)
    {
        return ($user && ($user->id == $document->user_id ||  $user->hasPermission('restore_document')));
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Document  $document
     * @return mixed
     */
    public function forceDelete(User $user, Document $document)
    {
        return ($user->id == $document->user_id || $user->hasPermission('force_delete_document'));
    }
}
