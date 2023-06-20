<?php

namespace App\Http\Controllers\api\work\workflow\define;

use App\Http\Controllers\BaseController\BaseController;
use App\Http\Controllers\Controller;
use App\Models\work\workflow\Wlphase;
use App\Repositories\work\workflow\define\PhaseProcess;
use App\Repositories\work\workflow\define\WorkflowDefineMain;
use Illuminate\Http\Request;

class PhaseController extends BaseController
{
    public function index(Request $request)
    {
        $phaseProcess = new PhaseProcess($request);
        $listPhases = $phaseProcess->index($request);

        $result = array();
        if ($listPhases) {
            $result['success'] = 1;
            $result['data'] = $listPhases;
        } 
        else {
            $result['success'] = 0;
            $result['errors'] = $phaseProcess->errors;
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);

    }
    public function edit(Request $request, $id)
    {
        $phaseProcess = new PhaseProcess($request);
        $currentPhase = $phaseProcess->edit($id);

        $result = array();
        if ($currentPhase) {
            $result['success'] = 1;
            $result['data']  = $currentPhase;
        } 
        else {
            $result['success'] = 0;
            $result['errors'] = $phaseProcess->errors;
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    public function store(Request $request)
    {
        $phaseProcess = new PhaseProcess($request);
        $newPhase = $phaseProcess->store();

        $result = array();
        if ($newPhase) {
            $result['success'] = 1;
            $result['data']  = $newPhase;
        } 
        else {
            $result['success'] = 0;
            $result['errors'] = $phaseProcess->errors;
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    public function update(Request $request, $id)
    {
        $phaseProcess = new PhaseProcess($request);
        $updatedPhase = $phaseProcess->update($id);

        $result = array();
        if ($updatedPhase) {
            $result['success'] = 1;
            $result['data']  = $updatedPhase;
        } 
        else {
            $result['success'] = 0;
            $result['errors'] = $phaseProcess->errors;
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    public function destroy(Request $request, $id)
    {
        try {
            $phaseProcess = new PhaseProcess($request);
            $totalDestroyItems = $phaseProcess->destroy($id);

            $result = array();
            if ($totalDestroyItems > 0) {
                $result['success'] = 1;
            } 
            else {
                $result['success'] = 0;
                $result['errors']  = $phaseProcess->errors;
            }
        } catch (\Throwable $th) {
            $result['data']['errors'] =  $th->getMessage();
        }

        // dd("test class");
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    public function updatePhaseOrders(Request $request)
    {
        $phaseProcess = new PhaseProcess($request);
        $updateSuccessfully = $phaseProcess->updatePhaseOrders();

        $result = array();
        if ($updateSuccessfully) {
            $result['success'] = 1;
        } 
        else {
            $result['success'] = 0;
            $result['errors'] = $phaseProcess->errors;
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
}
