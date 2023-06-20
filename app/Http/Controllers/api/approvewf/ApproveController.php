<?php

namespace App\Http\Controllers\api\approvewf;

use App\Http\Controllers\BaseController\BaseController;
use App\Models\shared\Wfapproved;
use App\Repositories\approvewf\ApproveMain;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ApproveController extends BaseController
{
    public function index(Request $request)
    {
        $result = array();
        $result['data'] = array();
        $result['success'] = "0";
        $approve = ApproveMain::create($request->type, null, $request);
        // dd( $request->type);
        $list = $approve->index($request);
        if ($list) {
            $result['data'] = $list;
            $result['success'] = "1";
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    // public function store_app_car(Request $request)
    // {

    //     $result = array();
    //     $result['data'] = array();

    //     $result['data']['success'] = '0';
    //     dd($request->all());
    //     $approve = ApproveMain::create($request, '',$request);
    //     dd($request->type);
    //     if ($approve->send()) {
    //         $result['data']['success'] = 1;
    //         $result['data']['user'] = $approve->user_approve;

    //     } else {
    //         $result['data']['errors'] = $approve->errors;
    //     }
    //     // dd("test class");
    //     return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);



    // }
    //gửi duyệt
    public function get_approve_info(Request $request)
    {

        $result = array();
        $result['data'] = array();

        $result['data']['success'] = '0';
        //dd("TYPE=". $request->type . ' id='. $request->id);
        $approve = ApproveMain::create($request->type, $request->id, $request);
        //dd( $approve->get_info());
        //dd($request->type);

        $result['data']['success'] = 1;
        //  dd($request->type);
        $result['data']['info'] = $approve->get_info();


        // dd("test class");
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);

    }

    //gửi duyệt
    public function approve_car_send(Request $request)
    {

        $result = array();
        $result['data'] = array();

        $result['data']['success'] = '0';

        $approve = ApproveMain::create($request->type, $request->id, $request);
        //dd($approve->errors);
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
    public function approve_car_agree(Request $request)
    {

        $result = array();
        $result['data'] = array();

        $result['data']['success'] = 0;

        $approve = ApproveMain::create($request->type, $request->id, $request);

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
    public function approve_car_refuse(Request $request)
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

            $approve = ApproveMain::create($request->type, $request->id, $request);

            $feedback = $approve->refuse($request->feedback);
            // dd($feedback);
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

    public function add_users(Request $request)
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

            $approve = ApproveMain::create($request->type, $request->id, $request);

            $feedback = $approve->add_users($request->list_user);
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
    public function log_approve()
    {
        $wfapprove = Wfapproved::where('online', 'X')->whereYear('created_at', date('Y'))->count();
        $count = 0;
        $wfapprove_delay = Wfapproved::select('id', 'wfapprovedable_id', 'checkout', 'expected_time')
            ->where('is_reminder', 1)
            ->where('online', 'X')
            ->whereYear('created_at', date('Y'))->get();
        for ($i = 0; $i < count($wfapprove_delay); $i++) {
            if (date('Y-m-d', strtotime($wfapprove_delay[$i]['checkout'])) > date('Y-m-d', strtotime($wfapprove_delay[$i]['expected_time']))) {
                $count++;
            }
        }
        $result['is_wfapprove'] =  $wfapprove;
        $result['is_wfapprove_delay'] = $count;

        return json_encode($result, JSON_UNESCAPED_UNICODE);
    }
}
