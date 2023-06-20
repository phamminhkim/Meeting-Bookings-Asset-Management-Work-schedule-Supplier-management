<?php

namespace App\Http\Controllers\api\category;

use App\Http\Controllers\Controller;
use App\Models\document\DocbrowserType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DocbrowserTypeController extends Controller
{
    public function index(Request $request)
    {
        if($request->filled('module')){
            $DocbrowserType = DocbrowserType::where('module',$request->module)->orderBy('name')->get();
        }else{
            $DocbrowserType = DocbrowserType::orderBy('name')->get();
        }


        if ($request->filled('type')) {
            $listDocument = array();
            foreach ($DocbrowserType as $key => $document) {
                $item['label'] = $document->name;
                $item['id'] =  $document->id;
                array_push($listDocument, $item);
            }

            $DocbrowserType = $listDocument;
        }

        $result = array();
        $result['data'] = array();
        $result['data'] = $DocbrowserType;
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

            'name' => 'required|max:150',
            'description' => 'required|max:255',
        ]);

        $failed = $validator->fails();
            //dd($failed);
        if ($failed) {
            $result['data']['errors']  = $validator->errors();
        } else {

            try {
                $re = DocbrowserType::create($request->all());
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
        $DocbrowserType = DocbrowserType::find($id);

        $result = array();
        $result['data'] =  array();
        $result['data'] = $DocbrowserType;


        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    public function update(Request $request, $id)
    {


        $result = array();
        $result['data'] =  array();
        $result['data']['success']  = 0;
        date_default_timezone_set("Asia/Ho_Chi_Minh");

        $validator = Validator::make($request->all(), [
            'description' => 'required|max:150',
            'name' => 'required|max:255',

        ]);

        $failed = $validator->fails();

        if ($failed) {
            $result['data']['errors']  = $validator->errors();
        } else {


            try {

                $DocbrowserType = DocbrowserType::findOrFail($id);
                if ($DocbrowserType) {

                    $DocbrowserType->fill($request->all());
                    // $DocbrowserType->description = $request->input('description');
                    // $DocbrowserType->name = $request->input('name');

                    if($DocbrowserType->save())
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
        $DocbrowserType = DocbrowserType::find($id);

        $result = array();
        $result['data'] = array();
        $result['data']['success']  = '0';
        try {
            if( $DocbrowserType && $DocbrowserType->delete() ){
                $result['data']['success']  ='1';
            }
        } catch (\Exception $e) {
            $result['data']['errors']  = $e->getMessage();
        }
       // dd($result);
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);

    }
}
