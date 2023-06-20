<?php

namespace App\Http\Controllers\api\work\workflow\define;

use App\Http\Controllers\BaseController\BaseController;
use App\Http\Controllers\Controller;
use App\Models\work\workflow\Wlphase;
use App\Repositories\work\workflow\define\WlJobProcess;
use App\Repositories\work\workflow\define\WorkflowDefineMain;
use Illuminate\Http\Request;

class WlJobController extends BaseController
{
   
    public function store(Request $request)
    {
        $JobProcess = new WlJobProcess($request);
        $newJob = $JobProcess->store();

        $result = array();
        if ($newJob) {
            $result['success'] = 1;
            $result['data']  = $newJob;
        } 
        else {
            $result['success'] = 0;
            $result['errors'] = $JobProcess->errors;
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    public function edit(Request $request,$id)
    {
        $JobProcess = new WlJobProcess($request);
        $currentJob = $JobProcess->edit($id);

        $result = array();
        if ($currentJob) {
            $result['success'] = 1;
            $result['data']  = $currentJob;
        } 
        else {
            $result['success'] = 0;
            $result['errors'] =  $JobProcess->errors;
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    public function update(Request $request, $id)
    {
        $JobProcess = new WlJobProcess($request);
        $updatedJob = $JobProcess->update($id);

        $result = array();
        if ($updatedJob) {

            $result['success'] = 1;
            $result['data']  = $updatedJob;
        } 
        else {
            $result['success'] = 0;
            $result['errors'] = $JobProcess->errors;
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    public function destroy(Request $request, $id)
    {
        try {
            $JobProcess = new WlJobProcess($request);
            $totalDestroyItems = $JobProcess->destroy($id);

            $result = array();
            if ($totalDestroyItems > 0) {
                $result['success'] = 1;
            } 
            else {
                $result['success'] = 0;
                $result['errors'] = $JobProcess->errors;
            }
        } catch (\Throwable $th) {
            $result['data']['errors'] =  $th->getMessage();
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
}
