<?php

namespace App\Http\Controllers\api\category;

use App\Http\Controllers\Controller;
use App\Models\car\TypeCar;
use App\Models\shared\Company;
use App\Models\shared\DocumentType;
use App\Models\shared\Wfmain;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Validator;
class TypeCarController extends Controller
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
        $query = TypeCar::query();

        try {
            if($request->filled('active')){
                $query = $query->where('active', $request->active );
            }

            $typecar = $query->get();
            $result['data'] = $typecar;
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
                $re = TypeCar::create($fields);
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
        $typecar = TypeCar::findOrFail($id);

        $result = array();
        $result['data'] =  array();
        $result['data'] = $typecar;
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
            $typecar = TypeCar::findOrFail($id);
            $typecar->name = $request->input('name');
            $typecar->active = $request->input('active');
            $typecar->description = $request->input('description');
            if($typecar->save()){
                $result['data']['success']  = 1;
                $result['data']  = $typecar;
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
       $typecar = TypeCar::findOrFail($id);
       $result = array();
       $result['data'] = array();
       $result['data']['success']  = 0;
       if( $typecar->delete() ){
           $result['data']['success']  = 1;
       }
       return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
}
