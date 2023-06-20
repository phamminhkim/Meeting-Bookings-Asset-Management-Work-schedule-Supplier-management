<?php

namespace App\Http\Controllers\api\asset;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\asset\AssetStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\User;
use Exception;

class AssetStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = AssetStatus::query();
        $result = array();
        $result['data'] = array();
        $role = User::find(auth()->user()->id);
             
            try {

                if($request->filled('id')){
                    $query = $query->where('id', $request->id );
                }
                if($request->filled('name')){
                    $query = $query->where('name', $request->name );
                }                  
                $status = $query->get();              
                $result['data'] = $status;
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
        $status = AssetStatus::all();

        $result = array();
        $result['data'] =  array();
        $result['data']['success']  = 0;
        date_default_timezone_set("Asia/Ho_Chi_Minh");  
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
            'name.required' => 'Tên không được để trống'
        ];
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ],$meesage);
        $fields = $request->all();
        $failed = $validator->fails();
        $isErr=false;
        // $status = AssetStatus::where('name', $request->id)->first();
        // if ($status) {
        //     $result['data']['message']  = "Trùng tên, vui lòng nhập tên khác";
        //     $validator->errors()->add('name', 'Trùng tên, vui lòng nhập tên khác');
        //     $isErr = true;
        // }
        if ($failed || $isErr) {
            $result['data']['errors']  = $validator->errors();
        } else {
            try {
            $name = $fields['name'];
            $fields['id'] =  $name ;
            $re = AssetStatus::create($fields);
            if ($re) {
                $result['data']  = $re;
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
        $meesage = [
            'name.required' => 'Tên không được để trống'
        ];
        $validator = Validator::make($request->all(), [
            'name' => 'required',
        ],$meesage);
        $fields = $request->all();
        $failed = $validator->fails();
        $isErr=false;
        if ($failed || $isErr) {
            $result['data']['errors']  = $validator->errors();
        } else {
            try {
                $status = AssetStatus::findOrFail($id);
                $status->name = $request->input('name');
            if($status->save()){
                $result['data']['success']  = 1;
                $result['data']  = $status;
            } 
           
            }
            catch (\Exception $e) {
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
        $status = AssetStatus::findOrFail($id);
        $result = array();
        $result['data'] = array();
        $result['data']['success']  = 0;
        if($status->delete() ){
            $result['data']['success']  = 1;
        }
        return json_encode($result, JSON_UNESCAPED_UNICODE); 
    }
}
