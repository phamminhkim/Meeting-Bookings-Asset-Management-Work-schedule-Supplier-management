<?php 
namespace App\Repositories\car\evaluate;

use App\Models\car\Car;
use App\Models\car\MonitorImplementation;
use App\Models\car\ResultEvaluation;
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
use League\CommonMark\Inline\Element\Code;
use SoareCostin\FileVault\Facades\FileVault as FacadesFileVault;
abstract class ResultEvaluationAbs{


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
        $query = ResultEvaluation::where('car_id',$request->car_id)->get();
       
        return $query;
    }
    protected function validate_store()
    {   
     
        //dd($this->request->all());
        $validator = Validator::make($this->request->all(), [
            'content'=> 'required|max:500',
            'result' => 'required',
            'date' => 'required|date'
           
        ],
        [
            'content.required' => "Nội dung đánh giá không được rỗng.",
            'result.required' => "Kết quả đánh giá không được rỗng.",
            'date.required' => 'Chưa chọn ngày đánh giá.'
        ]
        );
        return $validator;
    }
    public function show($id)
    {
        $car = ResultEvaluation::with('other_attacheds')->find($id);

        // $car = Car::find($id);

        $this->check_read_noti();
        return $car;
    }

    public function edit($id)
    {      
        $car = ResultEvaluation::find($id);
       
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
        //dd($fields['car_id'][0]);
        if ($failed) {
            $this->errors = $validator->errors();
           
        } else {
            //$car = Car::find($fields['car_id'][0]);
            $car = Car::with('approveds','document_type','monitor_implementation')->findOrFail($fields['car_id'][0]);
            //$document_type = DocumentType::find($car->document_type_id);
            $monitor = count($car['monitor_implementation']);
            $cause_approve = 0;
            if($car['document_type']['code']==='SCAR'){
                for($j=0;$j<count($car['approveds']);$j++){
                    if($car['approveds'][$j]['step']==2 &&
                       $car['approveds'][$j]['online']=='X'){
                        $cause_approve ++;
                    }
                }
    
            }else{
                for($j=0;$j<count($car['approveds']);$j++){
                    if($car['approveds'][$j]['step']==3 &&
                       $car['approveds'][$j]['online']=='X'){
                        $cause_approve ++;
                    }
                }
            }
            
           
            if($car->proposer==auth()->user()->id &&  $car->status !=2 && $cause_approve>0  && $monitor>0 &&  ($car['approveds'][count($car['approveds'])-1]['finished']===1 || $car['approveds'][count($car['approveds'])-1]['finished']===0)){
            try {
              
                DB::beginTransaction();
                $user = new User();
                $user = auth()->user();
                
                 
                try {
                   // dd($fields['evaluation']['object_id']);
                    //dd($fields['evaluation']['attachment_file']);
                    $evaluate = new ResultEvaluation();
                    $evaluate->car_id = $fields['car_id'][0];
                    $evaluate->content = $fields['content'];
                    $evaluate->result = $fields['result'];
                    $evaluate->date = $fields['date'];
                    $evaluate->save(); 
                    // $fields['car_id'] = $fields['evaluation']['object_id'];
                    // $fields['content'] = $fields['evaluation']['content'];
                    // $fields['result'] = $fields['evaluation']['result'];
                    // $fields['date'] = $fields['evaluation']['date'];

                    //dd( $fields['car_id']);
                   // $evaluate = ResultEvaluation::create($fields);
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
                         $evaluate->other_attacheds()->save($otherAttached);
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

                } catch (\Exception $e1) {
                 
                    // $validator->errors()->$e1->getMessage();
                     $this->errors = $e1->getMessage();
                   // dd('$fields');
                    return null;
                }
                $this->store_sub_data($evaluate);
                DB::commit();
                return $evaluate;

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
       
        $validator = Validator::make($this->request->all(),[
            'content'=> 'required|max:500',
            'result' => 'required',
            'date' => 'required|date'
           
        ],
        [
            'content.required' => "Nội dung đánh giá không được rỗng.",
            'result.required' => "Kết quả đánh giá không được rỗng.",
            'date.required' => 'Chưa chọn ngày đánh giá.'
        ]
        );
        $fields = $this->request->all();
       // dd($fields);
        $car = Car::findOrFail($fields['car_id']);
       
        if ($car->status == 2) {
            $validator->after(function ($validator) {
                $validator->errors()->add('chungtuhoantat', 'Phiếu car đã hoàn tất. Vui lòng không cập nhật.');
            });
        }
        // if ($car->status == -2) {
        //     $validator->after(function ($validator) {
        //         $validator->errors()->add('chungtuhuy', 'Phiếu car đã huỷ. Vui lòng không cập nhật.');

        //     });
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
        $result = Car::findOrFail($fields['car_id']);
        //dd($result);
        if($result->proposer==auth()->user()->id &&  $result->status !=2){
          
        if ($failed) {
            $this->errors = $validator->errors();
        } else {
          
            try {

                if ($result) {
                    DB::beginTransaction();
                    $user = new User();
                    $user = auth()->user();
                  
                    $updateData = ResultEvaluation::where('id',$fields['id'])->update([
                        'result'=>$fields['result'],
                        'content'=>$fields['content'],
                        'date' => $fields['date']
                    ]);

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
     }
        return null;
    }
    
    public function destroy($id)
    {

        //dd($id);
        $result = ResultEvaluation::with('other_attacheds')->findOrFail($id);
        $car = Car::with('approveds','document_type')->findOrFail($result->car_id);
        //dd($car['document_type']['code']);
        $result_approve = 0;
        if($car->proposer==auth()->user()->id){
        if($car['document_type']['code']==='SCAR'){
            for($j=0;$j<count($car['approveds']);$j++){
                if($car['approveds'][$j]['step']==3 && $car['approveds'][$j]['online']=='X'){
                    $result_approve ++;
                }
            }

        }else{
            for($j=0;$j<count($car['approveds']);$j++){
                if($car['approveds'][$j]['step']==4 && $car['approveds'][$j]['online']=='X'){
                    $result_approve ++;
                }
            }
        }

        if ($car->status == 2) {
            $this->message = "Phiếu đã hoàn tất không được xóa!";
        }
        elseif($result_approve>0  &&  $car->status!=-2){
            $this->message = "Đánh giá kết quả đang chờ duyệt hoặc đã được duyệt!";
        } else {
            try {
                DB::beginTransaction();
                $other_attacheds_del = $result['other_attacheds'];
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
                $this->destroy_sub_data($result);
                $result->delete();
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