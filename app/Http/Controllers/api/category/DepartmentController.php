<?php

namespace App\Http\Controllers\api\category;

use App\Http\Controllers\BaseController\BaseController;
use App\Http\Controllers\Controller;
use App\Models\shared\Company;
use App\Models\shared\Department;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DepartmentController extends  BaseController
{
    public function index(Request $request)
    {
        // return DepartmentResource::collection($department);
        $result = array();
        $result['data'] = array();

        $departments = Department::all();

        if ($request->filled('type')) {
            $type = $request->filled('type');
            switch ($type) {
                case 'tree_combobox':
                    $companys = $departments->pluck('company_id')->flatten();
                    $companys->sort();
                    $companys = array_unique($companys->toArray());

                    $listCompany = array();
                    $companys = Company::whereIn('id', $companys)->get();
                    foreach ($companys as $key => $comp) {
                        $itemCompany = array();
                        $itemCompany['label'] = $comp->name;
                        $itemCompany['id'] = "c" . $comp->id;
                        $itemCompany['children'] = array();
                        foreach ($departments as $department) {
                            if ($department->company_id == $comp->id) {
                                $item['id'] =  'd' . $department->id;
                                $item['label'] = $department->name;
                                array_push($itemCompany['children'], $item);
                            }
                        }
                        array_push($listCompany, $itemCompany);
                    }
                    $departments = $listCompany;
                    break;
            }
        }

        $result['data'] = $departments;

        $result['success'] = "1";
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        if (!auth()->user()->hasPermission('config_department')) {
            return response('Access denied', 403);
        }

        $result = array();
        $result['data'] =  array();
        $result['data']['success']  = 0;
        date_default_timezone_set("Asia/Ho_Chi_Minh");

        $validator = Validator::make($request->all(), [
            'company_id' => 'required|max:4',
            'code' => 'required|max:50',
            'name' => 'required|max:255',

        ]);

        $failed = $validator->fails();
        $isErr = false;
        if ($request->company_id) {

            $dep_temp = Department::where('company_id', $request->company_id)
                ->where('code', $request->code)->first();

            if ($dep_temp) {
                $result['data']['message']  = "Trùng mã, vui lòng nhập mã khác";
                $validator->errors()->add('code', 'Trùng mã, vui lòng nhập mã khác');
                $isErr = true;
            }
        }

        //dd($failed);
        if ($failed || $isErr) {
            $result['data']['errors']  = $validator->errors();
        } else {

            try {
                $re = Department::create($request->all());
                if ($re) {

                    $result['data']  = $re;
                    // $result['data']['success']  = 1;
                }
            } catch (\Exception $e) {
                $result['data']['errors']  =  $e->getMessage();
            }
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

    public function show($id)
    {
        $department = Department::findOrFail($id);

        $result = array();
        $result['data'] =  array();
        $result['data'] = $department;
        $result['success'] = "1";

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        if (!auth()->user()->hasPermission('config_department')) {
            return response('Access denied', 403);
        }

        $result = array();
        $result['data'] =  array();
        $result['data']['success']  = 0;
        date_default_timezone_set("Asia/Ho_Chi_Minh");

        $validator = Validator::make($request->all(), [
            'company_id' => 'required|max:4',
            'code' => 'required|max:50',
            'name' => 'required|max:255',

        ]);

        $failed = $validator->fails();
        $isErr = false;
        if ($request->company_id) {

            $dep_temp = Department::where('company_id', $request->company_id)
                ->where('code', $request->code)->first();

            if ($dep_temp && $dep_temp->id != $id) {
                $result['data']['message']  = "Trùng mã, vui lòng nhập mã khác";
                $validator->errors()->add('code', 'Trùng mã, vui lòng nhập mã khác');
                $isErr = true;
            }
        }
        if ($failed || $isErr) {
            $result['data']['errors']  = $validator->errors();
        } else {


            try {

                $department = Department::findOrFail($id);
                if ($department) {
                    $department->id =  $department->id;
                    $department->company_id = $request->input('company_id');
                    $department->code = $request->input('code');
                    $department->name = $request->input('name');

                    if ($department->save())
                        $result['data']['success']  = 1;
                }
            } catch (\Exception $e) {
                $result['data']['errors']  =  $e->getMessage();
            }
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

    public function destroy($id)
    {
        if (!auth()->user()->hasPermission('config_department')) {
            return response('Access denied', 403);
        }
        // Get article
        $department = Department::findOrFail($id);
        $result = array();
        $result['data'] = array();
        $result['data']['success']  = 0;
        if ($department->delete()) {
            $result['data']['success']  = 1;
        }
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
}
