<?php

namespace App\Http\Controllers\api\category;

use App\Http\Controllers\Controller;
use App\Models\shared\Bank;
use App\Models\work\workflow\WlworkflowType;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class WlworkflowTypeController extends Controller
{
    public function index(Request $request)
    {
        $workflowtypes = WlworkflowType::all();
        $result['data'] = $workflowtypes;
       
        $result['success'] = "1";

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

    public function store(Request $request)
    {

        $result = array();
        $result['data'] =  array();
        $result['data']['success']  = 0;
        date_default_timezone_set("Asia/Ho_Chi_Minh");
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);
        $failed = $validator->fails();
        if ($failed) {
            $result['data']['errors']  = $validator->errors();
        } else {
            try {
                $wlworkflowType = WlworkflowType::create($request->all());
                if ($wlworkflowType) {
                    $result['data']  = $wlworkflowType;
                    $result['data']['success']  = 1;
                }
            } catch (\Exception $e) {
                $result['data']['errors']  =  $e->getMessage();
            }
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

    public function show($id)
    {
        $wlworkflowType = WlworkflowType::findOrFail($id);

        $result = array();
        $result['data'] = $wlworkflowType;
        $result['success'] = "1";

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $result = array();
        $result['data'] =  array();
        $result['data']['success']  = 0;
        date_default_timezone_set("Asia/Ho_Chi_Minh");

        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ]);

        $failed = $validator->fails();
        if ($failed) {
            $result['data']['errors']  = $validator->errors();
        } else {
            try {
                $wlworkflowType = WlworkflowType::findOrFail($id);
                if ($wlworkflowType) {
                    $wlworkflowType->name = $request->input('name');
                    $wlworkflowType->description = $request->input('description');
                    $wlworkflowType->active = $request->input('active');

                    if ($wlworkflowType->save())
                        $result['data']['success']  = 1;
                }
            } catch (\Exception $e) {
                $result['data']['errors']  =  $e->getMessage();
            }
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $wlworkflowType = WlworkflowType::findOrFail($id);
        $result = array();
        $result['data'] = array();

        if ($wlworkflowType->delete()) {
            $result['data']['success']  = 1;
        } else {
            $result['data']['success'] = 0;
        }
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);

    }
}
