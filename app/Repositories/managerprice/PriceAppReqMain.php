<?php
namespace App\Repositories\managerprice;

use App\Models\payment\Payrequest;
use App\Models\shared\DocumentType;
use App\Repositories\managerprice\PriceAppReqDGIA;
use App\Ultils\Ultils;
use Illuminate\Http\Request;

final class PriceAppReqMain extends PriceAppReqBase{

    public static function create(Request $request,$type)
    {

        $obj = null;
        $documentType = DocumentType::find( $request->document_type_id);

        $doctype = "";
        if($documentType){
            $doctype = $documentType->code;
        }

        switch ($doctype) {
            case Ultils::$MODULE_DOCUMENT_DGIA: // Trình duyệt vượt ngân sách
                $obj= new PriceAppReqDGIA($request);
                break;
            default: // yêu cầu duyệt giá

                $obj= new PriceAppReqBase($request);

                break;
        }

        return  $obj;
    }
}



?>
