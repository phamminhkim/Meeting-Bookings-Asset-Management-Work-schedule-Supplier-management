<?php

namespace App\Http\Controllers\api\category;

use App\Http\Controllers\BaseController\BaseController;
use App\Http\Controllers\Controller;
use App\Models\shared\Workshop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WorkshopController extends  BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $workshop = Workshop::all();

        $result = array();
        $result['data'] = array();
        $result['data'] = $workshop;
        $result['success'] = "1";
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
        if (!auth()->user()->hasPermission('config_workshop')) {
            return response('Access denied', 403);
        }

        $result = array();
        $result['data'] =  array();
        $result['data']['success']  = 0;
        date_default_timezone_set("Asia/Ho_Chi_Minh");

        $validator = Validator::make($request->all(), [
            'company_id' => 'required|max:4',
            'department_id' => 'required',
            'code' => 'required|max:50',
            'name' => 'required|max:255',
        ]);

        $failed = $validator->fails();
        $isErr = false;
        if ($request->company_id && $request->department_id) {

            $dep_temp = Workshop::where('company_id', $request->company_id)
                ->where('department_id', $request->department_id)
                ->where('code', $request->code)->first();

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
                $newWorkshop = Workshop::create($request->all());
                if ($newWorkshop) {
                    $result['data']  = $newWorkshop;
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
        $workshop = Workshop::findOrFail($id);

        $result = array();
        $result['data'] =  array();
        $result['data'] = $workshop;
        $result['success'] = "1";

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
        if (!auth()->user()->hasPermission('config_workshop')) {
            return response('Access denied', 403);
        }

        $result = array();
        $result['data'] =  array();
        $result['data']['success']  = 0;
        date_default_timezone_set("Asia/Ho_Chi_Minh");

        $validator = Validator::make($request->all(), [
            'company_id' => 'required|max:4',
            'department_id' => 'required',
            'code' => 'required|max:50',
            'name' => 'required|max:255',

        ]);

        $failed = $validator->fails();
        $isErr = false;
        if ($request->company_id && $request->department_id) {
            $workshop = Workshop::where('company_id', $request->company_id)
                ->where('department_id', $request->department_id)
                ->where('code', $request->code)->first();

            if ($workshop && $workshop->id != $id) {
                $result['data']['message']  = "Trùng mã, vui lòng nhập mã khác";
                $validator->errors()->add('code', 'Trùng mã, vui lòng nhập mã khác');
                $isErr = true;
            }
        }
        if ($failed || $isErr) {
            $result['data']['errors']  = $validator->errors();
        } else {


            try {

                $workshop = Workshop::findOrFail($id);
                if ($workshop) {
                    $workshop->id =  $workshop->id;
                    $workshop->company_id = $request->input('company_id');
                    $workshop->department_id = $request->input('department_id');
                    $workshop->code = $request->input('code');
                    $workshop->name = $request->input('name');

                    if ($workshop->save())
                        $result['data']['success']  = 1;
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
        if (!auth()->user()->hasPermission('config_workshop')) {
            return response('Access denied', 403);
        }
        // Get article
        $workshop = Workshop::findOrFail($id);
        $result = array();
        $result['data'] = array();
        $result['data']['success']  = 0;
        if ($workshop->delete()) {
            $result['data']['success']  = 1;
        }
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);

    }
}
