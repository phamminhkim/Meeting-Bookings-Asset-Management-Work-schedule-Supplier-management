<?php

namespace App\Http\Controllers\api\payment;

use App\Http\Controllers\BaseController\BaseController;
use App\Models\payment\PaymentAttached;
use App\Models\payment\Payrequest;
use App\Repositories\payment\PayrequestMain;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use App\User;

class PayRequestController extends BaseController
{

    public function index(Request $request)
    {
        $this->authorize('review_yctt', new Payrequest());

        $result = array();
        $result['data'] = array();
        $result['success'] = "0";

        $result = array();
        $result['data'] = array();
        $result['success'] = "0";
        $payrequestBase = PayrequestMain::create($request, '');

        $list = $payrequestBase->index($request);
        if ($list) {
            $result['data'] = $list;
            $result['success'] = "1";
        }

        $role = User::find(auth()->user()->id);

        if ($role->hasRole('admin')) {
            $result['canviewteam'] = 1;
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);

    }
    //Lấy danh sách Tạm ứng của User login
    public function advance_money(Request $request)
    {
        $result = array();
        $result['data'] = array();
        $result['success'] = "0";

        $result = array();
        $result['data'] = array();
        $result['success'] = "0";
        $payrequestBase = PayrequestMain::create($request, '');

        $list = $payrequestBase->get_advance_money();
        if ($list) {
            $result['data'] = $list;
            $result['success'] = "1";
        }


        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create_yctt', new Payrequest());
        $result = array();
        $result['data'] = array();

        $result['data']['success'] = '0';



        $payrequestBase = PayrequestMain::create($request, '');
        $payRequestModel = $payrequestBase->store();
        if ($payRequestModel) {
            $result['data']['success'] = 1;

            $result['data']  = $payRequestModel;
        } else {
            $result['data']['errors'] = $payrequestBase->errors;
        }


        // dd("test class");
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);

    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $this->authorize('review_yctt', Payrequest::find($id));


        $payrequestBase = PayrequestMain::create($request, '');

        $payRequestModel = $payrequestBase->show($id);
        $result = array();
        $result['data'] =  array();
        $result['data'] = $payRequestModel;
        $result['data']['success']  = 1;
        if (!$payRequestModel) {
            $result['data']['success']  = 0;
        }



        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $this->authorize('update_yctt', Payrequest::find($id));


        $payrequestBase = PayrequestMain::create($request, '');
        $payRequestModel = $payrequestBase->edit($id);

        $result = array();
        $result['data'] =  array();
        $result['data'] = $payRequestModel;
        $result['data']['success']  = 1;
        if (!$payRequestModel) {
            $result['data']['success']  = 0;
        }



        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    public function update(Request $request, $id)
    {

        $this->authorize('update_yctt', Payrequest::find($id));

        $result = array();
        $result['data'] = array();

        $result['data']['success'] = 0;


        $payrequestBase = PayrequestMain::create($request, '');
        $payRequestModel = $payrequestBase->update($id);

        if ($payRequestModel) {
            $result['data']['success'] = 1;
            $result['data']  = $payRequestModel;
        } else {
            $result['data']['errors'] = $payrequestBase->errors;
        }


        // dd("test class");
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);


    }
    //Cập nhật chứng từ đã thanh toán
    public function update_paid(Request $request)
    {
        // update_dathanhtoan

        $this->authorize('update_dathanhtoan_yctt',  new Payrequest());

        $result = array();
        $result['data'] = array();

        $result['data']['success'] = 0;


        $payrequestBase = PayrequestMain::create($request, '');
        $re = $payrequestBase->update_paid();

        if ($re) {

            $result['data']['ids'] = $re;
            $result['data']['success'] = 1;
        }


        // dd("test class");
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    //Cập nhật chứng huỷ
    public function update_cancel(Request $request)
    {

        $this->authorize('update_cancel_yctt', Payrequest::find($request->id));

        $result = array();
        $result['data'] = array();
        $result['data']['success'] = 0;
        $payrequestBase = PayrequestMain::create($request, '');
        $re = $payrequestBase->update_cancel();

        if ($re) {
            $result['data']['success'] = 1;
        } else {
            $result['data']['message']  = "Chứng từ đã gửi cần phải trả lại mới được hủy.";
        }

        // dd("test class");
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    public function destroy(Request $request, $id)
    {

        $this->authorize('delete_yctt', Payrequest::find($id));
        $result = array();
        $result['data'] =  array();
        $result['data']['success']  = 0;

        $payrequestBase = PayrequestMain::create($request, '');

        if ($payrequestBase->destroy($id)) {
            $result['data']['success']  = 1;
        } else {
            $result['data']['message']  = $payrequestBase->message;
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }


    public function payment_attached_check(Request $request)
    {


        $payment_att = PaymentAttached::find($request->payment_attached_id);
        if ($payment_att) {

            $this->authorize('check_attach_yctt', Payrequest::find($payment_att->payrequest_id));
        } else {
            abort(404);
        }

        $result = array();
        $result['data'] =  array();

        $result['data']['success']  = 0;

        $payrequestBase = PayrequestMain::create($request, '');

        if ($payrequestBase->payment_attached_check()) {
            $result['data']['success']  = 1;
            $result['data']['status']  = $request->status;
        }


        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    public function update_hard_doc(Request $request)
    {


        $this->authorize('update_hard_doc_yctt', Payrequest::find($request->id));
        $result = array();
        $result['data'] =  array();

        $result['data']['success']  = 0;

        $payrequestBase = PayrequestMain::create($request, '');

        if ($payrequestBase->update_hard_doc()) {
            $result['data']['success']  = 1;
        }


        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    public function miss_invoice_check(Request $request)
    {

        $this->authorize('update_miss_invoice_yctt', Payrequest::find($request->id));
        $result = array();
        $result['data'] =  array();

        $result['data']['success']  = 0;

        $payrequestBase = PayrequestMain::create($request, '');

        if ($payrequestBase->miss_invoice_check()) {
            $result['data']['success']  = 1;
            $result['data']['miss_invoice']  = $request->miss_invoice;
        }


        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

    public function printed_check(Request $request)
    {

        $this->authorize('printed_check_yctt', Payrequest::find($request->id));
        $result = array();
        $result['data'] =  array();

        $result['data']['success']  = 0;

        $payrequestBase = PayrequestMain::create($request, '');

        if ($payrequestBase->printed_check()) {
            $result['data']['success']  = 1;
            $result['data']['printed']  = $request->printed;
        }


        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    public function statistics(Request $request)
    {
        $this->authorize('review_yctt', new Payrequest());

        $result = array();
        $result['data'] = array();
        $result['success'] = "0";

        $result = array();
        $result['data'] = array();
        $result['success'] = "0";
        $payrequestBase = PayrequestMain::create($request, '');

        $list = $payrequestBase->statistics($request);
        if ($list) {
            $result['data'] = $list;
            $result['success'] = "1";
        }


        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);

    }
    public function get_settlement_doc(Request $request, $id)
    {


        $this->authorize('review_yctt', Payrequest::find($id));

        $result = array();
        $result['data'] = array();
        $result['success'] = "0";

        $result = array();
        $result['data'] = array();
        $result['success'] = "0";
        $payrequestBase = PayrequestMain::create($request, '');

        $doc = $payrequestBase->get_settlement_doc($id);
        if ($doc) {
            $result['data'] =  $doc;
            $result['success'] = "1";
        }


        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
}
