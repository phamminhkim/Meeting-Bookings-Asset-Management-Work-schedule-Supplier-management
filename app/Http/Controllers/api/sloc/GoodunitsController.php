<?php

namespace App\Http\Controllers\api\sloc;

use App\Http\Controllers\Controller;
use App\Models\sloc\Goodunits;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Builder;

class GoodunitsController extends Controller
{
    public function index(Request $request)
    {
        $query = Goodunits::query();
        

        $result = array();
        $result['data'] = array();
       
        try{
           
            if($request->filled('name')){
                $query = $query->where('name', $request->name );
            }               
            $goodunits = $query->get();              
            $result['data'] = $goodunits;
            $result['success'] = "1";
        }catch (Exception $e) {
            $this->errors = $e->getMessage();
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

        $validator = Validator::make($request->all(), [
           
            'name' => 'required|max:50',

        ]);
        
        $failed = $validator->fails();
        $isErr = false;

        if($request->name){
            $dep_temp = Goodunits::where('name',$request->name)
                                    ->where('name',$request->name)->first();
             
            if($dep_temp  ){
                $result['data']['message']  = "Trùng mã, vui lòng nhập mã khác";
                $validator->errors()->add('name', 'Trùng mã, vui lòng nhập mã khác');
                $isErr = true;
            }
        }

        if ($failed || $isErr) {
            $result['data']['errors']  = $validator->errors();
        } else {
            try {

                $fields = $request->all();
                $re = Goodunits::create($fields);
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
        $goodunits = Goodunits::findOrFail($id);
        
        $result = array();
        $result['data'] =  array();
        $result['data'] = $goodunits;
        $result['success'] = "1";

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
            
            'name' => 'required|max:50',

        ]);

        $failed = $validator->fails();
        $isErr = false;
        if ($failed) {
            $result['data']['errors']  = $validator->errors();
        } else {
            $goodunits = Goodunits::findOrFail($id);
            $goodunits->name = $request->input('name');
          
            if($goodunits->save()){
                $result['data']['success']  = 1;
                $result['data']  = $goodunits;
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
       
        $goodunits = Goodunits::findOrFail($id);
        $result = array();
        $result['data'] = array();
        $result['data']['success']  = 0;
        if( $goodunits->delete() ){
            $result['data']['success']  = 1;
        }
        return json_encode($result, JSON_UNESCAPED_UNICODE); 
         
    }
    
}
