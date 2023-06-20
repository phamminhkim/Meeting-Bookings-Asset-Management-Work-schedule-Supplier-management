<?php
namespace App\Repositories\shared;

use App\Mail\EmailCarAssign;
use App\Mail\EmailCarRequest;
use App\Mail\EmailDocumentRequest;
use App\Models\shared\Company;
use App\Models\shared\Department;
use App\Models\shared\DocumentType;
use App\Models\shared\Group;
use App\Models\shared\Reminder;
use App\Models\shared\Shared;
use App\Models\shared\Timeline;
use App\Notifications\CommondNotification;
use App\Notifications\NotiBaseModel;
use App\Ultils\Ultils;
use App\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
abstract class SharedAbs{
    protected $parent;
    protected $sharedList;
    protected $request;
    protected $errors;
    protected $message;
    protected $layout;
    protected $url;
    public function __construct($request,$parent,$url)
    {

        $this->request = $request;
        $this->parent = $parent;
        $this->url = $url;

    }
    public function __set($name, $value)
    {
        $this->$name = $value;
    }
    public function __get($name)
    {
        return $this->$name;
    }

    protected function validate_store()
    {
        $validator = Validator::make($this->request->all(), [

            'object_ids' => 'required',
            'type' => 'required|min:0|max:3',

        ], [

            'object_ids.required' => 'object : không được rỗng',
            'type.required' => 'type : không được rỗng',

        ]);
        $fields = $this->request->all();
        return $validator;
    }

    public function store(){

        try {

            DB::beginTransaction();

            $fields = $this->request->all();

            $validator = $this->validate_store();
            $failed = $validator->fails();
            $user = User::find(auth()->user()->id);

            if ($failed) {

                $this->errors = $validator->errors();

            } else {

                //$shared->fill($fields);
                $sharedList = array();

                $ids = $fields['object_ids'];

                switch ($fields['type']) {
                    case '0':
                        $groups = Group::whereIn('id',$ids)->get();
                        foreach ($groups as   $group) {
                            if (!$this->isExist($group->id,$fields['type'])) {
                                $shared = new Shared();
                                $shared->type = $fields['type'];
                                $shared->object_id = $group->id;
                                $shared->user_id = $user->id;
                                array_push($sharedList,$shared);
                            }


                        }
                        break;
                    case '1':


                        $users = User::whereIn('id',$ids)->get();
                        foreach ($users as   $u) {
                            if (!$this->isExist($u->id,$fields['type'])) {
                                $shared = new Shared();
                                $shared->type = $fields['type'];
                                $shared->object_id = $u->id;
                                $shared->user_id = $user->id;
                                array_push($sharedList,$shared);
                            }
                        }

                        break;
                    case '2':
                        $departments =  Department::whereIn('id',$ids)->get();
                        foreach ($departments as   $dept) {
                            if (!$this->isExist($dept->id,$fields['type'])) {
                                $shared = new Shared();
                                $shared->type = $fields['type'];
                                $shared->object_id = $dept->id;
                                $shared->user_id = $user->id;
                                array_push($sharedList,$shared);
                            }

                        }

                        break;
                    case '3':
                        $companys = Company::whereIn('id',$ids)->get();
                        foreach ($companys as   $com) {

                            if (!$this->isExist($com->id,$fields['type'])) {
                                $shared = new Shared();
                                $shared->type = $fields['type'];
                                $shared->object_id = $com->id;
                                $shared->user_id = $user->id;
                                array_push($sharedList,$shared);
                            }

                        }
                        break;
                        case '4':
                            $users = User::whereIn('id',$ids)->get();
                           
                            foreach ($users as   $u) {
                               
                                if ($fields['type']) {
                                    $shared = new Shared();
                                    $shared->type = $fields['type'];
                                    $shared->object_id = $u->id;
                                    $shared->user_id = $user->id;
                                    array_push($sharedList,$shared);
                                    
                                }
                            }
    
                            break;
                }

                if($this->parent && method_exists($this->parent,'shareds')){
                    $document = $this->parent;
                    $url = $this->url;
  
                    $strUsers = '';
                    foreach ($sharedList as $shared) {
                        if (!$shared->id && $shared->id == '' && $shared->type == '1') {
                            $documentType = DocumentType::find($document->document_type_id);
                            $data = new NotiBaseModel;
                            $data->title = Str::ucfirst(Str::lower($documentType->name))  ." được chia sẻ" ;
                            $data->icon = "fas fa-share-square";
                            $data->content = $document->serial_num;
                            $data->url = URL('/').'/'.$url;
                            if($documentType->code == 'SCAR' || $documentType->code == 'PCAR'){
                                $email = new EmailCarRequest($data, $shared->viewer,$document);
                            }else{
                                $email = new EmailDocumentRequest($data, $shared->viewer,$document);   
                            }
                            $shared->viewer->notify(new CommondNotification($data,$shared->viewer,$email) );
                            if ($strUsers == '') {
                                $strUsers = $strUsers .'('.$shared->viewer->username.')'.  $shared->viewer->name;
                            }else {
                                $strUsers = $strUsers .', ' .'('.$shared->viewer->username.')'.  $shared->viewer->name;
                            }

                        }
                        if (!$shared->id && $shared->id == '' && $shared->type == '4') {
                           
                            $documentType = DocumentType::find($document->document_type_id);
                            $data = new NotiBaseModel;
                            $data->title =  "Yêu cầu cập nhật ". Str::lower($documentType->name) ;
                            $data->icon = "fa fa-tasks";
                            $data->content = $document->serial_num;
                            $data->reason = $fields['content'];
                            $data->url = URL('/').'/'.$url;
                         
                            $email = new EmailCarRequest($data, $shared->assign,$document);
                           
                            $shared->assign->notify(new CommondNotification($data,$shared->assign,$email) );
                            if ($strUsers == '') {
                                $strUsers = $strUsers .'('.$shared->assign->username.')'.  $shared->assign->name;
                            }else {
                                $strUsers = $strUsers .', ' .'('.$shared->assign->username.')'.  $shared->assign->name;
                            }
                        }

                        $this->parent->shareds()->save($shared) ;
                    }
                    //$this->shareds = $this->parent->shareds()->save($this->shareds) ;
                    $this->parent->load('shareds');

                    foreach ($this->parent->shareds as $shared) {
                        //load theo điều kiện
                        switch ( $shared->type) {
                            case '0':
                                $shared->group;
                                break;
                            case '1':
                                $shared->viewer;
                                break;
                            case '2':
                                $shared->department;
                                break;
                            case '3':
                                $shared->company;
                                break;
                            case '4':
                                $shared->assign;
                                break;
                        }

                    }
                    //luu vào timeline
                    if($shared->type=='4'){
                        $document->timelines()->save(new Timeline(['title'=>"form.assign_user",'icon'=>$data->icon,'content'=>$strUsers,'user_id'=>auth()->user()->id]));
                    }else{
                        $document->timelines()->save(new Timeline(['title'=>"form.shared",'icon'=>$data->icon,'content'=>$strUsers,'user_id'=>auth()->user()->id]));
                    }
                    


                }
             }


            DB::commit();

            return $this->parent->shareds;
        } catch (\Exception $e) {
            DB::rollback();
            $this->errors = $e->getMessage();
            return null;

        }
    }
    private function isExist($object_id, $type){
        foreach ($this->parent->shareds  as  $shared) {
            if ($shared->type == $type && $shared->object_id == $object_id) {
                return true;
            }
        }

    }
    public function show($id)
    {

    }
    public function edit($id)
    {

    }
    public function update($id){

    }
    public function destroy($id)
    {

        $shared = Shared::find($id);

        if (!$shared ) {
            abort(404);
        }
        $shared->delete();
        return true;

    }



}
?>
