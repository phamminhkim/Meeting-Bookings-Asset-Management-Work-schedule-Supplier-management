<?php

namespace App\Http\Controllers\frontend\managerprice;

use App\Http\Controllers\BaseController\BaseController;
use App\Http\Controllers\Controller;
use App\Models\managerprice\PriceReq;
use App\Models\shared\PrintedDoc;
use Illuminate\Http\Request;

class PriceAppReqContoller extends PriceAppReqFontend
{
    public function index(Request $request)
    {


        $priceAppReqFontend = new PriceAppReqFontend;
        $arrayData = $priceAppReqFontend->index($request);
        return view('managerprice.request.index',$arrayData);
        // return view('payment.request.index',compact('type','id','doctype','doctype_id','doctype_name','layout','module','notification_id'));

    }
    //chức năng này chỉ tạm thời lưu cho
    public function update_printed_docs(){
        $list = PriceReq::where('status','2')->get();
        foreach ($list as   $object) {
            $con = new PriceAppReqFontend;
            try {
                $html =  $con->print(new Request ,$object->id);
            } catch (\Throwable $th) {
                // throw $th;
            }


            $printDoc = new PrintedDoc();
            $printDoc->content = $html;
            $object->printedDocs()->save($printDoc);
        }
        return "DONE";
    }
}
