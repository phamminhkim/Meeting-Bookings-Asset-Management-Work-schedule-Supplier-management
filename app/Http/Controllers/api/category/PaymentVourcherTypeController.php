<?php

namespace App\Http\Controllers\api\category;

use App\Http\Controllers\Controller;
use App\Models\payment\PaymentVoucherType;
use Illuminate\Http\Request;

class PaymentVourcherTypeController extends Controller
{
    public function index(Request $request)
    {   
        
        $paymentVoucherType = PaymentVoucherType::all();

        // return DepartmentResource::collection($department);
        $result = array();
        $result['data'] = array();
        $result['data'] = $paymentVoucherType;
        $result['success'] = "1";
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
}
