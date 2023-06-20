<?php

namespace App\Http\Controllers\api\category;

use App\Exports\Category\ApproveroutingExport;
use App\Http\Controllers\Controller;
use App\Models\shared\ApprovedLimit;
use App\Models\shared\ApprovedRouting;
use App\Models\shared\DocumentType;
use App\Models\shared\Group;
use App\Models\shared\Team;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class ApproveRoutingController extends Controller
{
    public function index(Request $request)
    {
        $result = array();
        $result['data'] = array();
        // $group = Group::all();
        $query = ApprovedRouting::query();

        try {
            if ($request->filled('payment_type_id') && $request->payment_type_id != "undefined" &&  $request->payment_type_id != "null") {
                $query = $query->whereIn('payment_type_id', explode(",", $request->payment_type_id));
            }
            if ($request->filled('approved_limit_code') && $request->approved_limit_code != "undefined" &&  $request->approved_limit_code != "null") {
                $query = $query->whereIn('approved_limit_code', explode(",", $request->approved_limit_code));
            }
            if ($request->filled('group_id') && $request->group_id != "undefined" &&  $request->group_id != "null") {
                $query = $query->whereIn('group_id', explode(",", $request->group_id));
            }
            if ($request->filled('document_type_id') && $request->document_type_id != "undefined" && $request->document_type_id != "null") {
                $query = $query->whereIn('document_type_id', explode(",", $request->document_type_id));
            }
            if ($request->filled('active') && $request->active != "undefined" && $request->active != "null") {
                $query = $query->whereIn('active', explode(",", $request->active));
            }

            if ($request->filled('team_id')  && $request->team_id != "undefined" &&  $request->team_id != "null") {
                $query = $query->where('team_id', $request->team_id);
            }
            if ($request->filled('user_id')  && $request->user_id != "undefined" &&  $request->user_id != "null") {
                $user_id = $request->user_id;

                $query = $query->whereIn('team_id', function ($q) use ($user_id) {

                    $q->select(['team_id'])
                        ->from('user_team')
                        ->whereIn('user_id', explode(",", $user_id));
                });
            }
            if ($request->filled('usercc_id')  && $request->usercc_id != "undefined" &&  $request->usercc_id != "null") {
                $usercc_id = $request->usercc_id;

                $query = $query->whereIn('team_id', function ($q) use ($usercc_id) {

                    $q->select(['team_id'])
                        ->from('usercc_team')
                        ->whereIn('user_id', explode(",", $usercc_id));
                });
            }

            $approveRouting = $query->with('team')->get();
            $result['data'] = $approveRouting;
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
        date_default_timezone_set("Asia/Ho_Chi_Minh");
        //dd( $request);
        $validator = Validator::make($request->all(), [
            'document_type_id' => 'required',
            'payment_type_id' => 'required',
            'approved_limit_code' => 'required',
            'group_id' => 'required',
            'budget_type' => 'required',
            'currency' => 'required',
            // 'team_id' => 'required',
            'description' => 'required|max:255',
            'active' => 'required',
        ]);

        $failed = $validator->fails();
        $isErr = false;
        $team_id = "";
        $group = Group::find($request->group_id);
        $limit = ApprovedLimit::where('code', $request->approved_limit_code)->first();
        $document_type = DocumentType::find($request->document_type_id);
        if ($group && $limit && $document_type && $group->department) {


            $teamname = $this->create_team_name(
                $document_type->code,
                $group->company_id,
                $group->department->code,
                $request->payment_type_id,
                $group->name,
                $limit->name,
                $request->budget_type,
                $request->currency
            );
            $team = Team::where('name', $teamname)->first();

            if (!$team) {
                $team = Team::create(['name' => $teamname, 'description' => $teamname, 'active' => '1']);
            }
            $team_id = $team->id;
        }


        $isExist = ApprovedRouting::where('document_type_id', $request->document_type_id)
            ->where('approved_limit_code', $request->approved_limit_code)
            ->where('group_id', $request->group_id)
            ->where('payment_type_id', $request->payment_type_id)
            ->where('team_id', $team_id)->first();

        // dd($isExist);
        if ($isExist) {
            $validator->errors()->add('tontai', 'Tuyến duyệt đã được cấu hình'); //front-end kiểm tra dựa trên lỗi
            $result['data']['message'] = 'Tuyến duyệt đã được cấu hình'; //front-end thông báo
            $isErr = true;
        }

        if ($failed || $isErr) {
            $result['data']['errors']  = $validator->errors();
        } else {

            try {

                $fields = $request->all();
                $fields['team_id'] = $team_id;
                $re = ApprovedRouting::create($fields);
                if ($re) {
                    $re->load('team');
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
        $approvedRouting = ApprovedRouting::findOrFail($id)->with('team', 'payment_type', 'document_type', 'group');

        $result = array();
        $result['data'] =  array();
        $result['data'] = $approvedRouting;
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    // private function create_team_name( $document_type,$company,$payment_type,$group_code,$limit,$budget_type,$currency){
    //     $name = $document_type.'_'.$company.'_'.$payment_type.'_'.$group_code.'_'.$limit.'_'.$budget_type.'_'.$currency;
    //     return  $name;
    // }
    private function create_team_name($document_type, $company, $department_code, $payment_type, $group_code, $limit, $budget_type, $currency)
    {
        $name = $document_type . '_' . $company . '_' . $department_code . '_' . $payment_type . '_' . $group_code . '_' . $limit . '_' . $budget_type . '_' . $currency;
        return  $name;
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
        //dd( $request);
        $validator = Validator::make($request->all(), [
            'document_type_id' => 'required',
            'payment_type_id' => 'required',
            'approved_limit_code' => 'required',
            'group_id' => 'required',
            'team_id' => 'required',
            'description' => 'required|max:255',
            'active' => 'required',
        ]);

        $failed = $validator->fails();
        $isErr = false;
        if ($failed) {
            $result['data']['errors']  = $validator->errors();
        } else {


            try {
                $team_id = "";
                $group = Group::find($request->group_id);
                $limit = ApprovedLimit::where('code', $request->approved_limit_code)->first();
                $document_type = DocumentType::find($request->document_type_id);
                if ($group && $limit && $document_type &&  $group->department) {


                    $teamname = $this->create_team_name(
                        $document_type->code,
                        $group->company_id,
                        $group->department->code,
                        $request->payment_type_id,
                        $group->name,
                        $limit->name,
                        $request->budget_type,
                        $request->currency
                    );
                    $team = Team::where('name', $teamname)->first();

                    if (!$team) {
                        $team = Team::create(['name' => $teamname, 'description' => $teamname, 'active' => '1']);
                    }
                    $team_id = $team->id;
                }

                $approvedRouting = ApprovedRouting::findOrFail($id);
                // dd( $team);
                if ($approvedRouting) {
                    // $team->id =  $team->id;

                    $isExist = ApprovedRouting::where('document_type_id', $request->document_type_id)
                        ->where('approved_limit_code', $request->approved_limit_code)
                        ->where('group_id', $request->group_id)
                        ->where('payment_type_id', $request->payment_type_id)
                        ->where('team_id', $team_id)->first();
                    //  dd($team_id );
                    if ($isExist &&  $isExist->id != $id) {
                        $validator->errors()->add('tontai', 'Tuyến duyệt đã được cấu hình'); //front-end kiểm tra dựa trên lỗi
                        $result['data']['message'] = 'Tuyến duyệt đã được cấu hình'; //front-end thông báo
                        $isErr = true;
                    }
                    if (!$isErr) {
                        $approvedRouting->document_type_id = $request->input('document_type_id');
                        $approvedRouting->approved_limit_code = $request->input('approved_limit_code');
                        $approvedRouting->group_id = $request->input('group_id');
                        $approvedRouting->payment_type_id = $request->input('payment_type_id');
                        $approvedRouting->team_id =  $team_id; // $request->input('team_id');
                        $approvedRouting->description = $request->input('description');
                        $approvedRouting->active = $request->input('active');


                        if ($approvedRouting->save()) {
                            $result['data']['success']  = 1;
                            $approvedRouting->load('team');
                            $result['data']  = $approvedRouting;
                        }
                    }
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
        $approvedRouting = ApprovedRouting::findOrFail($id);
        $result = array();
        $result['data'] = array();
        $result['data']['success']  = 0;
        if ($approvedRouting->delete()) {
            $result['data']['success']  = 1;
        }
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);

    }
    public function export()
    {


        return Excel::download(new ApproveroutingExport, 'ApproveroutingExport.xlsx');
    }
}
