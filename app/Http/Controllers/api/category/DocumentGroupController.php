<?php

namespace App\Http\Controllers\api\category;

use App\Http\Controllers\Controller;
use App\Models\shared\DocumentGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DocumentGroupController extends Controller
{
    public function index(Request $request)
    {
        if($request->filled('module')){
            $documentGroup = DocumentGroup::where('module',$request->module)->get();
        }else{
            $documentGroup = DocumentGroup::all();
        }



        $result = array();
        $result['data'] = array();
        $result['data'] = $documentGroup;
        $result['success'] = "1";
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

    public function store(Request $request)
    {

        $result = array();
        $result['data'] =  array();
        $result['data']['success']  = 0;
        date_default_timezone_set("Asia/Ho_Chi_Minh");
        //dd( $request);
        $validator = Validator::make($request->all(), [

            'name' => 'required|max:50',
            'description' => 'required|max:50',
        ]);

        $failed = $validator->fails();
            //dd($failed);
        if ($failed) {
            $result['data']['errors']  = $validator->errors();
        } else {

            try {
                $re = DocumentGroup::create($request->all());
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
        $documentGroup = DocumentGroup::find($id);

        $result = array();
        $result['data'] =  array();
        $result['data'] = $documentGroup;


        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    public function update(Request $request, $id)
    {


        $result = array();
        $result['data'] =  array();
        $result['data']['success']  = 0;
        date_default_timezone_set("Asia/Ho_Chi_Minh");

        $validator = Validator::make($request->all(), [
            'description' => 'required|max:50',
            'name' => 'required|max:50',

        ]);

        $failed = $validator->fails();

        if ($failed) {
            $result['data']['errors']  = $validator->errors();
        } else {


            try {

                $documentGroup = DocumentGroup::findOrFail($id);
                if ($documentGroup) {

                    $documentGroup->description = $request->input('description');
                    $documentGroup->name = $request->input('name');

                    if($documentGroup->save())
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
        $documentGroup = DocumentGroup::find($id);

        $result = array();
        $result['data'] = array();
        $result['data']['success']  = '0';
        try {
            if( $documentGroup && $documentGroup->delete() ){
                $result['data']['success']  ='1';
            }
        } catch (\Exception $e) {
            $result['data']['errors']  = $e->getMessage();
        }
       // dd($result);
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);

    }
}
