<?php 
namespace App\Repositories\car\causemeasure;

use App\Models\car\Car;
use App\Models\car\CauseMeasure;
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
use App\Notifications\CommondNotification;
use App\Notifications\NotiBaseModel;
use Illuminate\Support\Str;
use App\Mail\EmailCarAssignResponse;
use App\Mail\EmailCarRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use SoareCostin\FileVault\Facades\FileVault as FacadesFileVault;
abstract class CauseMeasureAbs{


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

    public function index(Request $request)
    {
       
        $result = array();
        $result['data'] = array();
        $result['success'] = "0";
        $query = CauseMeasure::where('car_id',$request->car_id)->get();
       
        return $query;
    }
    protected function validate_store()
    {   
     
       
        $validator = Validator::make($this->request->all(), [
 
            'cause' => 'required',
            'measure' => 'required',
            'date' => 'required|date',
            'person_in_charge' => 'required'
        ],
        [
            'cause.required' => "Nguyên nhân không được rỗng.",
            'measure.required' => 'Biện pháp khắc phục không được rỗng.',
            'date.required' => 'Chưa chọn ngày.',
            'person_in_charge.required' => "Người phụ trách không được rỗng.",
        ]
        );
        return $validator;
    }
    public function show($id)
    {
        $car = CauseMeasure::with('other_attacheds')->find($id);

        // $car = Car::find($id);

        $this->check_read_noti();
        return $car;
    }

    public function edit($id)
    {      
        $car = CauseMeasure::find($id);
       
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
      
        $validator = $this->validate_store();
       
        $failed = $validator->fails();
        $fields = $this->request->all();
        //dd($this->request->all());
       //dd($fields['attachment_file']);
  
         if ($failed) {
             $this->errors = $validator->errors();
           
         } else {
            $us =  array();
            $car = Car::with('shareds')->find($fields['car_id']);
            for($i=0;$i<count($car->shareds);$i++){
                if($car->shareds[$i]['type']==4){
                    array_push($us,$car->shareds[$i]['assign']['id']);
                }
            }
            $document_type = DocumentType::find($car->document_type_id);
            $monitor = MonitorImplementation::where('car_id',$fields['car_id'])->count();
            if($car->proposer==auth()->user()->id &&
              $car->status !=2 && $monitor==0 &&
               (($document_type->code=='SCAR') ||
                ($document_type->code=='PCAR' && 
                $car->is_cause_measure===1)) ||
                 (in_array(auth()->user()->id,array_unique($us)))){
            try {
              
                DB::beginTransaction();
                $user = new User();
                $user = auth()->user();
                 
                try {
                    //dd($fields['steps'][0]['cause']);
                    //for($i=0;$i<count($fields['steps']);$i++){
                        $causemeasure = new CauseMeasure();
                        $causemeasure->car_id =$fields['car_id'];
                        $causemeasure->cause =$fields['cause'];
                        $causemeasure->measure =$fields['measure'];
                        $causemeasure->date =$fields['date'];
                        $causemeasure->person_in_charge = $fields['person_in_charge'];
                        //dd($causemeasure->save());
                        $causemeasure->save();
                   // }
                  
                   // $causemeasure = CauseMeasure::create($fields);
                    //File khác

                  
                                  // dd($fields['evaluation']['attachment_file']);
                                  
                                  $other_attacheds = $fields['attachment_file'];
                                  
                                  //dd($other_attacheds);
                                  for ($i = 0; $i < count($other_attacheds); $i++) {
                                       $otherAttached = new OtherAttached();
                                      // $otherAttached->objectable_id = $fields['evaluation']['object_id'];
                                       $otherAttached->name = $other_attacheds[$i]['name'];
                                       //dd($other_attacheds[$i]);
                                     // dd($evaluate->other_attacheds()->save($otherAttached));
                                       $causemeasure->other_attacheds()->save($otherAttached);
                                      //dd($evaluate->other_attacheds());
                                      //save file
                                    //  $attachment_file = $other_attacheds[$i]['attachment_file'];
                                     //dd( $attachment_file);
              
                               
                                          //dd(count($other_attacheds));
                                          $file = new File();
              
                                          $file->name = $other_attacheds[$i]["name"];
                                          //$ext = end(explode('.', $filename));
                                          // $file->ext = $attachment_file[$i]["ext"];
                                          $file->size = $other_attacheds[$i]["size"];
                                          $file->user_id = $user->id;
              
                                          $ext = substr($other_attacheds[$i]["name"], strrpos($other_attacheds[$i]["name"], '.') + 1);
                                          $name = "public/car/" . $user->username . "/" . uniqid() . '.' . $ext; // $attachment_file[$i]["ext"];
              
                                          $file->ext = $ext;
                                          $file->url = $name;
                                          $otherAttached->attachment_file()->save($file);
              
                                          //$name = "/avatars/" . $user->username . '.' . $this->request->file['ext'];
                                          $base64_str = substr($other_attacheds[$i]['base64'], strpos($other_attacheds[$i]['base64'], ",") + 1);
                                          $image = base64_decode($base64_str);
                                          //file_put_contents(public_path().$name,  $image );
                                          Storage::disk('local')->put($name, $image);
                                          FacadesFileVault::encrypt($name);
              
                                     
              
                                  }
                                    if((in_array(auth()->user()->id,array_unique($us)))){
                                    $data = new NotiBaseModel;
                                    $url =  Ultils::$URL_CAR_VIEW . $car->id;
                                    $documentType = DocumentType::find($car->document_type_id);
                                    $data->title = Str::ucfirst(Str::lower($documentType->name))  ." được thêm mới" ;
                                    $data->icon = "fa fa-tasks";
                                    $data->content = $car->serial_num;
                                    $data->url = URL('/').'/'.$url;
                                    if($documentType->code == 'PCAR'){
                                        if($car->user_id == $car->proposer){
                                            $car = Car::find($car->id);
                                            $user = User::find($car->proposer);
                                            $email = new EmailCarRequest($data, $user,$car);
                                            $user->notify(new CommondNotification($data,$user,$email) );
            
                                            }else{
                                                $car = Car::find($car->id);
                                                $user = User::find($car->proposer);
                                                $email = new EmailCarRequest($data, $user,$car);
                                                $user->notify(new CommondNotification($data,$user,$email) );
            
                                                $car = Car::with('user')->find($car->id);
                                                $email = new EmailCarRequest($data, $car->user,$car);
                                                $car->user->notify(new CommondNotification($data,$car->user,$email) );
                                            }
                                       }
                                    else{
                                        $car = Car::with('user')->find($car->id);
                                        $email = new EmailCarRequest($data, $car->user,$car);
                                        $car->user->notify(new CommondNotification($data,$car->user,$email) );
                                       }
                                    }

                } catch (\Exception $e1) {
                 
                    // $validator->errors()->$e1->getMessage();
                     $this->errors = $e1->getMessage();
                   // dd('$fields');
                    return null;
                }
                $this->store_sub_data($causemeasure);
                DB::commit();
                return $causemeasure;

            } catch (\Exception $e) {
                DB::rollback();
              
                $this->errors = $e->getMessage();
                return null;
            }
          }
        }
        return null;

    }
    protected function validate_update($id)
    {
       //dd($this->request->car_id);
       $validator = Validator::make($this->request->all(), [
 
        'cause' => 'required',
        'measure' => 'required',
        'date' => 'required|date',
        'person_in_charge' => 'required'
    ],
    [
        'cause.required' => "Nguyên nhân không được rỗng.",
        'measure.required' => 'Biện pháp khắc phục không được rỗng.',
        'date.required' => 'Chưa chọn ngày.',
        'person_in_charge.required' => "Người phụ trách không được rỗng.",
    ]
    );
        $fields = $this->request->all();

        //dd($fields['car_id']);
        $car = Car::findOrFail($fields['car_id']);
       
        if ($car->status == 2) {
            $validator->after(function ($validator) {
                $validator->errors()->add('chungtuhoantat', 'Phiếu car đã hoàn tất. Vui lòng không cập nhật.');

            });
        }
       // if ($car->status == -2) {
        //    $validator->after(function ($validator) {
         //       $validator->errors()->add('chungtuhuy', 'Phiếu car đã huỷ. Vui lòng không cập nhật.');

         //   });
       // }
       // $user = new User();
       // $user = auth()->user();
  
        return $validator;
    }
    public function update($id)
    {
        date_default_timezone_set("Asia/Ho_Chi_Minh");

        $validator = $this->validate_update($id);
        $failed = $validator->fails();
        $fields = $this->request->all();
       // dd($fields);
        $document = CauseMeasure::findOrFail($id);
        $car = Car::with('shareds')->find($fields['car_id']);
        $us =  array();
        for($i=0;$i<count($car->shareds);$i++){
            if($car->shareds[$i]['type']==4){
                array_push($us,$car->shareds[$i]['assign']['id']);
            }
        }
        $monitor = MonitorImplementation::where('car_id',$fields['car_id'])->count();
        // $user = new User();
        // $user = auth()->user();
       // dd($car->proposer);
        if ($failed) {
            $this->errors = $validator->errors();
        } else {
          
            try {

                if ($document && $car->proposer==auth()->user()->id &&
                 $car->status !=2 &&
                 $monitor==0 || 
                 ($document && 
                 $car->status !=2 &&
                 $monitor==0 && 
                 in_array(auth()->user()->id,array_unique($us)))) {
                    DB::beginTransaction();
                   
                   // dd($fields['date']);
                    $updateData = CauseMeasure::where('id',$id)->update([
                        'cause'=>$fields['cause'],
                        'measure'=>$fields['measure'],
                        'date' =>  $fields['date']
                    ]);
                    if((in_array(auth()->user()->id,array_unique($us)))){
                        $data = new NotiBaseModel;
                        $url =  Ultils::$URL_CAR_VIEW . $car->id;
                        $documentType = DocumentType::find($car->document_type_id);
                        $data->title = Str::ucfirst(Str::lower($documentType->name))  ." được cập nhật lại" ;
                        $data->icon = "fa fa-tasks";
                        $data->content = $car->serial_num;
                        $data->url = URL('/').'/'.$url;
                        if($documentType->code == 'PCAR'){
                                if($car->user_id == $car->proposer){
                                $car = Car::find($car->id);
                                $user = User::find($car->proposer);
                                $email = new EmailCarRequest($data, $user,$car);
                                $user->notify(new CommondNotification($data,$user,$email) );

                                }else{
                                    $car = Car::find($car->id);
                                    $user = User::find($car->proposer);
                                    $email = new EmailCarRequest($data, $user,$car);
                                    $user->notify(new CommondNotification($data,$user,$email) );

                                    $car = Car::with('user')->find($car->id);
                                    $email = new EmailCarRequest($data, $car->user,$car);
                                    $car->user->notify(new CommondNotification($data,$car->user,$email) );
                                }
                           }
                        else{
                            $car = Car::with('user')->find($car->id);
                            $email = new EmailCarRequest($data, $car->user,$car);
                            $car->user->notify(new CommondNotification($data,$car->user,$email) );
                           }
                        }
                    // //save file
                    // $attachment_file = $fields['attachment_file'];
                    // $attachment_file_removed = $fields['attachment_file_removed'];
                    // // dd($attachment_file);
                    // for ($j = 0; $j < count($attachment_file); $j++) {

                    //     //chỉ lưu  các file mới
                    //     if (!isset($attachment_file[$j]["id"])) {
                    //         $file = new File();

                    //         $file->name = $attachment_file[$j]["name"];
                    //         //$ext = end(explode('.', $filename));
                    //         // $file->ext = $attachment_file[$i]["ext"];
                    //         $file->size = $attachment_file[$j]["size"];
                    //         $file->user_id = $user->id;
                            
                    //         $strDate = date("Y"). "/" .date("m"). "/"  .date("d");
                    //         $ext = substr($attachment_file[$j]["name"], strrpos($attachment_file[$j]["name"], '.') + 1);
                    //         $name = "public/document/" .$strDate. "/" . uniqid() . '.' . $ext;

                    //         $file->ext = $ext;
                    //         $file->url = $name;
                    //         $document->attachment_file()->save($file);

                    //         //$name = "/avatars/" . $user->username . '.' . $request->file['ext'];
                    //         $base64_str = substr($attachment_file[$j]['base64'], strpos($attachment_file[$j]['base64'], ",") + 1);
                    //         $image = base64_decode($base64_str);
                    //         //file_put_contents(public_path().$name,  $image );
                    //         Storage::disk('local')->put($name, $image);
                    //         FacadesFileVault::encrypt($name);

                    //     }

                    // }
                    // //xoá các file trong mảng del

                    // for ($k = 0; $k < count($attachment_file_removed); $k++) {
                    //     if (isset($attachment_file_removed[$k]["id"])) {
                    //         $file = File::findOrFail($attachment_file_removed[$k]["id"]);
                    //         if ($file) {

                    //             Storage::delete($file->url . ".enc");
                    //             $file->delete();
                    //         }
                    //     }
                    // }
                    
                    $this->update_sub_data($updateData);
                    DB::commit();

                    return $updateData;
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
       // dd($id);
        $cause = CauseMeasure::with('other_attacheds')->findOrFail($id);
        $car = Car::with('approveds','document_type','monitor_implementation')->findOrFail($cause->car_id);
       
        $monitor = count($car['monitor_implementation']);
        $fast_approve = 0;
        if($car->proposer==auth()->user()->id && $monitor==0){
        if($car['document_type']['code']==='SCAR'){
            for($j=0;$j<count($car['approveds']);$j++){
                if($car['approveds'][$j]['step']==2 && $car['approveds'][$j]['online']=='X'){
                    $fast_approve ++;
                }
            }
        }
        if($car['document_type']['code']=='PCAR'){
            for($j=0;$j<count($car['approveds']);$j++){
                if($car['approveds'][$j]['step']==3 && $car['approveds'][$j]['online']=='X'){
                    $fast_approve ++;
                }
            }
        }
      
        if ($car->status == 2) {
            $this->message = "Phiếu đã hoàn tất không được xóa!";
        }
        elseif($fast_approve>0 &&  $car->status!=-2){
            $this->message = "Nguyên nhân và biện pháp khắc phục đang chờ duyệt!";
        }  else {
            try {
                DB::beginTransaction();
                $other_attacheds_del = $cause['other_attacheds'];
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
                $this->destroy_sub_data($cause);
                $cause->delete();
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