<?php

namespace App\Http\Controllers\api\category;

use App\Http\Controllers\BaseController\BaseController;
use App\Http\Controllers\Controller;
use App\Models\shared\Party;
use App\Models\shared\Company;
use App\Models\shared\Department;
use App\Models\shared\Workshop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;

class PartyController extends  BaseController
{
    public function index(Request $request)
    {
        $result = array();
        $result['data'] = array();

        try {
            $parties = Party::all();

            if ($request->filled('type')) {
                $type = $request->type;
                switch ($type) {
                    case 'tree_combobox':
                        $companys = $parties->pluck('company_id')->flatten();
                        $workshops = $parties->pluck('workshop_id')->flatten();
                        $companys->sort();
                        $workshops->sort();
                        $companys = array_unique($companys->toArray());
                        $workshops = array_unique($workshops->toArray());

                        $listCompany = array();
                        $companys = Company::whereIn('id', $companys)->get();
                        $workshops = Workshop::whereIn('id', $workshops)->get();
                        foreach ($companys as $key => $comp) {
                            $itemCompany = array();
                            $itemCompany['label'] = $comp->name;
                            $itemCompany['id'] = "c" . $comp->id;
                            $itemCompany['children'] = array();
                            foreach ($workshops as  $workshop) {

                                if ($workshop->company_id == $comp->id) {
                                    $itemWorkshop = array();
                                    $itemWorkshop['label'] = $workshop->name;
                                    $itemWorkshop['children'] = array();
                                    $itemWorkshop['id'] = "d" . $workshop->id;
                                    foreach ($parties as $pt) {

                                        if ($pt->company_id == $comp->id && $pt->workshop_id == $workshop->id) {
                                            $item['label'] = $pt->company_id . "-" .  $pt->name;
                                            $item['id'] =  $pt->id;
                                            array_push($itemWorkshop['children'], $item);
                                        }
                                    }
                                    array_push($itemCompany['children'], $itemWorkshop);
                                }
                            }
                            array_push($listCompany, $itemCompany);
                        }
                        $parties = $listCompany;
                        break;

                    case 'tree_fullscopes':
               
                        $companies = $parties->pluck('company_id')->flatten();
                        $departments = $parties->pluck('department_id')->flatten();
                        $workshops = $parties->pluck('workshop_id')->flatten();
                        $companies->sort();
                        $departments->sort();
                        $workshops->sort();
                        $companies = array_unique($companies->toArray());
                        $departments = array_unique($departments->toArray());
                        $workshops = array_unique($workshops->toArray());
                        $list_companies = array();
                        $companies = Company::whereIn('id', $companies)->get();
                        $departments = Department::whereIn('id', $departments)->get();
                        $workshops = Workshop::whereIn('id', $workshops)->get();
                        foreach ($companies as $company) {
                            $item_company = array();
                            $item_company['id'] = "c" . $company->id;
                            $item_company['label'] = $company->name;
                            $item_company['children'] = array();
                            foreach ($departments as $department) {
                                if ($department->company_id == $company->id) {
                                    $item_department = array();
                                    $item_department['id'] = "d" . $department->id;
                                    $item_department['label'] = $department->name;
                                    $item_department['children'] = array();
                                    foreach ($workshops as  $workshop) {
                                        if ($workshop->department_id == $department->id) {
                                            $item_workshop = array();
                                            $item_workshop['id'] = "w" . $workshop->id;
                                            $item_workshop['label'] = $workshop->name;
                                            $item_workshop['children'] = array();
                                            foreach ($parties as $party) {
                                                if ($party->workshop_id == $workshop->id) {
                                                    $item['id'] =  "p" . $party->id;
                                                    $item['label'] = $party->name;
                                                    array_push($item_workshop['children'], $item);
                                                }
                                            }
                                            array_push($item_department['children'], $item_workshop);
                                        }
                                    }
                                    array_push($item_company['children'], $item_department);
                                }
                            }
                            array_push($list_companies, $item_company);
                        }
                        $parties = $list_companies;
                        break;
                }
            }

            $result['data'] = $parties;
            $result['success'] = "1";
        } catch (Exception $e) {
            $this->errors = $e->getMessage();
        }
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        if (!auth()->user()->hasPermission('config_party')) {
            return response('Access denied', 403);
        }

        $result = array();
        $result['data'] =  array();
        $result['data']['success']  = 0;
        date_default_timezone_set("Asia/Ho_Chi_Minh");

        $validator = Validator::make($request->all(), [
            'company_id' => 'required|max:4',
            'department_id' => 'required',
            'workshop_id' => 'required',
            'code' => 'required|max:50',
            'name' => 'required|max:255',
        ]);

        $failed = $validator->fails();
        $isErr = false;
        if ($request->company_id && $request->department_id && $request->workshop_id) {
            $party = Party::where('company_id', $request->company_id)
                ->where('department_id', $request->department_id)
                ->where('workshop_id', $request->workshop_id)
                ->where('code', $request->code)->first();

            if ($party) {
                $result['data']['message']  = "Trùng mã, vui lòng nhập mã khác";
                $validator->errors()->add('code', 'Trùng mã, vui lòng nhập mã khác');
                $isErr = true;
            }
        }

        if ($failed || $isErr) {
            $result['data']['errors']  = $validator->errors();
        } else {

            try {
                $newParty = Party::create($request->all());
                if ($newParty) {
                    $result['data']  = $newParty;
                }
            } catch (\Exception $e) {
                $result['data']['errors']  =  $e->getMessage();
            }
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

    public function show($id)
    {
        $party = Party::findOrFail($id);

        $result = array();
        $result['data'] =  array();
        $result['data'] = $party;
        $result['success'] = "1";

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        if (!auth()->user()->hasPermission('config_party')) {
            return response('Access denied', 403);
        }

        $result = array();
        $result['data'] =  array();
        $result['data']['success']  = 0;
        date_default_timezone_set("Asia/Ho_Chi_Minh");

        $validator = Validator::make($request->all(), [
            'company_id' => 'required|max:4',
            'department_id' => 'required',
            'workshop_id' => 'required',
            'code' => 'required|max:50',
            'name' => 'required|max:255',
        ]);

        $failed = $validator->fails();
        $isErr = false;
        if ($request->company_id && $request->department_id && $request->workshop_id) {
            $party = Party::where('company_id', $request->company_id)
                ->where('department_id', $request->department_id)
                ->where('workshop_id', $request->workshop_id)
                ->where('code', $request->code)->first();

            if ($party && $party->id != $id) {
                $result['data']['message']  = "Trùng mã, vui lòng nhập mã khác";
                $validator->errors()->add('code', 'Trùng mã, vui lòng nhập mã khác');
                $isErr = true;
            }
        }

        if ($failed || $isErr) {
            $result['data']['errors']  = $validator->errors();
        } else {
            try {
                $party = Party::findOrFail($id);
                if ($party) {
                    $party->id =  $party->id;
                    $party->company_id = $request->input('company_id');
                    $party->department_id = $request->input('department_id');
                    $party->workshop_id = $request->input('workshop_id');
                    $party->code = $request->input('code');
                    $party->name = $request->input('name');

                    if ($party->save())
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
        if (!auth()->user()->hasPermission('config_party')) {
            return response('Access denied', 403);
        }
        // Get article
        $party = Party::findOrFail($id);
        $result = array();
        $result['data'] = array();
        $result['data']['success']  = 0;
        if ($party->delete()) {
            $result['data']['success']  = 1;
        }
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);

    }
}
