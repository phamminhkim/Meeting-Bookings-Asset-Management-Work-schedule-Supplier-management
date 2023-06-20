<?php

namespace App\Http\Controllers\api\asset;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\asset\AssetCat;
use Illuminate\Support\Facades\Validator;
use App\User;
use Exception;
class AssetCatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = AssetCat::query();
        $result = array();
        $result['data'] = array();          
            try {

                if($request->filled('name')){
                    $query = $query->where('name','LIKE', "%$request->name%" );
                }
                if($request->filled('code')){
                    $query = $query->where('code', $request->code );
                }                 
                $assetgroup = $query->get();              
                $result['data'] = $assetgroup;
                $result['success'] = "1";
            } catch (Exception $e) {
                $result['data']['errors'] = $e->getMessage();
            }
          
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $result = array();
        $result['data'] =  array();
        $result['data']['success']  = 0;
        date_default_timezone_set("Asia/Ho_Chi_Minh");
        $meesage = [
            'name.required' => 'Tên không được để trống',
            'code.required' => 'Mã không được để trống',
            'name.max' => 'Tối đa 100 kí tự',
            'code.max' => 'Tối đa 100 kí tự',
        ];
        $validator = Validator::make($request->all(), [
            'code' => 'required|max:100',
            'name' => 'required|max:100',
        ],$meesage);
        
        $failed = $validator->fails();
        $isErr=false;
        
        if ($request->code) {

            $dep_temp = AssetCat::where('code', $request->code)->first();

            if ($dep_temp) {
                $result['data']['message']  = "Trùng mã, vui lòng nhập mã khác";
                $validator->errors()->add('code', 'Trùng mã, vui lòng nhập mã khác');
                $isErr = true;
            }
        }
        if ($failed || $isErr) {
            $result['data']['errors']  = $validator->errors();
        } else {
            try {
              
                $fields = $request->all();
                $re = AssetCat::create($fields);
                if ($re) {
                    $result['data']= $re;
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        date_default_timezone_set("Asia/Ho_Chi_Minh");
        $meesage = [
            'name.required' => 'Tên không được để trống',
            'code.required' => 'Mã không được để trống',
            'name.max' => 'Tối đa 100 kí tự',
            'code.max' => 'Tối đa 100 kí tự',
        ];
        $validator = Validator::make($request->all(), [
            'code' => 'required|max:100',
            'name' => 'required|max:100',
        ],$meesage);
        $failed = $validator->fails();
        $isErr = false;
        if ($failed) {
            $result['data']['errors']  = $validator->errors();
        } else {
            try {
                $groups = AssetCat::findOrFail($id);
                $groups->name = $request->input('name');
                $groups->code = $request->input('code');
                if($groups->save()){
                    $result['data']['success']  = 1;
                    $result['data']  = $groups;
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
        $groups = AssetCat::findOrFail($id);
        $result = array();
        $result['data'] = array();
        $result['data']['success']  = 0;
        if( $groups->delete() ){
            $result['data']['success']  = 1;
        }
        return json_encode($result, JSON_UNESCAPED_UNICODE); 
    }
}
