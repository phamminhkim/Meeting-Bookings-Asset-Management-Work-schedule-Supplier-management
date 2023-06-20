<?php

namespace App\Repositories\approvewf;

use App\Models\car\Car;
use App\Models\car\CauseMeasure;
use App\Models\car\MonitorImplementation;
use App\Models\car\ResultEvaluation;
use App\Models\shared\DocumentType;
use App\Models\shared\Wfapproved;
use App\Models\shared\Team;
use App\Models\shared\Wfmain;
use App\Models\shared\Wfmainconfig;
use App\Models\shared\Wfstep;
use App\Models\shared\Wfstepconfig;
use App\Models\shared\Wfstepdetailconfig;
use App\Models\car\FastProcess;
use App\Repositories\car\causemeasure\CauseMeasureMain;
use App\Repositories\car\evaluate\ResultEvaluationAbs;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\CssSelector\Node\FunctionNode;

abstract class ApproveCarAbs
{

    protected $team;
    protected $object;
    protected $user_approve;
    protected $next_user;
    protected $list_approved;
    protected $is_owner = false;
    protected $errors;
    protected $request;
    protected $infos;
    protected $car_error_id;
    protected $cause;
    protected $risk;
    protected $is_cause_measure;
    protected $is_type_car;
    public function __construct($team, $object, $list_approved,$request,$infos,$car_error_id,$cause,$risk,$is_cause_measure,$is_type_car)
    {
        $this->team = $team;
       // $this->request = $request;
        $this->object = $object;
        $this->list_approved = $list_approved;
        $this->request = $request;
        $this->infos =$infos;
        $this->car_error_id = $car_error_id;
        $this->cause = $cause;
        $this->risk = $risk;
        $this->is_cause_measure = $is_cause_measure;
        $this->is_type_car = $is_type_car;
        //dd($this->list_approved);
        //cẩn thận sort nếu model bị thay đổi sort

        if ($this->team && $this->team->users) {
            $this->team->users = $this->team->users->sortBy('pivot.level')->sortBy('pivot.step');
        }

      //  dd( $this->team->users);
    }

    public function __set($name, $value)
    {
        $this->$name = $value;
    }
    public function __get($name)
    {
        return $this->$name;
    }
    //Hàm này sẽ được override thay đổi step ở các class con
    public function get_step_name($idstep)
    {
        $name = "Step ";
        $name = $name . $idstep;

        return $name;
    }
    //Lấy thông tin trong danh sách bảng duyệt theo id và step
    private function get_info_approve_by($user_id, $step)
    {
        foreach ($this->list_approved as $key => $info) {
            if ($info->user_id == $user_id && $info->step == $step && $info->online == 'X') {
                return $info;
            }
        }
        return null;
    }
    //lấy thông tin duyệt đã hoàn thành
    public function get_history_approved()
    {
        $result = array();
        $result['is_approve'] = false;
        $result['is_send'] = $this->is_send();
        $result['feedbacks'] = $this->get_feedback();
        $result['finished'] = 'X';
        $result['is_create'] = $this-> get_user_create();
        $info_last_approve = $this->get_info_approved_waiting();
        $info_user_approve = $this->get_user_approve();
        //$result['is_cause'] = $this->check_cause();
        //get_approved_online
        $result['is_approve'] = false;
        $result['user_wait'] = null;
        $list_history = $this->get_approved_online();
        $result['count_feedbacks'] = $this->count_feedback();
        $result['is_print'] = $this->check_print();
        if($info_user_approve){
            $result['info_user_approve'] =  $info_user_approve;
         //   $result['count_info_user_approve'] =  count($info_user_approve);
        }
        if ($list_history) {
            $steps = $list_history->pluck('step')->flatten();
            $steps->sort();
            $steps = array_unique($steps->toArray());
            $result['steps'] = array();
            foreach ($steps as $key => $step) {
                $stepinfo = [];
                $stepinfo['id'] = $step;
                $stepinfo['name'] = $this->get_step_name($step);

                $stepinfo['users'] = array();
                foreach ($list_history as $key => $history) {
                    if ($history->step == $step) {
                        $user = $history->user;
                        $user->release = "";
                        $user->checkout = null;
                        $user->checkin = null;
                        $user->waiting = false;

                        $user->release = $history->release;
                        $user->checkout = $history->checkout;
                        $user->checkin = $history->checkin;
                        $user->sign = $history->sign;
                        $user->review = $history->review;

                        array_push($stepinfo['users'], $user);
                    }

                }
                array_push($result['steps'], $stepinfo);
            }
            // dd( $steps);
        } else {
            $result['list_user'] = null;
            $result['steps'] = null;
        }

        return $result;
    }
    public function get_last_user_in_team(){

      return   $this->team->users[count($this->team->users) - 1];
    }
    /**
     * Lấy thông tin duyệt
     * nếu duyệt đã hoàn thành thì lấy lịch sử duyệt.
     * Nếu có sự thay đổi nhân sự trong nhóm duyệt thì lịch sử duyệt vẫn không thay đổi
     * Nếu đang duyệt thì hiển thị theo team duyệt hiện hành
     */
    public function get_info()
    {
       // dd("Test" );
       
        $info_last_approve = $this->get_info_approved_waiting();
        //Kiểm tra duyệt hoàn thành chưa
        if ($info_last_approve !=null && $info_last_approve->finished == 1) {
            return $this->get_history_approved();
        } else {
            return $this->get_info_online_by_team();
        }
    }
    //lấy thông tin đang duyệt và theo team hiện hành
    public function get_info_online_by_team()
    {
        $result = array();
        $result['is_approve'] = false;
        $result['is_send'] = $this->is_send();
        $result['feedbacks'] = $this->get_feedback();
        $result['is_car_cancel'] = $this->is_cancel();
        $info_last_approve = $this->get_info_approved_waiting();
        $info_step_approve = $this->get_wfstepapp();
        $info_user_approve = $this->get_user_approve();
        $result['is_create'] = $this->get_user_create();
        $result['is_step_exist'] = $this->get_step_exist();
        $result['is_fast_process'] = $this->check_fast_process();
        $result['is_cause'] = $this->check_cause();
        $result['is_monitor'] = $this->check_monitor();
        $result['is_evaluation'] = $this->check_evaluations();
        $result['is_checkout_step1'] = $this->check__checkout_step1();
        $result['is_checkout_step2'] = $this->check__checkout_step2();
        $result['is_checkout_step3'] = $this->check__checkout_step3();
        $result['count_feedbacks'] = $this->count_feedback();
        $result['is_step1_finished'] = $this->is_step1_finished();
        $result['is_refuse_step2'] = $this->check_refuse_step2();
        $result['is_refuse_step3'] = $this->check_refuse_step3();
        $result['is_refuse_step4'] = $this->check_refuse_step4();
        $result['is_type_car'] = $this->get_type_car();
        $result['is_print'] = $this->check_print();
        
        
        
        
        
        
       
       
        //Kiểm tra user đang login có phải là user chờ duyệt không?
        if($info_step_approve){
            $result['step_approve'] =  $info_step_approve;
        }
        if($info_user_approve){
            $result['info_user_approve'] =  $info_user_approve;
            
        }
       // dd($info_last_approve);
        if ($info_last_approve) {
            if ($info_last_approve->user_id == auth()->user()->id && $info_last_approve->release == 0) { //Nếu đã phản hồi thì không hiển thị duyệt
                $result['is_approve'] = true;
            }

            //$user = $this->get_user_in_team($info_last_approve->user_id, $info_last_approve->step, $info_last_approve->level);
            $result['user_wait'] = "";
        }

        //dd( $result['is_approve'] );
       // dd($this->object);
        // if ($this->team) {
        //     $result['list_user'] = $this->team->users;
        //     $steps = $this->team->users->pluck('pivot.step')->flatten();
        //     $steps->sort();
        //     $steps = array_unique($steps->toArray());
        //     // dd($steps);
        //     $user_wait = $this->user_wait_approved();
        //     //Danh sách user không được duyệt

        //     $list_id_not_approve = $this->get_list_user_not_approved()->pluck('id')->flatten();


        //     //Phân loại steps
        //     $result['steps'] = array();
        //     foreach ($steps as $key => $step) {
        //         $stepinfo = [];
        //         $stepinfo['id'] = $step;
        //         $stepinfo['name'] = $this->get_step_name($step);

        //         $stepinfo['users'] = array();
        //         foreach ($this->team->users as $key => $user) {
        //             if ($user->pivot->step == $step) {
        //                 //dd("test1");
        //                 $user->release = "";
        //                 $user->checkout = null;
        //                 $user->checkin = null;
        //                 $user->waiting = false;

        //                 $info_approve = $this->get_info_approve_by($user->id, $user->pivot->step);

        //                 if ($info_approve) {

        //                     $user->release = $info_approve->release;
        //                     $user->checkout = $info_approve->checkout;
        //                     $user->checkin = $info_approve->checkin;
        //                     $user->checkin = $info_approve->checkin;
        //                     $user->review = $info_approve->review;

        //                     // $stepinfo['users']['status'] = true;
        //                 }

        //                 //Kiểm tra user đang chờ duyệt ở step nào
        //                 if ($user_wait && $user_wait->id == $user->id && $user_wait->pivot->step == $user->pivot->step) {

        //                     $user->waiting = true;
        //                 }
        //                 //Ẩn các user trong team nhưng không có quyền duyệt: 2 trường hợp
        //                 //1.User trong team tạo yêu cầu duyệt
        //                 //2.User tạo
        //                 //3. Trường hợp đặc biệt là các Nhân viên reivew kế toán ở top
        //                 if( $user->id != $this->object->user_id && !$list_id_not_approve->contains($user->id)){
        //                     array_push($stepinfo['users'], $user);
        //                 }


        //             }

        //         }
        //         array_push($result['steps'], $stepinfo);
        //     }
        //     //$result['steps'] = array_unique($steps->toArray()) ;
        // } else {
        //     $result['list_user'] = null;
        //     $result['steps'] = null;
        // }
      //  dd($result);
        return $result;

    }
    //NGười tạo chứng từ mới có quyền gửi duyệt lần đầu hoặc khi phản hồi
    private function is_send()
    {

        $info_approve_wait = null;
        if ($this->list_approved && count($this->list_approved) > 0) {
            $info_approve_wait = $this->list_approved[count($this->list_approved) - 1];
            if ($info_approve_wait && $info_approve_wait->release == 1
                && $this->object->user_id == auth()->user()->id) {
                return true;
            }
            return false;
        } else {
            if ($this->object->user_id == auth()->user()->id) {
                return true;
            } else {
                return false;
            }

        }

    }
    //lấy user  duyệt lúc gửi bởi user bất kỳ , kể cả user review
    private function get_user_approved_first()
    {
        // $count = 0;
        // $user_approve = $this->team->users->first();

        // //nếu user đầu tiên là review và user này không phải là user tạo chứng từ
        // if ($user_approve->pivot->review && $user_approve->pivot->user_id != $this->object->user_id) {
        //     //To do nothing
        // } else {
        //     $is_owner = false;
        //     foreach ($this->team->users as $key => $value) {

        //         if ($value->pivot->user_id == $this->object->user_id) {
        //             $is_owner = true;
        //             $user_approve = $value;
        //         }
        //         //tìm thấy vị trí user gửi thì bắt đầu đếm
        //         if ($is_owner) {
        //             $count++;

        //         }
        //         //Lấy vị trí tiếp theo người gửi
        //         if ($count == 2) {
        //             $user_approve = $value;
        //             break;
        //         }

        //     }
        // }
        // // dd($user_approve);
        $user_approve = null;
        if ($this->list_approved && count($this->list_approved)>0) {
            $user_approve =$this->list_approved[0];
            if ($user_approve->next_step != 'X') {
                $user_approve = null;
            }
        }

        return $user_approve;

    }
    //Lúc user review gửi - tìm vị trí người gửi mà có trong danh sách duyệt (trừ người review)
    //Đây là phần nhảy cóc từ reivew cho đến vị trí người duyệt (tính từ vị trí người gửi có trong cây duyệt)
    private function get_user_in_list_approved_from_review()
    {
        $count = 0;
        $user_approve = $this->team->users->first();

        $is_owner = false;
        foreach ($this->team->users as $key => $value) {

            if ($value->pivot->user_id == $this->object->user_id) {
                if(!$value->pivot->review){
                    $is_owner = true;
                }

                $user_approve = $value;
            }
            //tìm thấy vị trí user gửi thì bắt đầu đếm
            if ($is_owner) {
                $count++;

            }
            //Lấy vị trí tiếp theo người gửi
            if ($count == 2) {
                $user_approve = $value;
                break;
            }

        }
        //nếu người tạo danh sách không có trong danh sách duyệt thì trả về null
        if (!$is_owner) {
            $user_approve = null;
        }

        return $user_approve;

    }
    private function is_user_create_in_team(){
        $flag = false;
        foreach ($this->team->users as  $u) {
            if($u->pivot->user_id == $this->object->user_id){
                $flag = true;
                break;
            }

        }

        return $flag;
    }
    private function is_user_create_and_review_in_team(){
        $flag = false;
        foreach ($this->team->users as  $u) {
            if($u->pivot->user_id == $this->object->user_id && $u->pivot->review){
                $flag = true;
                break;
            }

        }

        return $flag;
    }
     //Lúc danh sách user trong team duyệt nhưng không có quyền duyệt
     private function get_list_user_not_approved()
     {

        $list = collect();

        $list_not_approve = collect();
        if($this->is_user_create_in_team()){
            //Check user reivew in top
            if($this->is_user_create_and_review_in_team()){

                foreach ($this->team->users as $key => $u) {
                   if($u->pivot->user_id == $this->object->user_id){
                    $list_not_approve->add($u);
                    break;
                   }


                }

            }else{
                   //Loại bỏ các user review ở top
            foreach ($this->team->users as $key => $u) {
                if($u->pivot->review){
                    continue;

                }
                $list->add($u);


            }
            // dd($list);
                //,Chạy xuống phía dưới cho tới khi gặp user tạo
                foreach ($list as  $u) {

                    if($u->pivot->user_id == $this->object->user_id  ) {

                        $list_not_approve->add($u);
                        //array_push($list_not_approve,$u);
                    //  dd($list_not_approve[1]);
                        break;
                    } else{
                        $list_not_approve->add($u);
                    }
                }
            }

        }

         return $list_not_approve;

     }
    //Lấy thông tin đang chờ duyệt
    private function get_wfstepapp(){
        $step = "";
       //dd($this->list_approved);
        if($this->list_approved){
           
            for($i=0;$i<count($this->list_approved);$i++){
                if($this->list_approved[$i]->checkout===null && $this->list_approved[$i]->online==='X'){
                    $step = $this->list_approved[$i]->step;
                    break;
                }
            }

        }
        //dd($step);
        return $step;
    }
    private function get_step_exist(){
        $step_exist = array();
        if($this->list_approved){ 
            foreach($this->list_approved as $step){
                array_push($step_exist,$step->step);
            }
        }
        return $step_exist;
    }
    private function get_user_approve(){
        $info_user_approve =  array();
        if($this->list_approved){
        $info_user_approve = Wfapproved::Select('wfapprovedable_id','user_id','checkout','step','finished','release','expected_time')->where('wfapprovedable_id',$this->object->id)->where('online','X')->with('user')->get();
        }
        return $info_user_approve;
    }
    private function check__checkout_step1(){
        $info_step1_approve =  0;
        $info_step1_approve = Wfapproved::where('wfapprovedable_id',$this->object->id)->where('step','1')->where('checkout','<>',null)->where('online','X')->count();
        if($info_step1_approve>0)
        return true;
        return false;
    }
    private function check__checkout_step2(){
        $info_step2_approve =  0;
        $info_step2_approve = Wfapproved::where('wfapprovedable_id',$this->object->id)->where('step','2')->where('checkout','<>',null)->where('online','X')->get();
        $count  = count($info_step2_approve);
        if($count>0 &&  $info_step2_approve[$count-1]['finished']===1)
        return true;
        return false;
    }
    private function is_step1_finished(){
        $info_step1_approve =  0;
        $info_step1_approve = Wfapproved::where('wfapprovedable_id',$this->object->id)->where('step','1')->where('checkout','<>',null)->where('finished',1)->where('online','X')->count();
        if($info_step1_approve>0)
        return true;
        return false;
    }
    private function check_refuse_step2(){
        $info_step2_approve =  0;
        $info_step2_approve = Wfapproved::where('wfapprovedable_id',$this->object->id)->where('step','2')->where('checkout','<>',null)->where('release',-2)->where('online','X')->count();
        if($info_step2_approve>0)
        return true;
        return false;
    }
    private function check__checkout_step3(){
        $info_step3_approve =  0;
        $info_step3_approve = Wfapproved::where('wfapprovedable_id',$this->object->id)->where('step','3')->where('checkout','<>',null)->where('online','X')->get();
        $count  = count($info_step3_approve);
        if($count>0 &&  $info_step3_approve[$count-1]['finished']===1)
        return true;
        return false;
    }
    private function check_refuse_step3(){
        $info_step3_approve =  0;
        $info_step3_approve = Wfapproved::where('wfapprovedable_id',$this->object->id)->where('step','3')->where('checkout','<>',null)->where('release',-2)->where('online','X')->count();
        if($info_step3_approve>0)
        return true;
        return false;
    }
    private function check_refuse_step4(){
        $info_step4_approve =  0;
        $info_step4_approve = Wfapproved::where('wfapprovedable_id',$this->object->id)->where('step','4')->where('checkout','<>',null)->where('release',-2)->where('online','X')->count();
        if($info_step4_approve>0)
        return true;
        return false;
    }
    private function get_type_car(){
        $car = Car::find($this->object->id);
        $type = DocumentType::find($car->document_type_id);
        return $type->code;
    }
    private function finshed_pcar(){
        $car = Car::find($this->object->id);
        $last_step2_approve = Wfapproved::where('wfapprovedable_id',$this->object->id)->where('step','2')->where('online','X')->get();
        $count = count($last_step2_approve);
        $user_id =  $last_step2_approve[$count-1]['user_id'];
        $type = DocumentType::find($car->document_type_id);
        if($type->code=='PCAR' && $car->is_cause_measure===0 && $user_id == auth()->user()->id)
        return true;
        return false;
    }
    private function get_user_create(){
        $car = Car::find($this->object->id);
        if($car->proposer=== auth()->user()->id)
        return true;
        return false;
    }
    private function check_fast_process(){
        $check_exist = FastProcess::where('car_id',$this->object->id)->count();
        if($check_exist)
        return true;
        return false;
    }
    private function check_cause(){
        $check_exist = CauseMeasure::where('car_id',$this->object->id)->count();
        if($check_exist)
        return true;
        return false;
    }
    private function check_monitor(){
        $check_exist = MonitorImplementation::where('car_id',$this->object->id)->count();
        if($check_exist)
        return true;
        return false;
    } 
    private function check_evaluations(){
        $check_exist = ResultEvaluation::where('car_id',$this->object->id)->count();
        if($check_exist)
        return true;
        return false;
    } 
    private function check_print(){
        $car =$this->object->status;
        if($car===2)
        return true;
        return false;
    }
    // private function get_cause(){
    //     $list_step = Wfapproved::where('wfapprovedable_id',$this->object->id)->where('online','X')->get(); 
    //     $car = Car::find($this->object->id);
    //         if($car->user_id=== auth()->user()->id && count($list_step)>0){
    //             for($i=0; $i<count($list_step);$i++){
    //                 if($list_step[$i]->step === 1 && $list_step[$i]->checkout <> null && $list_step[$i]->finished=== 1){
    //                    return true; 
    //                 }elseif($list_step[$i]->step === 2 && $list_step[$i]->checkout === null && $list_step[$i]->finished=== null){
    //                         return true;
    //                         break;
    //                 }else{
    //                     return false;
    //                 }
    //             }
    //         }else{
    //             return false;
    //         }
           
    // }
    private function get_info_approved_waiting()
    {
       // dd($this->object->id);
       $id_approve ="";
       $list_step2 = Wfapproved::where('wfapprovedable_id',$this->object->id)->where('online','X')->get();
       if(count($list_step2)==='1'){
           foreach($list_step2 as $st){
            $id_approve = $st->wfapprovedable_id;
            }
       }else{
           //$i = 0;
           //dd(count($list_step2));
           for($i=0; $i<count($list_step2);$i++){
           //dd($list_step2[1]->user_id);
                   if(count($list_step2)==1 && $list_step2[0]->user_id === auth()->user()->id && $list_step2[0]->finished==0){
                      // dd($list_step2[$i]->wfapprovedable_id);
                      $id_approve = $list_step2[$i]->id; 
                   }
                   if(count($list_step2)>1 && $list_step2[0]->user_id === auth()->user()->id && $list_step2[0]->finished==0){
                    // dd($list_step2[$i]->wfapprovedable_id);
                    $id_approve = $list_step2[0]->id; 
                 }
                   if($i>0 && count($list_step2)>1 && $list_step2[$i-1]->checkout <> null && $list_step2[$i-1]->finished==1 && $list_step2[$i]->user_id === auth()->user()->id && $list_step2[$i]->finished==0){
                      // dd($list_step2[$i]->user_id);
                      $id_approve = $list_step2[$i]->id;
                   }
           }
       }
     
       $info_approve_wait = Wfapproved::find($id_approve);
      
        //dd($info_approve_wait);
        return $info_approve_wait;
    }
    private function get_user_approve_next($user_current)
    {

        $count = 0;
        $user_approve_next = null;

        foreach ($this->team->users as $key => $value) {

            if ($user_current && $value->pivot->step == $user_current->pivot->step
                && $value->pivot->level == $user_current->pivot->level) {
                $count++;
            }

            if ($count == 2) {

                 if($value->id != $this->object->user_id)
                 {
                    $user_approve_next = $value;
                    break;
                 }else{
                    //bỏ qua user tạo có trong cây duyệt
                    $count = 1;

                 }

            }
            //Đếm cho lần tiếp theo
            if ($count == 1) {
                $count++;
            }

        }

        return $user_approve_next;

    }
    private function user_wait_approved()
    {
        $info_approve_wait = null;
        $user = null;
        //lấy thông tin cuối cùng : đang chờ duyệt
        $info_approve_wait = $this->get_info_approved_waiting();

        if ($info_approve_wait) {
            $user = $this->get_user_in_team($info_approve_wait->user_id, $info_approve_wait->step, $info_approve_wait->level);
        }

        return $user;

    }
    private function get_user_in_team($id, $car_id)
    {
       $next_user = null;
       $step_approve =  Wfapproved::where('wfapprovedable_id',$car_id)->where('online','X')->get();
       if(count($step_approve)>1){
        for($i=0;$i<count($step_approve);$i++){
            if($step_approve[$i]->id ===$id && $i<count($step_approve)-1){
             //$next_user = $step_approve[$i+1]->user_id;
             $next_user = Wfapproved::find($step_approve[$i+1]->id);
             break;
            }
            if($step_approve[$i]->id ===$id && $i==count($step_approve)-1){
                $next_user = null; 
                break;
            }
        }
       }else{
        $next_user = null;  
       }
     
        // $user = null;
        // foreach ($this->team->users as $key => $value) {

        //     if ($value->pivot->step == $step
        //         && $value->pivot->level == $level) {
        //         $user = $value;
        //         break;
        //     }

        // }
        return $next_user;
    }
    //reset các trạng thái duyệt sang offline ( cột online = '')
    private function reset_approved()
    {
        foreach ($this->list_approved as  $oldApprove) {
            $oldApprove->online = '';
            $oldApprove->save();
        }

        //tạo lại danh sách mới
        foreach ($this->list_approved as  $newApprove) {
            $newApprove->online = 'X';
            $newApprove->id = '';
            $newApprove->save();
        }

    }

    /**
     * khi nào thì được add user:
     * 1-Khi chứng từ chưa được gửi lần nào,
     * 2-Khi chứng từ gửi duyệt lại
     * 3-Khi chuyển sang step khác - nghĩa là step hiện tại đã được duyệt - nhưng còn step tiếp theo
     *
     * - Có thể chỉnh sửa nếu trạng thái chưa được gửi duyệt (next_step ='')
     *
     * Lấy danh sách user từ người duyệt, gửi lên
     * Hệ thống sẽ kiểm tra danh sách người duyệt này và thêm vào list
     * Kiểm tra qui trình đang tới step nào
     *
     */
    public function add_users(){
        try {

            DB::beginTransaction();
            //xác định user duyệt
            $approve = null;
            //lấy thông tin cuối cùng : đang chờ duyệt
            $approve = $this->get_info_approved_waiting();
            //Kiểm tra người đang chờ duyệt là người login
            if (!$approve || $approve->user_id != auth()->user()->id) {
                abort(404);
            }
            if (!$this->validate()) {
                return null;
            }
            //cập nhật trạng thái duyệt
            $approve->checkin = now();
            $approve->checkout = now();
            $approve->release = 1; // từ chối duyệt
           // $approve->note = $feedback; // từ chối duyệt

            $approve->save();
            $this->update_status_refuse();

            $this->notifycation($approve); //Thông báo phản hồi cho người tạo:  $approve->release = 1
            DB::commit();
            return $approve;
            // $result['data']['feedback'] = $approve;
            // $result['data']['success'] = 1;

        } catch (\Exception $e) {
            DB::rollback();
            $this->errors = $e->getMessage();
            //dd($this->errors);
            return null;
        }
    }
    public function send()
    {       
        //$approve_info = null;
      //dd($this->infos);
        try {
           
            DB::beginTransaction();
            //dd($this->request);
           
            if ($this->object && auth()->user()->id == $this->object->proposer) {
                $wfmain = Wfmain::find($this->object->wfmain_id);
             
                // $wfstep = Wfstep::where('wfmain_id',$this->object->wfmain_id)->get();
               $check_exist = Wfmainconfig::where('wfmainconfigable_id',$this->object->id)->first();
              // dd($check_exist);
               if($check_exist==null){
               $wfmainconfig = new Wfmainconfig();
                $wfmainconfig->name =  $wfmain->name;
                $wfmainconfig->description =  $wfmain->name;
                //dd($this->object->wfmainconfigables()->save($wfmainconfig));
                $this->object->wfmainconfigables()->save($wfmainconfig);
                // dd($wfmainconfig->id);
               
                foreach($wfmain->wfsteps as $wftep) {
                  $wfstepconfig =   Wfstepconfig::create([
                       'name' => $wftep->name,
                       'description'  => $wftep->description,
                        'next_user'  => $wftep->next_user,
                        'form_approval'  => $wftep->form_approval,
                        'is_end'  => $wftep->is_end,
                        'wfmainconfig_id'  => $wfmainconfig->id,
                        'wfusertype_id'=> $wftep->wfusertype_id,
                    ]);
                  
                     foreach ($wftep->details as $detail) {
                        //dd($detail);
                        $wfstepdetailconfig =   Wfstepdetailconfig::create([
                            'wfstepconfig_id' => $wfstepconfig->id,
                            'wfusertype_id'  => $detail->wfusertype_id,
                             'wftapptype_id'  => $detail->wfapptype_id,
                             'level'  => $detail->level,
                             'required'  => $detail->required
                         ]);
                     }
                  }
                //   $list_approve = $this->request;
                //  // dd( $list_approve);
                //   $wfstep = Wfstep::find($list_approve['wfstep_id']);
                //   $wfapproved =  new Wfapproved();
                //   $wfapproved->user_id = $list_approve['user_id'];
                //   $wfapproved->step = $list_approve['wfstep_id'];
                //   foreach ($wfstep->details as $detail) {
                //       $wfapproved->level = $detail->level;
                //       $wfapproved->version = 1;
                //       $wfapproved->online = 'X';
                //       $wfapproved->finished = 0;
                //       $wfapproved->wfapptype_id = $detail->wfapptype_id;
                //       $wfapproved->wfusertype_id = $detail->wfusertype_id;
                //   }
                //   //dd($this->object->approveds()->save($wfapproved));
                //   $this->object->approveds()->save($wfapproved);  
                }
             //$this->errors= 'Cấu hình đã tồn tại';
             $list_approve = $this->request;
           // dd( $list_approve);
            //dd($list_approve['expected_time']);
             //dd($list_user);
            // dd($list_approve['wfstep_id']);
            
              $wfstep = Wfstep::find($list_approve['wfstep_id']);
              $check_wfapproved = Wfapproved::where('wfapprovedable_id',$this->object->id)
                                            ->where('step',$list_approve['wfstep_id'])
                                            ->first();
             $check_step_exist = Wfapproved::where('wfapprovedable_id',$this->object->id)
             ->where('step',$list_approve['wfstep_id'])
             ->where('checkout',null)
             ->count();
             $list_wfapproved = Wfapproved::where('wfapprovedable_id',$this->object->id)
             ->where('step',$list_approve['wfstep_id'])
             ->get();
             //dd(count($list_wfapproved));
             $check_refuse_step2 = $this->check_refuse_step2();
             $check_refuse_step3 = $this->check_refuse_step3();
             $check_refuse_step4 = $this->check_refuse_step4();
            //dd($check_wfapproved['release']);
              //dd(count($list_approve['user_id']));
              $wfapproved =  new Wfapproved();
   
              if(($list_approve['wfstep_id']=== 1 && $check_wfapproved === null && $list_approve['type_car']=="SCAR") || ($list_approve['wfstep_id']=== 3 && $check_wfapproved === null && $list_approve['type_car']=="SCAR") || ($list_approve['wfstep_id']=== 4 && $check_wfapproved === null && $list_approve['type_car']=="PCAR")){ 
                // dd($list_approve);
                $wfapproved->user_id = $list_approve['user_id'];
                $wfapproved->step = $list_approve['wfstep_id'];
                $wfapproved->expected_time = $list_approve['expected_time'];
               // dd($wfstep->details);
                foreach ($wfstep->details as $detail) {
                    $wfapproved->level = $detail->level;
                    $wfapproved->version = 1;
                    $wfapproved->online = 'X';
                    $wfapproved->finished = 0;
                    $wfapproved->wfapptype_id = $detail->wfapptype_id;
                    $wfapproved->wfusertype_id = $detail->wfusertype_id;
                }
               // dd($this->object->approveds()->save($wfapproved));
                $this->object->approveds()->save($wfapproved); 
                //dd($this->notifycation($wfapproved));
                $this->notifycation($wfapproved);
                if($list_approve['staff_qa']!=null){
                    Car::where('id',$this->object->id)->update(['proposer'=>$list_approve['staff_qa']]);
                }

              }
              elseif(($list_approve['wfstep_id']=== 1 && $check_wfapproved === null && $list_approve['type_car']=="PCAR")){
                // dd($list_approve);
                $array_list_step1 = array();
                $array_date_step1 = array();
                $wfapproved->user_id = $list_approve['user_id'];
                $wfapproved->step = $list_approve['wfstep_id'];
                $wfapproved->expected_time = $list_approve['expected_time'];
                array_push($array_list_step1,$list_approve['staff_qa'],$list_approve['user_id_tpqa']);
                array_push($array_date_step1,$list_approve['expected_time_qa'],$list_approve['expected_time_tpqa']);
               // dd($wfstep->details);
                foreach ($wfstep->details as $detail) {
                    $wfapproved->level = $detail->level;
                    $wfapproved->version = 1;
                    $wfapproved->online = 'X';
                    $wfapproved->finished = 0;
                    $wfapproved->wfapptype_id = $detail->wfapptype_id;
                    $wfapproved->wfusertype_id = $detail->wfusertype_id;
                }
               // dd($this->object->approveds()->save($wfapproved));
                $this->object->approveds()->save($wfapproved); 
                $this->notifycation($wfapproved);

                
                 foreach($array_list_step1 as $key=>$value){
                    $wfapproved_list =  new Wfapproved();
                    $wfapproved_list->user_id = $value;
                    $wfapproved_list->step = $list_approve['wfstep_id'];
                    $wfapproved_list->expected_time = $array_date_step1[$key];
                // dd($wfstep->details);
                 foreach ($wfstep->details as $detail) {
                     $wfapproved_list->level = $detail->level;
                     $wfapproved_list->version = 1;
                     $wfapproved_list->online = 'X';
                     $wfapproved_list->finished = 0;
                     $wfapproved_list->wfapptype_id = $detail->wfapptype_id;
                     $wfapproved_list->wfusertype_id = $detail->wfusertype_id;
                 }
                // dd($this->object->approveds()->save($wfapproved));
                 $this->object->approveds()->save($wfapproved_list); 
                 //dd($this->notifycation($wfapproved));
               
                //  if($list_approve['staff_qa']!=null){
                //      Car::where('id',$this->object->id)->update(['proposer'=>$list_approve['staff_qa']]);
                //  }
                }
 
               }
              elseif($list_approve['wfstep_id']=== 1 && $check_wfapproved['release']===-2 && $check_wfapproved['finished']===0 && $check_step_exist===0){  
                $updateDate = Wfapproved::where('wfapprovedable_id',$this->object->id)
                                             ->where('step',1)
                                             ->update(['online'=>'Y']);

                    if($updateDate){
                        $wfapproved =  new Wfapproved();
                        $wfapproved->user_id = $list_approve['user_id'];
                        $wfapproved->step = $list_approve['wfstep_id'];
                        $wfapproved->expected_time = $list_approve['expected_time'];
                        foreach ($wfstep->details as $detail) {
                            $wfapproved->level = $detail->level;
                            $wfapproved->version = 1;
                            $wfapproved->online = 'X';
                            $wfapproved->finished = 0;
                            $wfapproved->wfapptype_id = $detail->wfapptype_id;
                            $wfapproved->wfusertype_id = $detail->wfusertype_id;
                        } 
                        $this->object->approveds()->save($wfapproved);
                        $this->notifycation($wfapproved);
                        if($list_approve['staff_qa']!=null){
                            Car::where('id',$this->object->id)->update(['status'=>0,'proposer'=>$list_approve['staff_qa']]);
                        }else{
                            Car::where('id',$this->object->id)->update(['status'=>0]);
                        }
                    }
              }
              elseif(($list_approve['wfstep_id']=== 1 && $check_wfapproved['release']===-2 && $check_wfapproved['finished']===0 && $check_step_exist>0 && $list_approve['type_car']=="PCAR") || 
              (((count( $list_wfapproved)>0 && $list_wfapproved[0]['release']===-2 && $list_wfapproved[0]['finished']===0) ||
              (count( $list_wfapproved)>0 && $list_wfapproved[1]['release']===-2 && $list_wfapproved[1]['finished']===0) ||
              (count( $list_wfapproved)>0 && $list_wfapproved[2]['release']===-2 && $list_wfapproved[2]['finished']===0)
              ) && count($list_wfapproved)>0 && $list_approve['type_car']=="PCAR" && $list_approve['wfstep_id']=== 1)){  
               
                $updateDate = Wfapproved::where('wfapprovedable_id',$this->object->id)
                                             ->where('step',1)
                                             ->update(['online'=>'Y']);

                    if($updateDate){
                        $array_list_step1 = array();
                        $array_date_step1 = array();
                        $wfapproved->user_id = $list_approve['user_id'];
                        $wfapproved->step = $list_approve['wfstep_id'];
                        $wfapproved->expected_time = $list_approve['expected_time'];
                        array_push($array_list_step1,$list_approve['staff_qa'],$list_approve['user_id_tpqa']);
                        array_push($array_date_step1,$list_approve['expected_time_qa'],$list_approve['expected_time_tpqa']);
                       // dd($wfstep->details);
                        foreach ($wfstep->details as $detail) {
                            $wfapproved->level = $detail->level;
                            $wfapproved->version = 1;
                            $wfapproved->online = 'X';
                            $wfapproved->finished = 0;
                            $wfapproved->wfapptype_id = $detail->wfapptype_id;
                            $wfapproved->wfusertype_id = $detail->wfusertype_id;
                        }
                       // dd($this->object->approveds()->save($wfapproved));
                        $this->object->approveds()->save($wfapproved); 
                        $this->notifycation($wfapproved);
        
                        
                         foreach($array_list_step1 as $key=>$value){
                            $wfapproved_list =  new Wfapproved();
                            $wfapproved_list->user_id = $value;
                            $wfapproved_list->step = $list_approve['wfstep_id'];
                            $wfapproved_list->expected_time = $array_date_step1[$key];
                        // dd($wfstep->details);
                         foreach ($wfstep->details as $detail) {
                             $wfapproved_list->level = $detail->level;
                             $wfapproved_list->version = 1;
                             $wfapproved_list->online = 'X';
                             $wfapproved_list->finished = 0;
                             $wfapproved_list->wfapptype_id = $detail->wfapptype_id;
                             $wfapproved_list->wfusertype_id = $detail->wfusertype_id;
                         }
                         $this->object->approveds()->save($wfapproved_list); 
                        }
                    }
              }
              elseif(($list_approve['wfstep_id']=== 2 && $check_wfapproved === null && $list_approve['user_id'] != null) ||($list_approve['wfstep_id']=== 3 && $check_wfapproved === null && $list_approve['user_id'] != null && $list_approve['type_car']=="PCAR")){
                //dd($list_approve);
                $array_list = array();
                $array_date = array();
                $list_user = $this->infos;
                //dd($list_user);
                $wfapproved =  new Wfapproved();
               //dd($list_approve['wfstep_id']);
              // dd($list_approve['expected_time']);
                $wfapproved->user_id = $list_approve['user_id'];
                $wfapproved->step = $list_approve['wfstep_id'];
                $wfapproved->expected_time = $list_approve['expected_time'];
                foreach ($wfstep->details as $detail) {
                    $wfapproved->level = $detail->level;
                    $wfapproved->version = 1;
                    $wfapproved->online = 'X';
                    $wfapproved->finished = 0;
                    $wfapproved->wfapptype_id = $detail->wfapptype_id;
                    $wfapproved->wfusertype_id = $detail->wfusertype_id;
                }
               //dd($this->object->approveds()->save($wfapproved));
               $this->object->approveds()->save($wfapproved);
               $this->notifycation($wfapproved); 
                //dd( $list_user);
              
                foreach($list_user as $value){
                $wfapproved_user =  new Wfapproved();
                $wfapproved_user->user_id = $value['user_id'];
                $wfapproved_user->step = $list_approve['wfstep_id'];
                $wfapproved_user->expected_time = $value['expected_time'];
                foreach ($wfstep->details as $detail) {
                    $wfapproved_user->level = $detail->level;
                    $wfapproved_user->version = 1;
                    $wfapproved_user->online = 'X';
                    $wfapproved_user->finished = 0;
                    $wfapproved_user->wfapptype_id = $detail->wfapptype_id;
                    $wfapproved_user->wfusertype_id = $detail->wfusertype_id;
                }
              // dd($wfapproved);
               //dd($this->object->approveds()->save($wfapproved));
               $this->object->approveds()->save($wfapproved_user);                    
                }
               ///dd($list_approve);
               //dd($list_approve['expected_timexx']);
              if($list_approve['user_idxx']!=null){
                array_push($array_list,$list_approve['user_idxx'],$list_approve['user_idpd']);
                array_push($array_date,$list_approve['expected_timexx'],$list_approve['expected_timepd']);
                
            }
               else{
                array_push($array_list,$list_approve['user_idpd']);
                array_push($array_date,$list_approve['expected_timepd']);
               }
               // dd( $array_list); 
               //dd($array_date);
                foreach($array_list as $key=>$value){
                    $wfapproved_list =  new Wfapproved();
                    $wfapproved_list->user_id = $value;
                    $wfapproved_list->step = $list_approve['wfstep_id'];
                    $wfapproved_list->expected_time = $array_date[$key];
                    foreach ($wfstep->details as $detail) {
                        $wfapproved_list->level = $detail->level;
                        $wfapproved_list->version = 1;
                        $wfapproved_list->online = 'X';
                        $wfapproved_list->finished = 0;
                        $wfapproved_list->wfapptype_id = $detail->wfapptype_id;
                        $wfapproved_list->wfusertype_id = $detail->wfusertype_id;
                    }
                   // dd($this->object->approveds()->save($wfapproved));
                   //dd($wfapproved);
                    $this->object->approveds()->save($wfapproved_list);   
                }  
              }elseif($list_approve['wfstep_id']=== 2 && $check_refuse_step2===true && $list_approve['user_id'] != null){
                    $updateDate = Wfapproved::where('wfapprovedable_id',$this->object->id)
                    ->where('step', $list_approve['wfstep_id'])
                    ->update(['online'=>'Y']);
                    if($updateDate){
                        $array_list = array();
                        $array_date = array();
                        $list_user = $this->infos;
                        $wfapproved =  new Wfapproved();
                       //dd($list_approve['wfstep_id']);
                        $wfapproved->user_id = $list_approve['user_id'];
                        $wfapproved->step = $list_approve['wfstep_id'];
                        $wfapproved->expected_time = $list_approve['expected_time'];
                        foreach ($wfstep->details as $detail) {
                            $wfapproved->level = $detail->level;
                            $wfapproved->version = 1;
                            $wfapproved->online = 'X';
                            $wfapproved->finished = 0;
                            $wfapproved->wfapptype_id = $detail->wfapptype_id;
                            $wfapproved->wfusertype_id = $detail->wfusertype_id;
                        }
                       //dd($this->object->approveds()->save($wfapproved));
                       $this->object->approveds()->save($wfapproved); 
                       $this->notifycation($wfapproved);
                        //dd( $list_user);
                      
                        foreach($list_user as $value){
                        $wfapproved_user =  new Wfapproved();
                        $wfapproved_user->user_id = $value['user_id'];
                        $wfapproved_user->step = $list_approve['wfstep_id'];
                        $wfapproved_user->expected_time = $value['expected_time'];
                        foreach ($wfstep->details as $detail) {
                            $wfapproved_user->level = $detail->level;
                            $wfapproved_user->version = 1;
                            $wfapproved_user->online = 'X';
                            $wfapproved_user->finished = 0;
                            $wfapproved_user->wfapptype_id = $detail->wfapptype_id;
                            $wfapproved_user->wfusertype_id = $detail->wfusertype_id;
                        }
                      // dd($wfapproved);
                       //dd($this->object->approveds()->save($wfapproved));
                       $this->object->approveds()->save($wfapproved_user);                    
                        }
                      //  dd($array_user);
                      if($list_approve['user_idxx']!=null){
                        array_push($array_list,$list_approve['user_idxx'],$list_approve['user_idpd']);
                        array_push($array_date,$list_approve['expected_timexx'],$list_approve['expected_timepd']);
                      }
                       else{
                        array_push($array_list,$list_approve['user_idpd']);
                        array_push($array_date,$list_approve['expected_timepd']);
                       }
                       // dd( $array_list); 
                        foreach($array_list as $key=>$value){
                            $wfapproved_list =  new Wfapproved();
                            $wfapproved_list->user_id = $value;
                            $wfapproved_list->step = $list_approve['wfstep_id'];
                            $wfapproved_list->expected_time = $array_date[$key];
                            foreach ($wfstep->details as $detail) {
                                $wfapproved_list->level = $detail->level;
                                $wfapproved_list->version = 1;
                                $wfapproved_list->online = 'X';
                                $wfapproved_list->finished = 0;
                                $wfapproved_list->wfapptype_id = $detail->wfapptype_id;
                                $wfapproved_list->wfusertype_id = $detail->wfusertype_id;
                            }
                           // dd($this->object->approveds()->save($wfapproved));
                           //dd($wfapproved);
                            $this->object->approveds()->save($wfapproved_list);   
                        }
                        Car::where('id',$this->object->id)->update(['status'=>0]);
                    }
              }elseif($list_approve['wfstep_id']=== 2 && $check_wfapproved === null && $list_approve['user_id'] === null){
              // dd($list_approve);
               $array_list = array();
               $array_date = array();
               if($list_approve['user_idxx']!=null){
                array_push($array_list,$list_approve['user_idxn'],$list_approve['user_idxx'],$list_approve['user_idpd']);
                array_push($array_date,$list_approve['expected_timexn'],$list_approve['expected_timexx'],$list_approve['expected_timepd']);
              }
               else{
                array_push($array_list,$list_approve['user_idxn'],$list_approve['user_idpd']);
                array_push($array_date,$list_approve['expected_timexn'],$list_approve['expected_timepd']);
               }
              
               // dd( $array_list); 
               $wfapproved =  new Wfapproved();
               //dd($list_approve['wfstep_id']);
                $wfapproved->user_id = $list_approve['user_idxd'];
                $wfapproved->step = $list_approve['wfstep_id'];
                $wfapproved->expected_time = $list_approve['expected_timexd'];
                foreach ($wfstep->details as $detail) {
                    $wfapproved->level = $detail->level;
                    $wfapproved->version = 1;
                    $wfapproved->online = 'X';
                    $wfapproved->finished = 0;
                    $wfapproved->wfapptype_id = $detail->wfapptype_id;
                    $wfapproved->wfusertype_id = $detail->wfusertype_id;
                }
               $this->object->approveds()->save($wfapproved); 
               $this->notifycation($wfapproved);

                foreach($array_list as $key=>$value){
                    $wfapproved_list =  new Wfapproved();
                    $wfapproved_list->user_id = $value;
                    $wfapproved_list->step = $list_approve['wfstep_id'];
                    $wfapproved_list->expected_time = $array_date[$key];
                    foreach ($wfstep->details as $detail) {
                        $wfapproved_list->level = $detail->level;
                        $wfapproved_list->version = 1;
                        $wfapproved_list->online = 'X';
                        $wfapproved_list->finished = 0;
                        $wfapproved_list->wfapptype_id = $detail->wfapptype_id;
                        $wfapproved_list->wfusertype_id = $detail->wfusertype_id;
                    }
                   // dd($this->object->approveds()->save($wfapproved));
                   //dd($wfapproved_list);
                    $this->object->approveds()->save($wfapproved_list);
                    //$this->notifycation($wfapproved_list);
              }
            }elseif($list_approve['wfstep_id']=== 2 && $check_refuse_step2===true && $list_approve['type_car']=="PCAR" && $list_approve['user_id'] === null){
                $updateDate = Wfapproved::where('wfapprovedable_id',$this->object->id)
                ->where('step',2)
                ->update(['online'=>'Y']);
                if($updateDate){
                   $array_list = array();
                   $array_date = array();
                  if($list_approve['user_idxx']!=null){
                   array_push($array_list,$list_approve['user_idxn'],$list_approve['user_idxx'],$list_approve['user_idpd']);
                   array_push($array_date,$list_approve['expected_timexn'],$list_approve['expected_timexx'],$list_approve['expected_timepd']);
                 }
                  else{
                   array_push($array_list,$list_approve['user_idxn'],$list_approve['user_idpd']);
                   array_push($array_date,$list_approve['expected_timexn'],$list_approve['expected_timepd']);
                  }
                    //dd( $array_list); 
                    $wfapproved =  new Wfapproved();
                    //dd($list_approve['wfstep_id']);
                     $wfapproved->user_id = $list_approve['user_idxd'];
                     $wfapproved->step = $list_approve['wfstep_id'];
                     $wfapproved->expected_time = $list_approve['expected_timexd'];
                     foreach ($wfstep->details as $detail) {
                         $wfapproved->level = $detail->level;
                         $wfapproved->version = 1;
                         $wfapproved->online = 'X';
                         $wfapproved->finished = 0;
                         $wfapproved->wfapptype_id = $detail->wfapptype_id;
                         $wfapproved->wfusertype_id = $detail->wfusertype_id;
                     }
                    $this->object->approveds()->save($wfapproved); 
                    $this->notifycation($wfapproved);

                    foreach($array_list as $key=>$value){
                        $wfapproved_list =  new Wfapproved();
                        $wfapproved_list->user_id = $value;
                        $wfapproved_list->step = $list_approve['wfstep_id'];
                        $wfapproved_list->expected_time = $array_date[$key];
                        foreach ($wfstep->details as $detail) {
                            $wfapproved_list->level = $detail->level;
                            $wfapproved_list->version = 1;
                            $wfapproved_list->online = 'X';
                            $wfapproved_list->finished = 0;
                            $wfapproved_list->wfapptype_id = $detail->wfapptype_id;
                            $wfapproved_list->wfusertype_id = $detail->wfusertype_id;
                        }
                        $this->object->approveds()->save($wfapproved_list);  
                    }
                    Car::where('id',$this->object->id)->update(['status'=>0]);
                }
            }
              elseif(($list_approve['wfstep_id']=== 3 && $check_refuse_step3===true && $list_approve['type_car']=="SCAR") || ($list_approve['wfstep_id']=== 4 && $check_refuse_step4===true && $list_approve['type_car']=="PCAR")){
                        $updateDate = Wfapproved::where('wfapprovedable_id',$this->object->id)
                        ->where('step',$list_approve['wfstep_id'])
                        ->update(['online'=>'Y']);
                        if($updateDate){
                            $wfapproved =  new Wfapproved();
                            $wfapproved->user_id = $list_approve['user_id'];
                            $wfapproved->step = $list_approve['wfstep_id'];
                            $wfapproved->expected_time = $list_approve['expected_time'];
                            foreach ($wfstep->details as $detail) {
                                $wfapproved->level = $detail->level;
                                $wfapproved->version = 1;
                                $wfapproved->online = 'X';
                                $wfapproved->finished = 0;
                                $wfapproved->wfapptype_id = $detail->wfapptype_id;
                                $wfapproved->wfusertype_id = $detail->wfusertype_id;
                            } 
                            $this->object->approveds()->save($wfapproved);
                            $this->notifycation($wfapproved);
                            Car::where('id',$this->object->id)->update(['status'=>0]);
                        }
              }elseif($list_approve['wfstep_id']=== 3 && $check_refuse_step3===true && $list_approve['type_car']=="PCAR"){
                $updateDate = Wfapproved::where('wfapprovedable_id',$this->object->id)
                ->where('step', $list_approve['wfstep_id'])
                ->update(['online'=>'Y']);
                if($updateDate){
                    $array_list = array();
                    $array_date = array();
                    $list_user = $this->infos;
                    $wfapproved =  new Wfapproved();
                   //dd($list_approve['wfstep_id']);
                    $wfapproved->user_id = $list_approve['user_id'];
                    $wfapproved->step = $list_approve['wfstep_id'];
                    $wfapproved->expected_time = $list_approve['expected_time'];
                    foreach ($wfstep->details as $detail) {
                        $wfapproved->level = $detail->level;
                        $wfapproved->version = 1;
                        $wfapproved->online = 'X';
                        $wfapproved->finished = 0;
                        $wfapproved->wfapptype_id = $detail->wfapptype_id;
                        $wfapproved->wfusertype_id = $detail->wfusertype_id;
                    }
                   //dd($this->object->approveds()->save($wfapproved));
                   $this->object->approveds()->save($wfapproved); 
                   $this->notifycation($wfapproved);
                    //dd( $list_user);
                  
                    foreach($list_user as $value){
                    $wfapproved_user =  new Wfapproved();
                    $wfapproved_user->user_id = $value['user_id'];
                    $wfapproved_user->step = $list_approve['wfstep_id'];
                    $wfapproved_user->expected_time = $value['expected_time'];
                    foreach ($wfstep->details as $detail) {
                        $wfapproved_user->level = $detail->level;
                        $wfapproved_user->version = 1;
                        $wfapproved_user->online = 'X';
                        $wfapproved_user->finished = 0;
                        $wfapproved_user->wfapptype_id = $detail->wfapptype_id;
                        $wfapproved_user->wfusertype_id = $detail->wfusertype_id;
                    }
                  // dd($wfapproved);
                   //dd($this->object->approveds()->save($wfapproved));
                   $this->object->approveds()->save($wfapproved_user);                    
                    }
                  //  dd($array_user);
                  if($list_approve['user_idxx']!=null){
                    array_push($array_list,$list_approve['user_idxx'],$list_approve['user_idpd']);
                    array_push($array_date,$list_approve['expected_timexx'],$list_approve['expected_timepd']);
                  }
                   else{
                    array_push($array_list,$list_approve['user_idpd']);
                    array_push($array_date,$list_approve['expected_timepd']);
                   }
                   // dd( $array_list); 
                    foreach($array_list as $key=>$value){
                        $wfapproved_list =  new Wfapproved();
                        $wfapproved_list->user_id = $value;
                        $wfapproved_list->step = $list_approve['wfstep_id'];
                        $wfapproved_list->expected_time = $array_date[$key];
                        foreach ($wfstep->details as $detail) {
                            $wfapproved_list->level = $detail->level;
                            $wfapproved_list->version = 1;
                            $wfapproved_list->online = 'X';
                            $wfapproved_list->finished = 0;
                            $wfapproved_list->wfapptype_id = $detail->wfapptype_id;
                            $wfapproved_list->wfusertype_id = $detail->wfusertype_id;
                        }
                       // dd($this->object->approveds()->save($wfapproved));
                       //dd($wfapproved);
                        $this->object->approveds()->save($wfapproved_list);   
                    }
                    Car::where('id',$this->object->id)->update(['status'=>0]);
                }
              }
              else{
                return false;
              }
            }
            $this->update_status_send(); 
            DB::commit();
            //Gửi thông báo

            return true;
        } catch (\Exception $e) {
            DB::rollback();
            $this->errors = $e->getMessage();
            return false;
        }
    }
    // public function send1()
    // {
    //     // if (!$this->is_send()) {
    //     //     return false;
    //     // }
    //     // if (!$this->validate()) {
    //     //     return false;
    //     // }

    //     $approve_info = null;
    //    // $wfapproved  = new Wfapproved();
    //    $car = new Car();
           
    //    //1. luu mainconfig
    //    //2.luu wfstepconfigs
    //    //3.luu wfstepdetailconfigs
    //    //4.luu wfapproveds ( them 2 cot wfapptype_id, wfusertype_id )

    //    //loop list user
    //    //$this->object->approveds()->save($wfapproved);
    //    $this->object->wfmain()->save($car);
    //    dd( $this->object->wfmain()->save($car));
    //     try {

    //         DB::beginTransaction();
            
    //         //dd($this->user_id);
    //         // if (count($this->list_approved) == 0) {
    //         //     $this->user_approve = $this->get_user_approved_first();
    //         // } else {

    //         //     $approve_info = $this->get_info_approved_waiting(); // $this->list_approved[count($this->list_approved)-1];
    //         //     //dd($approve_info);
    //         //     if ($approve_info->release == 1) //Gửi lại từ đầu do từ chối
    //         //     {
    //         //         $this->user_approve = $this->get_user_approved_first();
    //         //         //reset lại trạng thái cũ
    //         //         $this->reset_approved();
    //         //     }
    //         // }
    //         //"1.lấy user đang chờ duyệt
    //         //1.1 Nếu tồn tại , kiểm tra user có đang từ chối hay không? nếu có thì reset tạo lại danh sách đang chờ, sau đó reset lại danh sách cũ
    //         //"2. Ktra không tìm thấy user chờ duyệt thì lấy user đầu tiên ( còn trống chưa check in  và đang online)
    //         //"3. Nếu không tìm thấy user chờ duyệt thì lấy user đầu tiên ( còn trống  chưa check in và đang onlnie)
    //         //"4.Khi đã có user chờ duyệt thì : Gửi thông tin cho người duyệt tiếp theo

    //         $approve_info = $this->get_info_approved_waiting();
    //         //dd($approve_info);
    //         if ($approve_info) {

    //             if ($approve_info->release == 1) //Gửi lại từ đầu do từ chối
    //             {
    //                 $this->reset_approved();//reset và copy
    //                 $this->user_approve = $this->get_user_approved_first();
    //                 //reset lại trạng thái cũ

    //             }
    //         }else {
    //             $this->user_approve = $this->get_user_approved_first();
    //         }



    //         if ($this->object && auth()->user()->id == $this->object->user_id) {

    //             //$user_approve =  User::find( auth()->user()->id);
    //             //  dd($this->user_id);
    //             $approved = new WfApproved();
    //             $approved->user_id = $this->user_id;

    //             //$approved->checkin = now();
    //             //$approved->checkout = now();
    //             // dd( $user_approve);
    //             //$approved->team_id = $this->user_approve->pivot->team_id;
    //            // $approved->level = $this->user_approve->pivot->level;
    //             //$approved->step = $this->user_approve->pivot->step;
    //             //$approved->review = $this->user_approve->pivot->review;
    //             $approved->sign = $this->user_approve->pivot->sign;

    //             if (!$approve_info) {
    //                 $approved->version = 1;
    //             } else {
    //                 $approved->version = $approve_info->version + 1;
    //             }

    //             $this->object->approveds()->save($approved);
    //             $this->notifycation($approved);
    //         }

    //         $this->update_status_send();
    //         DB::commit();
    //         //Gửi thông báo

    //         return true;
    //     } catch (\Exception $e) {
    //         DB::rollback();
    //         $this->errors = $e->getMessage();
    //         return false;
    //     }
    // }
    //Kiểm tra số người review chèn vào đầu- chỉ ở step 1
    public function count_review_first()
    {

        $count = 0;

        foreach ($this->team->users as $key => $value) {

            if ($value->pivot->step == 1 &&  $value->pivot->review) {
                //Người trong top review là người tạo yêu cầu thì không đếm
                // if( $value->pivot->user_id != $this->object->user_id){
                //     $count++;
                // }
                  $count++;

            } else {

                break;
            }

        }

        return $count;
    }
    //người đang duyệt
    public function user_approve_current()
    {

    }
    public function agree()
    {

        try {
           // dd("test");
            DB::beginTransaction();

            if (!$this->object) {
                abort(404);
            }
            if (!$this->validate()) {
                return null;
            }
            //xác định user duyệt
            $approve = null;
            //lấy thông tin cuối cùng : đang chờ duyệt
            $approve = $this->get_info_approved_waiting();
            //dd($approve->step);
           
            //dd($this->finshed_pcar());
            //Kiểm tra người đang chờ duyệt là người login
            if (!$approve || $approve->user_id != auth()->user()->id) {
                abort(404);
            }
           
            //Lấy user tiếp theo duyệt
            
            $next_user_approve = $this->get_user_in_team($approve->id, $approve->wfapprovedable_id);
           
           // dd($approve->user_id);
            //dd($user_in_team);
            //nếu user
            //$user_first = $this->team->users->first();
            // //kiểm tra user review duyệt thì:
            // //1- Lấy người duyệt tiếp theo nếu người tạo có trong danh sách duyệt
            // //2- Nếu người tạo không có trong danh sách duyệt thì trả về null
            // //3- Người đầu tiên là người review và là người đang chờ duyệt - Chỉ chèn 2 người review đầu tiên thôi -ưu tiên
            // if($user_first->pivot->review && $user_first->pivot->user_id == $user_in_team->pivot->user_id){

            //     $this->next_user = $this->get_user_approve_next($user_in_team);
            //     if(!$this->next_user->pivot->review ){
            //         $this->next_user = $this->get_user_in_list_approved_from_review();
            //     }



            //Kiểm tra người đang duyệt là người reivew cuối hay không?

            // if ($user_in_team && $user_in_team->pivot && $user_in_team->pivot->review && $user_in_team->pivot->step == 1
            //     &&  $this->count_review_first() == $user_in_team->pivot->level) {
            //         //dd("test".$this->count_review_first() );
            //     $this->next_user = $this->get_user_in_list_approved_from_review();

            // }

                
            //Nếu chưa tìm thấy người duyệt tiếp theo thì lấy user theo thứ tự tiếp theo


            // if (!$this->next_user) {
            //     $this->next_user = $this->get_user_approve_next($user_in_team);
            // }
            //dd( $this->next_user);
            // dd(  $this->next_user);
            //dd($this->next_user );
           // $last_user = $this->get_last_user_in_team();
            //dd($this->is_cause_measure);
            //dd(!$this->next_user ||  ( $this->next_user->id == $approve->user_id  && $approve->user_id == $last_user->id   ) );
            $this->update_status_agree(); //Đặt trước duyệt hoàn thành
            /// dd( $this->next_user );
            if ($approve->user_id === auth()->user()->id) {
                //cập nhật trạng thái đã duyệt
               // dd($this->is_type_car);
                if($this->car_error_id && $approve->step===1 && $approve->checkout=== null && $approve->online==='X' && $this->get_type_car() =='SCAR'){
                   $this->update_car_error($this->car_error_id, $this->is_cause_measure); 
                }
                if($this->list_approved[count($this->list_approved)-1]->step===1 && $this->list_approved[count($this->list_approved)-1]->checkout=== null && $this->list_approved[count($this->list_approved)-1]->online==='X' && $this->list_approved[count($this->list_approved)-1]->user_id  === auth()->user()->id && $this->get_type_car() =='PCAR'){
                    $this->update_car_error($this->car_error_id, $this->is_cause_measure);
                    $this->update_proposer($this->object->id,$this->list_approved[count($this->list_approved)-2]->user_id); 
                 }
               // dd($this->list_approved[count($this->list_approved)-1]->user_id);
                
                if($approve->step===3 && $approve->checkout=== null && $approve->online==='X' && $this->get_type_car()==='SCAR' || ($approve->step===4 && $approve->checkout=== null && $approve->online==='X' && $this->get_type_car()==='PCAR')){
                    $this->update_car_cause_risk($this->cause,$this->risk); 
                    $this->update_status_agree_finish();
                 }
                $approve->finished = 1;
                $approve->save();
                //Cập nhật trạng thái mở rộng tuỳ theo đối tượng kế thừa
                $this->notifycation($approve); //Thông báo phản hồi cho người tạo :  $approve->finished = 1;
               // $this->update_status_agree_finish();
            } 
            if($next_user_approve) {
                //Gửi cho người duyệt tiếp theo
               //dd($user_in_team->wfapprovedable_id);
                // $new_approve = new WfApproved();
                // $new_approve->user = $user_in_team->user_id;
                // $new_approve->wfapprovedable_id = $user_in_team->wfapprovedable_id;
                // $new_approve->review = $this->next_user->pivot->review;
                // $new_approve->sign = $this->next_user->pivot->sign;
                // $this->object->approveds()->save($new_approve);
               // dd($new_approve);
                $this->notifycation($next_user_approve); //Thông báo cho người duyệt tiếp theo
                //$new_approve->save();
                //thông báo noti cho người duyệt tiếp theo

            }
           // dd($approve->step);
            //cập nhật trạng thái duyệt
            $approve->checkin = now();
            $approve->checkout = now();
            $approve->release = 2; // đã duyệt
            $approve->save();
           
            if(($approve->step==3 && $this->get_type_car()==='SCAR')|| ($approve->step==2 && $this->finshed_pcar()===true) || ($approve->step==4 && $this->get_type_car()==='PCAR')){
               
                $this->update_status_agree_finish();  
            }
            DB::commit();

            return $approve;
        } catch (\Exception $e) {
            DB::rollback();
            $this->errors = $e->getMessage();
            return null;
            // $result['data']['errors'] = $e->getMessage();
        }
    }
    public function get_feedback()
    {
        $feedbacks = array();
        $list = null;

        foreach ($this->list_approved as $key => $value) {
            if ($value->release == -2) {
                $feedback = (object) [];
                $feedback->name = $value->user->name;
                $feedback->date = $value->checkout;
                $feedback->content = $value->note;
                array_push($feedbacks, $feedback);
            }
        }

        if ($feedbacks) {
            $list = collect($feedbacks)->sortByDesc('date')->values();

        }
        return $list;
    }
    public function count_feedback()
    {
        $feedbacks = array();
        $list = 0;

        foreach ($this->list_approved as $key => $value) {
            if ($value->release == -2) {
                $feedback = (object) [];
                $feedback->name = $value->user->name;
                $feedback->date = $value->checkout;
                $feedback->content = $value->note;
                array_push($feedbacks, $feedback);
            }
        }
        if($feedbacks){
            $list = count($feedbacks);
        }

        return $list;
    }
    public function get_approved_online()
    {
        $onlines = array();
        $list = null;

        foreach ($this->list_approved as $key => $value) {
            if ($value->online == 'X') {
                // $online = (object)[];
                // $online->name = $value->user->name;
                // $online->date = $value->checkout;
                // $online->content = $value->note;
                array_push($onlines, $value);
            }
        }

        if ($onlines) {
            $list = collect($onlines)->sortBy('date')->values();

        }
        return $list;
    }
    public function refuse($feedback)
    {

        try {

            DB::beginTransaction();
            //xác định user duyệt
            $approve = null;
            //lấy thông tin cuối cùng : đang chờ duyệt
            $approve = $this->get_info_approved_waiting();
            //Kiểm tra người đang chờ duyệt là người login
           // dd($this->update_pcar($approve['wfapprovedable_id'],$approve['step']));
          //dd($approve);
            if (!$approve || $approve->user_id != auth()->user()->id) {
                abort(404);
            }
            if (!$this->validate()) {
                return null;
            }
            if($approve && $approve['release']==-2){
                return null;
            }
            //cập nhật trạng thái duyệt
            $approve->checkin = now();
            $approve->checkout = now();
            $approve->release = -2; // từ chối duyệt
            //$approve->online = 'Y';
            $approve->note = $feedback; // từ chối duyệt

            $approve->save();
            $this->update_status_refuse();
            $this->notifycation($approve); //Thông báo phản hồi cho người tạo:  $approve->release = 1
            $this->update_pcar($approve['wfapprovedable_id'],$approve['step']);
            DB::commit();
            return $approve;
            // $result['data']['feedback'] = $approve;
            // $result['data']['success'] = 1;

        } catch (\Exception $e) {
            DB::rollback();
            $this->errors = $e->getMessage();
            //dd($this->errors);
            return null;
        }

    }

    abstract public function notifycation(Wfapproved $approve);
    abstract public function validate();
    abstract public function is_cancel();
    abstract public function update_status_send();
    abstract public function update_status_agree();
    abstract public function update_status_agree_finish();
    abstract public function update_car_error($car_error_id, $is_cause_measure);
    abstract public function update_car_cause_risk($cause,$risk);
    abstract public function update_status_refuse();
    abstract public function update_pcar($id,$step);
    abstract public function update_proposer($id,$user_id);
    abstract public function index(Request $request);

}
