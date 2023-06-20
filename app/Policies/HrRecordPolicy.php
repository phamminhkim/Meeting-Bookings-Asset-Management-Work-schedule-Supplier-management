<?php

namespace App\Policies;

use App\Models\productivity\HrproductivityMark;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class HrRecordPolicy
{
    use HandlesAuthorization;

    public function view(User $user, HrproductivityMark $hrproductivityMark)
    {
        return ($user &&  $user->hasPermission('view_hrpromark'));
    }

    public function update(User $user, HrproductivityMark $hrproductivityMark)
    {
        return ($user &&  $user->hasPermission('update_hrpromark'));
    }
}
