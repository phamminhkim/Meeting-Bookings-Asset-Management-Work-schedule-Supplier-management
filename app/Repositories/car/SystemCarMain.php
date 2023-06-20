<?php 
namespace App\Repositories\car;

use App\Models\shared\DocumentType;
use App\Ultils\Ultils;
use Illuminate\Http\Request;

final class SystemCarMain extends SystemCarBase{
    public static function create(Request $request)
    { 
        $obj = null;
        $documentType = null;
        $documentType = DocumentType::find($request->document_type_id);

        $doctype = "";
        if($documentType){
            $doctype = $documentType->code;
        }
        switch ($doctype) {
          
            // case Ultils::$MODULE_CAR:
            //     $obj= new SystemCarPDNS($request);
            //     break;  
            default:  
         
                $obj= new SystemCarBase($request);
                break;
        }
      
        return  $obj;
    }
}
?>