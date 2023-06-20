<?php

namespace App\Http\Controllers\api\category;

use App\Http\Controllers\Controller;
use App\Models\shared\Wfmain;
use App\Models\shared\Wfstep;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;
class WfstepController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
         $workflowSteps = Wfstep::where('wfmain_id',$request->wfmain_id)->get();
         $result = array();
         $result['data'] = array();
         $result['data'] = $workflowSteps;
         $result['success'] = "1";
         return json_encode($result, JSON_UNESCAPED_UNICODE);
    }
    public function get_workflow(Request $request)
    {
         $workflowSteps = Wfstep::where('code',$request->code)->get();
         $result = array();
         $result['data'] = array();
         $result['data'] = $workflowSteps;
         $result['success'] = "1";
         return json_encode($result, JSON_UNESCAPED_UNICODE);
    }
    public function list()
    {
        $step = Wfstep::all();
         $result = array();
         $result['data'] = array();
         $result['data'] = $step;

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
        $result = array();
        $result['data'] =  array();
        $result['data']['success']  = 0;
        date_default_timezone_set("Asia/Ho_Chi_Minh");

        $validator = Validator::make($request->all(), [
            'wfmain_id' => 'required',
            'steps.*.step'=>'sometimes|required',
            'steps.*.name'=>'sometimes|required'
            
        ],[
            'wfmain_id.required'=>"Loại workflow rỗng",
            'steps.*.step.required'=>'Step rỗng',
            'steps.*.name.required'=>'Tên rỗng',

        ]);

        $failed = $validator->fails();
        if ($failed) {
            $result['data']['errors']  = $validator->errors();
        } else {

            try {
                DB::beginTransaction();
                $fields = $request->all();
                $workflowSteps = $fields['steps'];
                for ($i = 0; $i < count($workflowSteps); $i++) {
                    $workflowSteps[$i]['wfmain_id'] = $fields['wfmain_id'];
                    if (isset($workflowSteps[$i]['id']) && $workflowSteps[$i]['id'] != 0) {
                        $step = Wfstep::find($workflowSteps[$i]['id']);
                        $step->fill($workflowSteps[$i]);
                        
                        $step->save();
                        //dd($step);
                    }else{

                      
                        Wfstep::create($workflowSteps[$i]);
                    }
                   
                }
               
                $workflowSteps_del = $fields['steps_del'];

                for ($i = 0; $i < count($workflowSteps_del); $i++) {
                     if( isset( $workflowSteps_del[$i]['id'])){
                        $step = Wfstep::find($workflowSteps_del[$i]['id']);
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
