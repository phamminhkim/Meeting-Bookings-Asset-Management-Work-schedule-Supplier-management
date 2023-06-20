<?php

namespace App\Http\Controllers\api\car;

use App\Http\Controllers\BaseController\BaseController;
use App\Http\Controllers\Controller;
use App\Models\car\Car;
use App\Models\car\FastProcess;
use App\Models\shared\DocumentType;
use App\Models\shared\Wfapproved;
use App\Repositories\car\fastprocess\FastProcessMain;
use Illuminate\Http\Request;

class FastProcessController extends BaseController
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
           $fastprocessBase = FastProcessMain::create($request->type, null,$request);
         
           $list = $fastprocessBase->index($request);
          // dd( $list);
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
             $fastprocessBase = FastProcessMain::create($request->id, $request->type, $request);
             $fastprocessModel = $fastprocessBase->store();
             if ($fastprocessModel) {
                 $result['data']['success'] = 1;
                // $result['data']['id']  = $causemeasureModel->id;
                // dd(  $result['data']);
               
             } else {
                 $result['data']['errors'] = $fastprocessBase->errors;
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
      
       
            $fastprocessBase = FastProcessMain::create($request->id, $request->type, $request);
          
            $fastprocessModel = $fastprocessBase->show($id);   
                               
            $result = array();
            $result['data'] =  array();
            $result['data'] = $fastprocessModel;
            $result['data']['success']  = 1;
            if(!$fastprocessModel){
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
         
     
                $fastprocessBase = FastProcessMain::create($request->id, $request->type, $request);
                $fastprocessModel = $fastprocessBase->edit($id);    
            
                $result = array();
                $result['data'] =  array();
                $result['data'] = $fastprocessModel;
                $result['data']['success']  = 1;
                if(!$fastprocessModel){
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
    
         
            $fastprocessBase = FastProcessMain::create($request->id, $request->type, $request);
            $fastprocessModel = $fastprocessBase->update($id);    

        if ($fastprocessModel) {
            $result['data']['success'] = 1;
            //$result['data']  = $fastprocessModel;
            
        } else {
            $result['data']['errors'] = $fastprocessBase->errors;
        }
       
        // dd("test class");
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);

        
    }
 //Cập nhật chứng huỷ
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
   
            $fastprocessBase = FastProcessMain::create($request->id, $request->type, $request);
    
            if ($fastprocessBase->destroy($id)) {
                $result['data']['success']  = 1;
            }   
             else{
                $result['data']['message']  = $fastprocessBase->message;
            }
         
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    public function check($id){
        $fast = FastProcess::find($id);
        //dd($fast->car_id);
        $result['is_step'] = '';
        //$car = Car::find($fast->car_id);
        $car = Car::with('approveds','document_type','fast_process')->find($fast->car_id);
       // dd( $car['document_type']['code']);
            //$document_type = DocumentType::find($car->document_type_id);
            //dd($document_type);
            if($car['document_type']['code']=='PCAR'){
                $step_wfapprove = $car['approveds']; 
            }
        //dd( $step_wfapprove);
       if(count($step_wfapprove)>0){
        for($i=0;$i<count($step_wfapprove);$i++){
           // dd( $step_wfapprove[$i]['step']);
            if($step_wfapprove[count( $step_wfapprove)-1]['finished']==1 && $step_wfapprove[count( $step_wfapprove)-1]['step']==2){
                $result['is_step'] = true;
            }else{
                $result['is_step'] = false;
            }
        }
       }else{
         $result['is_step'] = false;
       }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
}
