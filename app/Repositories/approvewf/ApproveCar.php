<?php

namespace App\Repositories\approvewf;

use App\Jobs\SendEmail;
use App\Mail\EmailCarRequest;
use App\Mail\EmailCarSuccess;
use App\Mail\EmailPayequest;
use App\Models\car\Car;
use App\Models\document\Document;
use App\Models\shared\ApprovedStep;
use App\Models\shared\DocumentType;
use App\Models\shared\Timeline;
use App\Models\shared\Wfapproved;
use App\Notifications\ApprovedPaymentNoti;
use App\Notifications\CommondNotification;
use App\Notifications\NotiBaseModel;
use App\Repositories\approvewf\ApproveCarAbs;
use App\Repositories\DocumentSerials;
use App\Ultils\Ultils;
use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
class ApproveCar extends ApproveCarAbs

{

    public function __set($name, $value)
    {
        $this->$name = $value;
    }
    public function __get($name)
    {
        return $this->$name;
    }
    public function index(Request $request)
    {
        $status = [0, 1, 2];

        if ($request->status != null || $request->status != '') {
            $status = [];
            array_push($status, $request->status);
        }
        if ($request->status != null || $request->status != '') {
            $status = [];
            array_push($status, $request->status);
        }
        // dd($status);
        try {
            $document = Car::whereHas('approveds', function (Builder $query) use ($status, $request) {
                //  dd($request->filled('start_date'));
                if ($request->filled('start_date')) {
                    if (!$request->filled('end_date')) {
                        $request->end_date = $request->start_date;
                    }
                    $start_date = Carbon::create($request->start_date);
                    $end_date = Carbon::create($request->end_date);
                    $end_date->addHours(23);
                    $end_date->addMinutes(59);
                    $end_date->addSeconds(59);

                    $query->whereBetween('created_at', [$start_date, $end_date]);
                    //  dd("test");
                }
                $query->where('user_id', auth()->user()->id)
                    ->whereIn('release', $status)
                    ->where('online', 'X')
                ;
            });

            if ($request->filled('company_id')) {
                $document = $document->where('company_id', $request->company_id);
            }
            if ($request->filled('department_id')) {
                $document = $document->where('department_id', $request->department_id);
            }
            if ($request->filled('document_type_id')) {
                $document = $document->where('document_type_id', $request->document_type_id);
            }
            if ($request->filled('serial_num')) {
                $document = $document->where('serial_num', $request->serial_num);
            }


            $document = $document->orderBy('created_at', 'desc')->with([  'approveds', 'user','approveds.user'])->withCount('approveds')->get();
            return $document;
        } catch (Exception $e) {
            $result['success'] = "0";
            return null;
        }

    }
    public function index_car_log(Request $request)
    {
        $status = [0, 1, 2];

        if ($request->status != null || $request->status != '') {
            $status = [];
            array_push($status, $request->status);
        }
        if ($request->status != null || $request->status != '') {
            $status = [];
            array_push($status, $request->status);
        }
        // dd($status);
        try {
            $document = Car::whereHas('approveds', function (Builder $query) use ($status, $request) {
                //  dd($request->filled('start_date'));
                if ($request->filled('start_date')) {
                    if (!$request->filled('end_date')) {
                        $request->end_date = $request->start_date;
                    }
                    $start_date = Carbon::create($request->start_date);
                    $end_date = Carbon::create($request->end_date);
                    $end_date->addHours(23);
                    $end_date->addMinutes(59);
                    $end_date->addSeconds(59);

                    $query->whereBetween('created_at', [$start_date, $end_date]);
                    //  dd("test");
                }
                $query->where('user_id', auth()->user()->id)
                    ->whereIn('release', $status)
                    ->where('online', 'X')
                ;
            });

            if ($request->filled('company_id')) {
                $document = $document->where('company_id', $request->company_id);
            }
            if ($request->filled('department_id')) {
                $document = $document->where('department_id', $request->department_id);
            }
            if ($request->filled('document_type_id')) {
                $document = $document->where('document_type_id', $request->document_type_id);
            }
            if ($request->filled('serial_num')) {
                $document = $document->where('serial_num', $request->serial_num);
            }


            $document = $document->orderBy('created_at', 'desc')->with([  'approveds', 'user','approveds.user'])->withCount('approveds')->get();
            return $document;
        } catch (Exception $e) {
            $result['success'] = "0";
            return null;
        }

    }
    // public function store_app_car()
    // {

    //     $result = array();
    //     $result['data'] = array();
    //     $result['data']['success'] = 0;
    //     date_default_timezone_set("Asia/Ho_Chi_Minh");

    //     $validator = $this->validate_store();

    //     $failed = $validator->fails();
    //     $fields = $this->request->all();

    //     if ($failed) {
    //         $this->errors = $validator->errors();

    //     } else {

    //         try {

    //             DB::beginTransaction();
    //             $user = new User();
    //             $user = auth()->user();

    //             $fields['user_id'] = $user->id;
    //             $fields['company_id'] = $user->company->id;
    //             $fields['department_id'] = $user->department->id;


    //             try {

    //                 $wfapprove = Wfapproved::create($fields);


    //             } catch (\Exception $e1) {

    //                 $validator->errors()->add('user_id', $e1->getMessage());
    //                 $this->errors = $validator->errors();

    //                 return null;
    //             }

    //             DB::commit();
    //             return $wfapprove;

    //         } catch (\Exception $e) {
    //             DB::rollback();

    //             $this->errors = $e->getMessage();
    //             return null;
    //         }
    //     }
    //     return null;

    // }
    protected function validate_store()
    {
        $validator = Validator::make($this->request->all(), [
            'user_id'=> 'required'
        ],
        [
            'user_id.required' => "Người xác nhận không được rỗng",
        ]
        );

        $fields = $this->request->all();
        $user = new User();
        $user = auth()->user();

        if (!$user->position) {
            $validator->after(function ($validator) {
                $validator->errors()->add('position', 'User chưa được cấu hình chức danh duyệt');

            });

        }

        return $validator;
    }
    //
    public function is_cancel(){

        if($this->object->status == -1){ //đã huỷ
            return true;
        }
        return false;

    }
    public function validate(){

       if($this->is_cancel()){
           return false;
       }
       return true;

    }
    public function notifycation(Wfapproved $approved)
    {
       //dd($approved);
       $data = new NotiBaseModel;
       $document  = new Car();
       $document = Car::find($approved->wfapprovedable_id);
       //dd($document->document_type_id);
       $documentType = DocumentType::find($document->document_type_id);
        if($approved->finished == 1){
            $data->title = "Đã duyệt " . Str::lower($documentType->name);
            $data->icon = "fas fa-briefcase";
            $data->content = $document->serial_num;
            $data->content_full = $documentType->name;
            $data->user = auth()->user()->name;
            $data->url = URL('/').'/'. Ultils::$URL_CAR_VIEW . $document->id;
            $data->object_type = Car::class;
            $data->object_id =  $document->id;

            //dd($document->user);
            $email = new EmailCarSuccess($data,$document->user,$document);
            $document->user->notify(new CommondNotification($data,$document->user,$email) );
            if($document->user_id != $document->proposer){
                $document->proposer = User::find($document->proposer);
                $email = new EmailCarSuccess($data,$document->proposer,$document);
                $document->user->notify(new CommondNotification($data,$document->proposer,$email) );
              }

        }else if($approved->release == -2){

            $data->title = "Phản hồi duyệt " . Str::lower($documentType->name);
            $data->icon = "fas fa-briefcase";

            $data->content = $document->serial_num;
            $data->user = auth()->user()->name;
            $data->url = URL('/').'/'. Ultils::$URL_CAR_VIEW  . $document->id;
            $data->object_type = Car::class;
            $data->object_id =  $document->id;

            $email = new EmailCarRequest($data,$document->user,$document);
            $document->timelines()->save(new Timeline(['title'=>"form.feedback",'icon'=>'fas fa-comments bg-warning','content'=>$approved->note,'user_id'=>auth()->user()->id]));
            $document->user->notify(new CommondNotification($data,$document->user,$email) );
            if($document->user_id != $document->proposer){
                $document->proposer = User::find($document->proposer);
                $email = new EmailCarRequest($data,$document->proposer,$document);
                $document->user->notify(new CommondNotification($data,$document->proposer,$email) );
              }

        }else{

            //dd($approved->user);
            $data->title = "Yêu cầu duyệt " .Str::lower($documentType->name);
            $data->icon = "fas fa-briefcase";
            $data->content = $document->serial_num;
            $data->user = auth()->user()->name;
            $data->url = URL('/').'/'.Ultils::$URL_CAR_VIEW . $document->id;
            $data->object_type = Car::class;
            $data->object_id =  $document->id;

            $email = new EmailCarRequest($data, $approved->user,$document);
            //dd($approved->user->notify(new CommondNotification($data,$approved->user,$email)));
            $approved->user->notify(new CommondNotification($data,$approved->user,$email));

        }



    }
    public function update_status_send()
    {
        $this->object->timelines()->save(new Timeline(['title'=>"form.send_approve",'icon'=>'fas fa-paper-plane bg-info','user_id'=>auth()->user()->id]));
        //cập nhật ngày gửi
        $this->object->status = 1;
        $this->object->send_date = now();
        $this->object->save();
    }
    public function update_status_agree()
    {
        $this->object->timelines()->save(new Timeline(['title'=>"form.approved",'icon'=>'fas fa-check bg-success','user_id'=>auth()->user()->id]));
    }
    public function update_status_refuse()
    {
        $this->object->timelines()->save(new Timeline(['title'=>"form.send_refuse",'icon'=>'fas fa-paper-plane bg-info','user_id'=>auth()->user()->id]));
        //cập nhật ngày gửi
        $this->object->status = -2;
        $this->object->save();
    }
    public function update_status_agree_finish()
    {

        $this->object->timelines()->save(new Timeline(['title'=>"form.complete_approval",'icon'=>'fas fa-check-circle bg-success','user_id'=>auth()->user()->id]));
        //Xác định người duyệt cuối
        $term_release_count = 0;
        $document = $this->object;

        //  dd('test o day');
        //cập nhật lại trạng thái chứng từ
        $document->status = 2; //Duyệt xong

        $document->save();

    }
    public function update_car_error($car_error_id,$is_cause_measure){
        $car = Car::find($this->object->id);
        $car->car_error_id = $car_error_id;
        $car->is_cause_measure = $is_cause_measure;
        $car->save();
    }
    public function update_car_cause_risk($cause,$risk){
        $car = Car::find($this->object->id);
        $car->cause = $cause;
        $car->risk = $risk;
        $car->save();
    }
    public function update_pcar($id,$step){

        $car = Car::find($id);

        $document = DocumentType::find($car->document_type_id);
        //dd($document->code);
        if($document->code=='PCAR' && $step===1){
        $updateDate = Car::where('id',$id)->update(['proposer'=>$car->user_id]);
        if($updateDate){
            return true;
        }
        return false;
        }
    }
    public function update_proposer($id,$user_id){
        $car = Car::find($id);
        $document = DocumentType::find($car->document_type_id);
        //dd($document->code);
        if($document->code=='PCAR'){
        $updateDate = Car::where('id',$id)->update(['proposer'=>$user_id]);
        if($updateDate){
            return true;
        }
        return false;
        }
    }
    public function get_step_name($stepid){
      $name = "";
      $documentType = DocumentType::where('id',$this->object->document_type_id)->first();

      if($documentType){
        $step = ApprovedStep::where('document_type_id',$documentType->id)->where('step',$stepid)->first();
        $name=   $step?$step->name:"";
       // dd($documentType->id);
      }
      if($name == ''){
        $name = parent::get_step_name($stepid);
      }

        return $name;

    }


}
