<?php

namespace App\Http\Controllers\api\category;

use App\Http\Controllers\Controller;
use App\Models\shared\Vendor;
use App\Models\shared\Wfapptype;
use App\Models\shared\Wfusertype;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WfusertypeController extends Controller
{
    public function index(Request $request)
    {

        $result = array();
        $result['data'] = array();
        $query = Wfusertype::query();

        try {



            $wfusertype = $query->orderBy('id','desc')->get();
            $result['data'] = $wfusertype;
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
                $re = Wfusertype::create($request->all());
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
        $vendor = Wfusertype::with('company')->findOrFail($id);

        $result = array();
        $result['data'] =  array();
        $result['data'] = $vendor;


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

                $wfusertype = Wfusertype::findOrFail($id);
                if ($wfusertype) {
                    $wfusertype->id = $request->input('id');
                    $wfusertype->name = $request->input('name');
                    $wfusertype->description = $request->input('description');
                    $wfusertype->active = $request->input('active');
                    if($wfusertype->save())
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
        $vendor = Wfusertype::findOrFail($id);
        $result = array();
        $result['data'] = array();
        $result['data']['success']  = 0;
        if( $vendor->delete() ){
            $result['data']['success']  = 1;
        }
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);

    }
}
