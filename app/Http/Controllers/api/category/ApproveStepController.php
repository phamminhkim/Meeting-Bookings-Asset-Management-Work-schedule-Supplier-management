<?php

namespace App\Http\Controllers\api\category;

use App\Http\Controllers\Controller;
use App\Models\shared\ApprovedStep;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ApproveStepController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $approvedSteps = ApprovedStep::where('document_type_id',$request->document_type_id)->get();
       // dd($request->document_type_id);
        // return DepartmentResource::collection($department);
        $result = array();
        $result['data'] = array();
        $result['data'] = $approvedSteps;
        $result['success'] = "1";
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!auth()->user()->hasPermission('config_category')) {
            return response('Access denied', 403);
        }

        $result = array();
        $result['data'] =  array();
        $result['data']['success']  = 0;
        date_default_timezone_set("Asia/Ho_Chi_Minh");

        $validator = Validator::make($request->all(), [
            'document_type_id' => 'required',
            'steps.*.step'=>'sometimes|required',
            'steps.*.name'=>'sometimes|required'

        ],[
            'document_type_id.required'=>"Loại chứng từ rỗng",
            'steps.*.step.required'=>'Step rỗng',
            'steps.*.name.required'=>'Tên rỗng',

        ]);

        $failed = $validator->fails();
            //dd($failed);
        if ($failed) {
            $result['data']['errors']  = $validator->errors();
        } else {

            try {
                DB::beginTransaction();
                $fields = $request->all();
                $approvdSteps = $fields['steps'];
                for ($i = 0; $i < count($approvdSteps); $i++) {
                    $approvdSteps[$i]['document_type_id'] = $fields['document_type_id'];
                    if (isset($approvdSteps[$i]['id']) && $approvdSteps[$i]['id'] != 0) {
                        $step = ApprovedStep::find($approvdSteps[$i]['id']);
                        $step->fill($approvdSteps[$i]);

                        $step->save();
                        //dd($step);
                    }else{


                        ApprovedStep::create($approvdSteps[$i]);
                    }

                }

                $approvdSteps_del = $fields['steps_del'];

                for ($i = 0; $i < count($approvdSteps_del); $i++) {
                     if( isset( $approvdSteps_del[$i]['id'])){
                        $step = ApprovedStep::find($approvdSteps_del[$i]['id']);
                        if ($step) {
                            $step->delete();
                        }
                     };

                }
                DB::commit();
                $result['data']['success'] = 1;

            } catch (\Exception $e) {
                DB::rollBack();
                $result['data']['errors']  =  $e->getMessage();
            }
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
