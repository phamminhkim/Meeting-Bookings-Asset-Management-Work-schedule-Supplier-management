<?php

namespace App\Http\Controllers\frontend\calendar;

use App\Http\Controllers\BaseController\BaseController;
use App\Http\Controllers\Controller;
use App\Models\calendar\Calendar;
use App\Models\calendar\CalendarDetail;
use App\Models\shared\DocumentType;
use App\Ultils\ReadCurrency;
use App\Ultils\Ultils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

/**
 * Class này quản lý dữ liệu cho frontend của payrequest
 */
class CalendarsFrontend extends BaseController{

    public function index(Request $request)
    {
      
        $type = $request->type;
        $id =   $request->id;
        $payrequest = CalendarDetail::find($id);   
       
        $doctype = "";
        if($payrequest){
             
            $documentType =Calendar::where('calendar_type_id',$payrequest->id)->first();
           
            if($documentType){
                $doctype = $documentType->id;
            }
           
        }else{
            $doctype = $request->doctype;
        }

        $documentType = Calendar::where('calendar_type_id',$doctype)->first();
       
        $doctype_id= '';
        $doctype_serial_num= '';
        
        
        if($documentType){
            $doctype_id = $documentType->id;
            $doctype_serial_num = $documentType->serial_num;
        }
     
    
        $notification_id = $request->notification_id;
        $array = [];
        $array['type'] = $type;
        $array['id'] = $id;
       
        $array['notification_id'] = $notification_id;
       
        return  $array ;
      
    }
}
?>