<?php

namespace App\Repositories\work\workflow\define;

use App\Models\work\workflow\runtime\WlworkflowDoc;
use App\Models\work\workflow\Wldataobject;
use App\Models\work\workflow\WldataobjectItms;
use App\Models\work\workflow\Wldoctype;
use App\Models\work\workflow\Wljob;
use App\Models\work\workflow\Wlphase;
use App\Models\work\workflow\Wlworkflow;
use App\Models\work\workflow\WlworkflowAdmin;
use App\Models\work\workflow\WlworkflowFollow;
use App\Models\work\workflow\WlworkflowMember;
use App\Models\work\workflow\WlworkflowScope;
use App\Repositories\approve\ApproveRoutingHelper;
use App\Repositories\work\workflow\runtime\WorkflowBase;
use App\User;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

abstract class WorkflowDefineAbs
{


    protected $wlworkflow;
    protected $request;
    protected $errors;
    protected $message;
    protected $layout;
    protected $form_name;

    public function __construct($request)
    {
        $this->request = $request;
    }
    public function __set($name, $value)
    {
        $this->$name = $value;
    }
    public function __get($name)
    {
        return $this->$name;
    }

    public function index()
    {
        $result = array();
        $result['data'] = array();
        $result['success'] = "0";
        $query = Wlworkflow::query();
        try {

            if ($this->request->filled('start_date')) {
                if (!$this->request->filled('end_date')) {
                    $this->request->end_date = $this->request->start_date;
                }
                $start_date = Carbon::create($this->request->start_date);
                $end_date = Carbon::create($this->request->end_date);
                $end_date->addHours(23);
                $end_date->addMinutes(59);
                $end_date->addSeconds(59);
                $query = $query->whereBetween('created_at', [$start_date, $end_date]);
            }
            if ($this->request->filled('wlworkflow_type_id')) {
                $query = $query->where('wlworkflow_type_id', $this->request->wlworkflow_type_id);
            }
            if ($this->request->filled('active')) {
                $query = $query->where('active', $this->request->active);
            }
            $query = $query->where('type', '0');
        } catch (Exception $e) {
            $this->errors = $e->getMessage();
        }

        $documents = $query->with(['wlworkflow_type', 'wlphase', 'admins', 'members', 'follows', 'wldoctypes', 'scope'])->orderBy('id', 'desc')->get();
        //$document->scope = json_decode($document['scope']->json_value);
        foreach ($documents as $document) {
            if ($document->scope) {

                $jsonValue = $document->scope->json_value;
                $jsonList = json_decode($jsonValue, true);
                $document->scope = $jsonList;
            }
        }
        return $documents;
    }
    protected function validate_store()
    {
        $fields = $this->request->all();
        $validator = Validator::make(
            $fields,
            [
                'name' => 'required',
            ],
            [
                'name.required' => "Tên không được rỗng",
            ]
        );
        return $validator;
    }
    public function show($id)
    {
        $document = Wlworkflow::with(['wlworkflow_type', 'wlphase', 'admins', 'members', 'follows', 'wldoctypes'])->find($id);
        if ($document->approve_type == 2 && $document->approve_team != null) {
            $document->load(['team', 'team.users']);
        }
        return $document;
    }

    public function edit($id)
    {
        $document = Wlworkflow::with(['wlworkflow_type', 'wlphase', 'admins', 'members', 'follows', 'wldoctypes', 'configs', 'activeConfig'])->find($id);
        if ($document->approve_type == 2 && $document->approve_team != null) {
            $document->load(['team', 'team.users']);
        }
        //if ($document->scope) {
        //    $jsonValue = $document->scope->json_value;
        //    $jsonList = json_decode($jsonValue, true);
        //    unset($document->scope);
        //    //dd($document->scope);
        //   
        //    //$document->scope = $jsonList;
        //    //return $document;
        //}
        return $document;
    }
    public function store()
    {
        $result = array();
        $result['data'] = array();
        $result['data']['success'] = 0;
        date_default_timezone_set("Asia/Ho_Chi_Minh");

        $fields = $this->request->all();

        $validator = $this->validate_store();

        $failed = $validator->fails();
        if ($failed) {

            $this->errors = $validator->errors();
        } else {

            try {
                DB::beginTransaction();

                if (!isset($fields['type']) || isset($fields['type']) == "") {
                    $fields['type'] = "0";
                }
                $wlworkflow = Wlworkflow::create($fields);

                if ($wlworkflow) {
                    //USER ADMIN
                    $admins = $fields['admin_values'];
                    if ($admins) {
                        for ($i = 0; $i < count($admins); $i++) {

                            $admin = new WlworkflowAdmin;
                            $admin->user_id = $admins[$i];
                            $wlworkflow->admins()->save($admin);
                        }
                    }

                    //USER MEMBERS
                    $members = $fields['member_values'];
                    if ($members) {
                        for ($i = 0; $i < count($members); $i++) {

                            $member = new WlworkflowMember;
                            $member->user_id = $members[$i];
                            $wlworkflow->members()->save($member);
                        }
                    }

                    //USER MEMBERS
                    $follows = $fields['follow_values'];
                    if ($follows) {
                        for ($i = 0; $i < count($follows); $i++) {

                            $follow = new WlworkflowFollow;
                            $follow->user_id = $follows[$i];
                            $wlworkflow->follows()->save($follow);
                        }
                    }
                    //Document types

                    // $document_types = $fields['document_types'];
                    // if ($document_types) {
                    //     for ($i = 0; $i < count($document_types); $i++) {

                    //         $wldoctype = new Wldoctype();
                    //         $wldoctype->document_type_id = $document_types[$i];                           
                    //         $wldoctype->wlworkflow_id =$wlworkflow->id;                           
                    //         $wldoctype->save();
                    //     }
                    // }
                    $wlphaseInit = new Wlphase();
                    $wlphaseInit->unique_name = "phase_initial";
                    $wlphaseInit->name = "Khởi tạo";
                    $wlphaseInit->description = "Khởi tạo";
                    $wlphaseInit->wlworkflow_id = $wlworkflow->id;
                    $wlphaseInit->index = -999;
                    $wlphaseInit->save();
                    $wlphaseEnd = new Wlphase();
                    $wlphaseEnd->unique_name = "phase_complete";
                    $wlphaseEnd->name = "Hoàn thành";
                    $wlphaseEnd->description = "Hoàn thành";
                    $wlphaseEnd->wlworkflow_id = $wlworkflow->id;
                    $wlphaseEnd->index = 999;
                    $wlphaseEnd->save();

                    if ($admins) {
                        for ($i = 0; $i < count($admins); $i++) {

                            $admin = new WlworkflowAdmin();
                            $admin->user_id = $admins[$i];
                            $wlphaseInit->admins()->save($admin);
                            $admin = new WlworkflowAdmin();
                            $admin->user_id = $admins[$i];
                            $wlphaseEnd->admins()->save($admin);
                        }
                    }
                    if ($members) {
                        for ($i = 0; $i < count($members); $i++) {

                            $member = new WlworkflowMember();
                            $member->user_id = $members[$i];
                            $wlphaseInit->members()->save($member);
                            $member = new WlworkflowMember();
                            $member->user_id = $members[$i];
                            $wlphaseEnd->members()->save($member);
                        }
                    }
                }

                DB::commit();

                return $wlworkflow;
            } catch (\Throwable $th) {
                DB::rollback();

                $this->errors = $th->getMessage();
                return null;
            }
        }

        return null;
    }

    protected function validate_update($id)
    {
        $fields = $this->request->all();

        $validator = Validator::make(
            $fields,
            [
                'name' => 'required',
            ],
            [
                'name.required' => "Tên không được rỗng",
            ]
        );

        return $validator;
    }


    public function update($id)
    {
        $result = array();
        $result['data'] = array();
        $result['data']['success'] = 0;
        date_default_timezone_set("Asia/Ho_Chi_Minh");

        $fields = $this->request->all();

        $validator = $this->validate_update($id);

        $failed = $validator->fails();
        if ($failed) {

            $this->errors = $validator->errors();
        } else {

            try {

                DB::beginTransaction();

                $wlworkflow = Wlworkflow::findOrFail($id);
                $wlworkflow->fill($fields);
                $wlworkflow->save();

                foreach ($wlworkflow->admins as $admin) {
                    $admin->delete();
                }
                foreach ($wlworkflow->members as $member) {
                    $member->delete();
                }
                foreach ($wlworkflow->follows as $follow) {
                    $follow->delete();
                }

                foreach ($wlworkflow->wldoctypes as $wldoctype) {
                    $wldoctype->delete();
                }

                if ($wlworkflow) {
                    //Scopes
                    if (isset($fields['scope'])) {
                        $scopeValue = array_unique($fields['scope']);
                        if ($wlworkflow->scope) {
                            $wlworkflow->scope->name = $wlworkflow->name;
                            $wlworkflow->scope->json_value = json_encode($scopeValue);
                            $wlworkflow->scope->save();
                        } else {
                            $scope = new WlworkflowScope;
                            $scope->name = $wlworkflow->name;
                            $scope->json_value = json_encode($scopeValue);
                            $wlworkflow->scope()->save($scope);
                        }
                        $wlworkflow->scope = $scopeValue;
                    }


                    //USER ADMIN
                    $admins = $fields['admin_values'];
                    if ($admins) {
                        for ($i = 0; $i < count($admins); $i++) {

                            $admin = new WlworkflowAdmin;
                            $admin->user_id = $admins[$i];
                            $wlworkflow->admins()->save($admin);
                        }
                    }

                    //USER MEMBERS
                    $members = $fields['member_values'];
                    if ($members) {
                        for ($i = 0; $i < count($members); $i++) {

                            $member = new WlworkflowMember;
                            $member->user_id = $members[$i];
                            $wlworkflow->members()->save($member);
                        }
                    }

                    //USER MEMBERS
                    $follows = $fields['follow_values'];
                    if ($follows) {
                        for ($i = 0; $i < count($follows); $i++) {

                            $follow = new WlworkflowFollow;
                            $follow->user_id = $follows[$i];
                            $wlworkflow->follows()->save($follow);
                        }
                    }
                }

                DB::commit();
                return $wlworkflow->load(['wlworkflow_type', 'wlphase', 'admins', 'members', 'follows', 'wldoctypes']);
            } catch (\Throwable $th) {
                DB::rollback();

                $this->errors = $th->getMessage();
                return null;
            }
        }


        return null;
    }
    public static function copy($workflow_id, $type = 0)
    {
        $old_wl =  Wlworkflow::find($workflow_id);

        if ($old_wl) {
            try {
                DB::beginTransaction();

                $user = auth()->user();

                $new_wl = $old_wl->replicate();
                $new_wl->type = $type;
                if ($type == 0) {
                    $new_wl->name = $new_wl->name . " (copy)";
                }
                $new_wl->original_id = $old_wl->id;
                $new_wl->save();

                if ($new_wl) {
                    //TEAM COPY
                    if ($old_wl->approve_team) {
                        $newTeamID = ApproveRoutingHelper::createTeamFrom($old_wl->approve_team);

                        if ($newTeamID > 0) {
                            $new_wl->approve_team = $newTeamID;
                            $new_wl->save();
                        }
                    }

                    //USER ADMIN                       
                    if ($old_wl->admins) {
                        $admins  = $old_wl->admins;
                        foreach ($admins as  $ad) {
                            $admin = new WlworkflowAdmin;
                            $admin->user_id = $ad->user_id;
                            $new_wl->admins()->save($admin);
                        }
                    }
                    //USER MEMBERS

                    if ($old_wl->members) {
                        $members =  $old_wl->members;
                        foreach ($members as   $mem) {
                            $member = new WlworkflowAdmin;
                            $member->user_id = $mem->user_id;
                            $new_wl->admins()->save($member);
                        }
                    }

                    //USER follows

                    if ($old_wl->follows) {
                        $follows = $old_wl->follows;
                        foreach ($follows as   $fo) {
                            $follow = new WlworkflowAdmin;
                            $follow->user_id = $fo->user_id;
                            $new_wl->admins()->save($follow);
                        }
                    }
                    $document_types = Wldoctype::where('wlworkflow_id', $old_wl->id)->get();
                    if ($document_types) {
                        foreach ($document_types as $doctype) {
                            $wldoctype = new Wldoctype();
                            $wldoctype->document_type_id = $doctype->document_type_id;
                            $wldoctype->wlworkflow_id = $new_wl->id;
                            $wldoctype->save();
                        }
                    }
                    //Copy control user nhập - form tạo

                    if ($old_wl->wldataobjects) {
                        $wldataobjects = $old_wl->wldataobjects;
                        foreach ($wldataobjects as   $old_dataobject) {
                            if ($old_dataobject->wlphase_id == null) {
                                $new_dataobject =  $old_dataobject->replicate();
                                $new_dataobject->wlphase_id = null;
                                $new_dataobject->wlworkflow_id = $new_wl->id;
                                $new_dataobject->refer_id  = $old_dataobject->id;
                                $new_dataobject->save();
                                //copy dataobject items
                                if ($old_dataobject->items) {
                                    $items = $old_dataobject->items;
                                    foreach ($items as  $old_item) {
                                        $new_item = $old_item->replicate();
                                        $new_item->wldataobject_id = $new_dataobject->id;
                                        $new_item->refer_id  = $old_item->id;
                                        $new_item->save();
                                    }
                                }
                            }
                        }
                    }

                    //

                    //copy Giai đoạn 
                    $wlphases = $old_wl->wlphase;

                    foreach ($wlphases as  $old_phase) {
                        $new_phase = $old_phase->replicate();
                        $new_phase->wlworkflow_id = $new_wl->id;
                        $new_phase->save();

                        //USER ADMIN                       
                        if ($old_phase->admins) {
                            $admins  = $old_phase->admins;
                            foreach ($admins as  $ad) {
                                $admin = new WlworkflowAdmin;
                                $admin->user_id = $ad->user_id;
                                $new_phase->admins()->save($admin);
                            }
                            if ($old_phase->addOwnerToPhaseAdmins) {
                                $admin = new WlworkflowAdmin;
                                $admin->user_id = $user->id;
                                $new_phase->admins()->save($admin);
                            }
                        }
                        //USER MEMBERS

                        if ($old_phase->members) {
                            $members =  $old_phase->members;
                            foreach ($members as   $mem) {
                                $member = new WlworkflowAdmin;
                                $member->user_id = $mem->user_id;
                                $new_phase->admins()->save($member);
                            }
                        }
                        //USER follows

                        if ($old_phase->follows) {
                            $follows = $old_phase->follows;
                            foreach ($follows as   $fo) {
                                $follow = new WlworkflowAdmin;
                                $follow->user_id = $fo->user_id;
                                $new_phase->admins()->save($follow);
                            }
                        }

                        //Copy Jobs
                        if ($old_phase->wljobs) {
                            $old_new_job_mapping = array();

                            $wljobs = $old_phase->wljobs;
                            foreach ($wljobs as $old_job) {
                                $new_job =  $old_job->replicate();
                                $new_job->wlphase_id = $new_phase->id;
                                $new_job->save();
                                $old_id = $old_job->id;
                                $new_id = $new_job->id;
                                $old_new_job_mapping[$old_id] = $new_id;

                                if ($old_job->wldataobjects) {
                                    $wldataobjects = $old_job->wldataobjects;
                                    foreach ($wldataobjects as   $old_dataobject) {
                                        $new_dataobject =  $old_dataobject->replicate();
                                        $new_dataobject->wlphase_id = $new_phase->id;
                                        $new_dataobject->wlworkflow_id = $new_phase->wlworkflow_id;
                                        $new_dataobject->wljob_id = $new_job->id;
                                        $new_dataobject->refer_id  = $old_dataobject->id;
                                        $new_dataobject->save();
                                        //copy dataobject items
                                        if ($old_dataobject->items) {
                                            $items = $old_dataobject->items;
                                            foreach ($items as  $old_item) {
                                                $new_item = $old_item->replicate();
                                                $new_item->wldataobject_id = $new_dataobject->id;
                                                $new_item->save();
                                            }
                                        }
                                    }
                                }

                                if ($old_job->dependencies) {
                                    foreach ($old_job->dependencies as $current_dependency) {
                                        //copy dependency
                                        $new_dependency =  $current_dependency->replicate();
                                        $new_dependency->jobid = $old_new_job_mapping[$current_dependency->jobid];
                                        $new_dependency->dependjobid = $old_new_job_mapping[$current_dependency->dependjobid];
                                        $new_dependency->save();
                                    }
                                }
                            }

                            foreach ($wljobs as $old_job) {
                                if ($old_job->navigates) {
                                    foreach ($old_job->navigates as $current_navigate) {
                                        //copy dependency
                                        $new_navigate =  $current_navigate->replicate();

                                        $new_navigate->job_id = $old_new_job_mapping[$current_navigate->job_id];
                                        $new_navigate->navigate_job_id = $old_new_job_mapping[$current_navigate->navigate_job_id];
                                        $new_navigate->save();
                                    }
                                }
                            }
                        }

                        //Copy dataobject
                        if ($old_phase->wldataobjects) {
                            $wldataobjects = $old_phase->wldataobjects;
                            foreach ($wldataobjects as   $old_dataobject) {
                                if ($old_dataobject->wljob_id == null) {
                                    $new_dataobject =  $old_dataobject->replicate();
                                    $new_dataobject->wlphase_id = $new_phase->id;
                                    $new_dataobject->wlworkflow_id = $new_phase->wlworkflow_id;
                                    $new_dataobject->refer_id  = $old_dataobject->id;
                                    $new_dataobject->save();
                                    //copy dataobject items
                                    if ($old_dataobject->items) {
                                        $items = $old_dataobject->items;
                                        foreach ($items as  $old_item) {
                                            $new_item = $old_item->replicate();
                                            $new_item->wldataobject_id = $new_dataobject->id;
                                            $new_item->save();
                                        }
                                    }
                                }
                            }
                        }
                    }

                    //đặt lại thuộc tính index_after_by

                    $childs = $new_wl->wldataobjects->whereNotNull('index_after_by');

                    foreach ($childs as   $child) {

                        $obj = $new_wl->wldataobjects->where('refer_id', $child->index_after_by)->first();
                        if ($obj) {
                            $child->index_after_by = $obj->id;
                            $child->save();
                        }
                    }

                    //Giai đoạn đầu tiền

                    if ($new_wl->wlphase) {
                        $wlphase =   $new_wl->wlphase->sortBy('index')->first();
                        //$wlphase =  $new_wl->wlphase->first();
                        if ($wlphase) {
                            $new_wl->current_phase =  $wlphase->id;
                            $new_wl->save();
                        }
                    }
                }
                DB::commit();

                return $new_wl;
            } catch (\Throwable $th) {
                DB::rollback();
                dd($th);
                //$this->errors = $th->getMessage();
                return null;
            }
        }

        return null;
    }
    public function destroy($id)
    {
        try {

            $wlworkflow = Wlworkflow::findOrFail($id);
            foreach ($wlworkflow->admins as $value) {
                $value->delete();
            }
            foreach ($wlworkflow->members as $value) {
                $value->delete();
            }
            foreach ($wlworkflow->follows as $value) {
                $value->delete();
            }
            foreach ($wlworkflow->wlphase as $value) {
                $phaseProcess = new PhaseProcess(new Request());
                $phaseProcess->destroy($value->id);
            }

            foreach ($wlworkflow->wldataobjects as $value) {
                if ($value->wlphase_id == null) {
                    $value->delete();
                }
            }
            foreach ($wlworkflow->wldoctypes as $wldoctype) {
                $wldoctype->delete();
            }
            $wlworkflow->delete();

            return true;
        } catch (\Throwable $th) {

            throw $th;
        }

        return false;
    }
}
