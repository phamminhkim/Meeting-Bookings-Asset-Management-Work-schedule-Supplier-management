<?php

namespace App\Repositories\admin;

use App\Models\document\Document;
use App\Models\managerprice\PriceApproveRequest;
use App\Models\managerprice\PriceReq;
use Illuminate\Support\Facades\DB;
use App\Models\payment\Payrequest;
use App\Models\productivity\HrproductivityDoc;
use App\Models\shared\Approved;
use App\Models\shared\Role;
use App\Models\shared\Team;
use App\Models\work\workflow\runtime\WlworkflowDoc;
use App\Models\work\workflow\WlworkflowAppdoc;
use App\Repositories\approve\document\ApproveDocument;
use App\Repositories\approve\price\ApprovePrice;
use App\User;
use Carbon\Carbon;
use Exception;
use App\Repositories\ApproveAbs;
use App\Repositories\approve\payment\ApprovePayment;
use App\Repositories\approve\productivity\ApproveHrProductivity;
use App\Repositories\approve\workflowdoc\ApproveWorkflowdoc;
use App\Ultils\Ultils;
use Illuminate\Http\Request;
use Laravel\Passport\Passport;

class AuditMain
{
    protected $request;
    protected $errors;
    protected $message;

    // protected $documentType;
    public function __construct($request)
    {
        $this->request = $request;
    }

    public function audit_users()
    {
        $all_users = User::all()->makeVisible('created_at');
        foreach ($all_users as $user) {
            $user->load('roles');
            
            if (is_numeric($user->username)) {
                $user['user_id'] = $user->username;
            }
            $last_token = DB::table('oauth_access_tokens')->select('created_at')->where('user_id', $user->id)->orderBy('created_at', 'desc')->first();
            if ($last_token) {
                $user['last_login_at'] = $last_token->created_at;
            }
            $role_array = $user->roles->pluck('name')->flatten();

            $user['current_roles'] = $role_array;
            unset($user['roles']);
        }

        return $all_users;
    }

    public function audit_roles()
    {
        $all_roles = Role::all()->makeVisible('created_at');
        foreach ($all_roles as $role) {
            $role->load('permissions');
            
            $permissions = $role->permissions()->pluck('description')->flatten();

            $role['current_permissions'] = $permissions;
            unset($role['permissions']);
        }

        return $all_roles;
    }
}
