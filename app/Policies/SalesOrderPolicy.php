<?php

namespace App\Policies;

use App\Models\tmdt\SaleOrders;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SalesOrderPolicy
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
     * @param  \App\Models\tmdt\SaleOrders  $saleOrders
     * @return mixed
     */
    public function view(User $user, SaleOrders $saleOrders)
    {

        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('create_salesorder');
    }
    public function upload_mvc(User $user)
    {

        return $user->hasPermission('upload_mvc');
    }
    public function in_phieu_giao_hang(User $user)
    {
        return $user->hasPermission('in_phieu_giao_hang');
    }
    public function upload_don_hang(User $user)
    {
        return $user->hasPermission('upload_don_hang');
    }
    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\tmdt\SaleOrders  $saleOrders
     * @return mixed
     */
    public function update(User $user, SaleOrders $saleOrders)
    {
        return ($user->username == $saleOrders->username || $user->hasPermission('update_salesorder'));
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\tmdt\SaleOrders  $saleOrders
     * @return mixed
     */
    public function delete(User $user, SaleOrders $saleOrders)
    {
        return ($user->username == $saleOrders->username || $user->hasPermission('delete_salesorder'));
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\tmdt\SaleOrders  $saleOrders
     * @return mixed
     */
    public function restore(User $user, SaleOrders $saleOrders)
    {
        return ($user->username == $saleOrders->username || $user->hasPermission('restore_salesorder'));
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Models\tmdt\SaleOrders  $saleOrders
     * @return mixed
     */
    public function forceDelete(User $user, SaleOrders $saleOrders)
    {
        return ($user->username == $saleOrders->username || $user->hasPermission('force_delete_salesorder'));
    }
}
