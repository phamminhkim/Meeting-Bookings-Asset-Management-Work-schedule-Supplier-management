<?php

namespace App\Http\Controllers\api\work\workflow\runtime;

use App\Http\Controllers\Controller;
use App\Repositories\work\workflow\runtime\WorkflowMain;
use Illuminate\Http\Request;
use App\Models\work\workflow\runtime\WlworkflowDoc;
use App\Models\work\workflow\Wlworkflow;
use App\Ultils\Ultils;
use App\Http\Controllers\BaseController\BaseController;
use App\Http\Controllers\frontend\work\workflow\runtime\WorkRuntimeFrontend;
use App\Policies\WorkflowPolicy;
use App\User;

class WorkflowController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $current_workflow_code = $request->current_workflow_code;
        $this->authorize('view', [new WlworkflowDoc(), $current_workflow_code]);

        $document_base = WorkflowMain::create($request);
        $return_workflows = $document_base->index();

        return $this->sendResponse($return_workflows);
    }
    public function submitReport(Request $request, $id)
    {
        $documentBase = WorkflowMain::create($request);
        $updatedReportObject = $documentBase->submitReport($id, $request);

        $result = array();
        if ($updatedReportObject) {
            $control = $documentBase->convertObjectToControlValue($updatedReportObject, true);
            $control->iscompleted = $documentBase->isFinishedPhaseReport($control);

            $result['success'] = 1;
            $result['data'] = $control;
            $result['is_ready_to_complete_phase']  = $documentBase->isReadyToCompletePhase($updatedReportObject->wlphase_id);
        } else {
            $result['success'] = 0;
            $result['errors'] = $documentBase->errors;
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    public function submitReports(Request $request)
    {
        $documentBase = WorkflowMain::create($request);
        $returnJobs = $documentBase->submitReports();

        $result = array();
        if ($returnJobs) {
            $result['success'] = 1;
            $result['data'] = $returnJobs;
            $result['is_ready_to_complete_phase']  = $documentBase->isReadyToCompletePhase($request->wlphase_id);
        } else {
            $result['success'] = '0';
            $result['errors'] = $documentBase->errors;
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    public function submitApprovements(Request $request)
    {
        $documentBase = WorkflowMain::create($request);
        $returnJobs = $documentBase->submitApprovements();

        $result = array();
        if ($returnJobs) {
            $result['success'] = 1;
            $result['data'] = $returnJobs;
            $result['is_ready_to_complete_phase']  = $documentBase->isReadyToCompletePhase($request->wlphase_id);
        } else {
            $result['success'] = '0';
            $result['errors'] = $documentBase->errors;
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    public function sendApprovement(Request $request)
    {
        $documentBase = WorkflowMain::create($request);
        $returnJobs = $documentBase->sendApprovement($request->is_accepted);

        $result = array();
        if ($returnJobs) {
            $result['success'] = 1;
            $result['data'] = $returnJobs;
            $result['is_ready_to_complete_phase']  = $documentBase->isReadyToCompletePhase($request->wlphase_id);
        } else {
            $result['success'] = '0';
            $result['errors'] = $documentBase->errors;
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

    public function createNewJob(Request $request)
    {
        $documentBase = WorkflowMain::create($request);
        $job = $documentBase->createNewJob();

        $result = array();
        if ($job) {
            $result['success'] = 1;
            $result['data'] = $job;
        } else {
            $result['success'] = 0;
            $result['errors'] = $documentBase->errors;
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    public function navigatingJobs(Request $request)
    {
        $documentBase = WorkflowMain::create($request);
        $returnJobs = $documentBase->navigatingJobs();

        $result = array();
        if ($returnJobs) {
            $result['success'] = 1;
            $result['data'] = $returnJobs;
            $result['is_ready_to_complete_phase']  = $documentBase->isReadyToCompletePhase($request->wlphase_id);
        } else {
            $result['success'] = '0';
            $result['errors'] = $documentBase->errors;
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    public function sendNewResponse(Request $request)
    {
        $documentBase = WorkflowMain::create($request);
        $job = $documentBase->sendNewResponse();

        $result = array();
        if ($job) {
            $result['success'] = 1;
            $result['data'] = $job;
        } else {
            $result['success'] = 0;
            $result['errors'] = $documentBase->errors;
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('create', [new WlworkflowDoc(), $request->wlworkflow_type_id]);
        //Khởi tạo qui trình
        //1. Tạo phiếu chạy qui trình
        //2. Gửi : id qui trình mẫu
        //2.1 Các user trong giai đoạn tiếp theo sẽ nhận email.

        $documentBase = WorkflowMain::create($request);
        $newWorkflow = $documentBase->store();

        $result = array();
        if ($newWorkflow) {
            $result['success'] = 1;
            $result['data'] = $newWorkflow;
        } else {
            $result['success'] = 0;
            $result['errors'] = $documentBase->errors;
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $this->authorize('view', WlworkflowDoc::find($id));

        $documentBase = WorkflowMain::create($request);
        $currentWorkflow = $documentBase->show($id);

        $result = array();
        if ($currentWorkflow) {
            $result['success'] = 1;
            $result['data'] = $currentWorkflow;
        } else {
            $result['success'] = 0;
            $result['errors'] = $documentBase->errors;
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $this->authorize('view', WlworkflowDoc::find($id));
        $this->authorize('update', WlworkflowDoc::find($id));

        $documentBase = WorkflowMain::create($request);
        $currentWorkflow = $documentBase->edit($id);

        $result = array();
        if ($currentWorkflow) {
            $result['success'] = 1;
            $result['data'] = $currentWorkflow;
        } else {
            $result['success'] = 0;
            $result['errors'] = $documentBase->errors;
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
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
        $this->authorize('update', WlworkflowDoc::find($id));

        $documentBase = WorkflowMain::create($request);
        $updatedWorkflow = $documentBase->update($id);

        $result = array();
        if ($updatedWorkflow) {
            $result['success'] = 1;
            $result['data'] = $updatedWorkflow;
        } else {
            $result['success'] = 0;
            $result['errors'] = $documentBase->errors;
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $this->authorize('delete', WlworkflowDoc::find($id));

        $documentBase = WorkflowMain::create($request);
        $totalDestroyItems = $documentBase->destroy($id);

        $result = array();
        if ($totalDestroyItems > 0) {
            $result['success'] = 1;
        } else {
            $result['success'] = 0;
            $result['errors']  = $documentBase->errors;
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

    //Cập nhật chứng huỷ
    public function cancelWorkflow(Request $request)
    {
        // $this->authorize('update_cancel_document', Document::find($request->id));

        $documentBase = WorkflowMain::create($request);
        $cancelSuccessfully = $documentBase->cancelWorkflow();

        $result = array();
        if ($cancelSuccessfully) {
            $result['success'] = 1;
        } else {
            $result['success'] = 0;
            $result['errors']  = $documentBase->errors;
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

    public function manualChangePhase(Request $request)
    {
        // $this->authorize('update_cancel_document', Document::find($request->id));

        $documentBase = WorkflowMain::create($request);
        $changeSuccessfully = $documentBase->changeWorkflowPhase($request->newphase_id);

        $result = array();
        if ($changeSuccessfully) {
            $result['success'] = 1;
        } else {
            $result['success'] = 0;
            $result['errors']  = $documentBase->errors;
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

    public function assignJob(Request $request)
    {
        $documentBase = WorkflowMain::create($request);
        $updatedJob = $documentBase->assignJob($request->job_id, $request->user_id, $request->note, $request->emails_to);

        $result = array();
        if ($updatedJob) {
            $result['success'] = 1;
            $result['data']  = $updatedJob;
        } else {
            $result['success'] = 0;
            $result['message'] = $documentBase->message;
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    public function takeJob(Request $request)
    {
        $documentBase = WorkflowMain::create($request);
        $updatedJob = $documentBase->takeJob($request->wljob_id);

        $result = array();
        if ($updatedJob) {

            $result['success'] = 1;
            $result['data']  = $updatedJob;
        } else {
            $result['success'] = 0;
            $result['errors'] = $documentBase->errors;
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    public function abandonJob(Request $request)
    {
        $documentBase = WorkflowMain::create($request);
        $updatedJob = $documentBase->abandonJob($request->wljob_id);

        $result = array();
        if ($updatedJob) {

            $result['success'] = 1;
            $result['data']  = $updatedJob;
        } else {
            $result['success'] = 0;
            $result['errors'] = $documentBase->errors;
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    public function updateWorkflowApproveSetting(Request $request)
    {
        $documentBase = WorkflowMain::create($request);
        $updatedWorkflow = $documentBase->updateWorkflowApproveSetting($request->workflowid, $request->approve_type, $request->approve_team, $request->teamusers, $request->sub_approve_type, $request->sub_approve_value, $request->default_group);

        $result = array();
        if ($updatedWorkflow) {
            $result['success'] = 1;
            $result['data']  = $updatedWorkflow->approve_team;
        } else {
            $result['success'] = 0;
            $result['errors'] = $documentBase->errors;
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    public function updatePhaseFinish(Request $request)
    {
        $documentBase = WorkflowMain::create($request);
        $updateSuccessfully = $documentBase->updatePhaseStatus($request->phase_id, true);

        $result = array();
        if ($updateSuccessfully) {
            $result['success'] = 1;
        } else {
            $result['success'] = 0;
            $result['errors'] = $documentBase->errors;
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

    public function getWorkflowStructure(Request $request)
    {
        $documentBase = WorkflowMain::create($request);
        $workflow_id = $request->workflow_id;
        $structure = $documentBase->getWorkflowStructure($workflow_id);

        $result = array();
        if ($structure) {
            $result['success'] = 1;
            $result['workflow'] = $structure;
        } else {
            $result['success'] = 0;
            $result['errors'] = $documentBase->errors;
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

    public function getWorkflowValue(Request $request)
    {
        $documentBase = WorkflowMain::create($request);
        $value = $documentBase->getWorkflowValue($request->workflow_id, $request->value_path);

        $result = array();
        $result['success'] = 1;
        $result['value'] = $value;

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

    public function getCreateTemplate(Request $request)
    {
        $frontend = new WorkRuntimeFrontend();

        $result = array();
        $result['success'] = 1;
        $result['data'] = $frontend->index($request);

        return json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    public function canEditDocument(Request $request) 
    {
        $user = User::find(auth()->user()->id);
        $document = WlworkflowDoc::find($request->id);

        $policy = new WorkflowPolicy();
        $result = $policy->update($user, $document);

        return json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    public function canCancelDocument(Request $request) 
    {
        $user = User::find(auth()->user()->id);
        $document = WlworkflowDoc::find($request->id);

        $policy = new WorkflowPolicy();
        $result = $policy->delete($user, $document);

        return json_encode($result, JSON_UNESCAPED_UNICODE);
    }
}
