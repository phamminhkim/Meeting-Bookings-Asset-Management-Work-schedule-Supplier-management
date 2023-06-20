<?php

namespace App\Http\Controllers\api\car;

use App\Http\Controllers\BaseController\BaseController;
use App\Http\Controllers\Controller;
use App\Models\car\ResultEvaluation;
use App\Repositories\car\evaluate\ResultEvaluationMain;
use Illuminate\Http\Request;

class ResultEvaluationController extends BaseController
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
           $systemcarBase = ResultEvaluationMain::create($request->type, null,$request);
         
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
            //dd($request->all());
             $resultevaluationBase = ResultEvaluationMain::create($request->id, $request->type, $request);
             $resultevaluationModel = $resultevaluationBase->store();
             if ($resultevaluationModel) {
                 $result['data']['success'] = 1;
                // $result['data']['id']  = $causemeasureModel->id;
                // dd(  $result['data']);
               
             } else {
                 $result['data']['errors'] = $resultevaluationBase->errors;
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
      
       
            $resultevaluationBase = ResultEvaluationMain::create($request->id, $request->type, $request);
          
            $resultevaluationModel = $resultevaluationBase->show($id);   
                               
            $result = array();
            $result['data'] =  array();
            $result['data'] = $resultevaluationModel;
            $result['data']['success']  = 1;
            if(!$resultevaluationModel){
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
         
     
                $resultevaluationBase = ResultEvaluationMain::create($request->id, $request->type, $request);
                $resultevaluationModel = $resultevaluationBase->edit($id);    
            
                $result = array();
                $result['data'] =  array();
                $result['data'] = $resultevaluationModel;
                $result['data']['success']  = 1;
                if(!$resultevaluationModel){
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
    
         
            $resultevaluationBase = ResultEvaluationMain::create($request->id, $request->type, $request);
            $resultevaluationModel = $resultevaluationBase->update($id);    

        if ($resultevaluationModel) {
            $result['data']['success'] = 1;
           // $result['data']  = $resultevaluationModel;
            
        } else {
            $result['data']['errors'] = $resultevaluationBase->errors;
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
   
            $resultevaluationBase = ResultEvaluationMain::create($request->id, $request->type, $request);
    
            if ($resultevaluationBase->destroy($id)) {
                $result['data']['success']  = 1;
            }   
             else{
                $result['data']['message']  = $resultevaluationBase->message;
            }
         
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
}
