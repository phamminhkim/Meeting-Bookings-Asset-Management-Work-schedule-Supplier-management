<?php

namespace App\Repositories\shared;

use App\Models\issue\Issue;
use App\Models\car\Car;
use App\Models\document\Document;
use App\Models\managerprice\PriceReq;
use App\Models\payment\Payrequest;
use App\Models\shared\Shared;
use App\Models\work\workflow\runtime\WlworkflowDoc;
use App\Ultils\Ultils;
use Illuminate\Http\Request;

final class SharedMain extends SharedBase
{

    public static function create(Request $request)
    {

        $obj = null;
        $module =   $request->module;
        $sharedBase = null;
        $url = "";

        //Cấu hình các loại chứng từ cụ thể sẽ phát triển sau  vào bên dưới
        //Mỗi chứng từ sẽ tương ứng 1 class con kế thừa từ class

        switch ($module) {
            case Ultils::$MODULE_PAYREQUEST_MODEL:

                $obj = Payrequest::find($request->doc_id);
                if ($obj) {
                    $url =  Ultils::$URL_PAYMENT_REQUEST_VIEW . $request->doc_id;
                }

                break;
            case Ultils::$MODULE_PRICE:

                $obj = PriceReq::find($request->doc_id);
                if ($obj) {
                    $url =  Ultils::$URL_PRICE_VIEW . $request->doc_id;
                }

                break;
            case Ultils::$MODULE_REPORT:

                $obj = Document::find($request->doc_id);
                if ($obj) {
                    $url =  Ultils::$URL_DOCUMENT_VIEW . $request->doc_id;
                }

                break;
            case Ultils::$MODULE_WORKFLOW:

                $obj = WlworkflowDoc::find($request->doc_id);
                if ($obj) {
                    $url =  Ultils::$URL_WORKFLOW_VIEW . $request->doc_id;
                }

                break;
            case Ultils::$MODULE_ISSUE:

                $obj = Issue::find($request->doc_id);
                if ($obj) {
                    $url =  Ultils::$URL_ISSUE_VIEW . $request->doc_id;
                }
                break;
            case Ultils::$MODULE_CARS:

                $obj = Car::find($request->doc_id);
                if ($obj) {
                    $url =  Ultils::$URL_CAR_VIEW . $request->doc_id;
                }

                break;
            default:

                break;
        }
        //dd($obj );
        if ($obj) {
            $sharedBase = new SharedBase($request, $obj, $url);
        }
        return $sharedBase;
    }
}
