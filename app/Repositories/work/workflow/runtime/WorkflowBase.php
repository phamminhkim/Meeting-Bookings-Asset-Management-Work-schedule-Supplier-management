<?php

namespace  App\Repositories\work\workflow\runtime;

use App\Jobs\Workflows\RemindJobsAfterCompletePhase;
use App\Models\shared\DocumentType;
use App\Models\shared\File;
use App\Models\shared\Team;
use App\Models\shared\Group;
use App\Models\work\workflow\runtime\WlworkflowDoc;
use App\Models\work\workflow\Wldataobject;
use App\Models\work\workflow\Wljob;
use App\Models\work\workflow\WljobDependency;
use App\Models\work\workflow\Wlphase;
use App\Models\work\workflow\Wlworkflow;
use App\Models\work\workflow\WlworkflowAdmin;
use App\Models\work\workflow\WlworkflowJobType;
use App\Repositories\approve\ApproveRoutingHelper;
use App\Repositories\HtmlAtrtibute;
use App\Repositories\work\workflow\define\WlJobProcess;
use App\Repositories\work\workflow\define\DataObjectProcess;
use App\Ultils\Ultils;
use App\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use SoareCostin\FileVault\Facades\FileVault as FacadesFileVault;
use Illuminate\Support\Str;

class WorkflowBase extends WorkflowAbs
{
    public  function getLayout()
    {
        $this->layout = new HtmlAtrtibute();
        //lấy phase init 
        // dd( $this->wlworkflow);



        if ($this->wlworkflow) {

            // $wlphase = $this->wlworkflow->wlphase->where('index',-999)->first();

            if ($this->wlworkflow->approve_type === 1) {
                $control = new HtmlAtrtibute();
                $this->layout->add_user = $control;
            }

            $dataobjs  = $this->wlworkflow->wldataobjects;

            foreach ($dataobjs as  $obj) {
                if ($obj->wlphase_id == null) {

                    $control = new HtmlAtrtibute();
                    $control->require_validation = $obj->require == 1 ? true : false;
                    $control->is_read_only = $obj->read_only == 1 ? true : false;
                    $control->has_custom_label = true;
                    $control->custom_label_text = $obj->name;
                    $control_id =  'wlcontrol_' .  $this->wlworkflow->id . '_00_' . $obj->id;
                    $control->control_id = $control_id;
                    $this->layout->{$control_id} = $control;
                }
            }

            // dd($this->layout);
        }
        return $this->layout;
    }
    public function getInitialControls()
    {
        $this->controls = array();

        if ($this->wlworkflow) {
            $object_list = Wldataobject::where('wlworkflow_id', $this->wlworkflow->id)
                ->where('wlphase_id', null)
                ->where('wljob_id', null)
                ->orderBy('order')->with(['items'])->get();
            //sắp xếp
            $object_list = $this->orderDataObjectList($object_list);

            foreach ($object_list as $object) {
                $control = $this->convertObjectToControlValue($object, true);

                array_push($this->controls, $control);
            }
        }

        return $this->controls;
    }
    public function getJobReports($wlphase_id, $user_id)
    {
        $this->jobs = array();
        if ($wlphase_id) {
            $wlphase = Wlphase::find($wlphase_id);
            $wlphase->load(['wljobs']);
            foreach ($wlphase->wljobs as $wljob) {
                // Kiểm tra job này có bị điều phối ko
                // Nếu có chịu điều phối, thì đã được approve điều phối hay chưa
                // Nếu chưa thì không hiển thị ra

                $wljob = $this->getJobFullData($wljob, $user_id);

                array_push($this->jobs, $wljob);
            }
        }

        return $this->jobs;
    }
    public function getJobFullData($job, $user_id)
    {
        $job->load(['dependencies', 'navigates', 'details']);
        $job->is_available = !$job->navigated_by_job || $job->is_navigated;

        if (!$job->action_user && $job->is_action_user_by_ref) {
            $job->action_user = (int)$this->getWorkflowValue($this->wlworkflow->id, $job->action_user_path_ref);
        }
        if ($job->action_user) {
            $job->load(['user']);
        }
        if (!$job->assign_user && $job->is_assign_user_by_ref) {
            $job->assign_user = (int) $this->getWorkflowValue($this->wlworkflow->id, $job->assign_user_path_ref);
        }
        if ($job->assign_user) {
            $job->load(['assigning_user']);
        }
        if ($this->canSeeThisJob($job, $user_id)) {
            $jobObjects = $this->getAllObjectsByJob($job->id);

            $job->viewable = true;
            $job->reports = $this->convertObjectsToControls($jobObjects, true);
            $job->datedue = WlJobProcess::getDateDue($job->id);
            $job->undone_jobs = $this->getDependencyUndoneJobs($job);
            $job->actions = $this->checkJobPrivilegesStatus($job, $user_id);
        } else {
            $job->viewable = false;
            $job->reports = [];
            $job->undone_jobs = [];
            $job->actions = [];
        }

        return $job;
    }
    public function getAllObjectsByJob($job_id)
    {
        $object_list = Wldataobject::where('wljob_id', $job_id)->orderBy('order')->with(['items'])->get();

        $object_list = $this->orderDataObjectList($object_list);

        return $object_list;
    }
    public function convertObjectsToControls($objectList, $get_reference_value)
    {
        $reports = array();

        foreach ($objectList as $object) {
            $control = $this->convertObjectToControlValue((object)$object, $get_reference_value);

            array_push($reports, $control);
        }

        return $reports;
    }

    public function convertObjectToControlValue($object, $get_reference_value)
    {
        //dd($object->require);
        $control = new HtmlAtrtibute();
        $control->id = $object->id;
        $control->unique_name = $object->unique_name;
        $control->require_validation = $object->require ? true : false;
        $control->is_read_only = $object->read_only == 1 ? true : false;
        $control->has_custom_label = true;
        $control->custom_label_text = $object->name;
        $control->control_id = 'wlcontrol_' .  $object->wlworkflow_id . '_00_' . $object->id;
        $control->type = $object->wldatatype_id . "";
        $control->items = $object->items;

        $control->description = $object->description;

        switch ($control->type) {
            case Ultils::$CONTROL_TYPE['USER']: //Trường người dùng
            case Ultils::$CONTROL_TYPE['NUMBER']: //Trường hợp số
                $control->value = null;
                if ($object->value_num > 0) {
                    $control->value = strval($object->value_num);
                }
                break;
            case Ultils::$CONTROL_TYPE['USERS']: //Trường hợp chọn nhiều
                $control->value_list = [];
                foreach ($control->items as $value) {
                    if (isset($value['select']) && $value->select == 'X') {
                        array_push($control->value_list, (int)$value->value);
                    }
                }
                break;

            case Ultils::$CONTROL_TYPE['LIST']: //Trường hợp chọn nhiều
                $control->value_list = [];
                foreach ($control->items as $value) {
                    if (isset($value['select']) && $value->select == 'X') {
                        array_push($control->value_list, $value->id);
                    }
                }
                break;
            case Ultils::$CONTROL_TYPE['DATE']:
                $control->value = $object->value_date;
                break;

            case Ultils::$CONTROL_TYPE['TIME']:
                $control->value = $object->value_time;
                break;

            case Ultils::$CONTROL_TYPE['BOOLEAN']:
                $control->value = $object->value_boolean;
                break;

            case Ultils::$CONTROL_TYPE['APPROVE']:
                $control->value = $object->value_boolean;
                $control->subvalue = $object->value_date . ' ' . $object->value_time;
                break;

                break;
            case Ultils::$CONTROL_TYPE['FILE']: //Trường hợp upload file
                if ($object->attachment_file) {
                    $control->attachment_file = $object->attachment_file;
                }
                break;

            default:
                $control->value = $object->value;
                break;
        }

        if ($object->reference_path && $get_reference_value) {
            if (
                $control->type == Ultils::$CONTROL_TYPE['USERS'] ||
                $control->type == Ultils::$CONTROL_TYPE['LIST']
            ) {
                if (!$control->value_list) {
                    $current_value_list = array();
                    $control->value_list = $this->getWorkflowValue($object->wlworkflow_id, $object->reference_path);
                    foreach ($control->value_list as $value) {
                        foreach ($control->items as $item) {
                            if ($item->name === $value) {
                                array_push($current_value_list, $item->id);
                                break;
                            }
                        }
                    }
                    $control->value_list = $current_value_list;
                }
            } else if (!$control->value) {
                $control->value = $this->getWorkflowValue($object->wlworkflow_id, $object->reference_path);
            }
        }

        return $control;
    }
    public function getDependencyUndoneJobs($wljob)
    {
        $undone_jobs = array();
        if (count($wljob->dependencies) > 0) {
            $depend_job_ids = $wljob->dependencies->pluck('dependjobid')->flatten();

            $list_undone_jobs = Wljob::whereIn('id', $depend_job_ids)->where('is_completed', false)->get();
            $list_unnavigated_jobs = array_column($this->getUnnavigatedJobs($list_undone_jobs), 'id');

            return $list_undone_jobs->filter(function ($item) use ($list_unnavigated_jobs) {
                return !in_array($item->id, $list_unnavigated_jobs);
            });
        }

        return $undone_jobs;
    }
    public function getUnnavigatedJobs($list_undone_jobs)
    {
        $list_unnavigated_jobs = array();

        foreach ($list_undone_jobs as $job) {
            // Kiểm tra xem job này có bị navigate ko
            if ($job->navigated_by_job) {
                // Nếu có navigate thì check job navigate đã hoàn thành chưa
                $navigator_job = Wljob::find($job->navigated_by_job->job_id);
                if ($navigator_job->is_completed && $job->is_navigated == false) {
                    // Nếu job điều phối đã hoàn thành & job hiện tại không được chọn để navigate thì thêm nó vào unavailable list
                    array_push($list_unnavigated_jobs, $job);
                }
            }
        }

        return $list_unnavigated_jobs;
    }

    public function hasAnyJobCompletedDependenciesOn($wljob)
    {
        $depend_jobs = WljobDependency::where('dependjobid', $wljob->id)->get();

        if (count($depend_jobs) > 0) {
            $depend_job_ids = array();
            foreach ($depend_jobs as $job) {
                array_push($depend_job_ids, $job['jobid']);
            }

            $done_jobs = Wljob::whereIn('id', $depend_job_ids)->where('is_completed', true)->get();
            if ($done_jobs && count($done_jobs) > 0) {
                return true;
            }
        }

        return false;
    }
    public function canSeeThisJob($wljob, $user_id)
    {
        if ($wljob->private_job == 0)
            return true;

        $phase = Wlphase::find($wljob->wlphase_id);
        $phase->load(['admins', 'members']);

        //Người thực hiện có thể thấy private job
        if ($wljob->action_user == $user_id) {
            return true;
        }

        //Admin có thể thấy private job
        foreach ($phase->admins as $admin) {
            if ($admin->user_id == $user_id) {
                return true;
            }
        }

        return false;
    }

    public function checkJobPrivilegesStatus($wljob, $user_id)
    {
        //Mặc định sẽ không có action nào
        $status = array();
        $status['can_view_report'] = false;
        $status['can_withdraw_job'] = false;
        $status['can_submit_job'] = false;
        $status['can_approve_with_dependencies_job'] = false;
        $status['can_approve_no_dependencies'] = false;
        $status['can_assign_user'] = false;
        $status['can_unassign_user'] = false;
        $status['can_self_assign_job'] = false;
        $status['can_abandon_job'] = false;
        $status['can_navigate_job'] = false;

        $phase = Wlphase::find($wljob->wlphase_id);
        $workflow = Wlworkflow::find($phase->wlworkflow_id);

        // Chỉ có thể tương tác các công việc trong phase hiện tại
        if ($workflow && $workflow->current_phase == $phase->id) {
            $phase->load(['admins', 'members']);

            // Công việc có báo cáo
            if ($wljob->job_type_id == 1) {
            } else if ($wljob->job_type_id == 2) { //Công việc xác nhận

            }

            foreach ($phase->admins as $admin) {
                if ($admin->user_id == $user_id) {
                    if (!$wljob->is_completed) {
                        //Admin phase có thể chỉ định người làm job
                        if ($wljob->allow_admin_assign_user) {
                            $status['can_assign_user'] = true;

                            if ($wljob->action_user && $wljob->action_user != $user_id) {
                                $status['can_unassign_user'] = true;
                            }
                        }

                        $status['can_self_assign_job'] = true;
                    } else {
                        //Admin phase có thể hủy bỏ công việc đã hoàn thành
                        if ($wljob->allow_admin_withdraw_job) {
                            $status['can_withdraw_job'] = true;
                        }
                    }
                }
            }

            foreach ($phase->members as $member) {
                if ($member->user_id == $user_id) {
                    if (!$wljob->is_completed && $wljob->allow_self_assign_job) {
                        //Config cho phép tự nhận nhiệm vụ & user trong team member
                        $status['can_self_assign_job'] = true;
                    }
                }
            }

            if ($wljob->action_user == $user_id) {
                if (!$wljob->is_completed) {
                    //Chỉ có người đang nhận nhiệm vụ mới có thể hoàn thành công việc
                    if ($wljob->job_type_id == 1) { //Công việc báo cáo
                        $status['can_submit_job'] = true;
                    } else if ($wljob->job_type_id == 2) {
                        if ($wljob->dependencies && count($wljob->dependencies) > 0) {
                            $status['can_approve_with_dependencies_job'] = true;
                        } else {
                            $status['can_approve_no_dependencies'] = true;
                        }
                    } else if ($wljob->job_type_id == 3) {
                        $status['can_navigate_job'] = true;
                    }

                    if ($wljob->allow_abandon_job) {
                        $status['can_abandon_job'] = true;
                    }

                    if ($wljob->allow_user_assign_another_user) {
                        $status['can_assign_user'] = true;
                    }
                } else {
                    if ($wljob->allow_user_withdraw_job) {
                        $status['can_withdraw_job'] = true;
                    }
                }
            }

            //Chắc chắn là nhiệm vụ chưa chỉ định thì mới có thể tự nhận nhiệm vụ
            if ($status['can_self_assign_job']) {
                $status['can_self_assign_job'] = $wljob->action_user == 0;
            }

            //Chắc chắn là không có pending jobs nào chưa hoàn thành
            if ($status['can_submit_job']) {
                $status['can_submit_job'] =  count($wljob->undone_jobs) == 0;
            }

            //Chắc chắn là không có pending jobs nào chưa hoàn thành
            if ($status['can_approve_with_dependencies_job']) {
                $status['can_approve_with_dependencies_job'] =  count($wljob->undone_jobs) == 0;
            }

            if ($status['can_withdraw_job']) {
                $status['can_withdraw_job'] = !$this->hasAnyJobCompletedDependenciesOn($wljob);
            }
            //

        }

        $status['can_view_report'] = $wljob->is_completed;

        //Hiển thị nút function khi có ít nhất 1 quyền
        $status['can_interact_job'] = count($wljob->undone_jobs) == 0 && ($status['can_submit_job'] || $status['can_approve_with_dependencies_job'] ||  $status['can_approve_no_dependencies'] || $status['can_navigate_job'] || $status['can_self_assign_job'] || $status['can_withdraw_job'] || $status['can_abandon_job']);
        $status['can_manage_job'] = $status['can_assign_user'] ||  $status['can_unassign_user'];

        return $status;
    }

    public function isFinishedPhaseReport($report)
    {
        switch ($report->type) {
            case Ultils::$CONTROL_TYPE['LIST']: //Trường hợp chọn nhiều
                $report->value_list = [];
                foreach ($report->items as $value) {
                    if ($value->select == 'X') {
                        array_push($report->value_list, $value->id);
                    }
                }
                if ($report->require_validation)
                    return count($report->value_list) > 0;
                return true;

            case Ultils::$CONTROL_TYPE['APPROVE']:
                if ($report->require_validation)
                    return $report->value != null && $report->subvalue != null;
                return true;

            case Ultils::$CONTROL_TYPE['FILE']: //Trường hợp upload file
                if ($report->require_validation)
                    return count($report->attachment_file) > 0;
                return true;
        }

        if ($report->require_validation) {
            return $report->value != null;
        }
        return true;
    }
    public function updatePhaseStatus($wlphase_id, $is_finished)
    {
        $flag = false;
        //Kiểm tra các cấu hình: có bắt buộc hoàn thành hay phải dợi công việc xong mới có thể hoàn thành
        $wlphase = Wlphase::find($wlphase_id);
        // Trường hợp giai đoạn đầu tiên có duyệt thì cho pass  
        if ($wlphase->index === -999) {
            $is_finished_all_job =  true;
        } else {
            $is_finished_all_job = $this->isReadyToCompletePhase($wlphase_id);
        }

        if ($is_finished_all_job) {
            $wlphase = Wlphase::find($wlphase_id);
            if ($wlphase) {
                $wlphase->finished_date = date('Y-m-d h:i:s');
                $wlphase->save();

                $flag = true;
                $wlworkflow = Wlworkflow::find($wlphase->wlworkflow_id);
                if ($wlworkflow) {
                    //xử lý logic tìm giai đoạn hợp lý theo cấu hình...vvv
                    ////
                    $count = count($wlworkflow->wlphase);
                    $nextPhase = null;
                    $wlphases = $wlworkflow->wlphase->sortBy('index')->values();

                    for ($i = 0; $i < $count; $i++) {
                        $wlphase = $wlphases[$i];


                        if (($count - 1)  ==  $i) //end
                        {
                            $nextPhase = $wlphases[$i];

                            $wlworkflow->finished_phase = $nextPhase->id;
                            $wlworkflow->save();

                            $document = WlworkflowDoc::where('wlworkflow_id', $wlworkflow->id)->first();
                            $document->finished = 1;
                            $document->save();
                            $this->addTimeline($document, 'Hoàn thành quy trình', 'WORKFLOWDONE', $wlworkflow->title, auth()->user());

                            $document_creator = $document->user;

                            $title =  'Hoàn thành ' . Str::lower($wlworkflow->wlworkflow_type->name);
                            $content = Str::lower($wlworkflow->wlworkflow_type->name) . ' của {gender} đã được xử lí xong';
                            $details = [
                                "Yêu cầu" => $document->title,
                                "Ngày yêu cầu" => $document->send_date,
                                "Ngày hoàn thành" => $wlphase->finished_date
                            ];

                            WorkflowEmailGate::sendNoticeWithMail($document_creator, $document, $title, $content, 'WORKFLOWDONE', $details);
                            break;
                        } else {
                            if ($wlphase->id == $wlphase_id) {
                                $interacting_user = User::find(Auth()->user()->id);
                                $nextPhase = $wlphases[$i + 1];
                                $wlworkflow->current_phase = $nextPhase->id;
                                $wlworkflow->save();

                                $wldocument = WlworkflowDoc::where('wlworkflow_id', $wlworkflow->id)->first();
                                $this->addTimeline($wldocument, 'Hoàn thành giai đoạn', 'PHASEDONE', $wlphase->name, auth()->user());
                                //Cập nhật hoàn thành cho giai đoạn cuối
                                if ($nextPhase->index === 999) {
                                    $nextPhase->finished_date = date('Y-m-d h:i:s');
                                    $nextPhase->save();

                                    //cập nhật cho chứng từ
                                    $wldocument->finished = 1;
                                    $wldocument->status = 3;
                                    $wldocument->save();
                                    $this->addTimeline($wldocument, 'Hoàn thành quy trình', 'WORKFLOWDONE', $wldocument->title, auth()->user());

                                    $document_creator = $wldocument->user;
                                    $title = 'Hoàn thành ' . Str::lower($wlworkflow->wlworkflow_type->name);
                                    $icon = 'WORKFLOWDONE';
                                    $content = $wldocument->serial_num;;
                                    $details = [];
                                    WorkflowEmailGate::sendNoticeWithMail($document_creator, $wldocument, $title, $content, 'WORKFLOWDONE', $details);
                                } else {
                                    $reminder = new RemindJobsAfterCompletePhase($wldocument, $nextPhase, $interacting_user);
                                    dispatch($reminder);
                                }
                                break;
                            }
                        }
                    }
                }
            }
        }
        return $flag;
    }
    public function changeWorkflowPhase($wlphase_id)
    {
        $document = WlworkflowDoc::with('user', 'document_type', 'company', 'department',  'team', 'team.users', 'approveds',  'attachment_file', 'timelines', 'shareds', 'shareds.group',  'reminders')->find($this->request->document_id);
        $wlworkflow =  Wlworkflow::find($document->wlworkflow_id);

        $wlworkflow->current_phase = $wlphase_id;
        $wlworkflow->save();

        return $wlworkflow->current_phase == $wlphase_id;
    }
    public function assignJob($job_id, $user_id, $note, $list_ccs)
    {
        try {
            $job = Wljob::find($job_id);
            $action_user = Auth()->user();
            $new_user = User::find($user_id);

            if ($job) {
                $assigning_user = User::find(auth()->user()->id);
                $old_user = $job->action_user ? User::find($job->action_user) : null;

                if (!$old_user || !$new_user || $new_user->id !== $old_user->id) {
                    $job->assign_user = $new_user == null || $assigning_user == null ? null : $assigning_user->id;
                    $job->action_user = $new_user == null ? null : $new_user->id;
                    $job->date_received = $new_user == null ? null : date('Y-m-d h:i:s');
                    $job->note = $new_user == null ? null : $note;
                    $job->save();

                    $job->load('user', 'assigning_user', 'wlphase');
                    $job->undone_jobs = $this->getDependencyUndoneJobs($job);
                    $job->actions = $this->checkJobPrivilegesStatus($job, $action_user->id);

                    $document = WlworkflowDoc::where('wlworkflow_id', $job->wlphase->wlworkflow_id)->first();
                    if ($new_user) {
                        WorkflowEmailGate::sendNewJobNotice($document, $job, $list_ccs);

                        $this->addTimeline($document, 'Chỉ định công việc', 'JOBASSIGNUSER', 'Chỉ định công việc [' . $job->name . '] cho ' . $new_user->name, auth()->user());
                    } else {
                        $this->addTimeline($document, 'Thu hồi công việc', 'JOBUNASSIGNUSER', 'Thu hồi công việc [' . $job->name . '] từ ' . $old_user->name, auth()->user());
                    }

                    return $job;
                } else {
                    $this->message = "Bạn đã đang chỉ định người này thực hiện rồi";
                    return null;
                }
            } else {
                $this->message = "Không tìm thấy công việc";
                return null;
            }
        } catch (\Throwable $th) {
            dd($th);
        }
    }
    public function takeJob($wljob_id)
    {
        $job = Wljob::find($wljob_id);
        $new_user = Auth::user();
        $old_user = User::find($job->action_user);

        if ($job) {
            if ($new_user !== $old_user) {
                $job->action_user = $new_user == null ? null : $new_user->id;
                $job->date_received = $new_user == null ? null : date('Y-m-d h:i:s');
                $job->save();


                $job->load('user', 'assigning_user', 'wlphase');
                $job->undone_jobs = $this->getDependencyUndoneJobs($job);
                $job->actions = $this->checkJobPrivilegesStatus($job, $new_user->id);

                $wlworkflowDoc = WlworkflowDoc::where('wlworkflow_id', $job->wlphase->wlworkflow_id)->first();
                $this->addTimeline($wlworkflowDoc, 'Nhận công việc', 'JOBUNASSIGNUSER', 'Nhận công việc [' . $job->name . ']', auth()->user());
            }
        }

        return $job;
    }
    public function abandonJob($wljob_id)
    {
        $job = Wljob::find($wljob_id);
        $new_user = Auth::user();
        $old_user = User::find($job->action_user);

        if ($job) {
            if ($new_user === $old_user) {
                $job->action_user = null;
                $job->date_received = null;
                $job->save();

                $job->load('user', 'assigning_user', 'wlphase');
                $job->undone_jobs = $this->getDependencyUndoneJobs($job);
                $job->actions = $this->checkJobPrivilegesStatus($job, $new_user->id);

                $wlworkflowDoc = WlworkflowDoc::where('wlworkflow_id', $job->wlphase->wlworkflow_id)->first();
                $this->addTimeline($wlworkflowDoc, 'Từ bỏ công việc', 'JOBUNASSIGNUSER', 'Từ bỏ công việc [' . $job->name . ']', auth()->user());
            }
        }

        return $job;
    }
    public function updateWorkflowApproveSetting($workflowid, $approve_type, $teamid, $teamusers, $sub_approve_type, $sub_approve_value, $default_group)
    {
        $returnTeamID = null;

        $wlworkflow = Wlworkflow::find($workflowid);
        $wlworkflow->approve_type = $approve_type;
        $wlworkflow->sub_approve_type = $sub_approve_type;
        $wlworkflow->sub_approve_value = $sub_approve_value;
        $wlworkflow->default_group = $default_group;

        if ($approve_type == 2) {
            $team = Team::find($teamid);
            if (!$teamid || !$team) {
                $returnTeamID =  $this->createTeamCustom($wlworkflow->wlworkflow_type_id, $wlworkflow->id, 'WORKFLOW');
            } else {
                $returnTeamID = $teamid;
            }
            $this->assign_user($returnTeamID, $teamusers);
            $wlworkflow->approve_team = $returnTeamID;
        } else {

            //Xoá team cũ nếu có cấu hình
            if ($wlworkflow->approve_team !== null) {
                $team = Team::find($wlworkflow->approve_team);
                if ($team) {
                    $team->delete();
                }
            }

            $wlworkflow->approve_team = null;
        }

        $wlworkflow->save();


        return $wlworkflow;
    }
    public function isReadyToCompletePhase($wlphase_id)
    {
        try {
            $wlphase =  Wlphase::with(['admins'])->find($wlphase_id);
            if ($wlphase) {
                $interacting_user = User::find(auth()->user()->id);

                $phase_admins = $wlphase->admins->pluck('user_id')->flatten();
                if ($phase_admins->contains($interacting_user->id)) {
                    $list_undone_jobs = Wljob::where('wlphase_id', $wlphase_id)->where('required_job', 1)->where('is_completed', false)->get();

                    $list_unnavigated_jobs = $this->getUnnavigatedJobs($list_undone_jobs);

                    return count($list_undone_jobs) - count($list_unnavigated_jobs) == 0;
                }
            }
        } catch (\Throwable $th) {
            dd($th);
        }

        return false;
    }


    //các hàm override
    protected function getTeam($obj)
    {
        $final_team_id = null;
        $workflow = Wlworkflow::find($this->request->wlworkflow_id);

        $workflow_team_id = null;
        if ($workflow->approve_type === 1) {
            if (!$obj || !$obj->team_id) {
                $workflow_team_id =  $this->createTeam();
            } else {
                $workflow_team_id = $obj->team_id;
            }

            $this->assign_user($workflow_team_id, $this->request->team_users);
        } else if ($workflow->approve_type === 2) {
            $workflow_team_id = $workflow->approve_team;
        }

        $sub_team_id = null;
        if ($workflow->sub_approve_type === 1) {
            $group = Group::find($this->request->group_id);

            $defaultTeamName = 'C' . $group->company_id . '_' . 'D' . $group->department_id;
            if ($group->workshop_id) {
                $defaultTeamName = $defaultTeamName . '_' . 'W' . $group->workshop_id;
            }
            if ($group->party_id) {
                $defaultTeamName = $defaultTeamName . '_' . 'P' . $group->party_id;
            }
            $defaultTeamName = $defaultTeamName . '_DefaultTeam';

            $sub_team = Team::where('name', $defaultTeamName)->first();
            $sub_team_id = $sub_team->id;
        } else if ($workflow->sub_approve_type === 2) {
            $group = Group::find($workflow->sub_approve_value);

            $defaultTeamName = 'C' . $group->company_id . '_' . 'D' . $group->department_id;
            if ($group->workshop_id) {
                $defaultTeamName = $defaultTeamName . '_' . 'W' . $group->workshop_id;
            }
            if ($group->party_id) {
                $defaultTeamName = $defaultTeamName . '_' . 'P' . $group->party_id;
            }
            $defaultTeamName = $defaultTeamName . '_DefaultTeam';

            $sub_team = Team::where('name', $defaultTeamName)->first();
            $sub_team_id = $sub_team->id;
        }

        if ($sub_team_id) {
            $final_team_id = ApproveRoutingHelper::mergeTwoTeam($sub_team_id, $workflow_team_id);
        } else {
            $final_team_id = $workflow_team_id;
        }

        return $final_team_id;
    }

    public function getWorkflowStructure($workflow_id)
    {
        $workflow = Wlworkflow::find($workflow_id);
        if ($workflow) {
            $workflow->load(['wlphase']);

            $workflow_struct = array();
            $workflow_struct['workflow_id'] = $workflow->id;
            $workflow_struct['name'] = $workflow->name;

            if ($workflow->type == 1) {
                $document = WlworkflowDoc::where('wlworkflow_id', $workflow_id)->first();
                if ($document) {
                    $workflow_struct['user_id'] = $document->user_id;
                    $workflow_struct['created_at'] = $document->created_at;

                    // Đã chạy quy trình
                    if ($document->status >= 2) {
                        // Không có cây duyệt => Quy trình tự động chạy ngay khi gửi
                        // => Ngày bắt đầu = ngày gửi duyệt
                        if (!$document->team_id) {
                            $workflow_struct['started_at'] = $document->send_date;
                        } else {
                            // Có cây duyệt, load cây duyệt lên
                            $document->load('approveds');
                            // Kiểm tra người duyệt cuối cùng duyệt lúc nào
                            if (count($document->approveds) > 0) {
                                $final_approve = $document->approveds[count($document->approveds) - 1];
                                $workflow_struct['started_at'] = $final_approve->checkout;
                            } else {
                                $workflow_struct['started_at'] = null;
                            }
                        }
                    } else {
                        $workflow_struct['started_at'] = null;
                    }
                }
            } else {
                $workflow_struct['user_id'] = null;
                $workflow_struct['created_at'] = null;
                $workflow_struct['started_at'] = null;
            }

            if ($workflow->current_phase) {
                $workflow->load(['currentPhase']);
                $workflow_struct['current_phase'] = $workflow->currentPhase ? $workflow->currentPhase->unique_name : null;
            } else {
                $workflow_struct['current_phase'] = null;
            }

            $workflow_dataobjects = Wldataobject::where('wlworkflow_id', $workflow_id)->where('wljob_id', null)->get();
            $workflow_struct['controls'] = $this->convertObjectsToStructs($workflow_dataobjects);

            $workflow_struct['phases'] = array();
            foreach ($workflow->wlphase as $phase) {
                $phase->load(['wljobs']);

                $phase_struct = array();
                $phase_struct['phase_id'] = $phase->id;
                $phase_struct['name'] = $phase->name;
                //$phase_struct['unique_name'] = $phase->unique_name;

                $phase_struct['jobs'] = array();
                foreach ($phase->wljobs as $job) {
                    $job->load(['wldataobjects']);

                    $job_struct = array();
                    $job_struct['job_id'] = $job->id;
                    $job_struct['name'] = $job->name;
                    $job_struct['action_user'] = $job->action_user;
                    //$job_struct['unique_name'] = $job->unique_name;

                    $job_struct['controls'] = $this->convertObjectsToStructs($job->wldataobjects);

                    $phase_struct['jobs'][$job->unique_name] = $job_struct;
                }

                $workflow_struct['phases'][$phase->unique_name] = $phase_struct;
            }

            return $workflow_struct;
        }
        return null;
    }

    public function convertObjectsToStructs($wldataobjects)
    {
        $structs = array();

        $controls = $this->convertObjectsToControls($wldataobjects, false);
        foreach ($controls as $control) {
            $struct = array();
            $struct['control_id'] = $control->id;
            //$struct['unique_name'] =  $control->unique_name;
            $struct['label_text'] =  $control->custom_label_text;

            if ($control->value !== "") {
                $struct['value'] =  $control->value;
            }
            // if (isset($control->value_list)) {
            //     $struct['value_list'] =  $control->value_list;
            // }
            if (count($control->items) > 0) {
                $values = array();
                $options = array();
                $index = 0;
                foreach ($control->items as $item) {
                    $option = array();
                    //$option['option_id'] = $item->id;
                    //$option['unique_name'] =  $item->name;
                    //$option['value'] =  $item->value;

                    $options[$index++] = $item;

                    if (isset($control->value_list) && in_array($item->id, $control->value_list)) {
                        array_push($values, $item->name);
                    }
                }
                $struct['value_options'] =  $options;
                $struct['value'] =  $values;
            }

            $structs[$control->unique_name] = $struct;
        }

        return $structs;
    }

    public function getWorkflowValue($workflow_id, $value_path)
    {
        try {
            $workflow_struct = $this->getWorkflowStructure($workflow_id);

            if ($workflow_struct) {
                // Tách path thành mảng, phân tách bằng dấu .
                $array_path = explode(".", $value_path);
                // Item đầu tiên bắt buộc phải là workflow. 
                if ($array_path[0] == "workflow") {
                    //Remove item đầu tiên
                    array_splice($array_path, 0, 1);

                    // Lấy cả workflow trả về khi không có tham chiếu phụ
                    if (count($array_path) == 0)
                        return $workflow_struct;

                    $current_index = 0;
                    $current_value = $workflow_struct;

                    // Duyệt từng phần tử trong path
                    while (true) {
                        $current_path_name = $array_path[$current_index];

                        // Nếu phần tử là một array (truy xuất bằng dấu [])
                        if (preg_match('~\["(.*?)\"]~', $current_path_name, $match) == 1) {
                            $array_path_name = str_replace($match[0], "", $current_path_name);
                            $array_index = $match[1];

                            if (isset($current_value[$array_path_name]) && isset($current_value[$array_path_name][$array_index])) {
                                $current_value = $current_value[$array_path_name][$array_index];
                            } else {
                                return null;
                            }
                        } else {
                            if (isset($current_value[$current_path_name])) {
                                $current_value = $current_value[$current_path_name];
                            } else {
                                return null;
                            }
                        }

                        // Đã duyệt xong
                        if ($current_index >= count($array_path) - 1) {
                            return $current_value;
                        } else if ($current_value !== null && gettype($current_value) == "array") {
                            $current_index++;
                        } else break;
                    }
                }
            }
            return null;
        } catch (\Throwable $th) {
            dd($th->getMessage());
            return null;
        }
    }

    public function getWorkflowValue_2($workflow_id, $value_path)
    {
        try {
            // Tách path thành mảng, phân tách bằng dấu .
            $array_path = explode(".", $value_path);
            // Item đầu tiên bắt buộc phải là workflow. 
            if ($array_path[0] == "workflow") {
                $workflow = Wlworkflow::find($workflow_id);
                if ($workflow) {
                    //Remove item đầu tiên
                    array_splice($array_path, 0, 1);

                    // Lấy cả workflow trả về khi không có tham chiếu phụ
                    if (count($array_path) == 0)
                        return $this->getWorkflowStructure($workflow_id);

                    $current_index = 0;
                    $current_value = $workflow;

                    // Duyệt từng phần tử trong path
                    while (true) {
                        $current_path_name = $array_path[$current_index];
                        dd($current_path_name);

                        // Nếu phần tử là một array (truy xuất bằng dấu [])
                        if (preg_match('~\["(.*?)\"]~', $current_path_name, $match) == 1) {
                            $array_path_name = str_replace($match[0], "", $current_path_name);
                            $array_index = $match[1];

                            if (isset($current_value[$array_path_name]) && isset($current_value[$array_path_name][$array_index])) {
                                $current_value = $current_value[$array_path_name][$array_index];
                            } else {
                                return null;
                            }
                        } else {
                            switch ($current_path_name) {
                                case 'workflow_id':
                                    return $workflow->id;
                                case 'name':
                                    return $workflow->id;
                                case 'user_id': {
                                        $document = WlworkflowDoc::where('wlworkflow_id', $workflow_id)->first();
                                        if ($document) {
                                            return $document->user_id;
                                        }
                                        return null;
                                    }
                                case 'current_phase': {
                                        if ($workflow->current_phase) {
                                            $workflow->load(['currentPhase']);
                                            return $workflow->currentPhase ? $workflow->currentPhase->unique_name : null;
                                        }
                                        return null;
                                    }
                            }

                            $workflow_dataobjects = Wldataobject::where('wlworkflow_id', $workflow_id)->where('wljob_id', null)->get();
                            $workflow_struct['controls'] = $this->convertObjectsToStructs($workflow_dataobjects);

                            $workflow_struct['phases'] = array();
                        }

                        // Đã duyệt xong
                        if ($current_index >= count($array_path) - 1) {
                            return $current_value;
                        } else if ($current_value !== null && gettype($current_value) == "array") {
                            $current_index++;
                        } else break;
                    }
                }
            }
        } catch (\Throwable $th) {
            dd($th->getMessage());
            return null;
        }
    }
}
