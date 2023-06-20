<?php
namespace App\Repositories\reminder;

use App\Models\issue\Issue;
use App\Models\car\Car;
use App\Models\document\Document;
use App\Models\managerprice\PriceReq;
use App\Models\payment\Payrequest;
use App\Models\shared\Reminder;
use App\Models\work\workflow\runtime\WlworkflowDoc;
use App\Ultils\Ultils;
use Illuminate\Http\Request;

final class ReminderMain extends ReminderBase
{

    public static function create(Request $request  )
    {

        $obj = null;
        $module =   $request->module;
        $reminderBase = null;
        $url = "";

        //Cấu hình các loại chứng từ cụ thể sẽ phát triển sau  vào bên dưới
        //Mỗi chứng từ sẽ tương ứng 1 class con kế thừa từ class

        switch ($module) {
            case Ultils::$MODULE_PAYREQUEST_MODEL:

                $obj = Payrequest::find($request->object_id);
                if ($obj){
                    $url =  Ultils::$URL_PAYMENT_REQUEST_VIEW . $request->object_id;
                }

               break;
            case Ultils::$MODULE_PRICE:

                $obj = PriceReq::find($request->object_id);
                if ($obj){
                    $url =  Ultils::$URL_PRICE_VIEW . $request->object_id;
                }

               break;
            case Ultils::$MODULE_REPORT:

                $obj = Document::find($request->object_id);
                if ($obj){
                    $url =  Ultils::$URL_DOCUMENT_VIEW . $request->object_id;
                }

               break;

               case Ultils::$MODULE_ISSUE:

                $obj = Issue::find($request->object_id);
                if ($obj){
                    $url =  Ultils::$URL_ISSUE_VIEW . $request->object_id;
                }
                break;

            case Ultils::$MODULE_CARS:

                $obj = Car::find($request->object_id);
                if ($obj){
                    $url =  Ultils::$URL_CAR_VIEW . $request->object_id;

                }

               break;
            case Ultils::$MODULE_WORKFLOW:

                $obj = WlworkflowDoc::find($request->object_id);
                if ($obj){
                    $url =  Ultils::$URL_CAR_VIEW . $request->object_id;

                }

               break;
            default:

                break;
        }
        //dd($obj );
        if($obj){
            $reminderBase = new ReminderBase($request,$obj);
            $reminderBase->url = $url;

        }

        return $reminderBase;
    }
}
