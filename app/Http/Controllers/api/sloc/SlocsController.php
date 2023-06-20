<?php

namespace App\Http\Controllers\api\sloc;

use App\Http\Controllers\Controller;
use App\Models\shared\Sloc;
use Illuminate\Http\Request;

class SlocsController extends Controller
{
    public function index(Request $request)
    {   
        $paginate = $request->paginate;
        $company = Sloc::paginate($paginate);

        // return DepartmentResource::collection($department);
        $result = array();
        $result['data'] = array();
        $result['data'] = $company;
        $result['success'] = "1";
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
}
