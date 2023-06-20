<?php

namespace App\Http\Controllers\api\category;

use App\Http\Controllers\Controller;
use App\Models\car\Standard;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Validator;
class StandardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $result = array();
        $result['data'] = array();
        $query = Standard::query();

        try {
            if($request->filled('active')){
                $query = $query->where('active', $request->active );
            }

            $standard = $query->get();
            $result['data'] = $standard;
            $result['success'] = "1";
        } catch (Exception $e) {
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
            'name' => 'required',
            'active' => 'required',
        ]);
        $failed = $validator->fails();
        if ($failed) {
            $result['data']['errors']  = $validator->errors();
        } else {
            try {

                $fields = $request->all();
                $re = Standard::create($fields);
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
        $standard = Standard::findOrFail($id);

        $result = array();
        $result['data'] =  array();
        $result['data'] = $standard;
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
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
            'name' => 'required',
            'active' => 'required',
        ]);

        $failed = $validator->fails();
        $isErr = false;
        if ($failed) {
            $result['data']['errors']  = $validator->errors();
        } else {
            $standard = Standard::findOrFail($id);
            $standard->name = $request->input('name');
            $standard->active = $request->input('active');
            $standard->description = $request->input('description');
            if($standard->save()){
                $result['data']['success']  = 1;
                $result['data']  = $standard;
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
       // Get article
       $standard = Standard::findOrFail($id);
       $result = array();
       $result['data'] = array();
       $result['data']['success']  = 0;
       if( $standard->delete() ){
           $result['data']['success']  = 1;
       }
       return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
}
