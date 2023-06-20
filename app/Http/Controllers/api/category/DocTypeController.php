<?php

namespace App\Http\Controllers\api\category;

use App\Http\Controllers\Controller;
use App\Models\document\DocType;
use Illuminate\Http\Request;

class DocTypeController extends Controller
{
    public function index(Request $request)
    {   
        
        $docType = DocType::all();

        // return DepartmentResource::collection($department);
        $result = array();
        $result['data'] = array();
        $result['data'] = $docType ;
        $result['success'] = "1";
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
}
