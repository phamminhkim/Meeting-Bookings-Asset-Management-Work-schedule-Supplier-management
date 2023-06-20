<?php

namespace App\Http\Controllers\api\car;

use App\Http\Controllers\BaseController\BaseController;
use App\Http\Controllers\Controller;
use App\Models\car\MonitorImplementation;
use App\Repositories\car\monitor\MonitorImplementationMain;
use Illuminate\Http\Request;

class MonitorImplementationController extends BaseController
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
           $systemcarBase = MonitorImplementationMain::create($request->type, null,$request);
         
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
             $causemeasureBase = MonitorImplementationMain::create($request->id, $request->type, $request);
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
      
       
            $causemeasureBase = MonitorImplementationMain::create($request->id, $request->type, $request);
          
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
         
     
                $causemeasureBase = MonitorImplementationMain::create($request->id, $request->type, $request);
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
    
         
            $causemeasureBase = MonitorImplementationMain::create($request->id, $request->type, $request);
            $causemeasureModel = $causemeasureBase->update($id);    

        if ($causemeasureModel) {
            $result['data']['success'] = 1;
          //  $result['data']  = $causemeasureModel;
            
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
   
            $causemeasureBase = MonitorImplementationMain::create($request->id, $request->type, $request);
    
            if ($causemeasureBase->destroy($id)) {
                $result['data']['success']  = 1;
            }   
             else{
                $result['data']['message']  = $causemeasureBase->message;
            }
         
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
}
