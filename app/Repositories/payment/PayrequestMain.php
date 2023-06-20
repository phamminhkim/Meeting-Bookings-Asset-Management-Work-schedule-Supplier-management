<?php 
namespace App\Repositories\payment;

use App\Models\payment\Payrequest;
use App\Models\shared\DocumentType;
use App\Ultils\Ultils;
use Illuminate\Http\Request;

final class PayrequestMain extends PayrequestBase{

    public static function create(Request $request,$type)
    {
     
        $obj = null;
        $documentType = DocumentType::find( $request->document_type_id);
         
        $doctype = "";
        if($documentType){
            $doctype = $documentType->code;
        }
       
        switch ($doctype) {
            case Ultils::$MODULE_PAYMENT_DNTT: // Đề nghị thanh toán
                $obj= new PayrequestDNTT($request);
                break;
            case Ultils::$MODULE_PAYMENT_DNTU: // Đề nghị tạm ứng
                $obj= new PayrequestDNTU($request);
                break;
            case Ultils::$MODULE_PAYMENT_QTTU: // Quyết toán tạm ứng
                $obj= new PayrequestQTTU($request);
                break;
            case Ultils::$MODULE_PAYMENT_CPTK: // Chi phí tiếp khách
                $obj= new PayrequestCPTK($request);
                break;
            default: //Đề nghị thanh toán
                
                $obj= new PayrequestBase($request);
              
                break;
        }
        return  $obj;
    }
}



?>