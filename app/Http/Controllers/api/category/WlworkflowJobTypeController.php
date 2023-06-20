<?php

namespace App\Http\Controllers\api\category;

use App\Http\Controllers\Controller;
use App\Models\shared\Bank;
use App\Models\work\workflow\WlworkflowJobType;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class WlworkflowJobTypeController extends Controller
{
    public function index(Request $request)
    {
        $query = WlworkflowJobType::query();

        $job_types = array();
        if ($request->filled('all')) {
            $job_types = $query->get();
        } else {
            if ($request->filled('can_be_created')) {
                $query = $query->where('is_can_be_created', true);
            }

            $response_jobs = (clone $query)->where('is_response_type', true)->get();
            $action_jobs = (clone $query)->where('is_response_type', false)->get();
            $job_types['response_job_types'] = $response_jobs;
            $job_types['action_job_types'] = $action_jobs;
        }


        $result = array();
        $result['data'] = $job_types;
        $result['success'] = "1";
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

    public function store(Request $request)
    {

        $result = array();
        $result['data'] =  array();
        $result['data']['success']  = 0;
        date_default_timezone_set("Asia/Ho_Chi_Minh");
        $validator = Validator::make($request->all(), [
            'code' => 'required',
            'name' => 'required',
        ]);
        $failed = $validator->fails();
        if ($failed) {
            $result['data']['errors']  = $validator->errors();
        } else {
            try {
                $workflow_job_type = WlworkflowJobType::create($request->all());
                if ($workflow_job_type) {
                    $result['data']  = $workflow_job_type;
                    $result['data']['success']  = 1;
                }
            } catch (\Exception $e) {
                $result['data']['errors']  =  $e->getMessage();
            }
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

    public function show($id)
    {
        $wlworkflowType = WlworkflowJobType::findOrFail($id);

        $result = array();
        $result['data'] = $wlworkflowType;
        $result['success'] = "1";

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

        $result = array();
        $result['data'] =  array();
        $result['data']['success']  = 0;
        date_default_timezone_set("Asia/Ho_Chi_Minh");

        $validator = Validator::make($request->all(), [
            'code' => 'required',
            'name' => 'required',
        ]);

        $failed = $validator->fails();
        if ($failed) {
            $result['data']['errors']  = $validator->errors();
        } else {
            try {
                $workflow_job_type = WlworkflowJobType::findOrFail($id);
                if ($workflow_job_type) {
                    $workflow_job_type->code = $request->input('code');
                    $workflow_job_type->name = $request->input('name');
                    $workflow_job_type->keyword = $request->input('keyword');
                    $workflow_job_type->icon = $request->input('icon');
                    $workflow_job_type->is_require_depends = $request->input('is_require_depends');
                    $workflow_job_type->require_depends_text = $request->input('require_depends_text');
                    $workflow_job_type->is_can_be_created = $request->input('is_can_be_created');
                    $workflow_job_type->is_response_type = $request->input('is_response_type');

                    if ($workflow_job_type->save())
                        $result['data']['success']  = 1;
                }
            } catch (\Exception $e) {
                $result['data']['errors']  =  $e->getMessage();
            }
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $workflow_job_type = WlworkflowJobType::findOrFail($id);
        $result = array();
        $result['data'] = array();

        if ($workflow_job_type->delete()) {
            $result['data']['success']  = 1;
        } else {
            $result['data']['success'] = 0;
        }
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);

    }
}
