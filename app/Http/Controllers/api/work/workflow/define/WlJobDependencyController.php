<?php

namespace App\Http\Controllers\api\work\workflow\define;

use App\Http\Controllers\BaseController\BaseController;
use App\Http\Controllers\Controller;
use App\Models\work\workflow\Wlphase;
use App\Repositories\work\workflow\define\JobDependencyProcess;
use App\Repositories\work\workflow\define\WorkflowDefineMain;
use Illuminate\Http\Request;

class WlJobDependencyController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\WlJobDependency  $WlJobDependency
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $process = new JobDependencyProcess($request);
        $updated_job = $process->update($id);

        $result = array();
        if ($updated_job) {

            $result['success'] = 1;
            $result['data']  = $updated_job;
        } 
        else {
            $result['success'] = 0;
            $result['errors'] = $process->errors;
        }
        
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
}
