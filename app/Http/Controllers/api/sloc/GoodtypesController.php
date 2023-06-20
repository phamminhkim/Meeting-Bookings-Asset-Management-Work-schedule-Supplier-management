<?php

namespace App\Http\Controllers\api\sloc;

use App\Http\Controllers\Controller;
use App\Models\sloc\Goodtypes;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Validator;
use App\User;
use Error;
use Illuminate\Database\Eloquent\Builder;
use App\Repositories\approve\ApproveMain;
use App\Ultils\Ultils;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Calculation\TextData\Replace;
class GoodtypesController extends Controller
{
    public function index(Request $request)
    {
        $query = Goodtypes::query();
        $result = array();
        $result['data'] = array();          
            try {

                if($request->filled('code')){
                    $query = $query->where('code', $request->code );
                }
                if($request->filled('name')){
                    $query = $query->where('name', $request->name );
                }               
                $goodtypes = $query->get();              
                $result['data'] = $goodtypes;
                $result['success'] = "1";
            } catch (Exception $e) {
                $this->errors = $e->getMessage();
            }
            
       
        // return DepartmentResource::collection($department);



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

        $validator = Validator::make($request->all(), [
            'code' => 'required|max:30',
            'name' => 'required|max:150',

        ]);
        
        $failed = $validator->fails();
        $isErr=false;
        if($request->code){
            $dep_temp = Goodtypes::where('name',$request->name)
                                    ->where('code',$request->code)->first();
             
            if($dep_temp){
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
                $re = Goodtypes::create($fields);
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
        $goodtypes = Goodtypes::findOrFail($id);
        
        $result = array();
        $result['data'] =  array();
        $result['data'] = $goodtypes;
        $result['data']['success'] = 1;
        if (!$goodtypes) {
            $result['data']['success'] = 0;
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); 
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
        $validator = Validator::make($request->all(), [
            'code' => 'required|max:30',
            'name' => 'required|max:150',
            
        ]);

        $failed = $validator->fails();
        $isErr = false;
        if ($failed) {
            $result['data']['errors']  = $validator->errors();
        } else {
            $goodtypes = Goodtypes::findOrFail($id);
            $goodtypes->name = $request->input('name');
            $goodtypes->code = $request->input('code');
            $goodtypes->description = $request->input('description');
            if($goodtypes->save()){
                $result['data']['success']  = 1;
                $result['data']  = $goodtypes;
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
       
        $goodtypes = Goodtypes::findOrFail($id);
        $result = array();
        $result['data'] = array();
        $result['data']['success']  = 0;
        if( $goodtypes->delete() ){
            $result['data']['success']  = 1;
        }
        return json_encode($result, JSON_UNESCAPED_UNICODE); 
         
    }
    
}
