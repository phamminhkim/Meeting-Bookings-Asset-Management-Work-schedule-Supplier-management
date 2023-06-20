<?php

namespace App\Http\Controllers\api\shared;

use App\Http\Controllers\BaseController\BaseController;
use App\Models\shared\Shared;
use App\Repositories\shared\SharedMain;
use Illuminate\Http\Request;

class SharedController extends BaseController
{
    public function index(Request $request)
    {

        $result = array();
        $result['data'] = array();
        $result['success'] = "0";

        $list = Shared::where('user_id', auth()->user()->id)->get();

        if ($list) {
            $result['data'] = $list;
            $result['success'] = "1";
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

    public function store(Request $request)
    {

        $result = array();
        $result['data'] = array();

        $result['data']['success'] = 0;
      
        $sharedBase = SharedMain::create($request);
        if ($sharedBase) {
            
            $shared = $sharedBase->store();
            if ($shared) {
                $result['data']['success'] = 1;
                $result['data']['shared'] = $shared;

            } else {
                $result['data']['errors'] = $sharedBase->errors;
            }
        }
        
        // dd("test class");
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    public function show(Request $request, $id)
    {

        $shared = Shared::find($id);

        $result = array();
        $result['data'] = array();
        $result['data'] = $shared;
        $result['data']['success'] = 1;
        if (!$shared) {
            $result['data']['success'] = 0;
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    public function edit(Request $request, $id)
    {

        $sharedBase = SharedMain::create($request);
        $sharedBase = $sharedBase->edit($id);

        $result = array();
        $result['data'] = array();
        $result['data'] = $sharedBase;
        $result['data']['success'] = 1;
        if (!$sharedBase) {
            $result['data']['success'] = 0;
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    public function update(Request $request, $id)
    {

        $this->authorize('update',Shared::find($id));

        $result = array();
        $result['data'] = array();

        $result['data']['success'] = 0;

        $sharedBase = SharedMain::create($request);
        //dd($sharedBase);
        $shared = $sharedBase->update($id);
        if ($shared) {
            $result['data']['success'] = 1;
            $result['data']['shared'] = $shared;

        } else {
            $result['data']['errors'] = $sharedBase->errors;
        }
        // dd("test class");
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

    public function destroy(Request $request, $id)
    {

        $this->authorize('delete',Shared::find($id));
        $result = array();
        $result['data'] = array();

        $result['data']['success'] = 0;

        $sharedBase = SharedMain::create($request);


        $re = $sharedBase->destroy($id);
        if ($re) {
            $result['data']['success'] = 1;


        } else {
            $result['data']['errors'] = $sharedBase->errors;
        }
        // dd("test class");
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }


}
