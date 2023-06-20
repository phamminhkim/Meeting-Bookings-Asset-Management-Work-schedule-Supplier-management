<?php

namespace App\Repositories\approve;

use App\Models\payment\Payrequest;
use App\Models\shared\Approved;
use App\Models\shared\PrintedDoc;
use App\Models\shared\Team;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

abstract class ApproveAbs
{

    protected $team;
    protected $object;
    protected $user_approve;
    protected $next_user;
    protected $list_approved;
    protected $is_owner = false;
    protected $errors;
    public function __construct($team, $object, $list_approved)
    {
        $this->team = $team;
        $this->object = $object;
        $this->list_approved = $list_approved;
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
        $info_last_approve = $this->get_info_approved_waiting();
        //get_approved_online
        $result['is_approve'] = false;
        $result['user_wait'] = null;
        $list_history = $this->get_approved_online();
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
        $result['is_document_cancel'] = $this->is_cancel();
        $info_last_approve = $this->get_info_approved_waiting();
        //Kiểm tra user đang login có phải là user chờ duyệt không?
        if ($info_last_approve) {
            if ($info_last_approve->user_id == auth()->user()->id && $info_last_approve->release == 0) { //Nếu đã phản hồi thì không hiển thị duyệt
                $result['is_approve'] = true;
            }

            $user = $this->get_user_in_team($info_last_approve->user_id, $info_last_approve->step, $info_last_approve->level);
            $result['user_wait'] = $user;
        }
        //dd( $result['is_approve'] );

        if ($this->team) {
            $result['list_user'] = $this->team->users;
            $steps = $this->team->users->pluck('pivot.step')->flatten();
            $steps->sort();
            $steps = array_unique($steps->toArray());
            // dd($steps);
            $user_wait = $this->user_wait_approved();
            //Danh sách user không được duyệt

            $list_id_not_approve = $this->get_list_user_not_approved()->pluck('id')->flatten();


            //Phân loại steps
            $result['steps'] = array();
            foreach ($steps as $key => $step) {
                $stepinfo = [];
                $stepinfo['id'] = $step;
                $stepinfo['name'] = $this->get_step_name($step);

                $stepinfo['users'] = array();
                foreach ($this->team->users as $key => $user) {
                    if ($user->pivot->step == $step) {
                        //dd("test1");
                        $user->release = "";
                        $user->checkout = null;
                        $user->checkin = null;
                        $user->waiting = false;

                        $info_approve = $this->get_info_approve_by($user->id, $user->pivot->step);

                        if ($info_approve) {

                            $user->release = $info_approve->release;
                            $user->checkout = $info_approve->checkout;
                            $user->checkin = $info_approve->checkin;
                            $user->checkin = $info_approve->checkin;
                            $user->review = $info_approve->review;

                            // $stepinfo['users']['status'] = true;
                        }

                        //Kiểm tra user đang chờ duyệt ở step nào
                        if ($user_wait && $user_wait->id == $user->id && $user_wait->pivot->step == $user->pivot->step) {

                            $user->waiting = true;
                        }
                        //Ẩn các user trong team nhưng không có quyền duyệt: 2 trường hợp
                        //1.User trong team tạo yêu cầu duyệt
                        //2.User tạo
                        //3. Trường hợp đặc biệt là các Nhân viên reivew kế toán ở top
                        if( $user->id != $this->object->user_id && !$list_id_not_approve->contains($user->id)){
                            array_push($stepinfo['users'], $user);
                        }


                    }

                }
                array_push($result['steps'], $stepinfo);
            }
            //$result['steps'] = array_unique($steps->toArray()) ;
        } else {
            $result['list_user'] = null;
            $result['steps'] = null;
        }

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
        $count = 0;
        $user_approve = $this->team->users->first();

        //nếu user đầu tiên là review và user này không phải là user tạo chứng từ
        if ($user_approve->pivot->review && $user_approve->pivot->user_id != $this->object->user_id) {
            //To do nothing
        } else {
            $is_owner = false;
            foreach ($this->team->users as $key => $value) {

                if ($value->pivot->user_id == $this->object->user_id) {
                    $is_owner = true;
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
        }
        // dd($user_approve);
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
    public function get_info_approved_waiting()
    {
        $info_approve_wait = null;
        if ($this->list_approved && count($this->list_approved) > 0) {
            $info_approve_wait = $this->list_approved[count($this->list_approved) - 1];
        }

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
    private function get_user_in_team($user_id, $step, $level)
    {

        $user = null;
        foreach ($this->team->users as $key => $value) {

            if ($value->pivot->step == $step
                && $value->pivot->level == $level) {
                $user = $value;
                break;
            }

        }
        return $user;
    }
    //reset các trạng thái duyệt sang offline ( cột online = '')
    private function reset_approved()
    {
        foreach ($this->list_approved as $key => $oldApprove) {
            $oldApprove->online = '';
            $oldApprove->save();
        }
    }

    public function send()
    {
        if (!$this->is_send()) {
            return false;
        }
        if (!$this->validate()) {
            return false;
        }
        $approve_info = null;
        try {

            DB::beginTransaction();

            if (count($this->list_approved) == 0) {
                $this->user_approve = $this->get_user_approved_first();
            } else {

                $approve_info = $this->get_info_approved_waiting(); // $this->list_approved[count($this->list_approved)-1];
                //dd($approve_info);
                if ($approve_info->release == 1) //Gửi lại từ đầu do từ chối
                {
                    $this->user_approve = $this->get_user_approved_first();
                    //reset lại trạng thái cũ
                    $this->reset_approved();
                }
            }

            if ($this->object && auth()->user()->id == $this->object->user_id) {

                //$user_approve =  User::find( auth()->user()->id);
                //  dd($this->user_approve);
                $approved = new Approved();
                $approved->user_id = $this->user_approve->pivot->user_id;

                //$approved->checkin = now();
                //$approved->checkout = now();
                // dd( $user_approve);
                $approved->team_id = $this->user_approve->pivot->team_id;
                $approved->level = $this->user_approve->pivot->level;
                $approved->step = $this->user_approve->pivot->step;
                $approved->review = $this->user_approve->pivot->review;
                $approved->sign = $this->user_approve->pivot->sign;

                if (!$approve_info) {
                    $approved->version = 1;
                } else {
                    $approved->version = $approve_info->version + 1;
                }

                $this->object->approveds()->save($approved);
                $this->notifycation($approved);
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
            //Kiểm tra người đang chờ duyệt là người login
            if (!$approve || $approve->user_id != auth()->user()->id) {
                abort(404);
            }
            if($approve->finished == 1){
               // dd("đã duyệt hoàn thành rồi" .$approve->finished);
               $this->errors = "Chứng từ đã được duyệt";
                return null;
            }
          
            //Lấy user tiếp theo duyệt
            $user_in_team = $this->get_user_in_team($approve->user_id, $approve->step, $approve->level);

            //nếu user
            $user_first = $this->team->users->first();
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

            if ($user_in_team && $user_in_team->pivot && $user_in_team->pivot->review && $user_in_team->pivot->step == 1
                &&  $this->count_review_first() == $user_in_team->pivot->level) {
                    //dd("test".$this->count_review_first() );
                $this->next_user = $this->get_user_in_list_approved_from_review();

            }


            //Nếu chưa tìm thấy người duyệt tiếp theo thì lấy user theo thứ tự tiếp theo


            if (!$this->next_user) {
                $this->next_user = $this->get_user_approve_next($user_in_team);
            }
            //dd( $this->next_user);
            // dd(  $this->next_user);
            //dd($this->next_user );
            $last_user = $this->get_last_user_in_team();


            //dd(!$this->next_user ||  ( $this->next_user->id == $approve->user_id  && $approve->user_id == $last_user->id   ) );
            $this->update_status_agree(); //Đặt trước duyệt hoàn thành
            /// dd( $this->next_user );
            if (!$this->next_user  ||  ( $this->next_user->id == $approve->user_id  && $approve->user_id == $last_user->id   )) {
                //cập nhật trạng thái đã duyệt
               
                $approve->finished = 1;
                $approve->save();
                //Cập nhật trạng thái mở rộng tuỳ theo đối tượng kế thừa
                $this->notifycation($approve); //Thông báo phản hồi cho người tạo :  $approve->finished = 1;
                $this->update_status_agree_finish();
            } else {
                //Gửi cho người duyệt tiếp theo

                $new_approve = new Approved();

                $new_approve->user_id = $this->next_user->pivot->user_id;
                $new_approve->level = $this->next_user->pivot->level;
                $new_approve->team_id = $this->next_user->pivot->team_id;
                $new_approve->step = $this->next_user->pivot->step;
                $new_approve->review = $this->next_user->pivot->review;
                $new_approve->sign = $this->next_user->pivot->sign;
                $this->object->approveds()->save($new_approve);
                $this->notifycation($new_approve); //Thông báo cho người duyệt tiếp theo
                //$new_approve->save();
                //thông báo noti cho người duyệt tiếp theo

            }
            //cập nhật trạng thái duyệt
            $approve->checkin = now();
            $approve->checkout = now();
            $approve->release = 2; // đã duyệt
            $approve->save();

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
            if ($value->release == 1) {
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
            $approve->note = $feedback; // từ chối duyệt

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
    public function save_printed_doc($html,$obj){
                //Lưu html chứng từ vào bảng

                $printDoc = new PrintedDoc();
                $printDoc->content = $html;
                $obj->printedDocs()->save($printDoc);
                //end lưu html
    }
    public function notify_current_approver() {
        $approve = $this->get_info_approved_waiting();
        $this->notifycation($approve);
    }
    abstract public function notifycation(Approved $approve);
    abstract public function validate();
    abstract public function is_cancel();
    abstract public function update_status_send();
    abstract public function update_status_agree();
    abstract public function update_status_agree_finish();
    abstract public function update_status_refuse();
    abstract public function index(Request $request);


}
