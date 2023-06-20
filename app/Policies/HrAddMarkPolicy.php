<?php

namespace App\Policies;

use App\Models\productivity\HraddMark;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class HrAddMarkPolicy
{
    use HandlesAuthorization;

    public function view(User $user, HrAddMark $hrAddMark)
    {
        return ($user &&  $user->hasPermission('view_hraddmark'));
    }

    public function create(User $user)
    {
        return ($user &&  $user->hasPermission('create_hraddmark'));
    }

    public function update(User $user, HrAddMark $hrAddMark)
    {
        return ($user &&  $user->hasPermission('update_hraddmark'));
    }

    public function delete(User $user, HrAddMark $hrAddMark)
    {
        return ($user &&  $user->hasPermission('delete_hraddmark'));
    }
}
