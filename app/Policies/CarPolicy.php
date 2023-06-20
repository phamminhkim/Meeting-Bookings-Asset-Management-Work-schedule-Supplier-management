<?php

namespace App\Policies;

use App\Models\car\Car;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CarPolicy
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
     * @param  \App\Car  $car
     * @return mixed
     */
    public function view(User $user, Car $car)
    {
            $is_view = false;
            $user = new User();
            $user = User::find(auth()->user()->id);
            if ($car && $car->id != null) {
                $group_ids = $user->groups->pluck('id')->flatten(); 
                if ( $group_ids->contains($car->group_id) && $car->status!=0) {
                    $is_view = true;
                }
                $approved_ids = $car->approveds->pluck('user_id')->flatten(); 
                if ( $approved_ids->contains($user->id)) {
                    $is_view = true;
                }
                if($car->proposer== $user->id || $car->user_id == $user->id){
                    $is_view = true; 
                }
                if ($user->hasPermission('review_statistical_car')) {
                    $is_view = true;
                }
                if ($user->hasPermission('review_car_log')) {
                    $is_view = true;
                } 
                foreach ($car->shareds as $shared) {
                    if ($shared->type == 0 && $group_ids->contains($shared->object_id)) {
                        $is_view = true;
                        break;
                    }
                    if ($shared->type == 1 && $shared->object_id ==  $user->id) {
                        $is_view = true;
                        break;
                    }
                    if ($shared->type == 4 && $shared->object_id ==  $user->id) {
                        $is_view = true;
                        break;
                    }
                 }  

            }else{
                if ($user->hasPermission('review_all_car')) {
                    $is_view = true;
                }
                if ($user->hasPermission('review_company_car')) {
                    $is_view = true;
                }
                if($user->hasPermission('review_department_car')){
                    $is_view = true;
                }
                if ($user->hasPermission('review_car')) {
                    $is_view = true;
                }
                 
            }


        return ($user && $is_view);        
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return ($user &&  $user->hasPermission('create_car'));
    }

    public function review_statistical_car(User $user)
    {
        return ($user &&  $user->hasPermission('review_statistical_car'));
    }
    public function review_car_log(User $user)
    {
        return ($user &&  $user->hasPermission('review_car_log'));
    }
    public function update_car(User $user)
    {
        return ($user &&  $user->hasPermission('update_car'));
    }
    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Car  $car
     * @return mixed
     */
    public function update(User $user, Car $car)
    {
        $is_update = false;
        if($user->hasPermission('update_car')){
            $is_update = true;
        }
      
        return ($user && ($user->id == $car->user_id)||( $is_update));
    }

    public function update_cancel(User $user, Car $car)
    {

        return ($user && ($user->id == $car->user_id ));
    }    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Car  $car
     * @return mixed
     */
    public function delete(User $user, Car $car)
    {
        return ($user && ($user->id == $car->user_id ));
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Car  $car
     * @return mixed
     */
    public function restore(User $user, Car $car)
    {
        return ($user && ($user->id == $car->user_id ||  $user->hasPermission('restore_car')));
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Car  $car
     * @return mixed
     */
    public function forceDelete(User $user, Car $car)
    {
        return ($user->id == $car->user_id || $user->hasPermission('force_delete_car'));
    }
}
