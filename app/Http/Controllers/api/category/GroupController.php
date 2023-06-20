<?php

namespace App\Http\Controllers\api\category;

use App\Http\Controllers\Controller;
use App\Models\shared\Company;
use App\Models\shared\Department;
use App\Models\shared\Group;
use App\Models\shared\Team;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GroupController extends Controller
{
    public function index(Request $request)
    {
        $result = array();
        $result['data'] = array();
        // $group = Group::all();
        $query = Group::query();

        try {
            if ($request->filled('self')) {
                $query = $query->whereHas('users', function ($q) {
                    $q->where('id', Auth()->user()->id);
                });
            }
            if ($request->filled('manv') || $request->filled('tennv')) {

                $user_query = User::query();

                if ($request->filled('manv')) {
                    $user_query = $user_query->where('username', 'like', '%' . $request->manv . '%');
                }
                if ($request->filled('tennv')) {
                    $user_query = $user_query->where('name', 'like', '%' . $request->tennv . '%');
                }
                $userlist = $user_query->get()->pluck('id')->flatten();

                $query = $query->whereHas('users', function ($q) use ($userlist) {
                    $q->whereIn('id', $userlist);
                });
            }

            if ($request->filled('active')) {
                $query = $query->where('active', $request->active);
            }

            if ($request->filled('company_id')) {
                $query = $query->where('company_id', $request->company_id);
            }
            if ($request->filled('department_id')) {
                $query = $query->where('department_id', $request->department_id);
            }
            $group = $query->withCount('users')->get();

            //type: sẽ điều khiển loại danh sách  sẽ trả về
            if ($request->filled('type')) {
                $type = $request->filled('type');
                switch ($type) {
                    case 'tree_combobox':
                        $companys = $group->pluck('company_id')->flatten();
                        $departments = $group->pluck('department_id')->flatten();
                        $companys->sort();
                        $departments->sort();
                        $companys = array_unique($companys->toArray());
                        $departments = array_unique($departments->toArray());

                        $ListCompany = array();
                        $companys = Company::whereIn('id', $companys)->get();
                        $departments = Department::whereIn('id', $departments)->get();
                        foreach ($companys as $key => $comp) {
                            $ItemCompany = array();
                            $ItemCompany['label'] = $comp->name;
                            $ItemCompany['id'] = "c" . $comp->id;
                            $ItemCompany['children'] = array();
                            foreach ($departments as   $dept) {

                                if ($dept->company_id == $comp->id) {
                                    $ItemDepartment = array();
                                    $ItemDepartment['label'] = $dept->name;
                                    $ItemDepartment['children'] = array();
                                    $ItemDepartment['id'] = "d" . $dept->id;
                                    foreach ($group as  $grp) {

                                        if ($grp->company_id == $comp->id && $grp->department_id == $dept->id) {

                                            $item['label'] = $grp->company_id . "-" .  $grp->name;
                                            $item['id'] =  $grp->id;
                                            array_push($ItemDepartment['children'], $item);
                                        }
                                    }
                                    array_push($ItemCompany['children'], $ItemDepartment);
                                }
                            }
                            array_push($ListCompany, $ItemCompany);
                        }
                        $group = $ListCompany;
                        break;
                    case 'tree_withusers': {

                            $companys = $groups->pluck('company_id')->flatten();
                            $departments = $groups->pluck('department_id')->flatten();
                            $companys->sort();
                            $departments->sort();
                            $companys = array_unique($companys->toArray());
                            $departments = array_unique($departments->toArray());

                            $companys = Company::whereIn('id', $companys)->get();
                            $departments = Department::whereIn('id', $departments)->get();
                            $users = User::all();

                            $listCompanies = array();
                            foreach ($companys as $company) {
                                $itemCompany = array();
                                $itemCompany['id'] = "c" . $company->id;
                                $itemCompany['label'] = $company->name;
                                $itemCompany['children'] = array();

                                foreach ($departments as $department) {
                                    if ($department->company_id == $company->id) {
                                        $itemDepartment = array();
                                        $itemDepartment['id'] = "d" . $department->id;
                                        $itemDepartment['label'] = $department->name;
                                        $itemDepartment['children'] = array();

                                        foreach ($groups as $group) {
                                            if ($group->company_id == $company->id && $group->department_id == $department->id) {
                                                $item['id'] = "g" .  $group->id;
                                                $item['label'] = $group->company_id . "-" .  $group->name;

                                                array_push($itemDepartment['children'], $item);
                                            }
                                        }
                                        array_push($itemCompany['children'], $itemDepartment);
                                    }
                                }
                                array_push($listCompanies, $itemCompany);
                            }

                            $itemUsers = array();
                            foreach ($users as $user) {
                                $itemUser = array();
                                $itemUser['id'] = "u" . $user->id;
                                $itemUser['label'] = $user->name . ' (' . $user->username . ')';

                                array_push($itemUsers, $itemUser);
                            }

                            $itemFull = array();
                            $itemAllCompanies = array();
                            $itemAllCompanies['id'] = "AllCompany";
                            $itemAllCompanies['label'] = "Toàn bộ công ty";
                            $itemAllCompanies['children'] = $listCompanies;
                            array_push($itemFull, $itemAllCompanies);

                            $itemAllUsers = array();
                            $itemAllUsers['id'] = "AllUsers";
                            $itemAllUsers['label'] = "Toàn bộ nhân viên";
                            $itemAllUsers['children'] = $itemUsers;
                            array_push($itemFull, $itemAllUsers);

                            $groups = $itemFull;
                            break;
                        }
                    default:

                        break;
                }
            } else {
                try {

                    $group->map(function ($item) {
                        $defaultTeamName = 'C' . $item->company_id . '_' . 'D' . $item->department_id;
                        if ($item->workshop_id) {
                            $defaultTeamName = $defaultTeamName . '_' . 'W' . $item->workshop_id;
                        }
                        if ($item->party_id) {
                            $defaultTeamName = $defaultTeamName . '_' . 'P' . $item->party_id;
                        }
                        $defaultTeamName = $defaultTeamName . '_DefaultTeam';

                        $defaultTeam = Team::where('name', $defaultTeamName)->get()->first();
                        if ($defaultTeam)
                            $defaultTeam->with('users');

                        $item['defaultTeamName'] = $defaultTeamName;

                        if ($defaultTeam) {
                            $item['teamID'] = $defaultTeam->id;
                            $item['teamCount'] = count($defaultTeam->users);
                        } else {
                            $item['teamID'] = null;
                        }

                        return $item;
                    });
                } catch (\Exception $e) {
                }
            }
            $result['data'] = $group;
            $result['success'] = "1";
        } catch (Exception $e) {
            $this->errors = $e->getMessage();
        }
        // return DepartmentResource::collection($department);



        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

    public function store(Request $request)
    {
        if (!auth()->user()->hasPermission('config_category')) {
            return response('Access denied', 403);
        }

        $result = array();
        $result['data'] =  array();
        $result['data']['success']  = 0;
        $isErr = false;
        date_default_timezone_set("Asia/Ho_Chi_Minh");
        //dd( $request);
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:50',
            'description' => 'required|max:255',
            'active' => 'required',
            'company_id' => 'required|min:4|max:4',
            'department_id' => 'required',

        ]);

        $failed = $validator->fails();

        if ($request->company_id && $request->department_id) {

            $group_temp = Group::where('company_id', $request->company_id)
                ->where('department_id', $request->department_id)
                ->where('name', $request->name)->first();
            if ($group_temp) {
                $result['data']['message']  = "Trùng mã, vui lòng nhập mã khác";
                $validator->errors()->add('name', 'Trùng mã, vui lòng nhập mã khác');
                $isErr = true;
            }
        }

        //dd($failed);
        if ($failed || $isErr) {
            $result['data']['errors']  = $validator->errors();
        } else {

            try {
                $re = Group::create($request->all());
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
        $group = Group::with('users')->findOrFail($id);

        $result = array();
        $result['data'] =  array();
        $result['data'] = $group;


        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    public function update(Request $request, $id)
    {
        if (!auth()->user()->hasPermission('config_category')) {
            return response('Access denied', 403);
        }

        $result = array();
        $result['data'] =  array();
        $result['data']['success']  = 0;
        date_default_timezone_set("Asia/Ho_Chi_Minh");
        $isErr = false;
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:50',
            'description' => 'required|max:255',
            'active' => 'required',


        ]);

        $failed = $validator->fails();
        if ($request->company_id && $request->department_id) {

            $group_temp = Group::where('company_id', $request->company_id)
                ->where('department_id', $request->department_id)
                ->where('name', $request->name)->first();

            if ($group_temp && $group_temp->id != $id) {
                $result['data']['message']  = "Trùng mã, vui lòng nhập mã khác";
                $validator->errors()->add('name', 'Trùng mã, vui lòng nhập mã khác');
                $isErr = true;
            }
        }
        if ($failed ||  $isErr) {
            $result['data']['errors']  = $validator->errors();
        } else {


            try {

                $group = Group::findOrFail($id);
                if ($group) {
                    // $team->id =  $team->id;
                    $group->name = $request->input('name');
                    $group->description = $request->input('description');
                    $group->active = $request->input('active');
                    $group->company_id = $request->input('company_id');
                    $group->department_id = $request->input('department_id');
                    $group->workshop_id = $request->input('workshop_id');
                    $group->party_id = $request->input('party_id');


                    if ($group->save())
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
        if (!auth()->user()->hasPermission('config_category')) {
            return response('Access denied', 403);
        }
        // Get article
        $group = Group::findOrFail($id);
        $result = array();
        $result['data'] = array();
        $result['data']['success']  = 0;
        if ($group->delete()) {
            $result['data']['success']  = 1;
        }
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);

    }
    public function assign_user(Request $request)
    {
        if (!auth()->user()->hasPermission('config_category')) {
            return response('Access denied', 403);
        }

        //dd('test');
        $result = array();
        $result['data'] =  array();
        $result['data']['success']  = 0;
        date_default_timezone_set("Asia/Ho_Chi_Minh");
        $fields = $request->all();
        $validator = Validator::make($fields, [
            'group' => 'required',
            'group.id' => 'required',
        ]);

        $failed = $validator->fails();

        if ($failed) {
            // dd("loi");
            $result['data']['errors']  = $validator->errors();
        } else {

            /// dd($fields['team']['id']);
            try {

                $group = Group::findOrFail($fields['group']['id']);
                $users = $fields['group']['users'];

                if ($group) {
                    foreach ($group->users as $key => $u) {
                        //     dd( $u['id']);
                        $user = User::find($u['id']);
                        $group->users()->detach($user);
                    }
                    foreach ($users as $key => $u) {
                        //     dd( $u['id']);
                        $user = User::find($u['id']);
                        // $level = isset($u['pivot']['level'])? $u['pivot']['level']:"1";
                        // $step = isset($u['pivot']['step'])? $u['pivot']['step']:"1";
                        // $review = isset($u['pivot']['review'])? $u['pivot']['review']:"";

                        // $team->users()->detach($user);
                        $group->users()->detach($user); //không cho trùng user trong nhóm
                        $group->users()->attach($user);
                    }


                    $result['data']['success']  = 1;
                }
            } catch (\Exception $e) {
                $result['data']['errors']  =  $e->getMessage();
            }
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);



    }
    public function detach_user(Request $request)
    {
        if (!auth()->user()->hasPermission('config_category')) {
            return response('Access denied', 403);
        }

        //dd('test');
        $result = array();
        $result['data'] = array();
        $result['success'] = "0";
        $level = $request->input('level');

        $group = Group::findOrFail($request->group_id);
        $user = User::findOrFail($request->user_id);
        if ($group && $user) {
            $group->users()->detach($user);
            //  $team->users()->attach($user,['level'=>$level]);
            $result['success'] = "1";
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
}
