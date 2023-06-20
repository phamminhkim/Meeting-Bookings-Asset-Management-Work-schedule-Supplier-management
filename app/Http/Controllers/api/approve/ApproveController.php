<?php

namespace App\Http\Controllers\api\approve;

use App\Repositories\approve\ApproveMain;
use App\Http\Controllers\BaseController\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApproveController extends BaseController
{
    public function index(Request $request)
    {
        $result = array();
        $result['data'] = array();
        $result['success'] = "0";

        $approve = ApproveMain::create($request->type, null, null);

        $list = $approve->index($request);
        if ($list) {
            $result['data'] = $list;
            $result['success'] = "1";
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    public function get_pending_count($type)
    {
        $approve = ApproveMain::create($type, null, null);

        return $approve->pending_count();
    }

    //gửi duyệt
    public function get_approve_info(Request $request)
    {

        $result = array();
        $result['data'] = array();
      
        $result['data']['success'] = '0';
        //dd("TYPE=". $request->type . ' id='. $request->id);
        $approve = ApproveMain::create($request->type, $request->id, null);
        
        $result['data']['success'] = 1;
        //  dd($request->type);
        if ($approve) {
            $result['data']['info'] = $approve->get_info();
        }
        else {
            $result['data']['info'] = null;
        }


        // dd("test class");
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);

    }

    //gửi duyệt
    public function approve_payment_send(Request $request)
    {

        $result = array();
        $result['data'] = array();

        $result['data']['success'] = '0';

        $approve = ApproveMain::create($request->type, $request->id, null);
        // dd($request->type);
        if ($approve->send()) {
            $result['data']['success'] = 1;
            $result['data']['user'] = $approve->user_approve;
        } else {
            $result['data']['errors'] = $approve->errors;
        }
        // dd("test class");
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);

    }
    //duyệt
    public function approve_payment_agree(Request $request)
    {

        $result = array();
        $result['data'] = array();

        $result['data']['success'] = 0;

        $approve = ApproveMain::create($request->type, $request->id, null);

        $approveInfo = $approve->agree();
        if ($approveInfo) {
            $result['data']['success'] = 1;
            $result['data']['user'] = $approve->next_user;
            $result['data']['approve'] = $approveInfo;
        } else {
            $result['data']['errors'] = $approve->errors;
        }
        // dd("test class");
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);

    }

    //Từ chối duyệt
    public function approve_payment_refuse(Request $request)
    {

        $result = array();
        $result['data'] = array();
        $result['data']['success'] = 0;

        //$request = new Payrequest();
        $validator = Validator::make($request->all(), [
            'feedback' => 'required|max:255',
            'id' => 'required',
        ]);
        $failed = $validator->fails();
        //dd($request->feedback);
        if ($failed) {


            $result['data']['errors'] = $validator->errors();
        } else {

            $approve = ApproveMain::create($request->type, $request->id, null);

            $feedback = $approve->refuse($request->feedback);
            //dd("toi day");
            if ($feedback) {
                $result['data']['success'] = 1;
                $result['data']['feedback'] = $feedback;
            } else {
                //dd($approve->errors);
                $result['data']['errors'] = $approve->errors;
            }
            return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

    public function approve_change_user(Request $request)
    {

        $result = array();
        $result['data'] = array();

        $result['data']['success'] = '0';

        $approve = ApproveMain::create($request->doctype, $request->docid, $request);
        $waiting_user = $approve->get_info_approved_waiting();

        //Kiểm tra người đang chờ duyệt là người login
        if (!$waiting_user) {
            abort(404);
        }

        foreach ($approve->team->users as $key => $new_user) {
            if ($new_user->id == $request->userid) {
                $waiting_user->user_id = $new_user->id;
                $waiting_user->level = $new_user->pivot->level;
                $waiting_user->team_id = $new_user->pivot->team_id;
                $waiting_user->step = $new_user->pivot->step;
                $waiting_user->review = $new_user->pivot->review;
                $waiting_user->sign = $new_user->pivot->sign;
                $waiting_user->save();

                if ($request->notify) {
                    $approve->notify_current_approver();
                }
                break;
            }
        }
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

    public function approve_payment_multipleagree(Request $request)
    {
        $result = array();
        $result['data'] = array();
        $fields = $request->all();
        $validator = Validator::make($fields, [
            'payments' => 'required',
            'type' => 'required'
        ]);

        $failed = $validator->fails();
        if ($failed) {
            $result['data']['errors']  = $validator->errors();
        } else {

            try {
                $paymentids = explode(",", $request->payments);

                $successApproves = array();
                $failApproves = array();

                foreach ($paymentids as $paymentid) {
                    $approve = ApproveMain::create($request->type, $paymentid, null);

                    $approveInfo = $approve->agree();
                    if ($approveInfo) {
                        array_push($successApproves, $approveInfo);

                    } else {
                        array_push($failApproves, $paymentid);
                    }
                }

                $result['data']['successApproves'] = $successApproves;
                $result['data']['failApproves'] = $failApproves;
            } catch (\Exception $e) {
                $result['data']['errors']  =  $e->getMessage();
            }
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);

    }
}
