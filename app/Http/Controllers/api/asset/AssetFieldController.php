<?php

namespace App\Http\Controllers\api\asset;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\asset\AssetField;
use App\Models\asset\AssetGroup;
use App\Models\asset\AssetTypeDetail;
use Illuminate\Support\Facades\Validator;
use App\User;
use Exception;

class AssetFieldController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = AssetField::query();
        $result = array();
        $result['data'] = array();   
        // $role= User::find(auth()->user()->id);   
            try {
            //     $users = User::whereNotIn('id',['1','2','3','4','5'])->where('id',$role->id)->where('active',1)->orderBy('name')->get();
            // if($users->toArray() !=[]){
            //     $query = $query->where('create_by',$role->id);
            //    }else{
               
            //    }
                if($request->filled('name')){
                    $query = $query->where('name','LIKE', "%$request->name%" );
                }              
                $Field = $query->get();              
                $result['data'] = $Field;
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
          
            'name.max' => 'Tối đa 250 kí tự',
            'description.max' => 'Tối đa 250 kí tự',
        ];
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:250',
            'description' => 'max:250',
            
        ],$meesage);
        
        $failed = $validator->fails();
        $isErr=false;
        $fields = $request->all();
        if ($request->name) {

            $field_temp = AssetField::where('name', $request->name)->first();

            if ($field_temp) {
                $result['data']['message']  = "Trùng tên, vui lòng nhập tên khác";
                $validator->errors()->add('name', 'Trùng tên, vui lòng nhập tên khác');
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
                
                $user= new User();
                $user = auth()->user();
                $fields['create_by'] = $user->id;
                $re = AssetField::create($fields);

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
          
            'name.max' => 'Tối đa 250 kí tự',
            'description.max' => 'Tối đa 250 kí tự',
        ];
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:250',
            'description' => 'max:250',
            
        ],$meesage);
        
        $failed = $validator->fails();
        $isErr = false;
        if ($request->name) {

            $field_temp = AssetField::where('name', $request->name)->first();

            if ($field_temp && $field_temp->id != $id) {
                $result['data']['message']  = "Trùng tên, vui lòng nhập tên khác";
                $validator->errors()->add('name', 'Trùng tên, vui lòng nhập tên khác');
                $isErr = true;
            }
        }
        if ($failed || $isErr) {
            $result['data']['errors']  = $validator->errors();
        } else {
            try {
                $fields_asset = AssetField::findOrFail($id);
                $fields_asset->name = $request->input('name');
                $fields_asset->description = $request->input('description');
                if($fields_asset->save()){
                    $result['data']['success']  = 1;
                    $result['data']['data']  = $fields_asset;
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
        $fields_asset = AssetField::findOrFail($id);
        $result = array();
        $result['data'] = array();
        $result['data']['success']  = 0;
        $check_delete=AssetTypeDetail::where('name',$fields_asset->name)->get();
        // dd($check_delete->toArray());
        if($check_delete->toArray()!=[] ){
            $result['data']['errors'] = 'Tồn tại Model thuộc cấu hình này , vui lòng không xóa';
        }else{
            if( $fields_asset->delete() ){
                $result['data']['success']  = 1;
            }
        }
       
        return json_encode($result, JSON_UNESCAPED_UNICODE); 
    }
}
