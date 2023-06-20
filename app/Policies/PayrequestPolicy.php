<?php

namespace App\Policies;

use App\Models\payment\Payrequest as PaymentPayrequest;
use App\Models\payment\Payrequest;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PayrequestPolicy
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
     * @param  \App\Payrequest  $payrequest
     * @return mixed
     */
    public function review_yctt(User $user, Payrequest $payrequest)
    {

        $is_view = false;
        //Kiểm tra nếu chứng từ cụ thể
        if ($payrequest && $payrequest->id != null) {

            $user = new User();
            $user = User::find(auth()->user()->id);

            //dd($user->hasPermission('review_company_yctt'));
            if ($user->hasPermission('review_all_yctt')) {
                //OK
                $is_view = true;
            }
            if ($user->hasPermission('review_company_yctt')) {
                $company_id = $user->company_id;
                if ($payrequest->company_id == $company_id) {
                    //OK
                    $is_view = true;
                }
            }
            //user trong nhóm có thể xem chứng từ
            $group_ids = $user->groups->pluck('id')->flatten(); //pluck('id')->flatten();
            if ( $group_ids->contains($payrequest->group_id)) {
                //OK
                $is_view = true;
            }
            //User đang ở team duyệt chứng từ
            $team_ids = $user->teams->pluck('id')->flatten(); //pluck('id')->flatten();
            if ( $team_ids->contains($payrequest->team_id)) {
                //OK
                $is_view = true;
            }
             //user trong nhóm shared có thể xem chứng từ
             foreach ($payrequest->shareds as $shared) {
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
            //hoặc user có thể xem lại các chứng từ cũ mình đã duyệt

            $approved_ids = $payrequest->approveds->pluck('user_id')->flatten(); //pluck('id')->flatten();
            if ( $approved_ids->contains($user->id)) {
                //OK
                $is_view = true;
            }

            //Chứng từ này chưa được gửi - chưa sinh số chứng từ
            if ($payrequest->serial_num ==  null) {
                $is_view = false;
            }
        } else {
            //Trường hợp có quyền xem danh sách chứng từ
            if ($user->hasPermission('review_yctt')) {
                $is_view = true;
            }
        }

        //quyền review review_yctt có vẻ thừa ?? Người tạo thì có quyền review chứng từ của mình, còn lại thì review theo nhóm và quyền cao hơn
        // return($user && ($user->id == $payrequest->user_id ||  $user->hasPermission('review_yctt')));
        return ($user && ($user->id == $payrequest->user_id  ||  $is_view));
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create_yctt(User $user)
    {
        return ($user &&  $user->hasPermission('create_yctt'));
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Payrequest  $payrequest
     * @return mixed
     */
    public function update_yctt(User $user, Payrequest $payrequest)
    {
        return ($user && ($user->id == $payrequest->user_id));
    }
    //cập nhật đã thanh toán
    public function update_dathanhtoan_yctt(User $user)
    {
        return ($user && $user->hasPermission('update_dathanhtoan_yctt'));
    }
    //cập nhật đã nhận bản cứng
    public function update_hard_doc_yctt(User $user, Payrequest $payrequest)
    {
        return ($user && $user->hasPermission('update_hard_doc_yctt'));
    }
    //quản lý nợ hoá đơn
    public function update_miss_invoice_yctt(User $user, Payrequest $payrequest)
    {
        return ($user && $user->hasPermission('update_miss_invoice_yctt'));
    }
    //quyền check đã in chứng từ
    public function printed_check_yctt(User $user, Payrequest $payrequest)
    {
        return ($user && $user->hasPermission('printed_check_yctt'));
    }

    //Đã kiểm tra file attach
    public function check_attach_yctt(User $user, Payrequest $payrequest)
    {
        return ($user && $user->hasPermission('check_attach_yctt'));
    }

    //cập nhật đã huỷ
    public function update_cancel_yctt(User $user, Payrequest $payrequest)
    {

        return ($user && ($user->id == $payrequest->user_id || $user->hasPermission('update_cancel_yctt')));
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Payrequest  $payrequest
     * @return mixed
     */
    public function delete_yctt(User $user, Payrequest $payrequest)
    {
        return ($user && ($user->id == $payrequest->user_id));
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Payrequest  $payrequest
     * @return mixed
     */
    public function restore_yctt(User $user, Payrequest $payrequest)
    {
        return ($user && ($user->id == $payrequest->user_id ||  $user->hasPermission('restore_yctt')));
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Payrequest  $payrequest
     * @return mixed
     */
    public function force_delete_yctt(User $user, Payrequest $payrequest)
    {
        return ($user->id == $payrequest->user_id || $user->hasPermission('force_delete_yctt'));
    }
}
