<?php

namespace App\Http\Controllers\frontend\sloc;

use App\Http\Controllers\BaseController\BaseController;
use App\Http\Controllers\Controller;
use App\Models\sloc\Gooddocu;
use App\Models\sloc\GooddocusDetail;
use App\Models\shared\DocumentType;
use App\Ultils\ReadCurrency;
use App\Ultils\Ultils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

/**
 * Class này quản lý dữ liệu cho frontend của payrequest
 */
class GoodSlocFrontend extends BaseController{

    public function index(Request $request)
    {
      
        $type = $request->type;
        $id =   $request->id;
        $payrequest = GooddocusDetail::find($id);   
       
        $doctype = "";
        if($payrequest){
             
            $documentType =Gooddocu::where('id',$payrequest->id)->first();
           
            if($documentType){
                $doctype = $documentType->sloc_id;
            }
           
        }else{
            $doctype = $request->doctype;
        }
      
      
        // switch ($type) {
        //     case 'add':
        //         $this->authorize('create_docus', new GoodDetails());
        //         break;
        //     case 'view':
        //         $this->authorize('review_docus', GoodDetails::findOrFail($id));
        //         break;
        //     case 'edit':
        //         $this->authorize('update_docus',  GoodDetails::findOrFail($id));                
        //         break;
        //     case 'print':
        //         $this->authorize('review_docus',  GoodDetails::findOrFail($id));                
        //         break;
        //     default:
        //         $this->authorize('review_docus', new GoodDetails());               
        //         break;
        // }
         
        // Điều khiển layout ẩn hiện và hiển thị dấu (*)
       
        // switch ($doctype) {
        //     case Ultils::$MODULE_PAYMENT_DNTT:
        //         $payrequestBase = new  PayrequestDNTT($request);
        //         // dd( $payrequestBase->getLayout());
        //         break;
        //     case Ultils::$MODULE_PAYMENT_DNTU:
        //         $payrequestBase = new  PayrequestDNTU($request);
               
        //         break;
        //     case Ultils::$MODULE_PAYMENT_QTTU:
        //         $payrequestBase = new  PayrequestQTTU($request);
        //         break;
        //     case Ultils::$MODULE_PAYMENT_CPTK:
        //         $payrequestBase = new  PayrequestCPTK($request);
        //         break;                            
        //     default: //DNTT
        //         $payrequestBase = new  PayrequestBase($request);
               
        //         break;
        // }
        // $layout =$request->getLayout();

        $documentType = Gooddocu::where('sloc_id',$doctype)->first();
       
        $doctype_id= '';
        $doctype_serial_num= '';
        
        
        if($documentType){
            $doctype_id = $documentType->id;
            $doctype_serial_num = $documentType->serial_num;
        }
     
       // dd($payrequest->amount);  
       // return view('payment.request.index',compact('type','id','doctype','doctype_id','doctype_name','payrequest'));
        // $module = Ultils::$MODULE_PAYMENT;
        
      
       // dd("tesst" . $request->notification_id);
        $notification_id = $request->notification_id;
        $array = [];
        $array['type'] = $type;
        $array['id'] = $id;
        // $array['doctype'] = $doctype;
        // $array['doctype_id'] = $doctype_id;
        // $array['doctype_name'] = $doctype_name;
        // $array['layout'] = $layout;
        // $array['module'] = $module;
        $array['notification_id'] = $notification_id;
       
        return  $array ;
      
    }
}
?>