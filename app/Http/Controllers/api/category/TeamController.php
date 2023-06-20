<?php

namespace App\Http\Controllers\api\category;

use App\Http\Controllers\BaseController\BaseController;
use App\Http\Controllers\Controller;
use App\Models\document\Document;
use App\Models\shared\ApprovedRouting;
use App\Models\shared\Team;
use App\User;
use App\Models\shared\DocumentType;
use App\Models\shared\Group;
use App\Models\shared\ApprovedLimit;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Calculation\TextData\Replace;
use App\Models\payment\Payrequest;
use App\Models\shared\Approved;
use App\Repositories\approve\ApproveRoutingHelper;
use Error;
use Illuminate\Database\Eloquent\Builder;
use App\Repositories\approve\ApproveMain;
use App\Ultils\Ultils;

class TeamController extends BaseController
{
    public function index(Request $request)
    {
        $result = array();
        $result['data'] = array();
        // $group = Group::all();
        $query = Team::query();

        try {

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
            $group = $query->get();
            $group = $query->withCount('users')->get();
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
        date_default_timezone_set("Asia/Ho_Chi_Minh");
        //dd( $request);
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:50|unique:teams',
            'description' => 'required|max:255',
            'active' => 'required',


        ]);

        $failed = $validator->fails();
        //dd($failed);
        if ($failed) {
            $result['data']['errors']  = $validator->errors();
        } else {

            try {
                $re = Team::create($request->all());
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
        $team = Team::with(['users', 'userccs'])->findOrFail($id);

        $result = array();
        $result['data'] =  array();
        $result['data'] = $team;
        $team_id = $id;
        $original_teamid = 0;
        $payRequest = Payrequest::where('team_id', $team_id )->first();
        $id = 0;
        $doctype = null;
      
        if ($payRequest) {
            //$original_teamid = ApproveRoutingHelper::get_team($payRequest->company_id, $payRequest['document_type_id'], $payRequest['group_id'], $payRequest['budget_type'], $payRequest['amount'], $payRequest['amount'], $payRequest['currency'], $payRequest['payment_type_id'], 0, 0);
            $original_teamid = $payRequest->teamconfig_id;
            $id = $payRequest->id;
            $doctype = Ultils::$MODULE_PAYMENT;
        } else {
           
            $document = Document::where('team_id', $team_id )->first();
           // dd( $document);
            if ($document) {
                //$original_teamid = ApproveRoutingHelper::get_team($document->company_id, $document['document_type_id'], $document['group_id'], $document['budget_type'], $document['amount'], $document['amount'], $document['currency'], $document['payment_type_id'], 0, 0);
                $original_teamid = $document->teamconfig_id;
                $id = $document->id;
                $doctype =  Ultils::$MODULE_REPORT; //$document->document_type;
            }
        }

        if ($doctype) {
            $approve = ApproveMain::create($doctype, $id);
            if ($approve) {
                $user_wait = $approve->get_info_approved_waiting();
                if ($user_wait) {
                    $listUserInApprove = array();
                    foreach ($team->users as $key => $user) {
                        $item['label'] =  '[' . $user->username . '] ' . $user->name;
                        $item['id'] =  $user->id;
                        array_push($listUserInApprove, $item);
                    }

                    $result['data']['current_approval_tree'] = $listUserInApprove;
                    $result['data']['current_approval'] = $user_wait->user->id;
                    $result['data']['doctype'] = $doctype;
                    $result['data']['docid'] = $id;
                }
            }
        }


        if ($original_teamid > 0) {
            $original_team = Team::with(['users'])->where('id', $original_teamid)->first();
            $result['data']['original_team'] = $original_team;
        }

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

        $validator = Validator::make($request->all(), [
            'name' => 'required|max:50|unique:teams,name,' . $id,
            'description' => 'required|max:255',
            'active' => 'required',


        ]);

        $failed = $validator->fails();

        if ($failed) {
            $result['data']['errors']  = $validator->errors();
        } else {


            try {

                $team = Team::findOrFail($id);
                if ($team) {
                    // $team->id =  $team->id;
                    $team->name = $request->input('name');
                    $team->description = $request->input('description');
                    $team->active = $request->input('active');


                    if ($team->save())
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
        $team = Team::findOrFail($id);
        $result = array();
        $result['data'] = array();
        $result['data']['success']  = 0;
        if ($team->delete()) {
            $result['data']['success']  = 1;
        }
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);

    }

    public function search_user(Request $request)
    {

        $searchterm = $request->search;
        if ($searchterm != '') {
            $userlist = User::where('name', 'like', '%' . $searchterm . '%')
                ->orwhere('username', 'like', '%' . $searchterm . '%')
                ->orwhere('company_id', 'like', '%' . $searchterm . '%')
                ->orwhere('department_id', 'like', '%' . $searchterm . '%')
                ->paginate(10);
        } else {
            $userlist = User::paginate(10);
        }


        //$userlist = User::paginate(5);
        // return DepartmentResource::collection($department);
        $result = array();
        $result['data'] = array();
        $result['data'] = $userlist;
        $result['success'] = "1";
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
            'team' => 'required',
            'team.id' => 'required',
        ]);

        $failed = $validator->fails();

        if ($failed) {
            //  dd("loi");
            $result['data']['errors']  = $validator->errors();
        } else {

            /// dd($fields['team']['id']);
            try {

                $team = Team::findOrFail($fields['team']['id']);
                $users = $fields['team']['users'];
                // dd( $users);
                if ($team) {
                    $team->updated_by = auth()->user()->id;
                    $team->update();

                    foreach ($team->users as $key => $u) {
                        //     dd( $u['id']);
                        $user = User::find($u['id']);
                        $team->users()->detach($user);
                    }
                    foreach ($users as $key => $u) {
                        //     dd( $u['id']);
                        $user = User::find($u['id']);
                        $level = isset($u['pivot']['level']) ? $u['pivot']['level'] : "1";
                        $step = isset($u['pivot']['step']) ? $u['pivot']['step'] : "1";
                        $review = isset($u['pivot']['review']) ? $u['pivot']['review'] : "";
                        $sign = isset($u['pivot']['sign']) ? $u['pivot']['sign'] : null;

                        // $team->users()->detach($user);
                        $team->users()->attach($user, ['level' => $level, 'step' => $step, 'review' => $review, 'sign' => $sign]);
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

        $team = Team::findOrFail($request->team_id);
        $user = User::findOrFail($request->user_id);
        if ($team && $user) {
            $team->updated_by = auth()->user()->id;
            $team->update();

            $team->users()->detach($user);
            //  $team->users()->attach($user,['level'=>$level]);
            $result['success'] = "1";
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    //assign danh sách gửi email sau khi duyệt hoàn thành
    public function assign_user_cc(Request $request)
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
            'team' => 'required',
            'team.id' => 'required',
        ]);

        $failed = $validator->fails();

        if ($failed) {
            //  dd("loi");
            $result['data']['errors']  = $validator->errors();
        } else {

            /// dd($fields['team']['id']);
            try {

                $team = Team::findOrFail($fields['team']['id']);
                $users = $fields['team']['userccs'];

                if ($team) {
                    $team->updated_by = auth()->user()->id;
                    $team->update();

                    foreach ($team->userccs as $key => $u) {
                        $user = User::find($u['id']);
                        $team->userccs()->detach($user);
                    }

                    foreach ($users as $key => $u) {
                        $user = User::find($u['id']);
                        $team->userccs()->attach($user);
                    }

                    $result['data']['success']  = 1;
                }
            } catch (\Exception $e) {
                $result['data']['errors']  =  $e->getMessage();
            }
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);


    }

    public function detach_user_cc(Request $request)
    {
        if (!auth()->user()->hasPermission('config_category')) {
            return response('Access denied', 403);
        }

        //dd('test');
        $result = array();
        $result['data'] = array();
        $result['success'] = "0";
        $level = $request->input('level');

        $team = Team::findOrFail($request->team_id);
        $user = User::findOrFail($request->user_id);
        if ($team && $user) {
            $team->updated_by = auth()->user()->id;
            $team->update();

            $team->userccs()->detach($user);
            //  $team->users()->attach($user,['level'=>$level]);
            $result['success'] = "1";
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

    public function get_list_user(Request $request)
    {

        $result = array();
        $result['data'] = array();
        $result['success'] = "0";

        $team = Team::find($request->team_id);
        if ($team) {
        }
    }
    /**
     * Thay thế user A thành user B trong Team
     */
    public function replace_user(Request $request)
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
            'routing_ids' => 'required',
            'old_user_id' => 'required',
            'new_user_id' => 'required',
        ]);

        $failed = $validator->fails();
        if ($failed) {
            //  dd("loi");
            $result['data']['errors']  = $validator->errors();
        } else {
            /// dd($fields['team']['id']);
            try {
                $team_news =  collect([]);
                // dd($teams);
                $old_user = User::find($request->old_user_id);
                $new_user = User::find($request->new_user_id);
                $teams_ids = array();
                $ids = explode(",", $request->routing_ids);
                $routings =   ApprovedRouting::whereIn('id', $ids)->get();
                foreach ($routings as $routing) {
                    array_push($teams_ids, $routing->team_id);
                }
                $teams = Team::whereIn('id', $teams_ids)->get();
                //Chỉ Lấy các team có user cần thay thế
                $teams->each(function ($team, $key) use ($old_user, $team_news) {
                    $ids =  $team->users->flatten()->pluck('id')->all();
                    if (in_array($old_user->id, $ids)) {
                        $team_news->add($team);
                    }
                });
                //dd($team_news);

                if ($old_user && $new_user &&  $team_news) {

                    foreach ($team_news  as  $team) {
                        //$test=false;
                        //xóa user cần thay thế trong vị trí cũ và thay vào vị trí mới
                        foreach ($team->users as $u) {
                            //   dd("test");
                            if ($new_user->id == $u->id) {

                                $user = User::find($u->id);
                                $level = $u->pivot->level;
                                $step = $u->pivot->step;
                                $review = $u->pivot->review;
                                $sign =  $u->pivot->sign;
                                $team->users()->detach($user);
                                // dd("xoa");
                                //  $test = true;
                                // $team->users()->attach($user_by,['level'=>$level,'step'=>$step,'review'=>$review,'sign'=>$sign]);
                            }
                        }
                        $team->updated_by = auth()->user()->id;
                        $team->update();
                        // if ($test) {
                        //     dd($team->users);
                        // }
                        $team->load('users');
                        // dd($team->users);
                        foreach ($team->users as $u) {

                            if ($old_user->id == $u->id) {

                                $user = User::find($u->id);
                                $level = $u->pivot->level;
                                $step = $u->pivot->step;
                                $review = $u->pivot->review;
                                $sign =  $u->pivot->sign;
                                $team->users()->detach($user);
                                $team->users()->attach($new_user, ['level' => $level, 'step' => $step, 'review' => $review, 'sign' => $sign]);
                            }
                        }
                    }
                    $result['data']['success']  = 1;
                }
            } catch (\Exception $e) {
                $result['data']['errors']  =  $e->getMessage();
            }
        }
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    public function remove_user(Request $request)
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
            'routing_ids' => 'required',
            'user_id' => 'required',

        ]);

        $failed = $validator->fails();
        if ($failed) {
            //  dd("loi");
            $result['data']['errors']  = $validator->errors();
        } else {
            /// dd($fields['team']['id']);
            try {

                $teams_ids = array();
                $ids = explode(",", $request->routing_ids);
                $routings =   ApprovedRouting::whereIn('id', $ids)->get();
                foreach ($routings as $routing) {
                    array_push($teams_ids, $routing->team_id);
                }
                $teams = Team::whereIn('id', $teams_ids)->get();
                $user = User::find($request->user_id);

                if ($user  &&  $teams) {

                    foreach ($teams  as  $team) {

                        foreach ($team->users as $u) {

                            if ($user->id == $u->id) {
                                // dd($u->id);
                                $user = User::find($u->id);
                                $level = $u->pivot->level;
                                $step = $u->pivot->step;
                                $review = $u->pivot->review;
                                $sign =  $u->pivot->sign;
                                $team->users()->detach($user);
                                // $team->users()->attach($user_by,['level'=>$level,'step'=>$step,'review'=>$review,'sign'=>$sign]);
                            }
                        }

                        $team->updated_by = auth()->user()->id;
                        $team->update();
                    }
                    $result['data']['success']  = 1;
                }
            } catch (\Exception $e) {
                $result['data']['errors']  =  $e->getMessage();
            }
        }
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

    public function batchreplace_usercc(Request $request)
    {
        if (!auth()->user()->hasPermission('config_category')) {
            return response('Access denied', 403);
        }

        $result = array();
        $result['data'] =  array();
        $result['data']['success']  = 0;
        date_default_timezone_set("Asia/Ho_Chi_Minh");
        $fields = $request->all();
        $validator = Validator::make($fields, [
            'routing_ids' => 'required',
            'old_usercc_id' => 'required',
            'new_usercc_id' => 'required',
        ]);

        $failed = $validator->fails();
        if ($failed) {
            $result['data']['errors']  = $validator->errors();
        } else {

            try {
                $teamcc_news =  collect([]);
                $old_user = User::find($request->old_usercc_id);

                $new_user = User::find($request->new_usercc_id);
                $teams_ids = array();
                $ids = explode(",", $request->routing_ids);
                $routings =   ApprovedRouting::whereIn('id', $ids)->get();
                foreach ($routings as $routing) {
                    array_push($teams_ids, $routing->team_id);
                }
                $teams = Team::whereIn('id', $teams_ids)->with('userccs')->get();

                //Chỉ Lấy các team có user cần thay thế
                $teams->each(function ($team, $key) use ($old_user, $teamcc_news) {

                    $ids =  $team->userccs->flatten()->pluck('id')->all();
                    if (in_array($old_user->id, $ids)) {
                        $teamcc_news->add($team);
                    }
                });

                if ($old_user && $new_user &&  $teamcc_news) {
                    foreach ($teamcc_news  as  $team) {
                        foreach ($team->userccs as $u) {
                            if ($old_user->id == $u->id) {
                                $team->userccs()->detach($old_user);
                                $team->userccs()->attach($new_user);
                            }
                        }
                        $team->updated_by = auth()->user()->id;
                        $team->update();
                    }
                    $result['data']['success']  = 1;
                }
            } catch (\Exception $e) {
                $result['data']['errors']  =  $e->getMessage();
            }
        }
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

    public function batchadd_usercc(Request $request)
    {
        if (!auth()->user()->hasPermission('config_category')) {
            return response('Access denied', 403);
        }

        $result = array();
        $result['data'] =  array();
        $result['data']['success']  = 0;
        date_default_timezone_set("Asia/Ho_Chi_Minh");
        $fields = $request->all();
        $validator = Validator::make($fields, [
            'routing_ids' => 'required',
            'new_usercc_id' => 'required',
        ]);

        $failed = $validator->fails();
        if ($failed) {
            $result['data']['errors']  = $validator->errors();
        } else {

            try {
                $teamcc_news =  collect([]);

                $new_user = User::find($request->new_usercc_id);
                $teams_ids = array();
                $ids = explode(",", $request->routing_ids);
                $routings =   ApprovedRouting::whereIn('id', $ids)->get();
                foreach ($routings as $routing) {
                    array_push($teams_ids, $routing->team_id);
                }
                $teams = Team::whereIn('id', $teams_ids)->with('userccs')->get();

                //Chỉ Lấy các team có user cần thay thế
                $teams->each(function ($team, $key) use ($teamcc_news) {
                    $teamcc_news->add($team);
                });

                if ($new_user &&  $teamcc_news) {
                    foreach ($teamcc_news  as  $team) {
                        if (!$team->userccs()->find($new_user)) {
                            $team->userccs()->attach($new_user);
                        }
                        $team->updated_by = auth()->user()->id;
                        $team->update();
                    }
                    $result['data']['success']  = 1;
                }
            } catch (\Exception $e) {
                $result['data']['errors']  =  $e->getMessage();
            }
        }
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

    public function batchremove_usercc(Request $request)
    {
        if (!auth()->user()->hasPermission('config_category')) {
            return response('Access denied', 403);
        }
        //tesst
        $result = array();
        $result['data'] =  array();
        $result['data']['success']  = 0;
        date_default_timezone_set("Asia/Ho_Chi_Minh");
        $fields = $request->all();
        $validator = Validator::make($fields, [
            'routing_ids' => 'required',
            'old_usercc_id' => 'required',
        ]);

        $failed = $validator->fails();
        if ($failed) {
            $result['data']['errors']  = $validator->errors();
        } else {

            try {
                $teamcc_news =  collect([]);

                $old_user = User::find($request->old_usercc_id);
                $teams_ids = array();
                $ids = explode(",", $request->routing_ids);
                $routings =   ApprovedRouting::whereIn('id', $ids)->get();
                foreach ($routings as $routing) {
                    array_push($teams_ids, $routing->team_id);
                }
                $teams = Team::whereIn('id', $teams_ids)->with('userccs')->get();

                //Chỉ Lấy các team có user cần thay thế
                $teams->each(function ($team, $key) use ($teamcc_news) {
                    $teamcc_news->add($team);
                });

                if ($old_user &&  $teamcc_news) {
                    foreach ($teamcc_news  as  $team) {
                        if ($team->userccs()->find($old_user)) {
                            $team->userccs()->detach($old_user);
                        }
                        $team->updated_by = auth()->user()->id;
                        $team->update();
                    }
                    $result['data']['success']  = 1;
                }
            } catch (\Exception $e) {
                $result['data']['errors']  =  $e->getMessage();
            }
        }
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

    public function batchreview_user(Request $request)
    {
        if (!auth()->user()->hasPermission('config_category')) {
            return response('Access denied', 403);
        }
        //tesst
        $result = array();
        $result['data'] =  array();
        $result['data']['success']  = 0;
        date_default_timezone_set("Asia/Ho_Chi_Minh");
        $fields = $request->all();
        $validator = Validator::make($fields, [
            'routing_ids' => 'required',
            'turnon' => 'required',
            'review_user_id' => 'required',
        ]);

        $failed = $validator->fails();
        if ($failed) {
            $result['data']['errors']  = $validator->errors();
        } else {

            try {
                $team_news =  collect([]);
                $review_user = User::find($request->review_user_id);

                $teams_ids = array();
                $ids = explode(",", $request->routing_ids);
                $routings =   ApprovedRouting::whereIn('id', $ids)->get();
                foreach ($routings as $routing) {
                    array_push($teams_ids, $routing->team_id);
                }
                $teams = Team::whereIn('id', $teams_ids)->with('users')->get();

                //Chỉ Lấy các team có user cần thay thế
                $teams->each(function ($team, $key) use ($review_user, $team_news) {

                    $ids =  $team->users->flatten()->pluck('id')->all();
                    if (in_array($review_user->id, $ids)) {
                        $team_news->add($team);
                    }
                });

                if ($review_user && $team_news) {
                    foreach ($team_news  as  $team) {
                        foreach ($team->users as $u) {
                            if ($review_user->id == $u->id) {

                                $user = User::find($u->id);
                                $level = $u->pivot->level;
                                $step = $u->pivot->step;
                                $review = $request->turnon;
                                $sign =  $u->pivot->sign;
                                $team->users()->detach($user);
                                $team->users()->attach($user, ['level' => $level, 'step' => $step, 'review' => $review, 'sign' => $sign]);
                            }
                        }
                        $team->updated_by = auth()->user()->id;
                        $team->update();
                    }
                    $result['data']['success']  = 1;
                }
            } catch (\Exception $e) {
                $result['data']['errors']  =  $e->getMessage();
            }
        }
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

    public function batchadd_routing(Request $request)
    {
        if (!auth()->user()->hasPermission('config_category')) {
            return response('Access denied', 403);
        }
        //tesst
        $result = array();
        $result['data'] =  array();
        $result['data']['success']  = 0;
        date_default_timezone_set("Asia/Ho_Chi_Minh");
        $fields = $request->all();
        $validator = Validator::make($fields, [
            'group_ids' => 'required',
            'document_type_ids' => 'required',
            'currency' => 'required',
            'budget_type' => 'required',
            'payment_type_id' => 'required',
            'active' => 'required',
        ]);

        $failed = $validator->fails();
        if ($failed) {
            $result['data']['errors']  = $validator->errors();
        } else {
            $totalCreateNew = 0;
            $totalExisted = 0;

            foreach ($request->group_ids  as  $group_id) {
                $group = Group::find($group_id);

                if ($group) {
                    foreach ($request->document_type_ids  as  $document_type_id) {
                        $document_type = DocumentType::find($document_type_id);

                        //$limit = ApprovedLimit::where('code', $request->approved_limit_code);

                        $allLimits = ApprovedLimit::where('company_id', $group->company_id)
                            ->where('document_type_id', $document_type->id)
                            ->where('currency', $request->currency)
                            ->where('budget_type', $request->budget_type)
                            ->where('payment_type_id', $request->payment_type_id)->get();

                        if ($allLimits) {
                            foreach ($allLimits  as  $limit) {
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

                                $isExist = ApprovedRouting::where('document_type_id', $document_type_id)
                                    ->where('approved_limit_code', $limit->code)
                                    ->where('group_id', $group_id)
                                    ->where('payment_type_id', $request->payment_type_id)
                                    ->where('team_id', $team_id)->first();

                                if (!$isExist) {
                                    $fields['document_type_id'] = $document_type_id;
                                    $fields['approved_limit_code'] = $limit->code;
                                    $fields['group_id'] = $group_id;
                                    $fields['payment_type_id'] = $request->payment_type_id;
                                    $fields['team_id'] =  $team_id;
                                    $fields['description'] = $teamname;
                                    $fields['active'] = $request->active;

                                    $newRouting = ApprovedRouting::create($fields);
                                    if ($newRouting) {
                                        $totalCreateNew++;
                                    }
                                } else {
                                    $totalExisted++;
                                }

                                $team->updated_by = auth()->user()->id;
                                $team->update();
                            }
                        }
                    }
                }
            }

            $result['data']['totalCreateNew']  = $totalCreateNew;
            $result['data']['totalExisted']  = $totalExisted;
        }
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

    public function createTeamForGroup(Request $request)
    {
        //tesst
        $result = array();
        $result['data'] =  array();
        $result['data']['success']  = 0;
        date_default_timezone_set("Asia/Ho_Chi_Minh");

        $totalCreateNew = 0;
        $totalExisted = 0;
        
        foreach ($request->group_ids as $group_id) {
            $group = Group::find($group_id);

            if ($group) {
                $defaultTeamName = 'C'.$group->company_id.'_'.'D'.$group->department_id;
                if ($group->workshop_id) {
                    $defaultTeamName = $defaultTeamName.'_'.'W'.$group->workshop_id;
                }
                if ($group->party_id) {
                    $defaultTeamName = $defaultTeamName.'_'.'P'.$group->party_id;
                }
                $defaultTeamName = $defaultTeamName.'_DefaultTeam';
                $team = Team::where('name', $defaultTeamName)->first();

                if (!$team) {
                    $team = Team::create(['name' => $defaultTeamName, 'description' => $defaultTeamName, 'active' => '1']);
                    $totalCreateNew++;
                } 
                else {
                    $totalExisted++;
                }
            }
        }

        $result['data']['totalCreateNew']  = $totalCreateNew;
        $result['data']['totalExisted']  = $totalExisted;
        
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

    private function create_team_name($document_type, $company, $department_code, $payment_type, $group_code, $limit, $budget_type, $currency)
    {
        $name = $document_type . '_' . $company . '_' . $department_code . '_' . $payment_type . '_' . $group_code . '_' . $limit . '_' . $budget_type . '_' . $currency;
        return  $name;
    }
}
