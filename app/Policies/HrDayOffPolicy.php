<?php

namespace App\Policies;

use App\Models\productivity\Hrdayoff;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class HrDayOffPolicy
{
    use HandlesAuthorization;

    public function view(User $user, Hrdayoff $hrdayoff)
    {
        return ($user &&  $user->hasPermission('view_hrdayoff'));
    }

    public function create(User $user)
    {
        return ($user &&  $user->hasPermission('create_hrdayoff'));
    }

    public function update(User $user, Hrdayoff $hrdayoff)
    {
        return ($user &&  $user->hasPermission('update_hrdayoff'));
    }

    public function delete(User $user, Hrdayoff $hrdayoff)
    {
        return ($user && $user->hasPermission('delete_hrdayoff'));
    }
}
