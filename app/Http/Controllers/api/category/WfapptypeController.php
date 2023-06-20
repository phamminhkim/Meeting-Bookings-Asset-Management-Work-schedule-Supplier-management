<?php

namespace App\Http\Controllers\api\category;

use App\Http\Controllers\Controller;
use App\Models\shared\Vendor;
use App\Models\shared\Wfapptype;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WfapptypeController extends Controller
{
    public function index(Request $request)
    {

        $result = array();
        $result['data'] = array();
        $query = Wfapptype::query();

        try {


            $Wfapptype = $query->orderBy('id','desc')->get();
            $result['data'] = $Wfapptype;
            $result['success'] = "1";
        } catch (Exception $e) {
            $this->errors = $e->getMessage();
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    public function store(Request $request)
    {

        $result = array();
        $result['data'] =  array();
        $result['data']['success']  = 0;
        date_default_timezone_set("Asia/Ho_Chi_Minh");
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'name' => 'required',

        ]);

        $failed = $validator->fails();
        if ($failed) {
            $result['data']['errors']  = $validator->errors();
        } else {

            try {
                $re = Wfapptype::create($request->all());
                if ($re) {

                    $result['data']  = $re;
                    // $result['data']['success']  = 1;
                }
            } catch (\Exception $e) {
                $result['data']['errors']  =  $e->getMessage();
            }
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    public function show($id)
    {
        $Wfapptype = Wfapptype::with('company')->findOrFail($id);

        $result = array();
        $result['data'] =  array();
        $result['data'] = $Wfapptype;


        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    public function update(Request $request, $id)
    {
        $result = array();
        $result['data'] =  array();
        $result['data']['success']  = 0;
        date_default_timezone_set("Asia/Ho_Chi_Minh");

        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'name' => 'required',

        ]);

        $failed = $validator->fails();

        if ($failed) {
            $result['data']['errors']  = $validator->errors();
        } else {
            try {

                $wfapptype = Wfapptype::findOrFail($id);
                if ($wfapptype) {
                    $wfapptype->id = $request->input('id');
                    $wfapptype->name = $request->input('name');
                    $wfapptype->description = $request->input('description');
                    $wfapptype->active = $request->input('active');
                    if($wfapptype->save())
                     $result['data']['success']  = 1;
                }
            } catch (\Exception $e) {
                $result['data']['errors']  =  $e->getMessage();
            }
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    public function destroy($id)
    {
        // Get article
        $Wfapptype = Wfapptype::findOrFail($id);
        $result = array();
        $result['data'] = array();
        $result['data']['success']  = 0;
        if( $Wfapptype->delete() ){
            $result['data']['success']  = 1;
        }
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);

    }
}
