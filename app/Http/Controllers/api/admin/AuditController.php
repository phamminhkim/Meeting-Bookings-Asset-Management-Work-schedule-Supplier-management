<?php

namespace App\Http\Controllers\api\admin;

use App\Repositories\approve\ApproveMain;
use App\Http\Controllers\BaseController\BaseController;
use App\Repositories\admin\AuditMain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuditController extends BaseController
{
    public function auditUsers(Request $request)
    {
        $result = array();
        $result['data'] = array();
        $result['success'] = "0";

        $request = new AuditMain($request);

        $list = $request->audit_users();
        if ($list) {
            $result['data'] = $list;
            $result['success'] = "1";
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

    public function auditRoles(Request $request)
    {
        $result = array();
        $result['data'] = array();
        $result['success'] = "0";

        $request = new AuditMain($request);

        $list = $request->audit_roles();
        if ($list) {
            $result['data'] = $list;
            $result['success'] = "1";
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
}
