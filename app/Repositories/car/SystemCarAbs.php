<?php
namespace App\Repositories\car;

use App\Models\car\Car;
use App\Models\shared\DocumentType;
use App\Models\shared\File;
use App\Models\shared\OtherAttached;
use App\Models\shared\Reminder;
use App\Models\shared\Step;
use App\Models\shared\Wfapproved;
use App\Repositories\approve\ApproveRoutingHelper;
use App\Repositories\DocumentSerials;
use App\Ultils\Ultils;
use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\shared\Shared;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use SoareCostin\FileVault\Facades\FileVault as FacadesFileVault;
abstract class SystemCarAbs{


    protected $document;
    protected $request;
    protected $errors;
    protected $message;
    protected $layout;
    protected $form_name;
    public function __construct($request)
    {
        $this->request = $request;

    }
    public function __set($name, $value)
    {
        $this->$name = $value;
    }
    public function __get($name)
    {
        return $this->$name;
    }

    public function index()
    {

        $result = array();
        $result['data'] = array();
        $result['success'] = "0";
        $query = Car::query();

        try {
            //dd($this->request->all());
            if ($this->request->filled('start_date')) {
                if (!$this->request->filled('end_date')) {
                    $this->request->end_date = $this->request->start_date;
                }
                $start_date = Carbon::create($this->request->start_date);
                $end_date = Carbon::create($this->request->end_date);
                $end_date->addHours(23);
                $end_date->addMinutes(59);
                $end_date->addSeconds(59);
                $query = $query->whereBetween('created_at', [$start_date, $end_date]);
            }
            //dd(explode(",",$this->request->status));

            if ($this->request->filled('status')) {
                    $query = $query->whereIn('status', explode(",",$this->request->status));
            }
            //Lấy theo loại chứng từ
            if ($this->request->filled('document_type_id')) {
                $query = $query->where('document_type_id', $this->request->document_type_id);
            }
            if ($this->request->filled('company_id')) {
                $query = $query->where('company_id', $this->request->company_id);
            }
            if ($this->request->filled('department_id')) {
                $query = $query->where('department_id', $this->request->department_id);
            }
            if ($this->request->filled('document_type_id')) {

                $query = $query->where('document_type_id', $this->request->document_type_id);
            }
            if ($this->request->filled('serial_num')) {
                $query = $query->where('serial_num', $this->request->serial_num);
            }
            if ($this->request->filled('type_car_id')) {
                $query = $query->where('type_car_id', $this->request->type_car_id);
            }
            if ($this->request->filled('standard_id')) {
                $query = $query->where('standard_id', $this->request->standard_id);
            }
            if ($this->request->filled('user_id')) {
                $query = $query->where('user_id', $this->request->user_id);
            }
        } catch (Exception $e) {
            $this->errors = $e->getMessage();
        }


        $user = new User();
        $user = User::find(auth()->user()->id);
        $group_ids = $user->groups->pluck('id')->flatten();
        $arr_group = array();
        if(count($group_ids)>0){
            foreach($group_ids as $gr){
                array_push($arr_group,$gr);
            }
        }
        $array_step2 = array();
        $car_list = Car::select('id','user_id','proposer','group_id')->where('status','<>',-10)->with('shareds')->get();
       
        $step22 = array();
        foreach($car_list as $car){

            if($car->user_id===auth()->user()->id || $car->proposer===auth()->user()->id){
                array_push($step22,$car->id);
            }else if(count($group_ids)>0
                    && ($car->user_id != auth()->user()->id || $car->proposer != auth()->user()->id)
                    &&(in_array($car->group_id, $arr_group) && $car->status!=0)
                    ){
                        array_push($step22,$car->id);
            }else if(count($car['shareds'])>0
                    && ($car->user_id != auth()->user()->id || $car->proposer != auth()->user()->id)){
                        for($j=0;$j<count($car['shareds']);$j++){
                            if($car['shareds'][$j]['object_id']==auth()->user()->id){
                                array_push($step22,$car['shareds'][$j]['sharedable_id']);
                            }
                        }
            }
            else{
                $query_step2 = Wfapproved::where('user_id',auth()->user()->id)->where('wfapprovedable_id',$car->id)->where('online','X')->orderBy('id', 'ASC')->get();
                foreach($query_step2 as $step2){
                    array_push($array_step2, $step2->wfapprovedable_id);
                }
            }
        }
        array_unique($array_step2);

        foreach($array_step2 as $value){
        $list_step2= Wfapproved::Select('id','checkout','user_id','wfapprovedable_id','finished','release')->where('wfapprovedable_id',$value)->where('online','X')->orderBy('id', 'ASC')->get();
        //dd($list_step2);
       //dd(count($list_step2));
            if(count($list_step2)==='1'){
                foreach($list_step2 as $st){
                 array_push($step22, $st->wfapprovedable_id);
                 }
            }else{
                //$i = 0;
                //dd(count($list_step2));
                for($i=0; $i<count($list_step2);$i++){
                //dd($list_step2[1]->user_id);
                        if($list_step2[0]->user_id === auth()->user()->id){
                           // dd($list_step2[$i]->wfapprovedable_id);
                            array_push($step22, $list_step2[$i]->wfapprovedable_id);
                        }
                        if($i>0 && $list_step2[$i-1]->checkout <> null && $list_step2[$i-1]->finished==1 && $list_step2[$i]->user_id === auth()->user()->id){
                           // dd($list_step2[$i]->user_id);
                           //dd($list_step2[$i-1]->finished);
                            array_push($step22, $list_step2[$i]->wfapprovedable_id);
                        }
                }
            }
        }
       // var_dump($step22);
        //print_r($array_step2);
        //var_dump($array_step2);
        $withModel = [ 'approveds', 'user', 'document_type'];
        if ($user->hasPermission('review_all_car')) {
         
            $document = $query->where('status','<>',-10)->orderBy('id', 'desc')->with($withModel)->withCount('approveds')->get();
        
        }else if($user->hasPermission('review_company_car')) {
            $company_id = $user->company_id;
            $document = $query->where('company_id',$company_id)->where('status','<>',-10)->orderBy('id', 'desc')->with($withModel)->withCount('approveds')->get();
        }
        else if($user->hasPermission('review_department_car')){
            $department_id = $user->department_id;
            $document = $query->where('department_id',$department_id)->where('status','<>',-10)->orderBy('id', 'desc')->with($withModel)->withCount('approveds')->get();
        }
         else {

           // $group_ids = $user->groups->pluck('id')->flatten();//pluck('id')->flatten();
            // $document = $query->where('user_id', $user->id)->orderBy('id', 'desc')->with($withModel)->withCount('approveds')->get();
      
            $document = $query->whereIn('id', $step22)->where('status','<>',-10)->orderBy('id', 'desc')->with($withModel)->withCount('approveds')->get();
        }

        return $document;
    }
    protected function validate_store()
    {
        $document = DocumentType::find($this->request->document_type_id);
        if($document->code=='SCAR'){
            $validator = Validator::make($this->request->all(), [

                'document_type_id' => 'required',
                'issue_count' => 'required',
                'issue_date' => 'required|date',
                'company_id' => 'required',
                'department_id' => 'required',
                'content' => 'required|max:10000',
                'group_id' => 'required',
                'wfmain_id'=> 'required',
                'standard_id' => 'required',

            ],
            [
                'document_type_id.required' => "Loại chứng từ không được rỗng",
                'issue_count.required' => 'Chưa nhập số lần tái diễn.',
                'issue_date.required' => 'Chưa chọn ngày.',
                'company_id.required' => 'Chưa chọn công ty.',
                'department_id.required' => 'Chưa chọn phòng ban.',
                'content.required' => 'Chưa nhập nội dung.',
                'group_id.required' => 'Chưa chọn nhóm.',
                'wfmain_id.required'=> 'Workflow Main không được rỗng.',
                'standard_id.required' => 'Chưa chọn tiêu chuẩn.'
            ]
            );
        }
        if($document->code=='PCAR'){
            $validator = Validator::make($this->request->all(), [

                'document_type_id' => 'required',
                'issue_count' => 'required',
                'issue_date' => 'required|date',
                'company_id' => 'required',
                'department_id' => 'required',
                'content' => 'required|max:10000',
                'group_id' => 'required',
                'type_car_id' => 'required',
                'wfmain_id'=> 'required'

            ],
            [
                'document_type_id.required' => "Loại chứng từ không được rỗng",
                'issue_count.required' => 'Chưa nhập số lần tái diễn.',
                'issue_date.required' => 'Chưa chọn ngày.',
                'company_id.required' => 'Chưa chọn công ty.',
                'department_id.required' => 'Chưa chọn phòng ban.',
                'content.required' => 'Chưa nhập nội dung.',
                'group_id.required' => 'Chưa chọn nhóm.',
                'type_car_id.required' => 'Chưa chọn loại phiếu car.',
                'wfmain_id.required'=> 'Workflow Main không được rỗng'
            ]
            );
        }


        $fields = $this->request->all();
       // dd( $fields);
       // dd($fields["attachment_file"]);
        $user = new User();
        $user = auth()->user();

        if (!$user->company) {
            $validator->after(function ($validator) {
                $validator->errors()->add('company', 'User chưa được cấu hình công ty');

            });

        }

        if (!$user->department) {

            $validator->after(function ($validator) {
                $validator->errors()->add('department', 'User chưa được cấu hình phòng ban');

            });
        }


        // if($user->company){
        //     $team_id = ApproveRoutingHelper::get_team($user->company->id, $fields['document_type_id'], $fields['group_id'], 0, 0, 0, 0,0,0,0);
        //     if ($team_id == "") {
        //         $validator->after(function ($validator) {
        //             $validator->errors()->add('team_id', 'Chưa phân tuyến duyệt. Vui lòng liên hệ IT- Admin hệ thống');
        //         });

        //     }
        // }




        return $validator;
    }
    public function show($id)
    {
        $car = Car::with('department','proposer','users','group', 'reminders','approveds','company', 'other_attacheds','other_attacheds.attachment_file',
        'timelines','cause_measure','monitor_implementation','result_evaluation','document_type','fast_process','type_car','standard','comments','shareds')->find($id);
        foreach ($car->shareds as  $shared) {
            switch ($shared->type) {
                case '0':
                   $shared->group;
                    break;
                    case '1':
                       $shared->viewer;
                       break;
                    case '4':
                       $shared->assign;
                        break;
                default:
                    # code...
                    break;
            }
       }

        $this->check_read_noti($id);
        return $car;
    }

    public function edit($id)
    {
        $car = Car::with('department','group', 'reminders','approveds','company', 'other_attacheds','other_attacheds.attachment_file',
        'timelines','cause_measure','monitor_implementation','result_evaluation')->find($id);

        return $car;
    }
    public function check_read_noti($id){
        $request = new Request();
        $request = $this->request;
        $user = User::findOrFail(auth()->user()->id);
     //   dd( $this->request->notification_id);
        if($this->request->filled('notification_id')){
          // dd($request->get('notification_id'));

            $user->unreadNotifications->map(function($noti) use($request){
                if($noti->id == $request->get('notification_id')){
                    $noti->markAsRead();
                }
            });
        }else {
            $user->unreadNotifications->map(function($noti) use($id){

                try {
                    $result = $noti->data['data'];
                    if ( isset($result['object_type'] ) &&  $result['object_type'] == Car::class && $result['object_id'] == $id ) {
                        $noti->markAsRead();

                    }
                } catch (\Throwable $th) {
                   Log::error($th->getMessage());
                }


            });
        }
      //  dd("tesst");
    }
    public function store()
    {

        $result = array();
        $result['data'] = array();
        $result['data']['success'] = 0;
        date_default_timezone_set("Asia/Ho_Chi_Minh");

        $validator = $this->validate_store();

        $failed = $validator->fails();
        $fields = $this->request->all();
       //dd( $fields);
        if ($failed) {
            $this->errors = $validator->errors();

        } else {

            try {

                DB::beginTransaction();
                $user = new User();
                $user = auth()->user();
                $fields['user_id'] = $user->id;
                $fields['status'] = 0;

                try {
                    //dd( $fields);
                    $car = Car::create($fields);

                    $documentType = DocumentType::find($car->document_type_id);

                    if ($documentType) {
                        // dd('$car->serial_num');
                        $car->serial_num = DocumentSerials::getSerial(Ultils::$MODULE_CARS, $documentType->code,$car->company_id,
                            Ultils::$MODULE_FORMAT_SERIAL_NUMBER, true);

                        $car->save();

                    }

                    //File khác
                    $other_attacheds = $fields['other_attacheds'];
                    for ($i = 0; $i < count($other_attacheds); $i++) {
                         $otherAttached = new OtherAttached($other_attacheds[$i]);
                        $car->other_attacheds()->save($otherAttached);

                        //save file
                        $attachment_file = $other_attacheds[$i]['attachment_file'];

                        for ($j = 0; $j < count($attachment_file); $j++) {

                            $file = new File();

                            $file->name = $attachment_file[$j]["name"];
                            //$ext = end(explode('.', $filename));
                            // $file->ext = $attachment_file[$i]["ext"];
                            $file->size = $attachment_file[$j]["size"];
                            $file->user_id = $user->id;

                            $ext = substr($attachment_file[$j]["name"], strrpos($attachment_file[$j]["name"], '.') + 1);
                            $name = "public/car/" . $user->username . "/" . uniqid() . '.' . $ext; // $attachment_file[$i]["ext"];

                            $file->ext = $ext;
                            $file->url = $name;
                            $otherAttached->attachment_file()->save($file);

                            //$name = "/avatars/" . $user->username . '.' . $this->request->file['ext'];
                            $base64_str = substr($attachment_file[$j]['base64'], strpos($attachment_file[$j]['base64'], ",") + 1);
                            $image = base64_decode($base64_str);
                            //file_put_contents(public_path().$name,  $image );
                            Storage::disk('local')->put($name, $image);
                            FacadesFileVault::encrypt($name);

                        }

                    }


                } catch (\Exception $e1) {

                    $validator->errors()->add('serial_number', $e1->getMessage());
                    $this->errors = $validator->errors();

                    return null;
                }
                $this->store_sub_data($car);
                DB::commit();
                return $car;

            } catch (\Exception $e) {
                DB::rollback();

                $this->errors = $e->getMessage();
                return null;
            }
        }
        return null;

    }
    protected function validate_update($id)
    {
        $document = DocumentType::find($this->request->document_type_id);
        if($document->code=='SCAR'){
            $validator = Validator::make($this->request->all(), [

                'document_type_id' => 'required',
                'issue_count' => 'required',
                'issue_date' => 'required|date',
                'company_id' => 'required',
                'department_id' => 'required',
                'content' => 'required|max:10000',
                'group_id' => 'required',
                'wfmain_id'=> 'required',
                'standard_id' => 'required',
                'serial_num'=> 'required',

            ],
            [
                'document_type_id.required' => "Loại chứng từ không được rỗng",
                'issue_count.required' => 'Chưa nhập số lần tái diễn.',
                'issue_date.required' => 'Chưa chọn ngày.',
                'company_id.required' => 'Chưa chọn công ty.',
                'department_id.required' => 'Chưa chọn phòng ban.',
                'content.required' => 'Chưa nhập nội dung.',
                'group_id.required' => 'Chưa chọn nhóm.',
                'wfmain_id.required'=> 'Workflow Main không được rỗng.',
                'standard_id.required' => 'Chưa chọn tiêu chuẩn.',
                'serial_num.required' => 'Số phiếu không được rỗng',
            ]
            );
        }
        if($document->code=='PCAR'){
            $validator = Validator::make($this->request->all(), [

                'document_type_id' => 'required',
                'issue_count' => 'required',
                'issue_date' => 'required|date',
                'company_id' => 'required',
                'department_id' => 'required',
                'content' => 'required|max:10000',
                'group_id' => 'required',
                'type_car_id' => 'required',
                'wfmain_id'=> 'required',
                'serial_num'=> 'required',

            ],
            [
                'document_type_id.required' => "Loại chứng từ không được rỗng",
                'issue_count.required' => 'Chưa nhập số lần tái diễn.',
                'issue_date.required' => 'Chưa chọn ngày.',
                'company_id.required' => 'Chưa chọn công ty.',
                'department_id.required' => 'Chưa chọn phòng ban.',
                'content.required' => 'Chưa nhập nội dung.',
                'group_id.required' => 'Chưa chọn nhóm.',
                'type_car_id.required' => 'Chưa chọn loại phiếu car.',
                'wfmain_id.required'=> 'Workflow Main không được rỗng',
                'serial_num.required' => 'Số phiếu không được rỗng',
            ]
            );
        }

        //dd($this->request->all());
        $fields = $this->request->all();

       // dd( $this->request->amount);
        $car = Car::with('approveds')->findOrFail($id);

        $user = new User();
        $user = auth()->user();
        $role = User::find(auth()->user()->id);
       //dd($car);
        if ($car->status == 2 && !$role->hasPermission('update_car')) {
            $validator->after(function ($validator) {
                $validator->errors()->add('chungtuhoantat', 'Phiếu car đã hoàn tất. Vui lòng không cập nhật.');
            });
        }
        if ($car->status == -1 && !$role->hasPermission('update_car')) {
            $validator->after(function ($validator) {
                $validator->errors()->add('chungtuhuy', 'Phiếu car đã huỷ. Vui lòng không cập nhật.');
            });
        }
        if(count($car['approveds'])>0 && $car->status == 1 && !$role->hasPermission('update_car')){
            for($i=0;$i<count($car['approveds']);$i++){
                if($car['approveds'][$i]['step']=='1' && $car['approveds'][$i]['online']=='X'){
                    $validator->after(function ($validator) {
                        $validator->errors()->add('chungtuhoantat', 'Phiếu car đã duyệt hoặc đang chờ duyệt. Vui lòng không cập nhật.');
                    });
                    break;
                }
            }
        }

        if (!$user->company) {
            $validator->after(function ($validator) {
                $validator->errors()->add('company', 'User chưa được cấu hình công ty');
            });
        }
        if (!$user->department) {

            $validator->after(function ($validator) {
                $validator->errors()->add('department', 'User chưa được cấu hình phòng ban');
            });
        }
        return $validator;
    }
    public function update_cancel( ){

        $car = Car::find($this->request->id);
        //dd($this->request->id);
        if($car->status !=2){
            $car->status = -2;
            $car->save();
            return true;
        }else{
            return false;
        }
    }
    public function update($id)
    {
        date_default_timezone_set("Asia/Ho_Chi_Minh");

        $validator = $this->validate_update($id);
        $failed = $validator->fails();
        $fields = $this->request->all();
        //kiểm tra thông tin hợp đồng nếu có , validate thêm
        $car = Car::findOrFail($id);

        if ($failed) {
            $this->errors = $validator->errors();
        } else {

            try {

                if ($car) {
                    DB::beginTransaction();
                    $user = new User();
                    $user = auth()->user();
                    $role = User::find(auth()->user()->id);
                    //dd($fields['serial_num']);
                    if($role->hasPermission('update_car')){
                        $car = Car::where('id',$fields['id'])->update(['company_id'=>$fields['company_id'],
                                                                        'department_id'=>$fields['department_id'],
                                                                        'content'=>$fields['content'],
                                                                        'status'=>$fields['status'],
                                                                        'proposer'=>$fields['proposer'],
                                                                        'serial_num'=>$fields['serial_num']
                                                                         ]);
                    }else{
                        $fields['status'] = 0;
                        $fields['user_id'] = $user->id;
                        $fields['proposer'] = $user->id;
                        $fields['issue_date'] = $fields['issue_date'];
                        $fields['issue_count'] = $fields['issue_count'];
                        $fields['company_id'] = $fields['company_id'];
                        $fields['department_id'] = $fields['department_id'];
                        $fields['group_id'] = $fields['group_id'];
                        $car->fill($fields);
                        $car->save();
                    }


                    //save file


                   // $attachment_file = $fields['other_attacheds'];
                        //File car


                        // $list_file_car = $fields['other_attacheds'];
                        // //dd( $list_file_car);
                        // for ($i = 0; $i < count( $list_file_car); $i++) {

                        //         //Chỉ upload file mới
                        //         //dd($list_file_car);
                        //         if (!isset($list_file_car[$i]['id']) || $list_file_car[$i]['id'] == 0) {
                        //             //dd($list_file_car[$i]);
                        //             $list_file_new = $list_file_car[$i];
                        //            // dd($list_file_new);
                        //             for($j=0;$j<count($list_file_new['attachment_file']);$j++){
                        //                // dd($list_file_new['attachment_file'][$j]["size"]);
                        //                 $file = new File();
                        //                 $file->name = $list_file_new['attachment_file'][$j]["name"];

                        //                 //$ext = end(explode('.', $filename));
                        //                 // $file->ext = $attachment_file[$i]["ext"];
                        //                // dd($list_file_car[$i]["size"]);
                        //                 $file->size = $list_file_new['attachment_file'][$j]["size"];
                        //                 $file->user_id = $user->id;
                        //                // dd($user->id);
                        //                 $ext = substr($list_file_new['attachment_file'][$j]["name"], strrpos($list_file_new['attachment_file'][$j]["name"], '.') + 1);
                        //                 $name = "public/car/" . $user->username . "/" . uniqid() . '.' . $ext; // $attachment_file[$i]["ext"];

                        //                 $file->ext = $ext;
                        //                 $file->url = $name;
                        //                // dd( $car->attachment_file()->save($file));
                        //                 $car->attachment_file()->save($file);

                        //                 //$name = "/avatars/" . $user->username . '.' . $this->request->file['ext'];
                        //                 $base64_str = substr($list_file_new['attachment_file'][$j]['base64'], strpos($list_file_new['attachment_file'][$j]['base64'], ",") + 1);
                        //                 $image = base64_decode($base64_str);
                        //                 //file_put_contents(public_path().$name,  $image );
                        //                 Storage::disk('local')->put($name, $image);
                        //                 FacadesFileVault::encrypt($name);
                        //             }

                        //         }
                        //        // dd($list_file_car[$i]['attachment_file_removed']);
                        // }

                        //Lưu các other_attached mới
                        $other_attacheds = $fields['other_attacheds'];
                       // dd($other_attacheds);
                        for ($i = 0; $i < count($other_attacheds); $i++) {
                            // $other_attacheds[$i]['priceRequest_id'] = $priceRequest->id;

                            // $other_attacheds[$i]['paycatset_id'] = $paycatset_id ;
                            if (isset($other_attacheds[$i]['id']) && $other_attacheds[$i]['id'] != 0) {
                                $otherAttached = OtherAttached::find($other_attacheds[$i]['id']);
                                $otherAttached->fill($other_attacheds[$i]);
                               // dd($otherAttached->fill($other_attacheds[$i]));
                                $otherAttached->save();
                            } else {

                                $otherAttached = new OtherAttached($other_attacheds[$i]);
                                //dd($car->other_attacheds()->save($otherAttached));
                                $car->other_attacheds()->save($otherAttached);

                            }

                            //save file
                            $attachment_file = $other_attacheds[$i]['attachment_file'];
                            $attachment_file_removed = $other_attacheds[$i]['attachment_file_removed'];
                            //  dd($attachment_file);
                            for ($j = 0; $j < count($attachment_file); $j++) {

                                //chỉ lưu  các file mới
                                if (!isset($attachment_file[$j]["id"])) {
                                    $file = new File();

                                    $file->name = $attachment_file[$j]["name"];
                                    //$ext = end(explode('.', $filename));
                                    // $file->ext = $attachment_file[$i]["ext"];
                                    $file->size = $attachment_file[$j]["size"];
                                    $file->user_id = $user->id;

                                    $ext = substr($attachment_file[$j]["name"], strrpos($attachment_file[$j]["name"], '.') + 1);
                                    $name = "public/car/" . $user->username . "/" . uniqid() . '.' . $ext;

                                    $file->ext = $ext;
                                    $file->url = $name;

                                    $otherAttached->attachment_file()->save($file);
                                    //$name = "/avatars/" . $user->username . '.' . $request->file['ext'];
                                    $base64_str = substr($attachment_file[$j]['base64'], strpos($attachment_file[$j]['base64'], ",") + 1);
                                    $image = base64_decode($base64_str);
                                    //file_put_contents(public_path().$name,  $image );
                                    Storage::disk('local')->put($name, $image);
                                    FacadesFileVault::encrypt($name);

                                }

                            }
                            //xoá các file trong mảng del

                            for ($k = 0; $k < count($attachment_file_removed); $k++) {
                                if (isset($attachment_file_removed[$k]["id"])) {
                                    $file = File::findOrFail($attachment_file_removed[$k]["id"]);
                                    if ($file) {

                                        Storage::delete($file->url . ".enc");
                                        $file->delete();
                                    }
                                }
                            }

                        }
                      //Xoá  payment_attached_del
                      $other_attacheds_del = $fields['other_attacheds_del'];
                      //dd($other_attacheds_del);
                      for ($i = 0; $i < count($other_attacheds_del); $i++) {

                          $otherAttached = OtherAttached::find($other_attacheds_del[$i]['id']);


                          if ($otherAttached) {

                              foreach ($otherAttached->attachment_file as $file) {
                                  Storage::delete($file->url . ".enc");
                                  $file->delete();
                              }
                              $otherAttached->delete();
                          }
                      }
                     //dd($attachment_file);

                    //xoá các file trong mảng del
                    $this->update_sub_data($car);
                    DB::commit();

                    return $car;
                }

            } catch (\Exception $e) {
                DB::rollback();
                $this->errors = $e->getMessage();
            }
        }

        return null;
    }
    public function update_date_limit()
    {
        date_default_timezone_set("Asia/Ho_Chi_Minh");

        $fields = $this->request->all();
       // dd($this->request->all());
        $car = Car::findOrFail($this->request->object_id);
        if($car->proposer==auth()->user()->id && $car->status !=2){
            try {

                if ($car) {
                    DB::beginTransaction();
                    Car::where('id',$this->request->object_id)->update(['date_to_limit'=>$fields['date_to_limit']]);
                    $this->update_sub_data($car);
                    DB::commit();

                    return $car;
                }

            } catch (\Exception $e) {
                DB::rollback();
                $this->errors = $e->getMessage();
            }
        }

        return null;
    }
    public function destroy($id)
    {

        $document = Car::findOrFail($id);

        if (!$document) {
            abort(404);
        }
        if ($document->status == 2) {
            $this->message = "Vui lòng không xoá chứng từ đã hoàn tất!";
        } else {
            try {
                // dd($contract->attachment_file());
                DB::beginTransaction();
                $this->destroy_sub_data($document);
                $document->delete();
                DB::commit();
                return true;

            } catch (\Exception $e) {
                DB::rollback();
                $this->errors = $e->getMessage();
                return false;
            }
        }

        return false;
    }
    public function statistics()
    {

        $result = array();
        $result['data'] = array();
        $result['success'] = "0";
        $query = Car::query();

        try {
            //dd($this->request);
            if ($this->request->filled('start_date')) {
                if (!$this->request->filled('end_date')) {
                    $this->request->end_date = $this->request->start_date;
                }
                $start_date = Carbon::create($this->request->start_date);
                $end_date = Carbon::create($this->request->end_date);
                $end_date->addHours(23);
                $end_date->addMinutes(59);
                $end_date->addSeconds(59);
                $query = $query->whereBetween('created_at', [$start_date, $end_date]);
            }

            if ($this->request->filled('status')) {
                    $query = $query->whereIn('status', explode(",",$this->request->status));
            }

            if ($this->request->filled('document_type_id')) {
                $query = $query->where('document_type_id', $this->request->document_type_id);
            }
            if ($this->request->filled('company_id')) {
                $query = $query->where('company_id', $this->request->company_id);
            }
            if ($this->request->filled('department_id')) {
                $query = $query->where('department_id', $this->request->department_id);
            }
            if ($this->request->filled('document_type_id')) {

                $query = $query->where('document_type_id', $this->request->document_type_id);
            }
            if ($this->request->filled('serial_num')) {
                $query = $query->where('serial_num', $this->request->serial_num);
            }
            if ($this->request->filled('type_car_id')) {
                $query = $query->where('type_car_id', $this->request->type_car_id);
            }
            if ($this->request->filled('standard_id')) {
                $query = $query->where('standard_id', $this->request->standard_id);
            }
            if ($this->request->filled('user_id')) {
                $query = $query->where('user_id', $this->request->user_id);
            }

        } catch (Exception $e) {
            $this->errors = $e->getMessage();
        }

        $car_list = Car::select('id')->get();
        $step22 = array();

        foreach($car_list as $car){
                array_push($step22,$car->id);
        }
        $withModel = [ 'approveds', 'user', 'document_type','department','fast_process','cause_measure','monitor_implementation','result_evaluation','proposer','car_error','company','type_car','standard'];

            $document = $query->whereIn('id', $step22)->orderBy('id', 'desc')->with($withModel)->withCount('approveds')->get();

        return $document;
    }
    public function statistic_status()
    {

        $result = array();
        $result['data'] = array();
        $result['success'] = "0";
        $query = Car::query();
     // dd($this->request->filled('status'));
        try {
            if ($this->request->filled('status')) {
                    $query = $query->whereIn('status', explode(",",$this->request->status))
                                    ->whereYear('created_at', date('Y'));
            }

        } catch (Exception $e) {
            $this->errors = $e->getMessage();
        }

        $car_list = Car::select('id')->get();
        $step22 = array();

        foreach($car_list as $car){
                array_push($step22,$car->id);
        }
        $withModel = [ 'approveds', 'user', 'document_type','department','fast_process','cause_measure','monitor_implementation','result_evaluation','proposer','car_error','company','type_car','standard'];

            $document = $query->whereIn('id', $step22)->orderBy('id', 'desc')->with($withModel)->withCount('approveds')->get();

        return $document;
        //dd($document);
    }
    //Hàm trả về Team duyệt
    //abstract protected function getTeam();
    //các hàm này không sử dụng commit
    abstract protected function store_sub_data($obj);
    abstract protected function update_sub_data($obj);
    abstract protected function destroy_sub_data($obj);


}

?>
