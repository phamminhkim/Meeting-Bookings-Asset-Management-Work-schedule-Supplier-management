<?php

namespace App\Http\Controllers\frontend\payment;

use App\Http\Controllers\BaseController\BaseController;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;

class ContractController extends BaseController
{
    public function index(Request $request)
    {
        $type = $request->type;
        $parent = $request->has('parent')?$request->input('parent'):"" ;
        $contract_type = $request->has('contract_type')?$request->input('contract_type'):"1";
        $id =   $request->id;
        $user = new User();
        $user = auth()->user();
        $company_id =  $user->company_id;
        return view('payment.contract.index',compact('type','id','parent','contract_type','company_id'));
      
    }
}
