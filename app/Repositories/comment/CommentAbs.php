<?php 
namespace App\Repositories\comment;

use App\Mail\EmailCarRequest;
use App\Mail\EmailComment;
use App\Mail\EmailCommentCar;
use App\Models\shared\Comment;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\shared\File;
use App\Models\shared\OtherAttached;
use Illuminate\Http\Request;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use App\Models\shared\DocumentType;
use App\Notifications\CommondNotification;
use App\Notifications\NotiBaseModel;
use Illuminate\Support\Str;
use SoareCostin\FileVault\Facades\FileVault as FacadesFileVault;
abstract class CommentAbs{
    protected $parent;
    protected $comment;
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
           
            'content' => 'required|max:1000'
        ], [
           
            'content.required' => 'Nội dung bình luận không được rỗng.'
        
        ]);
        $fields = $this->request->all();     
        return $validator;
    }

    public function store(){

        try {
        
            DB::beginTransaction();
            $user = new User();
            $user = auth()->user();
          
            $fields = $this->request->all();
            $validator = $this->validate_store();
            $failed = $validator->fails();
           
            if ($failed) {
                $this->errors = $validator->errors();
            } else {
                $this->comment = new Comment();
                $this->comment->fill($fields);
                $this->comment->user_id = auth()->user()->id;
              
                if($this->parent){
                    $this->comment= $this->parent->comments()->save($this->comment) ;
                    $this->comment->load('user');
                }
                $other_attacheds = $fields['attachment_file'];
                                  
                for ($i = 0; $i < count($other_attacheds); $i++) {
                     $otherAttached = new OtherAttached();
  
                     $otherAttached->name = $other_attacheds[$i]['name'];

                     $this->comment->other_attacheds()->save($otherAttached);


                    for ($j = 0; $j < count($other_attacheds); $j++) {

                        $file = new File();

                        $file->name = $other_attacheds[$j]["name"];
                        $file->size = $other_attacheds[$j]["size"];
                        $file->user_id = $user->id;

                        $ext = substr($other_attacheds[$j]["name"], strrpos($other_attacheds[$j]["name"], '.') + 1);
                        $name = "public/car/" . $user->username . "/" . uniqid() . '.' . $ext; // $attachment_file[$i]["ext"];

                        $file->ext = $ext;
                        $file->url = $name;
                        $otherAttached->attachment_file()->save($file);

                        $base64_str = substr($other_attacheds[$j]['base64'], strpos($other_attacheds[$j]['base64'], ",") + 1);
                        $image = base64_decode($base64_str);

                        Storage::disk('local')->put($name, $image);
                        FacadesFileVault::encrypt($name);

                    }

                }
                if($this->parent){
                    $document = $this->parent;
                    $comment = $this->comment;
                    $url = $this->url;
                    $documentType = DocumentType::find($document->document_type_id);
                    $data = new NotiBaseModel;
                    $data->title = Str::ucfirst(Str::lower($documentType->name))  ." có bình luận mới" ;
                    $data->icon = "fas fa-comment";
                    $data->content = $document->serial_num;
                    $data->url = URL('/').'/'.$url;
                    if($comment->parent_id==null){
                        if($documentType->code == 'PCAR'){
                            if($document->user_id !== $document->proposer){
                               $user = User::find($document->proposer);
                               $email = new EmailComment($data, $user,$comment);
                               $document->proposer = User::find($document->proposer);
                               $document->user_id->notify(new CommondNotification($data,$document->proposer,$email) );    
                            }
                       }else{
                           $user = User::find($document->user_id);
                           $email = new EmailComment($data, $user,$comment);
                           $document->user_id = User::find($document->user_id);
                           $document->user_id->notify(new CommondNotification($data,$document->user_id,$email) );
                       }
                    }else{
                        
                           $comment = Comment::find($comment->parent_id);
                           $user = User::find($comment->user_id);
                           $email = new EmailComment($data, $user,$comment);
                           $comment->user_id = User::find($comment->user_id);
                           $comment->user_id->notify(new CommondNotification($data,$comment->user_id,$email) );
                      
                    }

                    
                }
             }

            DB::commit();
           
            return $this->comment;
        } catch (\Exception $e) {
            DB::rollback();
            $this->errors = $e->getMessage();
            return null;
           
        }
    }
    public function show($id)
    {
        $comment = Comment::with('other_attacheds')->find($id);
      
        return $comment;
    }
    public function edit($id)
    {   
     
        $comment = Comment::with('other_attacheds')->find($id);
      
        return $comment;
    }
    public function update($id){
        try {

            DB::beginTransaction();
           
            $fields = $this->request->all();
            // dd($fields);
            $validator = $this->validate_store();
            $failed = $validator->fails();

            if ($failed) {
                $this->errors = $validator->errors();
            } else {
                $this->comment = Comment::find($id);
                $this->comment->fill($fields);
                $this->comment->save();
             }

            DB::commit();
           
            return $this->comment;
        } catch (\Exception $e) {
            DB::rollback();
            $this->errors = $e->getMessage();
            return null;
           
        }
    }
    public function destroy($id)
    {
     
        $comment = Comment::with('other_attacheds')->find($id);
        if (!$comment) {
            abort(404);
        }
        if($comment->user_id==auth()->user()->id){
            try {
                DB::beginTransaction();
                $other_attacheds_del = $comment['other_attacheds'];
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
                $this->destroy_sub_data($comment);
                $comment->delete();
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
    abstract protected function store_sub_data($obj);
    abstract protected function update_sub_data($obj);
    abstract protected function destroy_sub_data($obj);
}
?>