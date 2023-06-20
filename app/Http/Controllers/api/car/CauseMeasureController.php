<?php

namespace App\Http\Controllers\api\car;

use App\Http\Controllers\BaseController\BaseController;
use App\Http\Controllers\Controller;
use App\Models\car\Car;
use App\Models\car\CauseMeasure;
use App\Models\shared\DocumentType;
use App\Models\shared\Wfapproved;
use App\Repositories\car\causemeasure\CauseMeasureMain;
use Illuminate\Http\Request;

class CauseMeasureController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   

       
        //$this->authorize('view', new Car());
           $result = array();
           $result['data'] = array();
           $result['success'] = "0";
           $systemcarBase = CauseMeasureMain::create($request->type, null,$request);
         
           $list = $systemcarBase->index($request);
           if ($list) {
               $result['data'] = $list;
               $result['success'] = "1";
           }

      
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
       // $this->authorize('create', new Car());
      
            $result = array();
            $result['data'] = array();
            $result['data']['success'] = 0;
      
             $causemeasureBase = CauseMeasureMain::create($request->type, null,$request);
             $causemeasureModel = $causemeasureBase->store();
             if ($causemeasureModel) {
                 $result['data']['success'] = 1;
                // $result['data']['id']  = $causemeasureModel->id;
                // dd(  $result['data']);
               
             } else {
                 $result['data']['errors'] = $causemeasureBase->errors;
             }
        
           
        // dd("test class");
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
       
       // $this->authorize('view',Car::find($id));
      
       
            $causemeasureBase = CauseMeasureMain::create($request->type, null,$request);
          
            $causemeasureModel = $causemeasureBase->show($id);   
                               
            $result = array();
            $result['data'] =  array();
            $result['data'] = $causemeasureModel;
            $result['data']['success']  = 1;
            if(!$causemeasureModel){
                $result['data']['success']  = 0;
            }
       
       

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,$id)
    {     
       // $this->authorize('update', Car::find($id));
         
     
                $causemeasureBase = CauseMeasureMain::create($request->type, null,$request);
                $causemeasureModel = $causemeasureBase->edit($id);    
            
                $result = array();
                $result['data'] =  array();
                $result['data'] = $causemeasureModel;
                $result['data']['success']  = 1;
                if(!$causemeasureModel){
                $result['data']['success']  = 0;
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
    public function update(Request $request,$id)
    {

        //$this->authorize('update', Car::find($id));
        
        $result = array();
        $result['data'] = array();

        $result['data']['success'] = 0;
    
         
            $causemeasureBase = CauseMeasureMain::create($request->type, null,$request);
            $causemeasureModel = $causemeasureBase->update($id);    

        if ($causemeasureModel) {
            $result['data']['success'] = 1;
           // $result['data']  = $causemeasureModel;
            
        } else {
            $result['data']['errors'] = $causemeasureBase->errors;
        }
       
        // dd("test class");
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);

        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id){

       // $this->authorize('delete', Car::find($id));
        $result = array();
        $result['data'] =  array();
        $result['data']['success']  = 0;
   
            $causemeasureBase = CauseMeasureMain::create($request->type, null,$request);
    
            if ($causemeasureBase->destroy($id)) {
                $result['data']['success']  = 1;
            }   
             else{
                $result['data']['message']  = $causemeasureBase->message;
            }
         
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    public function check($id){
        $cause = CauseMeasure::find($id);
        //dd($cause->car_id);
        $result['is_step'] = '';
        $car = Car::with('approveds','document_type','cause_measure')->find($cause->car_id);
        //$car = Car::find($cause->car_id);
         //   $document_type = DocumentType::find($car->document_type_id);
            //dd($document_type);
            if($car['document_type']['code']=='SCAR'){
                $step_wfapprove = $car['approveds']; 
            }
            if($car['document_type']['code']=='PCAR'){
                $step_wfapprove = $car['approveds']; 
            }
        if(count($step_wfapprove)>0){
        for($i=0;$i<count( $step_wfapprove);$i++){
            if(($step_wfapprove[count( $step_wfapprove)-1]['finished']==1 && 
            $car['document_type']['code']=='SCAR' &&
            $step_wfapprove[count( $step_wfapprove)-1]['step']==2) || 
            ($step_wfapprove[count( $step_wfapprove)-1]['finished']==1 && 
            $car['document_type']['code']=='PCAR' &&
            $step_wfapprove[count( $step_wfapprove)-1]['step']==3)){
                $result['is_step'] = true;
            }else{
                $result['is_step'] = false;
            }
        }
    }
    else{
        $result['is_step'] = false;
      }
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
}
