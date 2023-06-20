<?php

namespace App\Http\Controllers\frontend\sloc;

use App\Http\Controllers\BaseController\BaseController;
use App\Http\Controllers\Controller;
use App\Models\sloc\GoodDetails;
use App\Repositories\approve\ApproveMain;
use App\Ultils\ReadCurrency;
use App\Ultils\Ultils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class GoodSlocController extends BaseController
{
    public function index(Request $request)
    {


        $PayrequestFrontend = new GoodSlocFrontend;
        $arrayData = $PayrequestFrontend->index($request);
        return view('sloc.index',$arrayData);
        // return view('payment.request.index',compact('type','id','doctype','doctype_id','doctype_name','layout','module','notification_id'));

    }
    public function print(Request $request,$id){


        // $paymain =  PayrequestMain::create($request,"");
        // $payrequest =  $paymain->show($id);
      //  $payrequest = details::find($id)->with(['goods']);
    //    dd(Ultils::convert_number_to_words(12500.34));
       $info = array();

       $total = 0;
       $info['list_file'] = array();
       $list_file = array();
    }
}