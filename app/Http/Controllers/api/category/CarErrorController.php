<?php

namespace App\Http\Controllers\api\category;

use App\Http\Controllers\Controller;
use App\Models\shared\CarError;
use App\Models\shared\Company;
use App\Models\shared\DocumentType;
use App\Models\shared\Wfmain;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Validator;
class CarErrorController extends Controller
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
        $query = CarError::query();

        try {
            if($request->filled('active')){
                $query = $query->where('active', $request->active );
            }

            $carerror = $query->get();
            $result['data'] = $carerror;
            $result['success'] = "1";
        } catch (Exception $e) {
            $this->errors = $e->getMessage();
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
       
    
    }
    public function get_car_error_qa(){
        $car_error = CarError::Select('id','name')->where('description','QA')->where('active',1)->orderby('name')->get();
        try {
            $result['data'] = $car_error;
            $result['success'] = "1";
        } catch (Exception $e) {
            $this->errors = $e->getMessage();
        }
    return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    public function get_car_error_qc(){
        $car_error = CarError::Select('id','name')->where('description','QC')->where('active',1)->orderby('id','DESC')->get();
        try {
            $result['data'] = $car_error;
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
                $re = CarError::create($fields);
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
        $carerror = CarError::findOrFail($id);

        $result = array();
        $result['data'] =  array();
        $result['data'] = $carerror;
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
            $carerror = CarError::findOrFail($id);
            $carerror->name = $request->input('name');
            $carerror->active = $request->input('active');
            $carerror->description = $request->input('description');
            if($carerror->save()){
                $result['data']['success']  = 1;
                $result['data']  = $carerror;
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
       $carerror = CarError::findOrFail($id);
       $result = array();
       $result['data'] = array();
       $result['data']['success']  = 0;
       if( $carerror->delete() ){
           $result['data']['success']  = 1;
       }
       return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
}
