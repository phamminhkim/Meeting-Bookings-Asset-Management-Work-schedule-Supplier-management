<?php

namespace App\Http\Controllers\Admin\Audit;


use App\Http\Controllers\BaseController\BaseController;
use App\Http\Controllers\Controller;
use App\Models\shared\Company;
use App\Models\shared\Role;
use App\Models\shared\Sloc;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Admin\UserRequest;
use App\Http\Resources\Users;
use App\Models\shared\Department;
use App\Mail\EmailUserRegisterSuccess;
use App\Mail\EmailUserDisable;
use App\Mail\EmailUserChangePass;
use App\Models\shared\Group;
use App\Notifications\CommondNotification;
use App\Notifications\NotiBaseModel;

class UserAuditController extends BaseController
{

    public function auditUsers()
    {
        return view('admin.audit.audit_users');
    }

    public function auditRoles()
    {
        return view('admin.audit.audit_roles');
    }
}
