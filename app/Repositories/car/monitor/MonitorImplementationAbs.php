<?php 
namespace App\Repositories\car\monitor;

use App\Models\car\Car;
use App\Models\car\MonitorImplementation;
use App\Models\shared\DocumentType;
use App\Models\shared\File;
use App\Models\shared\OtherAttached;
use App\Models\shared\Reminder;
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
use SoareCostin\FileVault\Facades\FileVault as FacadesFileVault;
abstract class MonitorImplementationAbs{


    protected $document;
    protected $request;
    protected $errors;
    protected $message;
    protected $layout;
    protected $form_name;
    protected $type;
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

    public function index(Request $request)
    {
       
        $result = array();
        $result['data'] = array();
        $result['success'] = "0";
        $query = MonitorImplementation::where('car_id',$request->car_id)->get();
       
        return $query;
    }
    protected function validate_store()
    {   
     
        //dd($this->request->all());
        $validator = Validator::make($this->request->all(), [
 
            'result' => 'required',
            'date' => 'required|date',
            'finished_date' => 'required|date'
           
        ],
        [
            'result.required' => "Kết quả đánh giá không được rỗng.",
            'date.required' => 'Chưa chọn ngày đánh giá.',
            'finished_date.required' => 'Chưa chọn ngày hoàn thành đánh giá.'
        ]
        );
        return $validator;
    }
    public function show($id)
    {
        $car = MonitorImplementation::find($id);

        // $car = Car::find($id);

        $this->check_read_noti();
        return $car;
    }

    public function edit($id)
    {      
        $car = MonitorImplementation::find($id);
       
        return $car;
    }
    public function check_read_noti(){
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
        }
      //  dd("tesst");
    }
    public function store()
    {
        
        $result = array();
        $result['data'] = array();
        $result['data']['success'] = 0;
        date_default_timezone_set("Asia/Ho_Chi_Minh");
 
        $fields = $this->request->all();
        
       // dd( $fields);
  
            $car = Car::with('approveds','document_type')->findOrFail($fields['id']);
            $cause_approve = 0;

           if($car['document_type']['code']==='SCAR'){
            for($j=0;$j<count($car['approveds']);$j++){
                if($car['approveds'][$j]['step']==2 &&
                  $car['approveds'][$j]['online']=='X' &&
                  $car['approveds'][$j]['finished']==1){
                    $cause_approve ++;
                }
            }
        }

        if($car['document_type']['code']==='PCAR'){
            for($j=0;$j<count($car['approveds']);$j++){
                if($car['approveds'][$j]['step']==3 &&
                  $car['approveds'][$j]['online']=='X' &&
                  $car['approveds'][$j]['finished']==1){
                    $cause_approve ++;
                }
            }
        }
       // dd($car['approveds'][count($car['approveds'])-1]);

            if($car->proposer==auth()->user()->id &&  $car->status !=2 && $cause_approve>0 &&  $car['approveds'][count($car['approveds'])-1]['finished']===1){
            try {
                DB::beginTransaction();
                try {
                   // dd($fields['monitor']);
                    for($i=0;$i<count($fields['monitor']);$i++){
                        $check_exist = MonitorImplementation::where('cause_measure_id',$fields['monitor'][$i]['id'])->first();
                        if($check_exist===null){
                            $monitor = new MonitorImplementation();
                            $monitor->car_id =$fields['monitor'][$i]['car_id'];
                            $monitor->cause_measure_id =$fields['monitor'][$i]['id'];
                            $monitor->result =$fields['monitor'][$i]['result'];
                            $monitor->date =$fields['monitor'][$i]['date'];
                            $monitor->finished_date =$fields['monitor'][$i]['finished_date'];
                            $monitor->save();
                        }else{
                            $monitor = MonitorImplementation::find($check_exist->id);
                            $monitor->result =$fields['monitor'][$i]['result'];
                            $monitor->date =$fields['monitor'][$i]['date'];
                            $monitor->finished_date =$fields['monitor'][$i]['finished_date'];
                            $monitor->save();   
                        }
                    }
                  
                } catch (\Exception $e1) {
                 
                    // $validator->errors()->$e1->getMessage();
                     $this->errors = $e1->getMessage();
                   // dd('$fields');
                    return null;
                }
                $this->store_sub_data($monitor);
                DB::commit();
                return $monitor;

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
       
        $validator = Validator::make($this->request->all(),[
            'result' => 'required',
            'date' => 'required|date',
            'finished_date' => 'required|date'
           
        ],
        [
            'result.required' => "Kết quả đánh giá không được rỗng.",
            'date.required' => 'Chưa chọn ngày đánh giá.',
            'finished_date.required' => 'Chưa chọn ngày hoàn thành đánh giá.'
        ]
        );
        $fields = $this->request->all();
        //$fields['id'];
        //dd( $fields['id']);
        $car = Car::findOrFail($fields['id']);
       
        if ($car->status == 2) {
            $validator->after(function ($validator) {
                $validator->errors()->add('chungtuhoantat', 'Phiếu car đã hoàn tất. Vui lòng không cập nhật.');
            });
        }
        if ($car->status == -2) {
            $validator->after(function ($validator) {
                $validator->errors()->add('chungtuhuy', 'Phiếu car đã huỷ. Vui lòng không cập nhật.');

            });
        }
        $user = new User();
        $user = auth()->user();
        return $validator;
    }

    public function update($id)
    {
        date_default_timezone_set("Asia/Ho_Chi_Minh");

        // $validator = $this->validate_update($id);
        // $failed = $validator->fails();
        $fields = $this->request->all();
      // dd(($fields['id']));
        $monitor = Car::findOrFail($fields['id']);
        if($monitor->proposer==auth()->user()->id &&  $monitor->status !=2){
     //dd($monitor);
        // if ($failed) {
        //     $this->errors = $validator->errors();
        // } else {
          
            try {
                if ($monitor) {
                    DB::beginTransaction();
                    // $user = new User();
                    // $user = auth()->user();
                   // dd($fields['monitor'][0]['id']);
                    for($i=0;$i<count($fields['monitor']);$i++){
                        $updateData =  MonitorImplementation::where('cause_measure_id',$fields['monitor'][$i]['id'])->update([
                            'result'=> $fields['monitor'][$i]['result'],
                            'date'=> $fields['monitor'][$i]['date'],
                            'finished_date'=> $fields['monitor'][$i]['finished_date']
                        ]);
                    }

                    $this->update_sub_data($updateData);
                    DB::commit();

                    return $updateData;
                }

            } catch (\Exception $e) {
                DB::rollback();
                $this->errors = $e->getMessage();
            }
        }

        // return null;
    }
    
    public function destroy($id)
    {
        $getData = MonitorImplementation::where('cause_measure_id',$id)->get();
       // dd($getData);
        foreach($getData as $mn)
        $monitor = MonitorImplementation::findOrFail($mn->id);
        //dd($monitor);

        $car = Car::with('approveds','document_type')->findOrFail($monitor->car_id);
        if($car['document_type']['code']==='SCAR'){
            for($j=0;$j<count($car['approveds']);$j++){
                if($car['approveds'][$j]['step']==3 && $car['approveds'][$j]['online']=='X'){
                    $result_approve ++;
                }
            }
        }
        if($car['document_type']['code']==='PCAR'){
            for($j=0;$j<count($car['approveds']);$j++){
                if($car['approveds'][$j]['step']==4 && $car['approveds'][$j]['online']=='X'){
                    $result_approve ++;
                }
            }
        }
        //dd($result_approve);
        if($car->proposer==auth()->user()->id &&  $car->status !=2 && $result_approve===0){
        if ($car->status == 2) {
            $this->message = "Phiếu đã hoàn tất không được xóa!";
        }
         else {
            try {
                // dd($contract->attachment_file());
                DB::beginTransaction();
                $this->destroy_sub_data($monitor);
                $monitor->delete();
                DB::commit();
                return true;

            } catch (\Exception $e) {
                DB::rollback();
                $this->errors = $e->getMessage();
                return false;
            }
        }
    }
        return false;
    }
     
    abstract protected function store_sub_data($obj);
    abstract protected function update_sub_data($obj);
    abstract protected function destroy_sub_data($obj);

}

?>