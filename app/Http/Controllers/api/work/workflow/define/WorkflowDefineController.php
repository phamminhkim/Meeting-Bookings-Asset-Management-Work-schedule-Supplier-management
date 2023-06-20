<?php

namespace App\Http\Controllers\api\work\workflow\define;

use App\Http\Controllers\BaseController\BaseController;
use App\Http\Controllers\Controller;
use App\Repositories\work\workflow\define\WorkflowDefineMain;
use Illuminate\Http\Request;

class WorkflowDefineController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $documentBase = WorkflowDefineMain::create($request, '');
        $listWorkflows = $documentBase->index($request);

        $result = array();
        if ($listWorkflows) {
            $result['success'] = 1;
            $result['data'] = $listWorkflows;
        } else {
            $result['success'] = 0;
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
        $documentBase = WorkflowDefineMain::create($request);
        $createdWorkflow = $documentBase->store();

        $result = array();
        if ($createdWorkflow) {
            $result['success'] = 1;
            $result['data']  = $createdWorkflow;
        } else {
            $result['success'] = 0;
            $result['errors'] = $documentBase->errors;
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    public function copy(Request $request)
    { 
        $type = 0;
        if ($request->filled('type')) {
            $type = $request->filled('type');
        }

        $documentBase = WorkflowDefineMain::create($request);
        $clonedWorkflow = $documentBase->copy($request->workflow_id, $type);

        $result = array();
        if ($clonedWorkflow) {
            $result['success'] = 1;
            $result['data'] = $clonedWorkflow;
        } 
        else {
            $result['success'] = 0;
            $result['errors'] = $documentBase->errors;
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

    public function show(Request $request, $id)
    {
        $documentBase = WorkflowDefineMain::create($request);
        $currentWorkflow = $documentBase->show($id);

        $result = array();
        if ($currentWorkflow) {
            $result['success'] = 1;
            $result['data']  = $currentWorkflow;
        } 
        else {
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
        $documentBase = WorkflowDefineMain::create($request);
        $currentWorkflow = $documentBase->edit($id);

        $result = array();
        if ($currentWorkflow) {
            $result['success'] = 1;
            $result['data']  = $currentWorkflow;
        } 
        else {
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
        $documentBase = WorkflowDefineMain::create($request);
        $updatedWorkflow = $documentBase->update($id);

        $result = array();
        if ($updatedWorkflow) {
            $result['success'] = 1;
            $result['data'] = $updatedWorkflow;
        } 
        else {
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
        try {
            $documentBase = WorkflowDefineMain::create($request);
            $totalDestroyItems = $documentBase->destroy($id);

            $result = array();
            if ($totalDestroyItems > 0) {
                $result['success'] = 1;
            } 
            else {
                $result['success'] = 0;
                $result['errors'] = $documentBase->errors;
            }
        } catch (\Throwable $th) {
            $result['errors'] = $th->getMessage();
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
}
