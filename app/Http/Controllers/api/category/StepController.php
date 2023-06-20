<?php

namespace App\Http\Controllers\api\category;

use App\Http\Controllers\Controller;
use App\Models\shared\Step;
use Illuminate\Http\Request;

class StepController extends Controller
{
   public function index(){
    $step = Step::all();
    // dd($request->document_type_id);
     // return DepartmentResource::collection($department);
     $result = array();
     $result['data'] = array();
     $result['data'] = $step;
     $result['success'] = "1";
     return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
   }
}
