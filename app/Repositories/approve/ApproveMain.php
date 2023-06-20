<?php

namespace App\Repositories\approve;

use App\Models\document\Document;
use App\Models\managerprice\PriceApproveRequest;
use App\Models\managerprice\PriceReq;
use Illuminate\Support\Facades\DB;
use App\Models\payment\Payrequest;
use App\Models\shared\Approved;
use App\Models\shared\Team;
use App\Models\work\workflow\runtime\WlworkflowDoc;
use App\Models\work\workflow\WlworkflowAppdoc;
use App\Repositories\approve\document\ApproveDocument;
use App\Repositories\approve\price\ApprovePrice;
use App\User;
use Carbon\Carbon;
use Exception;
use App\Repositories\ApproveAbs;
use App\Repositories\approve\payment\ApprovePayment;
use App\Repositories\approve\workflowdoc\ApproveWorkflowdoc;
use App\Ultils\Ultils;
use Illuminate\Http\Request;

final class ApproveMain
{

    public static function create($type, $object_id)
    {
        $approve = null;
        $obj = null;
        $team = null;
        $list_approved = null;
        $module = $type;

        //Cấu hình các loại chứng từ cụ thể sẽ phát triển sau  vào bên dưới
        //Mỗi chứng từ sẽ tương ứng 1 class con kế thừa từ class ApproveAbs
        switch ($module) {


            case Ultils::$MODULE_WORKFLOW:
                //Lấy theo từng đối tượng nếu tồn tại ID
                //Nếu không có ID thì truyền các đối tượng NULL
                
                $obj = WlworkflowDoc::find($object_id);
                if ($obj) {
                    $team = Team::findOrFail($obj->team_id);
                    $list_approved = $obj->approveds;
                }

                $approve = new ApproveWorkflowdoc($team, $obj, $list_approved);

                break;
            case Ultils::$MODULE_PAYMENT:
                //Lấy theo từng đối tượng nếu tồn tại ID
                //Nếu không có ID thì truyền các đối tượng NULL
                $obj = Payrequest::find($object_id);
                if ($obj) {
                    $team = Team::findOrFail($obj->team_id);
                    $list_approved = $obj->approveds;
                }

                $approve = new ApprovePayment($team, $obj, $list_approved);

                break;
            case Ultils::$MODULE_REPORT:
                //Lấy theo từng đối tượng nếu tồn tại ID
                //Nếu không có ID thì truyền các đối tượng NULL
                $obj = Document::find($object_id);
                if ($obj) {
                    $team = Team::findOrFail($obj->team_id);
                    $list_approved = $obj->approveds;
                }

                $approve = new ApproveDocument($team, $obj, $list_approved);

                break;
            case Ultils::$MODULE_PRICE:
                //Lấy theo từng đối tượng nếu tồn tại ID
                //Nếu không có ID thì truyền các đối tượng NULL

                $obj = PriceReq::find($object_id);

                if ($obj) {
                    $team = Team::findOrFail($obj->team_id);
                    $list_approved = $obj->approveds;
                    // dd($team);
                }

                $approve = new ApprovePrice($team, $obj, $list_approved);

                break;

            default: //yêu cầu thanh toán

                break;
        }
        return $approve;
    }
}
