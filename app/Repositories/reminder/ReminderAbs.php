<?php
namespace App\Repositories\reminder;

use App\Models\shared\File;
use App\Models\shared\Reminder;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use SoareCostin\FileVault\Facades\FileVault as FacadesFileVault;
abstract class ReminderAbs{
    protected $parent;
    protected $reminder;
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

    protected function validate_store()
    {
        $validator = Validator::make($this->request->all(), [

            'content' => 'required',
            'date_due' => 'required|date',
            'type_id' => 'required',
        ], [

            'content.required' => 'Nội dung : rỗng',
            'date_due.required' => 'Ngày đến hạn : rỗng',
            'type_id.required' => 'Type : rỗng',

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

            if ($failed) {
                $this->errors = $validator->errors();
            } else {
                $this->reminder = new Reminder();
                $fields['active'] = 1;
                $this->reminder->fill($fields);

               // dd($this->reminder);
                $this->reminder->url = $this->url ;
                $this->reminder->user_id = auth()->user()->id;

                if($this->parent && method_exists($this->parent,'reminder')){

                    $this->reminder= $this->parent->reminder()->save($this->reminder) ;
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
                            $file->user_id =  auth()->user()->id;
                            $strDate = date("Y"). "/" .date("m"). "/"  .date("d");
                            $ext = substr($attachment_file[$j]["name"], strrpos($attachment_file[$j]["name"], '.') + 1);
                            $name = "public/reminder/" .$strDate. "/" . uniqid() . '.' . $ext;

                            $file->ext = $ext;
                            $file->url = $name;
                            $this->reminder->attachment_file()->save($file);
                            //dd($file);

                            //$name = "/avatars/" . $user->username . '.' . $this->request->file['ext'];
                            $base64_str = substr($attachment_file[$j]['base64'], strpos($attachment_file[$j]['base64'], ",") + 1);
                            $image = base64_decode($base64_str);
                            //file_put_contents(public_path().$name,  $image );
                            Storage::disk('local')->put($name, $image);
                            FacadesFileVault::encrypt($name);

                        }
                    }
                    $this->reminder->load('user');
                    $this->reminder->load('attachment_file');
                 //   dd($this->reminder);
                }
             }

            DB::commit();

            return $this->reminder;
        } catch (\Exception $e) {
            DB::rollback();
            $this->errors = $e->getMessage();
            return null;

        }
    }
    public function show($id)
    {

        $reminder = Reminder::with('attachment_file')->find($id);
        return $reminder;
    }
    public function edit($id)
    {

        $reminder = Reminder::with('attachment_file')->find($id);
        return $reminder;
    }
    public function update($id){
        try {

            DB::beginTransaction();

            $fields = $this->request->all();

            $validator = $this->validate_store();
            $failed = $validator->fails();

            if ($failed) {
                $this->errors = $validator->errors();
            } else {


                $this->reminder =Reminder::find($id);
                $this->reminder->fill($fields);
                $this->reminder->save();

                 //save file
                 $attachment_file = $fields['attachment_file'];
                 $attachment_file_removed = $fields['attachment_file_removed'];

                 // dd($attachment_file);
                 for ($j = 0; $j < count($attachment_file); $j++) {
                   // dd($j);
                     //chỉ lưu  các file mới
                     if (!isset($attachment_file[$j]["id"])) {
                         $file = new File();

                         $file->name = $attachment_file[$j]["name"];
                         //$ext = end(explode('.', $filename));
                         // $file->ext = $attachment_file[$i]["ext"];
                         $file->size = $attachment_file[$j]["size"];
                         $file->user_id =  auth()->user()->id;

                         $strDate = date("Y"). "/" .date("m"). "/"  .date("d");
                         $ext = substr($attachment_file[$j]["name"], strrpos($attachment_file[$j]["name"], '.') + 1);
                         $name = "public/reminder/" .$strDate. "/" . uniqid() . '.' . $ext;

                         $file->ext = $ext;
                         $file->url = $name;

                         $this->reminder->attachment_file()->save($file);

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

                        $file = File::find($attachment_file_removed[$k]["id"]);

                        if ($file) {

                            Storage::delete($file->url . ".enc");
                            $file->delete();
                        }
                    }
                }


             }

            DB::commit();

            $this->reminder = Reminder::with('attachment_file')->find($id);
            return $this->reminder;
        } catch (\Exception $e) {
            DB::rollback();
            $this->errors = $e->getMessage();
            return null;

        }
    }
    public function destroy($id)
    {
        $reminder = Reminder::find($id);

        if (!$reminder) {
            abort(404);
        }
        $reminder->delete();
        return true;
        // if($this->parent && property_exists($this->parent,'reminderOne')){


        //     $this->parent->reminderOne()->detach($this->reminder->id);
        // }
    }



}
?>
