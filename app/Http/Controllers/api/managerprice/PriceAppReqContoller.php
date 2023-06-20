<?php

namespace App\Http\Controllers\api\managerprice;

use App\Http\Controllers\BaseController\BaseController;
use App\Http\Controllers\Controller;
use App\Models\managerprice\PriceReq;
use App\Repositories\managerprice\PriceAppReqMain;
use Illuminate\Http\Request;
use App\User;

class PriceAppReqContoller extends BaseController
{
    public function index(Request $request)
    {
        $this->authorize('view', new PriceReq());

        $result = array();
        $result['data'] = array();
        $result['success'] = "0";

        $result = array();
        $result['data'] = array();
        $result['success'] = "0";
        $priceappreqBase = PriceAppReqMain::create($request, '');

        $list = $priceappreqBase->index($request);

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
    public function store(Request $request)
    {
        $this->authorize('create', new PriceReq()); //ycdg : yêu cầu duyệt giá

        $result = array();
        $result['data'] = array();

        $result['data']['success'] = '0';

        $prapRequestBase = PriceAppReqMain::create($request, '');
        $prapRequestModel = $prapRequestBase->store();
        if ($prapRequestModel) {
            $result['data']['success'] = 1;

            $result['data']  = $prapRequestModel;
        } else {
            $result['data']['errors'] = $prapRequestBase->errors;
        }


        // dd("test class");
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);

    }
    public function show(Request $request, $id)
    {
        $this->authorize('view', PriceReq::find($id));


        $prapRequestBase = PriceAppReqMain::create($request, '');

        $prapRequestModel = $prapRequestBase->show($id);
        $result = array();
        $result['data'] =  array();
        $result['data'] = $prapRequestModel;
        $result['data']['success']  = 1;
        if (!$prapRequestModel) {
            $result['data']['success']  = 0;
        }



        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    public function edit(Request $request, $id)
    {
        $this->authorize('update', PriceReq::find($id));


        $prapRequestBase = PriceAppReqMain::create($request, '');
        $prapRequestModel = $prapRequestBase->edit($id);

        $result = array();
        $result['data'] =  array();
        $result['data'] = $prapRequestModel;
        $result['data']['success']  = 1;
        if (!$prapRequestModel) {
            $result['data']['success']  = 0;
        }



        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    public function update(Request $request, $id)
    {

        $this->authorize('update', PriceReq::find($id));

        $result = array();
        $result['data'] = array();

        $result['data']['success'] = 0;


        $prapRequestBase = PriceAppReqMain::create($request, '');
        $prapRequestModel = $prapRequestBase->update($id);

        if ($prapRequestModel) {
            $result['data']['success'] = 1;
            $result['data']  = $prapRequestModel;
        } else {
            $result['data']['errors'] = $prapRequestBase->errors;
        }


        // dd("test class");
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);


    }
    public function update_cancel(Request $request)
    {


        $this->authorize('update_cancel', PriceReq::find($request->id));

        $result = array();
        $result['data'] = array();
        $result['data']['success'] = 0;
        $prapRequestBase = PriceAppReqMain::create($request, '');
        $re = $prapRequestBase->update_cancel();

        if ($re) {
            $result['data']['success'] = 1;
        }

        // dd("test class");
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
}
