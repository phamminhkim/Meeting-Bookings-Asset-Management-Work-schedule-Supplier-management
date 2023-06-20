<?php

namespace App\Http\Controllers\api\asset;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\asset\AssetGroup;
use Illuminate\Support\Facades\Validator;
use App\User;
use Exception;

class AssetGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = AssetGroup::query();
        $result = array();
        $result['data'] = array();   
        // $role = User::find(auth()->user()->id);       
            try {
                // $users = User::whereNotIn('id',['1','2','3','4','5'])->where('id',$role->id)->where('active',1)->orderBy('name')->get();
                // if($users->toArray() !=[]){
                //     $query = $query->where('create_by',$role->id);
                //    }else{
                   
                //    }
                if($request->filled('name')){
                    $query = $query->where('name','LIKE', "%$request->name%" );
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
          
            'name.max' => 'Tối đa 50 kí tự',
            'description.max' => 'Tối đa 255 kí tự',
        ];
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:50',
            'description' => 'max:255',
            
        ],$meesage);
        
        $failed = $validator->fails();
       
       
        $isErr = false;
        $fields = $request->all();
        if ($request->name) {

            $dep_temp = AssetGroup::where('name', $request->name)->first();

            if ($dep_temp) {
                $result['data']['message']  = "Trùng tên nhóm, vui lòng nhập lại";
                $validator->errors()->add('name', 'Trùng tên nhóm, vui lòng nhập lại');
                $isErr = true;
            }
        }
        if ($failed || $isErr) {
            $result['data']['errors']  = $validator->errors();
        } else {
            try {
                // $user_id = new User();
                // $user_id = auth()->user();
                // $fields['user_id'] = $user_id->id;
                $fields = $request->all();
                $user= new User();
                $user = auth()->user();
                $fields['create_by'] = $user->id;
                $re = AssetGroup::create($fields);
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
        $meesage = [
            'name.required' => 'Tên không được để trống',
          
            'name.max' => 'Tối đa 50 kí tự',
            'description.max' => 'Tối đa 255 kí tự',
        ];
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:50',
            'description' => 'max:255',
            
        ],$meesage);
        
        $failed = $validator->fails();
        $isErr = false;
        if($request->name){
            $dep_temp = AssetGroup::where('name', $request->name)->first();
             
            if($dep_temp && $dep_temp->id != $id ){
                $result['data']['message']  = "Trùng tên nhóm, vui lòng nhập lại";
                $validator->errors()->add('name', 'Trùng tên nhóm, vui lòng nhập lại');

                $isErr = true;
            }
        }
        if ($failed || $isErr) {
            $result['data']['errors']  = $validator->errors();
        } else {
            try {
                $groups = AssetGroup::findOrFail($id);
                $groups->name = $request->input('name');
                $groups->description = $request->input('description');
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
        $groups = AssetGroup::findOrFail($id);
        $result = array();
        $result['data'] = array();
        $result['data']['success']  = 0;
        if( $groups->delete() ){
            $result['data']['success']  = 1;
        }
        return json_encode($result, JSON_UNESCAPED_UNICODE); 
    }
}
