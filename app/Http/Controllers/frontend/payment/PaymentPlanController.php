<?php

namespace App\Http\Controllers\frontend\payment;

use App\Http\Controllers\BaseController\BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentPlanController extends BaseController  
{
    public function index(Request $request)
    {
        // $type = $request->type;
        // $parent = $request->has('parent')?$request->input('parent'):"" ;
        // $contract_type = $request->has('contract_type')?$request->input('contract_type'):"1";
        // $id =   $request->id;
        // return view('payment.plans.index',compact('type','id','parent','contract_type'));
        return view('payment.plans.index');
      
    }
}
