<?php 
namespace App\Repositories\commentvote;

use App\Models\car\Car;
use App\Models\shared\CommentVote;
use App\Models\shared\DocumentType;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\shared\File;
use App\Models\shared\OtherAttached;
use Illuminate\Http\Request;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use SoareCostin\FileVault\Facades\FileVault as FacadesFileVault;
use App\Models\shared\Timeline;
use App\Notifications\CommondNotification;
use App\Notifications\NotiBaseModel;
abstract class CommentVoteAbs{
    protected $parent;
    protected $comment;
    protected $request;
    protected $errors;
    protected $message;
    protected $layout;
    protected $url;
    public function __construct($request,$parent)
    {
      
        $this->request = $request;
        $this->parent = $parent;
      
    }
    public function __set($name, $value)
    {
        $this->$name = $value;
    }
    public function __get($name)
    {
        return $this->$name;
    }
 

    public function store(){
        $result = array();
        $result['data'] = array();
        $result['data']['success'] = 0;
        date_default_timezone_set("Asia/Ho_Chi_Minh");
        $fields = $this->request->all();
        $check = CommentVote::where('comment_id',$fields['comment_id'])
                              ->where('user_id',auth()->user()->id)
                              ->first();
        if(!$check){
        try {
                $user = new User();
                $user = auth()->user();
                $this->commentvote = new CommentVote();
                $this->commentvote->fill($fields);
                $this->commentvote->user_id = auth()->user()->id;
                $this->commentvote->save() ;
                $this->commentvote->load('user');
                
                
                DB::commit();
           
            return $this->commentvote;
        } catch (\Exception $e) {
            DB::rollback();
            $this->errors = $e->getMessage();
            return null;
           
         }
        }else{
            try {
                $user = new User();
                $user = auth()->user();
                $this->commentvote = CommentVote::find($check->id);
                $this->commentvote->delete();
                $this->commentvote->load('user');
                 DB::commit();
           
            return $this->commentvote;
        } catch (\Exception $e) {
            DB::rollback();
            $this->errors = $e->getMessage();
            return null;
           
         }
        }
    }
/*     public function store(){
        $result = array();
        $result['data'] = array();
        $result['data']['success'] = 0;
        date_default_timezone_set("Asia/Ho_Chi_Minh");
        $fields = $this->request->all();
        $check = CommentVote::where('comment_id',$fields['object_id'])
                              ->where('user_id',auth()->user()->id)
                              ->first();

        if(!$check){
        try {
           
                $user = new User();
                $user = auth()->user();
                $this->commentvote = new CommentVote();
                $this->commentvote->fill($fields);
                $this->commentvote->user_id = auth()->user()->id;
                $this->commentvote->comment_id = $fields['object_id'];
                $this->commentvote->save() ;
                $this->commentvote->load('user');
                 DB::commit();
           
            return $this->commentvote;
        } catch (\Exception $e) {
            DB::rollback();
            $this->errors = $e->getMessage();
            return null;
           
         }
        }else{
            try {
                $user = new User();
                $user = auth()->user();
                $this->commentvote = CommentVote::find($check->id);
                $this->commentvote->delete();
                $this->commentvote->load('user');
                 DB::commit();
           
            return $this->commentvote;
        } catch (\Exception $e) {
            DB::rollback();
            $this->errors = $e->getMessage();
            return null;
           
         }
        }
    } */
    public function show($id)
    {

    }
    public function edit($id)
    {   
        $commentvote = CommentVote::find($id);
        return $commentvote;
    }
    
    public function update($id){
        $result = array();
        $result['data'] = array();
        $result['data']['success'] = 0;
        date_default_timezone_set("Asia/Ho_Chi_Minh");
        $fields = $this->request->all();
        $check = CommentVote::where('comment_id',$id)
                              ->where('user_id',auth()->user()->id)
                              ->first();
        if(!$check){
            try {
                    $user = new User();
                    $user = auth()->user();
                    $this->commentvote = new CommentVote();
                    $this->commentvote->fill($fields);
                    $this->commentvote->user_id = auth()->user()->id;
                    $this->commentvote->save() ;
                    $this->commentvote->load('user');
                     DB::commit();
               
                return $this->commentvote;
            } catch (\Exception $e) {
                DB::rollback();
                $this->errors = $e->getMessage();
                return null;
               
             }
            }else{
                try {
                    $user = new User();
                    $user = auth()->user();
                    $this->commentvote = CommentVote::find($check->id);
                    $this->commentvote->delete();
                    $this->commentvote->load('user');
                     DB::commit();
               
                return $this->commentvote;
            } catch (\Exception $e) {
                DB::rollback();
                $this->errors = $e->getMessage();
                return null;
               
             }
            }
    }
    public function destroy($id)
    {

    }
  
}
?>