<?php

namespace App\Http\Controllers\api\car;

use App\Http\Controllers\BaseController\BaseController;
use App\Http\Controllers\Controller;
use App\Models\car\Car;
use App\Models\car\CauseMeasure;
use App\Models\car\FastProcess;
use App\Models\car\MonitorImplementation;
use App\Models\car\ResultEvaluation;
use App\Models\shared\Wfapproved;
use App\Repositories\car\SystemCarMain;
use App\Models\shared\DocumentType;
use App\Models\shared\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SoareCostin\FileVault\Facades\FileVault as FacadesFileVault;

class SystemCarController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {   

       
          $this->authorize('view', new Car());
           $result = array();
           $result['data'] = array();
           $result['success'] = "0";
           $systemcarBase = SystemCarMain::create($request, '');
         
           $list = $systemcarBase->index($request);
           if ($list) {
               $result['data'] = $list;
               $result['success'] = "1";
           }

      
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);

    }
    public function index_statistic(Request $request)
    {   

       
           $this->authorize('view', new Car());
          
           $result = array();
           $result['data'] = array();
           $result['success'] = "0";
           $systemcarBase = SystemCarMain::create($request, '');
         
           $list = $systemcarBase->statistics($request);
           if ($list) {
               $result['data'] = $list;
               $result['success'] = "1";
           }

      
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);

    }
    public function statistic_status(Request $request)
    {   

       
           $this->authorize('view', new Car());
          // dd($request->all());
           $result = array();
           $result['data'] = array();
           $result['success'] = "0";
           $systemcarBase = SystemCarMain::create($request, '');
         
           $list = $systemcarBase->statistic_status($request);
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
            $this->authorize('create', new Car());
      
            $result = array();
            $result['data'] = array();
            $result['data']['success'] = '0';
    
             $systemcarBase = SystemCarMain::create($request);

             $systemcarModel = $systemcarBase->store();
            
             if ($systemcarModel) {
                 $result['data']['success'] = 1;
                 
                 $result['data']  = $systemcarModel;
     
             } else {
                 $result['data']['errors'] = $systemcarBase->errors;
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
       
           $this->authorize('view',Car::find($id));
      
       
            $systemcarBase = SystemCarMain::create($request);
          
            $systemcarModel = $systemcarBase->show($id);   
                               
            $result = array();
            $result['data'] =  array();
            $result['data'] = $systemcarModel;
            $result['data']['success']  = 1;
            if(!$systemcarModel){
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
               $this->authorize('update', Car::find($id));
         
     
                $systemcarBase = SystemCarMain::create($request);
                $systemcarModel = $systemcarBase->edit($id);    
            
                $result = array();
                $result['data'] =  array();
                $result['data'] = $systemcarModel;
                $result['data']['success']  = 1;
                if(!$systemcarModel){
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

        $this->authorize('update', Car::find($id));
        
        $result = array();
        $result['data'] = array();

        $result['data']['success'] = 0;
    
         
            $systemcarBase = SystemCarMain::create($request);
            $systemcarModel = $systemcarBase->update($id);    

        if ($systemcarModel) {
            $result['data']['success'] = 1;
            $result['data']  = $systemcarModel;
            
        } else {
            $result['data']['errors'] = $systemcarBase->errors;
        }
       
        // dd("test class");
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);

        
    }
    public function update_date_limit(Request $request)
    {
       // $this->authorize('update', Car::find($id));
        
        $result = array();
        $result['data'] = array();

        $result['data']['success'] = 0;
    
         //dd($request->all());
            $systemcarBase = SystemCarMain::create($request, '');
           // dd($systemcarBase);
            $systemcarModel =  $systemcarBase->update_date_limit( );  
            
            if ($systemcarModel) {
                $result['data']['success'] = 1;
            } else{
                 $result['data']['message']  = 'Cập nhật không thành công!';
            }        
            
 
        // dd("test class");
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);

        
    }
 //Cập nhật chứng huỷ
 public function update_cancel(Request $request){
      
    
    $this->authorize('update_cancel', Car::find($request->id));
    
    $result = array();
    $result['data'] = array();
    $result['data']['success'] = 0;
    //dd( $request->all());
        $systemcarBase = SystemCarMain::create($request, '');
       // dd( $systemcarBase);
        $re = $systemcarBase->update_cancel( );    

    if ($re) {
        $result['data']['success'] = 1;
    } else{
         $result['data']['message']  = 'Phiếu đã hoàn tất không hủy được!';
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

        $this->authorize('delete', Car::find($id));
        $result = array();
        $result['data'] =  array();
        $result['data']['success']  = 0;
   
            $systemcarBase = SystemCarMain::create($request);
    
            if ($systemcarBase->destroy($id)) {
                $result['data']['success']  = 1;
            }   
             else{
                $result['data']['message']  = $systemcarBase->message;
            }
         
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

    public function downloadFile($idfile)
    {
        $file = File::findOrFail($idfile);
        if (!$file) {
            abort(404);
        }
        $filepath = $file->url . ".enc";
        if (!Storage::has($filepath)) {
            abort(404);
        }
        // return  Storage::download($filepath);

        $filename = $file->name;
       // dd($filepath);
        return response()->streamDownload(function () use ($filepath) {
            FacadesFileVault::streamDecrypt($filepath);
        }, Str::replaceLast('.enc', '', $filename));
    }

    public function get_info_approve($id){
           
            $result = array();
            $result['data'] = array();
            $step_now =  array();
            $step= null;
            $check_step1 =  array();
            $check_step2 =  array();
            $check_step3 =  array();
            $check_step4 =  array();
            $step_approve_2 =  array();
            $step_3 =  array();
            $step_approve3 =0;
            $car = Car::with('approveds','document_type')->find($id);
            for($i=0;$i<count($car['approveds']);$i++){
                if($car['approveds'][$i]['online']=='X'){
                    array_push($step_now,$car['approveds'][$i]);
                }
            }
            
            for($i=0;$i<count($step_now);$i++){
                $result['is_step']= $step_now[count($step_now)-1]['step']; 
            }
            if( $car->proposer === auth()->user()->id)
            $result['user']=1;
            else
            $result['user']=0;

            if($car->status===2)
            $result['is_status']=2;
            elseif($car->status===-2)
            $result['is_status']=-2;
            else
            $result['is_status']=1;

            $check_exist_process = FastProcess::where('car_id',$id)->count();
            if($check_exist_process>0  && $car->status==-2)
            $result['is_process']=1;
            else
            $result['is_process']=0;

            if($car->is_cause_measure==1)
            $result['is_cause_measure']=1;
            else
            $result['is_cause_measure']=0;

            $check_exist_cause = CauseMeasure::where('car_id',$id)->count();
            if($check_exist_cause>0 && $car->status==-2)
            $result['is_cause']=1;
            else
            $result['is_cause']=0;

            if($check_exist_cause>0)
            $result['is_exist_cause']=1;
            else
            $result['is_exist_cause']=0;

            $check_exist_monitor = MonitorImplementation::where('car_id',$id)->count();
            if($check_exist_monitor>0 && $car->status==-2)
            $result['is_monitor']=1;
            else
            $result['is_monitor']=0;

            if($check_exist_monitor>0)
            $result['is_exist_monitor']=1;
            else
            $result['is_exist_monitor']=0;

            $check_exist_evaluation = ResultEvaluation::where('car_id',$id)->count();
            if($check_exist_evaluation>0 && $car->status==-2)
            $result['is_evaluation']=1;
            else
            $result['is_evaluation']=0;


            if($check_exist_evaluation>0)
            $result['is_exist_evaluation']=1;
            else
            $result['is_exist_evaluation']=0;

            $result['is_document']= $car['document_type']['code'];
          
            for($i=0;$i<count($car['approveds']);$i++){
                if($car['approveds'][$i]['online']=='X' && $car['approveds'][$i]['step']==1){
                    array_push($check_step1,$car['approveds'][$i]);
                }
            }
            if(count($check_step1)>0 && $check_step1[count($check_step1)-1]['finished']===1){
                $result['is_step1_approve']=1;
            }
            else{
                $result['is_step1_approve']=0;
            }
            for($i=0;$i<count($car['approveds']);$i++){
                if($car['approveds'][$i]['online']=='X' && $car['approveds'][$i]['step']==2){
                    array_push($check_step2,$car['approveds'][$i]);
                }
            }

            if(count($check_step2)>0 && $check_step2[count($check_step2)-1]['finished']===1){
                $result['is_step2_approve']=1;
            }
            else{
                $result['is_step2_approve']=0;
            }
            for($i=0;$i<count($car['approveds']);$i++){
                if($car['approveds'][$i]['online']=='X' && $car['approveds'][$i]['step']==3){
                    array_push($check_step3,$car['approveds'][$i]);
                }
            }

            if(count($check_step3)>0 && $check_step3[count($check_step3)-1]['finished']===1){
                $result['is_step3_approve']=1;
            }
            else{
                $result['is_step3_approve']=0;
            }
            for($i=0;$i<count($car['approveds']);$i++){
                if($car['approveds'][$i]['online']=='X' && $car['approveds'][$i]['step']==4){
                    array_push($check_step4,$car['approveds'][$i]);
                }
            }

            if(count($check_step4)>0 && $check_step4[count($check_step4)-1]['finished']===1){
                $result['is_step4_approve']=1;
            }
            else{
                $result['is_step4_approve']=0;
            }
            $check_exist = count($step_now);
            if($check_exist>0){
                $step_approve = $step_now;
            if(count($step_approve) === 1 && $step_approve[0]['step']===1 && $step_approve[0]['checkout']<> null && $step_approve[0]['finished']===1){
             $step = 1;
             $result['data']  = $step;
            }
            elseif(count($step_approve) > 1 && $step_approve[count($step_approve)-1]['step']===1 && $step_approve[count($step_approve)-1]['checkout']<> null && $step_approve[count($step_approve)-1]['finished']===1){
                $step = 1;
                $result['data']  = $step;
               }
            else{
                $step = null;
                $result['data']  = $step;  
            }
            for($i=0;$i<count($step_approve);$i++){
            $monitor = MonitorImplementation::where('car_id',$id)->count();
                if($step_approve[$i]['step']===2){
                array_push($step_approve_2,$step_approve[$i]);
                }
                if($step_approve[$i]['step']===3){
                    array_push($step_3,$step_approve[$i]);
                }
            }
             $step_approve3  = count($step_3);
                        for($j=0;$j<count($step_approve_2);$j++){
                            if($step_approve_2[0]['checkout']=== null && $step_approve_2[0]['finished']===0){
                                $step = 2;
                                $result['data']  = $step;
                            }elseif($step_approve_2[count($step_approve_2)-1]['checkout']<> null && $step_approve_2[count($step_approve_2)-1]['finished']===1 && $monitor===0 && $step_approve3===0){
                                $step = 3;
                                $result['data']  = $step;
                            }
                            else{
                                $step_approve_3 = count($step_3);
                                
                                if($monitor>0 && $step_approve_3===0){
                                    $step = 4;
                                    $result['data']  = $step;
                                }
                                if($step_approve_3>0){
                                    $step_approve_3 = $step_3;
                                for($k=0;$k<count($step_approve_3);$k++){
                                    if($step_approve_3[0]['checkout']=== null && $step_approve_3[0]['finished']===0){
                                        $step = 4;
                                        $result['data']  = $step;
                                    }elseif($step_approve_3[count($step_approve_3)-1]['checkout'] <> null && $step_approve_3[count($step_approve_3)-1]['finished']===1){
                                        $step = 4;
                                        $result['data']  = $step;
                                     }
                                    
                                    }
                                }
                            }
                        }
       
            }else{
                $step = null;
                $result['data']  = $step;
            }

            return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    public function statistics(){
        $car_new = Car::where('status',0)->whereYear('created_at', date('Y'))->count();
        $car_pendding = Car::where('status',1)->whereYear('created_at', date('Y'))->count();
        $car_finished = Car::where('status',2)->whereYear('created_at', date('Y'))->count();
        $car_denied = Car::where('status',-2)->whereYear('created_at', date('Y'))->count();
        $result['is_new']=  $car_new;
        $result['is_pendding']=  $car_pendding;
        $result['is_finished']=  $car_finished;
        $result['is_denied']=  $car_denied;
        return json_encode($result, JSON_UNESCAPED_UNICODE);
    }
    public function get_user_create(){
        $result = array();
        $result['data'] =  array();
        $result['data']['success']  = 0;
        $car = Car::Select('user_id')->distinct()->with('user')->get();
        if($car){
            $result['data'] = $car;
            $result['success'] = "1";
        }
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
}
