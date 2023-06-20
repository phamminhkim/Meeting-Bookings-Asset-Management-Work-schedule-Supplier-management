<?php

namespace App\Http\Controllers\api\category;

use App\Http\Controllers\Controller;
use App\Models\shared\Module;
use App\Models\shared\UniqueId;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class UniqueIdController extends Controller
{
    public function index(Request $request)
    {
        $module = UniqueId::all();

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
            'document_type_code' => 'required|max:8',
            'module' => 'required|max:10',
            'company_id' => 'required|max:4',
            //'serial' => 'required|max:6',
        ]);
        $failed = $validator->fails();
        if ($failed) {
            $result['data']['errors']  = $validator->errors();
        } else {
            try {
                $exist_uid = UniqueId::where('module', $request->module)
                                    ->where('company_id', $request->company_id)
                                    ->where('document_type_code', $request->document_type_code)->first();
                if ($exist_uid) {
                    //$exist_uid->serial = $request->input('serial');
                    $exist_uid->letter = $request->input('letter');
                    $exist_uid->year = $request->input('year');
                    $exist_uid->auto = $request->input('auto');
                    $exist_uid->save();
                }
                else {
                    $exist_uid = UniqueId::create($request->all());
                }
               
                if ($exist_uid) {
                    $result['data']  = $exist_uid;
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
        $uniqueId = UniqueId::findOrFail($id);

        $result = array();
        $result['data'] = $uniqueId;
        $result['success'] = "1";

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
        $uniqueId = UniqueId::findOrFail($id);
        $result = array();
        $result['data'] = array();

        if ($uniqueId->delete()) {
            $result['data']['success']  = 1;
        } else {
            $result['data']['success'] = 0;
        }
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);

    }
}
