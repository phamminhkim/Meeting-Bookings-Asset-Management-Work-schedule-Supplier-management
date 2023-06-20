<?php

namespace App\Http\Controllers\frontend\car;

use App\Http\Controllers\BaseController\BaseController;
use App\Http\Controllers\Controller;
use App\Models\car\Car;
use App\Models\shared\CarError;
use App\Models\shared\DocumentType;
use App\Models\shared\Position;
use App\Models\shared\PositionApprove;
use App\Models\shared\Wfapproved;
use App\Models\shared\Wfmain;
use App\Repositories\approvewf\ApproveMain;
use App\Repositories\car\SystemCarBase;
use App\Repositories\car\SystemCarMain;
use App\Repositories\car\SystemCarPDNS;
use App\Ultils\Ultils;
use Illuminate\Http\Request;

class SystemCarFrontend extends BaseController
{
    public function index(Request $request)
    {

        $form_name = "";
        $type = $request->type;
        //dd( $type);
        $id =   $request->id;
        $car = Car::find($id);

        
        if($car){

            $documentType =DocumentType::where('id',$car->document_type_id)->first();

            if($documentType){
                $doctype = $documentType->code;
            }

        }else{
            $doctype = $request->doctype;
        }

      //dd($type);
        switch ($type) {
            case 'add':
                $this->authorize('create', new Car());
                break;
            case 'view':
                $this->authorize('view', Car::findOrFail($id));
                break;
            case 'edit':
                $this->authorize('update',  Car::findOrFail($id));
                break;
           case 'print':
                $this->authorize('view',  Car::findOrFail($id));
                break;
            default:

                $this->authorize('view', new Car());

                break;
        }

        //Điều khiển layout ẩn hiện và hiển thị dấu (*)
       // dd($doctype);
        switch ($doctype) {
            case Ultils::$MODULE_CARS :
              
                $systemcarBase = new  SystemCarPDNS($request);
                if($systemcarBase){
                    $form_name = $systemcarBase->form_name;
                }

                break;

            default:
              
                $systemcarBase = new  SystemCarBase($request);

                break;
        }
        $layout =  $systemcarBase->getLayout();

        $documentType = DocumentType::where('code',$doctype)->first();

        $doctype_id= '';
        $doctype_name= '';
        $wfmain_id= '';
        

        if($documentType){
            $doctype_id = $documentType->id;
            $doctype_name = $documentType->name;
            $wfmain  = Wfmain::where('document_type_id',$doctype_id)->first();
            $wfmain_id  =  $wfmain->id;
          
        }

        $module = Ultils::$MODULE_CARS;

       // dd("tesst" . $request->notification_id);
        $notification_id = $request->notification_id;

        $array = [];

        $array['type'] = $type;
        $array['id'] = $id;
        $array['doctype'] = $doctype;
        $array['doctype_id'] = $doctype_id;
        $array['doctype_name'] = $doctype_name;
        $array['layout'] = $layout;
        $array['module'] = $module;
        $array['notification_id'] = $notification_id;
        $array['form_name'] = $form_name;
        $array['wfmain_id']= $wfmain_id;

        //dd($array);
        return $array;

   
        // return view('document.index',compact('type','id','doctype','doctype_id','doctype_name','layout','module','notification_id','form_name'));

    }
    public function print(Request $request,$id){

        $car_id = Car::find($id);
        $request->document_type_id  = $car_id->document_type->id;
        
        //dd($car_id->document_type->code);
        $docmain =  SystemCarMain::create($request);
        //dd($docmain->show($id));
        $car =  $docmain->show($id);
        $form_name = $docmain->form_name;

       $info = array();

       $info['list_file'] = array();
       $list_file = array();
      // dd($request);
       $info['form_name'] = $form_name !=""?$form_name:($car->document_type?$car->document_type->name:"");
       $info['layout'] = $docmain->getLayout() ;

        if($car->other_attacheds){

            foreach ($car->other_attacheds as $file) {
                $attached_file['name'] = $file->name ;
                $attached_file['value'] = "" ;
                array_push( $list_file,$attached_file);
            }

        }
        $info['list_file']['file_attached']  =array();
        $info['list_file']['file_attached'] = $list_file;

        $approve = ApproveMain::create(Ultils::$MODULE_CARS, $id,$request);
        ///dd($approve);
        $approve_info = $approve->get_info();
        //dd($approve->get_info());
       // dd($approve_info['info_user_approve']);
       $signs = array();
       $step_3 =  array();
       $step_2 =  array();
       $step_1 =  array();
       // dd( $approve_info);
       if($approve_info['info_user_approve']){

          for ($i=0;$i<count($approve_info['info_user_approve']);$i++) {
            if($approve_info['info_user_approve'][$i]['step']==1 && $approve_info['info_user_approve'][$i]['finished']==1){

                array_push($step_1,$approve_info['info_user_approve'][$i]);
                $result['is_step1'] =  $step_1;
                $result['is_count_step1'] = count($step_1); 

                // $result['is_step1'] = $approve_info['info_user_approve'][$i]['step'];
                // $result['is_user_step1'] = $approve_info['info_user_approve'][$i]['user']['name'];
                // $result['is_checkout_step1'] = $approve_info['info_user_approve'][$i]['checkout'];
                // $position = PositionApprove::where('user_id',$approve_info['info_user_approve'][$i]['user']['id'])->get(); 
                // foreach($position as $pos){
                //     $name_position = Position::find($pos->position_id);
                //     $result['is_position_step1'] = $name_position->name;
                // }  
                }
            if($approve_info['info_user_approve'][$i]['step']==3 && $approve_info['info_user_approve'][$i]['finished']==1 && $car_id->document_type->code=='SCAR'){
                $result['is_step3'] = $approve_info['info_user_approve'][$i]['step'];
                $result['is_user_step3'] = $approve_info['info_user_approve'][$i]['user']['name'];
                $result['is_user_id_step3'] = $approve_info['info_user_approve'][$i]['user']['id'];
                $result['is_checkout_step3'] = $approve_info['info_user_approve'][$i]['checkout'];     
            }
         
            if($approve_info['info_user_approve'][$i]['step']==3 && $approve_info['info_user_approve'][$i]['finished']==1 && $car_id->document_type->code=='PCAR'){
                array_push($step_3,$approve_info['info_user_approve'][$i]);
                $result['is_step3'] =  $step_3;
                $result['is_count_step3'] = count($step_3);    
            }
            if($approve_info['info_user_approve'][$i]['step']==4 && $approve_info['info_user_approve'][$i]['finished']==1){
                $result['is_step4'] = $approve_info['info_user_approve'][$i]['step'];
                $result['is_user_step4'] = $approve_info['info_user_approve'][$i]['user']['name'];
                $result['is_user_id_step4'] = $approve_info['info_user_approve'][$i]['user']['id'];
                $result['is_checkout_step4'] = $approve_info['info_user_approve'][$i]['checkout'];     
            }
            if($approve_info['info_user_approve'][$i]['step']==2){
                array_push($step_2,$approve_info['info_user_approve'][$i]);
                $result['is_step2'] =  $step_2;
                $result['is_count_step2'] = count($step_2);   
            }

          }
       }
      //dd($car);

       $car_error =  CarError::where('description','QA')->where('active',1)->get();
       $car_error_qc =  CarError::where('description','QC')->where('active',1)->get();
      //dd($step_1);
     // dd($car['result_evaluation'][0]);
      // dd($car['cause_measure']);
        return view('car.report.index',compact('car','info','result','car_error','car_error_qc'));

    }
}
