<?php
namespace App\Repositories\issue;

use App\Models\issue\Issue;
use App\Models\shared\DocumentType;
use App\Models\shared\File;
use App\Models\shared\Group;
use App\Models\shared\Reminder;
use App\Models\shared\Shared;
use App\Models\shared\Team;
use App\Models\shared\Timeline;
use App\Repositories\approve\ApproveRoutingHelper;
use App\Repositories\DocumentSerials;
use App\Traits\HasTeamManual;
use App\Ultils\Ultils;
use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use SoareCostin\FileVault\Facades\FileVault as FacadesFileVault;
abstract class IssueAbs{
use HasTeamManual;

    protected $document;
    protected $request;
    protected $errors;
    protected $message;
    protected $layout;
    protected $form_name;
    protected $documentType;
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
        $query = Issue::query();
        $user = new User();
        $user = Auth()->user();
        try {

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

            $status  = explode(',',$this->request->status);
            $status_null = false;
            for ($i = 0; $i < count($status) ; $i++) {
                if( $status[$i] == 0 ){
                    $status_null = true;
                }
            }

            if ($this->request->filled('status')) {
                // if ($this->request->status == 0) {
                //     $query = $query->where('status', null);
                // } else {
                //     $query = $query->whereIn('status',  $status);
                // }
               // dd($status );
                if ($status_null) {
                    $query = $query->Where(function($q) use($status){
                        $q = $q->whereIn('status',  $status);
                        $q = $q->orWhereNull('status');
                    });

                }else{
                    $query = $query->whereIn('status',  $status);
                }

              //  dd( $query);
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
               // dd($this->request->serial_num);
               $query = $query->where('serial_num','like','%'. $this->request->serial_num.'%');
            }

              //lọc theo phạm vi: của tôi, theo nhóm, .....
              if ($this->request->filled('visibility')) {
                switch ($this->request->visibility) {
                    case '0'://owner
                        $query = $query->where('user_id',$user->id);
                        break;
                    // case '1'://group
                    //     $query = $query->whereNotNull('serial_num');
                    //     break;
                }
            }else {
                 //Lấy những chứng từ của nhóm ( not nul Serial_num) và của tôi ( bao gồm cả null Serial_num)
                 $query = $query->where(function($q ) use ($user){
                   $q = $q->whereNotNull('serial_num');
                   $q = $q->orWhere(function($q1) use ($user){
                    $q1 = $q1->whereNull ('serial_num');
                    $q1 = $q1->where('user_id',$user->id);
                   });
                   // $q = $q->where('user_id',$user->id);
                 });
            }



        } catch (Exception $e) {
            $this->errors = $e->getMessage();
        }


        $user = new User();
        $user = User::find(auth()->user()->id);

        $withModel = [ 'approveds', 'user', 'document_type','reminders'];
        if ($user->hasPermission('review_all_document')) {

            $document = $query->orderBy('id', 'desc')->with($withModel)->withCount('approveds')->get();

        }else if($user->hasPermission('review_company_document')) {
            $company_id = $user->company_id;
            $document = $query->where('company_id',$company_id)->orderBy('id', 'desc')->with($withModel)->withCount('approveds')->get();
        }
         else {

            //Các chứng từ hạn chế xem trong group
            //Những chứng từ chỉ có người tạo mới nhận lại được
            $documentType_ids = DocumentType::where('private','1')->get();
            $special_ids = $documentType_ids->pluck('id')->flatten();
            $query = $query->where(function($q) use($special_ids,$user){
                $q->whereNotIn('document_type_id',$special_ids)
                    ->orWhere(function($sub) use($special_ids,$user){
                    $sub->whereIn('document_type_id',$special_ids)
                        ->where('user_id',$user->id);
                    });
            });

            $group_ids = $user->groups->pluck('id')->flatten();//pluck('id')->flatten();
            //Lấy các chứng từ shared group
            $query = $query->where(function($q) use($group_ids){
                $q->whereIn('group_id',$group_ids)
                    ->orWhereHas('shareds', function($q) use($group_ids){
                        $q->whereIn('group_id',$group_ids);
                    });
            });
            $document = $query->orderBy('id', 'desc')->with($withModel)->withCount('approveds')->get();
            // $document = $query->where('user_id', $user->id)->orderBy('id', 'desc')->with($withModel)->withCount('approveds')->get();
            // $document = $query->whereIn('group_id', $group_ids)->orderBy('id', 'desc')->with($withModel)->withCount('approveds')->get();
        }


        return $document;
    }
    protected function validate_store()
    {

        $validator = Validator::make($this->request->all(), [

            'document_type_id' => 'required',
            'group_id' => 'required',
            'amount' => 'required',
            'content' => 'required',
            'title' => 'required|max:150',

        ],
        [
            'document_type_id.required' => "Loại chứng từ không được rỗng",
            'group_id.required' => "Nhóm không được rỗng",
            'amount.required' => "Số tiền không được rỗng",
            'content.required' => "Nội dung không được rỗng",
            'title.required' => "Tiêu đề không được rỗng",
            'title.max' => "Tiêu đề tối đa 150 kí tự",
        ]
        );

        $fields = $this->request->all();
        //dd( $fields);
       // dd($fields["attachment_file"]);
        $user = new User();
        $user = auth()->user();
        // if (!$user->company) {
        //     $validator->after(function ($validator) {
        //         $validator->errors()->add('company', 'User chưa được cấu hình công ty');

        //     });

        // }

        // if (!$user->department) {

        //     $validator->after(function ($validator) {
        //         $validator->errors()->add('department', 'User chưa được cấu hình phòng ban');

        //     });
        // }

        $documentType = DocumentType::find($this->request->document_type_id);
        if ($documentType->approve_manual == '1') {
            $this->request['team_id'] =  $this->createTeam();
            $this->assign_user($this->request['team_id'],$this->request->team_users);

            if ($this->request['team_id']=='') {
                $validator->after(function ($validator) {
                    $validator->errors()->add('team_id', 'Tạo Team bị lỗi. Vui lòng liên hệ IT- Admin hệ thống');
                });
            }
        }else {
            $group = Group::find($fields['group_id']);

            if($group->company){

                //dd($fields['amount']);
                $team_id = ApproveRoutingHelper::get_team($group->company->id, $fields['document_type_id'], $fields['group_id'], $fields['budget_type'], $fields['amount'], $fields['amount'], $fields['currency'],$fields['payment_type_id'],0,0);
               // dd ($group->company->id);
                // dd($group->company->id."-". $fields['document_type_id']."-". $fields['group_id']."-". $fields['budget_type']."-". $fields['amount']."-". $fields['amount']."-". $fields['currency']."-".$fields['payment_type_id']);
                if ($team_id == "") {
                    $validator->after(function ($validator) {
                        $validator->errors()->add('team_id', 'Chưa phân tuyến duyệt. Vui lòng liên hệ IT- Admin hệ thống');
                    });

                }
            }

        }

        return $validator;
    }
    public function show($id)
    {
        $document = Issue::with(  'user','document_type', 'company', 'department',  'team', 'team.users', 'approveds', 'payment_type','attachment_file','timelines','doc_type','shareds','shareds.group', 'reminders')->find($id);

        $this->check_read_noti();
        return $document;
    }

    public function edit($id)
    {
        $document = Issue::with( 'user','document_type', 'company', 'department',  'team', 'team.users', 'approveds','attachment_file','timelines','shareds')->find($id);

        return $document;
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
        $team_id = "";


        //dd("test");
        //dd( $this->request->attachment_file);
        $validator = $this->validate_store();

        $failed = $validator->fails();
        $fields = $this->request->all();
        //dd($failed);
        if ($failed) {
            $this->errors = $validator->errors();
        } else {

            try {

                DB::beginTransaction();
                $user = new User();
                $user = auth()->user();
                $fields['user_id'] = $user->id;

                $group = Group::find($fields['group_id']);

                $fields['company_id'] =   $group->company_id;
                $fields['department_id'] = $group->department->id;

                // $fields['company_id'] = $user->company->id;
                // $fields['department_id'] = $user->department->id;
                $fields['teamconfig_id'] =  $this->getTeamConfig();
                $fields['team_id'] =  $this->getTeam(null);
               // dd( $fields['team_id'] );



                //Cấp dãy số sau khi lưu thành công
                try {

                    $document = Issue::create($fields);

                    $documentType = DocumentType::find($document->document_type_id);

                    if ($documentType) {
                        $document->serial_num = DocumentSerials::getSerial(Ultils::$MODULE_ISSUE, $documentType->code,$document->company_id,
                            Ultils::$MODULE_FORMAT_SERIAL_NUMBER, true);
                        $document->save();

                    }

                   $attachment_file = $fields['attachment_file'];
                 //  dd($fields);
                    // dd($attachment_file);
                    for ($j = 0; $j < count($attachment_file); $j++) {

                        //chỉ lưu  các file mới
                        if (!isset($attachment_file[$j]["id"])) {
                            $file = new File();

                            $file->name = $attachment_file[$j]["name"];
                            //$ext = end(explode('.', $filename));
                            // $file->ext = $attachment_file[$i]["ext"];
                            $file->size = $attachment_file[$j]["size"];
                            $file->user_id = $user->id;
                            $strDate = date("Y"). "/" .date("m"). "/"  .date("d");
                            $ext = substr($attachment_file[$j]["name"], strrpos($attachment_file[$j]["name"], '.') + 1);
                            $name = "public/issue/" .$strDate. "/" . uniqid() . '.' . $ext;

                            $file->ext = $ext;
                            $file->url = $name;
                            $document->attachment_file()->save($file);

                            //$name = "/avatars/" . $user->username . '.' . $this->request->file['ext'];
                            $base64_str = substr($attachment_file[$j]['base64'], strpos($attachment_file[$j]['base64'], ",") + 1);
                            $image = base64_decode($base64_str);
                            //file_put_contents(public_path().$name,  $image );
                            Storage::disk('local')->put($name, $image);
                            FacadesFileVault::encrypt($name);

                        }
                    }
                    $shared_groups = $fields['shared_groups'];
                   // dd($shareds);
                    for ($i = 0; $i < count($shared_groups); $i++) {
                            $shared = new Shared();
                            $shared->user_id =  $user->id;
                            $shared->group_id =  $shared_groups[$i];
                            $document->shareds()->save($shared);
                    }


                } catch (\Exception $e1) {

                    $validator->errors()->add('serial_number', $e1->getMessage());
                    $this->errors = $validator->errors();

                    return null;
                }
                $this->store_sub_data($document);
                DB::commit();
                return $document;

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
            'document_type_id'=>'required',
            'group_id'=>'required',
            'amount'=>'required',
            'title'=>'required|max:150'
        ],
        [
            'document_type_id.required' => "Loại chứng từ không được rỗng",
            'group_id.required' => "Nhóm không được rỗng",
            'amount.required' => "Số tiền không được rỗng",
            'title.required' => "Tiêu đề không được rỗng",
            'title.max' => "Tiêu đề tối đa 150 kí tự",
        ]
        );
        $fields = $this->request->all();

       // dd( $this->request->amount);
        $document = Issue::findOrFail($id);

        if ($document->status == 2) {
            $validator->after(function ($validator) {
                $validator->errors()->add('chungtuhoantat', 'Chứng từ đã hoàn tất. Vui lòng không cập nhật.');

            });
        }
        if ($document->status == -1) {
            $validator->after(function ($validator) {
                $validator->errors()->add('chungtuhuy', 'Chứng từ đã huỷ. Vui lòng không cập nhật.');

            });
        }

        if ($document->status == 2) {

            $validator->after(function ($validator) {
                $validator->errors()->add('chungtuhoantat', 'Chứng từ đã hoàn tất. Vui lòng không cập nhật.');

            });
        }
        if ($document->status == -1) {

            $validator->after(function ($validator) {
                $validator->errors()->add('chungtuhuy', 'Chứng từ đã huỷ. Vui lòng không cập nhật.');

            });
        }
        if ($document->locked == 1) {

            $validator->after(function ($validator) {
                $validator->errors()->add('locked', 'Chứng từ đang bị khoá. Vui lòng không cập nhật.');

            });
        }
        $user = new User();
        $user = auth()->user();

        $documentType = DocumentType::find($this->request->document_type_id);
        if ($documentType->approve_manual == '1') {
            //$this->request['team_id'] =  $this->createTeam();
           // dd($document);
            $this->assign_user($document->team_id,$this->request->team_users);
            // if ($this->request['team_id']=='') {
            //     $validator->after(function ($validator) {
            //         $validator->errors()->add('team_id', 'Tạo Team bị lỗi. Vui lòng liên hệ IT- Admin hệ thống');
            //     });
            // }
        }else {
            $group = Group::find($fields['group_id']);
            if($group->company){
                $team_id = ApproveRoutingHelper::get_team($group->company->id, $fields['document_type_id'], $fields['group_id'], $fields['budget_type'], $fields['amount'], $fields['amount'], $fields['currency'],$fields['payment_type_id'],0,0);

                if ($team_id == "") {
                    $validator->after(function ($validator) {
                        $validator->errors()->add('team_id', 'Chưa phân tuyến duyệt. Vui lòng liên hệ IT- Admin hệ thống');
                    });

                }
            }
        }


        return $validator;
    }

    public function update_cancel( ){

        $document = Issue::find($this->request->id);
        $document->status = -1;//Đã huỷ
        if($document->status == 2){ //Đã duyệt hoàn tất thì không thể huỷ
            return false;
        }
        if($document->save()){
            $document->timelines()->save(new Timeline(['title'=>"form.document_cancel",'icon'=>'fas fa-window-close bg-danger','user_id'=>auth()->user()->id]));
            return true;
        }
        return false;
    }
    public function update($id)
    {
        date_default_timezone_set("Asia/Ho_Chi_Minh");

        $validator = $this->validate_update($id);
        $failed = $validator->fails();
        $fields = $this->request->all();
        //kiểm tra thông tin hợp đồng nếu có , validate thêm
        $document = Issue::findOrFail($id);

        if ($failed) {
            $this->errors = $validator->errors();
        } else {

            try {

                if ($document) {
                    DB::beginTransaction();
                    $user = new User();
                    $user = auth()->user();
                    $fields['user_id'] = $user->id;

                    $fields['teamconfig_id'] =  $this->getTeamConfig();
                    $fields['team_id'] = $this->getTeam($document);

                    $group = Group::find($fields['group_id']);

                    $fields['company_id'] =   $group->company_id;
                    $fields['department_id'] = $group->department->id;

                    $document->fill($fields);
                    $document->save();

                    //save file
                    $attachment_file = $fields['attachment_file'];
                    $attachment_file_removed = $fields['attachment_file_removed'];
                    // dd($attachment_file);
                    for ($j = 0; $j < count($attachment_file); $j++) {

                        //chỉ lưu  các file mới
                        if (!isset($attachment_file[$j]["id"])) {
                            $file = new File();

                            $file->name = $attachment_file[$j]["name"];
                            //$ext = end(explode('.', $filename));
                            // $file->ext = $attachment_file[$i]["ext"];
                            $file->size = $attachment_file[$j]["size"];
                            $file->user_id = $user->id;

                            $strDate = date("Y"). "/" .date("m"). "/"  .date("d");
                            $ext = substr($attachment_file[$j]["name"], strrpos($attachment_file[$j]["name"], '.') + 1);
                            $name = "public/issue/" .$strDate. "/" . uniqid() . '.' . $ext;

                            $file->ext = $ext;
                            $file->url = $name;
                            $document->attachment_file()->save($file);

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
                    if ($document->shareds) {
                        foreach ($document->shareds as $shared) {
                            $shared->delete();
                        }
                    }
                    $shared_groups = $fields['shared_groups'];
                    // dd($shareds);
                     for ($i = 0; $i < count($shared_groups); $i++) {
                             $shared = new Shared();
                             $shared->user_id =  $user->id;
                             $shared->group_id =  $shared_groups[$i];
                             $document->shareds()->save($shared);
                     }

                    $this->update_sub_data($document);
                    DB::commit();

                    return $document;
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

        $document = Issue::findOrFail($id);

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
    protected function getTeamConfig()
    {
        $user = new User();
        $user = auth()->user();
        $fields = $this->request->all();

        $group = Group::find($fields['group_id']);
        //$fields['payment_type_id'] = "0";

        $team_id = ApproveRoutingHelper::get_team($group->company->id, $fields['document_type_id'], $fields['group_id'], $fields['budget_type'], $fields['amount'],$fields['amount'], $fields['currency'],$fields['payment_type_id'],0,0);
        return $team_id;
    }

    //Hàm trả về Team duyệt
    abstract protected function getTeam($obj);
    //các hàm này không sử dụng commit
    abstract protected function store_sub_data($obj);
    abstract protected function update_sub_data($obj);
    abstract protected function destroy_sub_data($obj);


}

?>
