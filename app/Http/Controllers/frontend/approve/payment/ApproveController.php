<?php

namespace App\Http\Controllers\frontend\approve\payment;

use App\Http\Controllers\BaseController\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\frontend\payment\PayrequestFrontend;
use App\Models\payment\Payrequest;
use App\Models\shared\DocumentType;
use App\Repositories\payment\PayrequestBase;
use App\Repositories\payment\PayrequestCPTK;
use App\Repositories\payment\PayrequestDNTU;
use App\Repositories\payment\PayrequestQTTU;
use App\Ultils\Ultils;
use Illuminate\Http\Request;

class ApproveController extends BaseController
{
    public function index(Request $request)
    {
      
            $PayrequestFrontend = new PayrequestFrontend;
            $arrayData = $PayrequestFrontend->index($request);
            return view('approve.payment.index',$arrayData);
       
    }
}
