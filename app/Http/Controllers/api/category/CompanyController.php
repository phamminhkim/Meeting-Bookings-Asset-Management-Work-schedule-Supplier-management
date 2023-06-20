<?php

namespace App\Http\Controllers\api\category;

use App\Http\Controllers\Controller;
use App\Models\shared\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $result = array();
        $result['data'] = array();

        if ($request->filled('type')) {
            $type = $request->filled('type');
            switch ($type) {
                case 'tree_combobox':
                    $listCompany = array();
                    $companies = Company::all();

                    foreach ($companies as $company) {
                        $item['label'] = "[". $company->id . "] " . $company->name;
                        $item['id'] =  $company->id;
                        array_push($listCompany, $item);
                    }
                    $result['data'] = $listCompany;
                    $result['success'] = "1";
                    break;
            }
        } else {
            $company = Company::all();
            $result['data'] = $company;
            $result['success'] = "1";
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        if (!auth()->user()->hasPermission('config_company')) {
            return response('Access denied', 403);
        }

        $result = array();
        $result['data'] =  array();
        $result['data']['success']  = 0;
        date_default_timezone_set("Asia/Ho_Chi_Minh");

        $validator = Validator::make($request->all(), [
            'id' => 'required|max:4',
            'name' => 'required|max:255',
        ]);

        $failed = $validator->fails();
        $isErr = false;

        $company = Company::where('id', $request->id)->first();
        if ($company) {
            $result['data']['message']  = "Trùng mã, vui lòng nhập mã khác";
            $validator->errors()->add('id', 'Trùng mã, vui lòng nhập mã khác');
            $isErr = true;
        }

        if ($failed || $isErr) {
            $result['data']['errors']  = $validator->errors();
        } else {

            try {
                $newCompany = Company::create($request->all());
                dd($newCompany);
                if ($newCompany) {
                    $result['data']  = $newCompany;
                }
            } catch (\Exception $e) {
                $result['data']['errors']  =  $e->getMessage();
            }
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

    public function show($id)
    {
        $company = Company::findOrFail($id);

        $result = array();
        $result['data'] =  array();
        $result['data'] = $company;
        $result['success'] = "1";

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        if (!auth()->user()->hasPermission('config_company')) {
            return response('Access denied', 403);
        }

        $result = array();
        $result['data'] =  array();
        $result['data']['success']  = 0;
        date_default_timezone_set("Asia/Ho_Chi_Minh");

        $validator = Validator::make($request->all(), [
            'id' => 'required|max:4',
            'name' => 'required|max:255',
        ]);

        $failed = $validator->fails();
        $isErr = false;

        $company = Company::where('id', $request->id)->first();
        if ($company && $company->id != $id) {
            $result['data']['message']  = "Trùng mã, vui lòng nhập mã khác";
            $validator->errors()->add('id', 'Trùng mã, vui lòng nhập mã khác');
            $isErr = true;
        }

        if ($failed || $isErr) {
            $result['data']['errors']  = $validator->errors();
        } else {


            try {

                $company = Company::findOrFail($id);
                if ($company) {
                    $company->id =  $company->id;
                    $company->name = $request->input('name');

                    if ($company->save())
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
        if (!auth()->user()->hasPermission('config_company')) {
            return response('Access denied', 403);
        }
        // Get article
        $company = Company::findOrFail($id);
        $result = array();
        $result['data'] = array();
        $result['data']['success']  = 0;
        if ($company->delete()) {
            $result['data']['success']  = 1;
        }
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
}
