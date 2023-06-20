<?php

namespace App\Policies;

use App\Models\productivity\HrsalaryInfo;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class HrSalaryInfoPolicy
{
    use HandlesAuthorization;

    public function view(User $user, HrsalaryInfo $hrsalaryinfo)
    {
        return ($user &&  $user->hasPermission('view_hrsalaryinfo'));
    }

    public function create(User $user)
    {
        return ($user &&  $user->hasPermission('create_hrsalaryinfo'));
    }

    public function update(User $user, HrsalaryInfo $hrsalaryinfo)
    {
        return ($user &&  $user->hasPermission('update_hrsalaryinfo'));
    }

    public function delete(User $user, HrsalaryInfo $hrsalaryinfo)
    {
        return ($user && $user->hasPermission('delete_hrsalaryinfo'));
    }
}
