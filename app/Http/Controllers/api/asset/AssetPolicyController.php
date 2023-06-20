<?php

namespace App\Http\Controllers\api\asset;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\asset\AssetPolicy;
use Illuminate\Support\Facades\Validator;
use App\User;
use App\Models\shared\File;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SoareCostin\FileVault\Facades\FileVault as FacadesFileVault;
use Exception;


class AssetPolicyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = AssetPolicy::query()->with(['attachment_file']);
        $result = array();
        $result['data'] = array();
        // $role = User::find(auth()->user()->id);          
            try {
            //     $users = User::whereNotIn('id',['1','2','3','4','5'])->where('id',$role->id)->where('active',1)->orderBy('name')->get();
            // if($users->toArray() !=[]){
            //     $query = $query->where('create_by',$role->id);
            //    }else{
               
            //    }
                if($request->filled('name')){
                    $query = $query->where('name', $request->name );
                }
                if($request->filled('type')){
                    $query = $query->where('type', $request->type );
                } 
                if($request->filled('policy_conditions')){
                    $query = $query->where('policy_conditions', $request->policy_conditions );
                }                
                $policy = $query->get();              
                $result['data'] = $policy;
                $result['success'] = "1";
            } catch (Exception $e) {
                $result['data']['errors'] = $e->getMessage();
            }
          
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $result = array();
        $result['data'] =  array();
        $result['data']['success']  = 0;
        date_default_timezone_set("Asia/Ho_Chi_Minh");
        $meesage = [
            'name.required' => 'Tên không được để trống',
            'name.max' => 'Tối đa 100 kí tự',
            'type.required' => 'Chưa chọn chính sách',
            'policy_conditions.max' => 'Tối đa 255 kí tự',

        ];
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:100',
            'type' => 'required',
            'policy_conditions' => 'max:255',
            
        ],$meesage);
       
        $fields = $request->all();
        $failed = $validator->fails();
        $isErr=false;

        if ($failed || $isErr) {
            $result['data']['errors']  = $validator->errors();
        } else {
            try {
                $user = new User();
                $user = auth()->user();
                $fields['create_by'] = $user->id;
                
                DB::beginTransaction();
               
                // $fields['user_id'] = $user_id->id;
               
                $re = AssetPolicy::create($fields);
                if ($re) {
                    
                    $attachment_file = $fields['attachment_file'];
                    // dd($attachment_file);
                    for ($i = 0; $i < count($attachment_file); $i++) {
                       
                        $file = new File();

                        $file->name = $attachment_file[$i]["name"];
                        //$ext = end(explode('.', $filename));
                        // $file->ext = $attachment_file[$i]["ext"];
                        $file->size = $attachment_file[$i]["size"];
                        $file->user_id = $user->id;

                        $ext = substr($attachment_file[$i]["name"], strrpos($attachment_file[$i]["name"], '.') + 1);
                        $name = "public/goods/" . $user->username . "/" . uniqid() . '.' . $ext; // $attachment_file[$i]["ext"];

                        $file->ext = $ext;
                        $file->url = $name;
                        $re->attachment_file()->save($file);

                        //$name = "/avatars/" . $user->username . '.' . $request->file['ext'];
                        $base64_str = substr($attachment_file[$i]['base64'], strpos($attachment_file[$i]['base64'], ",") + 1);
                        $image = base64_decode($base64_str);
                        //file_put_contents(public_path().$name,  $image );
                        Storage::disk('local')->put($name, $image);
                        FacadesFileVault::encrypt($name);
                }
            
                    
                }
                DB::commit();
                $result['data']['success'] = 1;
                $re->load("attachment_file");
                $result['data']['data'] = $re;
            } catch (\Exception $e) {
                 DB::rollback();
                $result['data']['errors']  =  $e->getMessage();
            }
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $policy = AssetPolicy::with('attachment_file')->findOrFail($id);
        
        $result = array();
        $result['data'] =  array();
        $result['data'] = $policy;
        $result['data']['success'] = 1;
        if (!$policy) {
            $result['data']['success'] = 0;
        }
        return json_encode($result, JSON_UNESCAPED_UNICODE); 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $policy = AssetPolicy::with('attachment_file')->findOrFail($id);

      
        $result = array();
        $result['data'] =  array();
        $result['data'] = $policy;
        $result['data']['success']  = 1;
         if (!$policy) {
            $result['data']['success']  = 0;
        }
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $result = array();
        $result['data'] =  array();
        $result['data']['success']  = 0;
        date_default_timezone_set("Asia/Ho_Chi_Minh");
        $meesage = [
            'name.required' => 'Tên không được để trống',
            'name.max' => 'Tối đa 100 kí tự',
            'type.required' => 'Chưa chọn chính sách',
            'policy_conditions.max' => 'Tối đa 255 kí tự',

        ];
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:100',
            'type' => 'required',
            'policy_conditions' => 'max:255',
            
        ],$meesage);
        $user = new User();
        $user = auth()->user();
        $fields = $request->all();
        $failed = $validator->fails();
        $isErr = false;
        if ($failed) {
            $result['data']['errors']  = $validator->errors();
        } else {
                DB::beginTransaction();
                $policy = AssetPolicy::findOrFail($id);
                $policy->name = $request->input('name');
                $policy->type = $request->input('type');
                $policy->policy_conditions = $request->input('policy_conditions');
                $policy->save();
        try {
            if ($policy) {
                
                $attachment_file = $fields['attachment_file'];
                
                for ($i = 0; $i < count($attachment_file); $i++) {
                    if (!isset($attachment_file[$i]["id"])) {
                    $file = new File();
                    $file->name = $attachment_file[$i]["name"];
                    
                    //$ext = end(explode('.', $filename));
                    // $file->ext = $attachment_file[$i]["ext"];
                    $file->size = $attachment_file[$i]["size"];
                    $file->user_id = $user->id;

                    $ext = substr($attachment_file[$i]["name"], strrpos($attachment_file[$i]["name"], '.') + 1);
                    $name = "public/goods/" . $user->username . "/" . uniqid() . '.' . $ext; // $attachment_file[$i]["ext"];

                    $file->ext = $ext;
                    $file->url = $name;
                    $policy->attachment_file()->save($file);

                    //$name = "/avatars/" . $user->username . '.' . $request->file['ext'];
                    $base64_str = substr($attachment_file[$i]['base64'], strpos($attachment_file[$i]['base64'], ",") + 1);
                    $image = base64_decode($base64_str);
                    //file_put_contents(public_path().$name,  $image );
                    Storage::disk('local')->put($name, $image);
                    FacadesFileVault::encrypt($name);
                    }
            }
            $attachment_file_del = $fields['attachment_file_del'];
            //dd($attachment_file_del);
                    for ($i = 0; $i < count($attachment_file_del); $i++) {
                      
                        if (isset($attachment_file_del[$i]["id"])) {
                            $file = File::find($attachment_file_del[$i]["id"]);
                            if ($file) {
                              //  dd($file->url);
                                Storage::delete($file->url . ".enc");
                                $file->delete();
                            }
                        }
                    }
                    DB::commit();
                    $policy = AssetPolicy::findOrFail($id);
                    $policy->load("attachment_file");
                    $result['data']['success'] = 1;
                  
                    $result['data']['data'] = $policy;
                   // dd($result);
        }
        
            } catch (\Exception $e) {
                DB::rollback();
                $result['data']['errors'] = $e->getMessage();
            }
        }
            

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $policies = AssetPolicy::findOrFail($id);
        $result = array();
        $result['data'] = array();
        $result['data']['success']  = 0;
        if( $policies->delete() ){
            $result['data']['success']  = 1;
        }else {
            try {
                // dd($contract->attachment_file());
                DB::beginTransaction();
                //$contract->load('attachment_file');
                foreach ($policies->attachment_file as $file) {

                    //  dd($file);
                    // Storage::delete($file->url.".enc");
                    Storage::delete($file->url . ".enc");
                    $file->delete();
                }
                // dd('khong load file được');
                foreach ($policies->contract_terms as $term) {

                    $term->delete();
                }
                $policies->delete();
                DB::commit();
                $result['data']['success'] = 1;
            } catch (\Exception $e) {
                DB::rollback();
                $result['data']['errors'] = $e->getMessage();
            }
        }
        return json_encode($result, JSON_UNESCAPED_UNICODE); 
    }
}
