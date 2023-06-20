<?php

namespace App\Http\Controllers\api\category;

use App\Http\Controllers\Controller;
use App\Models\shared\Paycattype;
use Illuminate\Http\Request;

class PaycattypeController extends Controller
{
    public function index()
    {
        $paycattype = Paycattype::all();

        // return DepartmentResource::collection($department);
        $result = array();
        $result['data'] = array();
        $result['data'] = $paycattype;
        $result['success'] = "1";
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
}
