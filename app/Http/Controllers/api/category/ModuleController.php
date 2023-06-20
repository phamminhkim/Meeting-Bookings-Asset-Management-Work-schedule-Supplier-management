<?php

namespace App\Http\Controllers\api\category;

use App\Http\Controllers\Controller;
use App\Models\shared\Module;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    public function index(Request $request)
    {
        $module = Module::all();

        $result = array();
        $result['data'] = $module;
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
            'id' => 'required|max:10',
            'name' => 'required|max:30',
        ]);
        $failed = $validator->fails();
        if ($failed) {
            $result['data']['errors']  = $validator->errors();
        } else {
            try { 
                $module = Module::create($request->all());
                if ($module) {
                   
                    $result['data']  = $module;
                    $result['data']['success']  = 1;
                }
            } catch (\Exception $e) {
                $result['data']['errors']  =  $e->getMessage();
            }
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $module = Module::findOrFail($id);

        $result = array();
        $result['data'] = $module;
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
            'id' => 'required|max:10',
            'name' => 'required|max:30',
        ]);

        $failed = $validator->fails();
        if ($failed) {
            $result['data']['errors']  = $validator->errors();
        } else {
            try {
                $module = Module::findOrFail($id);
                if ($module) {
                    $module->name = $request->input('name');

                    if ($module->save())
                        $result['data']['success'] = 1;
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
        $module = Module::findOrFail($id);
        $result = array();
        $result['data'] = array();

        if ($module->delete()) {
            $result['data']['success']  = 1;
        }
        else {
            $result['data']['success'] = 0;
        }
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);

    }
}
