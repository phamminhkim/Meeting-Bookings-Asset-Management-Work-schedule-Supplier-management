<?php

namespace App\Http\Controllers\api\category;

use App\Http\Controllers\Controller;
use App\Models\shared\PaymentType;
use Illuminate\Http\Request;

class PaymentTypeController extends Controller
{
    public function index(Request $request)
    {   
        
        $paymentType = PaymentType::all();
      //  dd($paymentType);
        // return DepartmentResource::collection($department);
        $result = array();
        $result['data'] = array();
        $result['data'] = $paymentType;
        $result['success'] = "1";
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
}
