<?php

namespace App\Policies;

use App\Models\managerprice\PriceReq;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PricePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
    public function viewAny(User $user)
    {
        //
    }
    public function view(User $user, PriceReq $priceReq)
    {

        $is_view = false;

        //Kiểm tra nếu chứng từ cụ thể
        if ($priceReq && $priceReq->id != null) {

            $user = new User();
            $user = User::find(auth()->user()->id);

            //dd($user->hasPermission('review_company_yctt'));
            if ($user->hasPermission('review_all_ycdg')) {
                //OK
                $is_view = true;
            }
            if ($user->hasPermission('review_company_ycdg')) {
                $company_id = $user->company_id;
                if ($priceReq->company_id == $company_id) {
                    //OK
                    $is_view = true;
                }
            }

            //user trong nhóm có thể xem chứng từ
            $group_ids = $user->groups->pluck('id')->flatten(); //pluck('id')->flatten();

            if ( $group_ids->contains($priceReq->group_id)) {
                //OK
                $is_view = true;
            }

            //User đang ở team duyệt chứng từ
            $team_ids = $user->teams->pluck('id')->flatten(); //pluck('id')->flatten();
            if ( $team_ids->contains($priceReq->team_id)) {
                //OK
                $is_view = true;
            }
            //hoặc user có thể xem lại các chứng từ cũ mình đã duyệt

            $approved_ids = $priceReq->approveds->pluck('user_id')->flatten(); //pluck('id')->flatten();
            if ( $approved_ids->contains($user->id)) {
                //OK
                $is_view = true;
            }

            //Chứng từ này chưa được gửi - chưa sinh số chứng từ
            if ($priceReq->serial_num ==  null) {
                $is_view = false;
            }
        } else {

            //Trường hợp có quyền xem danh sách chứng từ
            if ($user->hasPermission('review_ycdg')) {
                $is_view = true;
            }
        }

        //quyền review review_yctt có vẻ thừa ?? Người tạo thì có quyền review chứng từ của mình, còn lại thì review theo nhóm và quyền cao hơn
        // return($user && ($user->id == $payrequest->user_id ||  $user->hasPermission('review_yctt')));
        //dd(($user && ($user->id == $priceReq->user_id  ||  $is_view)));
        return ($user && ($user->id == $priceReq->user_id  ||  $is_view));
    }
    public function create(User $user)
    {
        return ($user &&  $user->hasPermission('create_ycdg'));
    }
    public function update(User $user, PriceReq $priceReq)
    {
        return ($user && ($user->id == $priceReq->user_id));
    }

     //cập nhật đã huỷ
     public function update_cancel(User $user,  PriceReq $priceReq)
     {

         return ($user && ($user->id == $priceReq->user_id || $user->hasPermission('update_cancel_ycdg')));
     }
     public function delete(User $user, PriceReq $priceReq)
     {
         return ($user && ($user->id == $priceReq->user_id));
     }

}
