<?php

namespace App\Http\Controllers\api\sloc;

use App\Http\Controllers\Controller;
use App\Models\sloc\Goods;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\Builder;
use Error;
use App\User;
use App\Models\shared\File;
use SoareCostin\FileVault\Facades\FileVault as FacadesFileVault;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Repositories\approve\ApproveMain;
use App\Ultils\Ultils;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Calculation\TextData\Replace;
class GoodsController extends Controller
{
    public function index(Request $request)
    {
        $query = Goods::query();
        
        $user = User::find(Auth()->user()->id);

        $result = array();
        $result['data'] = array();
       
        try{
            if($request->filled('user')){

                $query = $query->where('goodunit_id', $user->goodunit_id);
            }
            if($request->filled('name')){
                $query = $query->where('name', $request->name );
            }  
            if($request->filled('code')){
                $query = $query->where('code', $request->code );
            } 
            if($request->filled('goodunit_id')){
                $query = $query->where('goodunit_id', $request->goodunit_id );
            }               
            $goods = $query->get();              
            $result['data'] = $goods;
            $result['success'] = "1";
        }catch (Exception $e) {
            $this->errors = $e->getMessage();
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
        $goods = Goods::all();

      

        $result = array();
        $result['data'] =  array();
        $result['data']['success']  = 0;
        date_default_timezone_set("Asia/Ho_Chi_Minh");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // if($request->get('image'))
        // {
        //    $image = $request->get('image');
        //    $goods = time().'.' . explode('/', explode(':', substr($image, 0, strpos($image, ';')))[1])[1];
        //    Image::make($request->get('image'))->save(public_path('images/').$goods);
        //  }
 
        // $image= new Goods();
        // $image->image_name = $goods;
        // $image->save();
        $user = new User();
        $user = auth()->user();
        $result = array();
        $result['data'] =  array();
        $result['data']['success']  = 0;
        date_default_timezone_set("Asia/Ho_Chi_Minh");

        $validator = Validator::make($request->all(), [
           
            'name' => 'required|max:150',
            'code' => 'required|max:10',
            'goodunit_id' => 'required',

        ]);
        
        $failed = $validator->fails();
        $isErr = false;
        $fields = $request->all();

        if($request->goodunit_id){
            $dep_temp = Goods::where('goodunit_id',$request->goodunit_id)
                                    ->where('code',$request->code)->first();
             
            if($dep_temp  ){
                $result['data']['message']  = "Trùng mã, vui lòng nhập mã khác";
                $validator->errors()->add('code', 'Trùng mã, vui lòng nhập mã khác');
                $isErr = true;
            }
        }

        if ($failed || $isErr) {
            $result['data']['errors']  = $validator->errors();
        } else {
            try {
                DB::beginTransaction();

                $fields = $request->all();
                $re = Goods::create($fields);
                if ($re) {
                    $result['data']  = $re;
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
                        $name = "/goods/" . $file->name;

                        $file->ext = $ext;
                        $file->url = $name;
                        $re->attachment_file()->save($file);

                        //$name = "/avatars/" . $user->username . '.' . $request->file['ext'];
                        
                        // $image = base64_decode($base64_str);
                        $extension = explode('/', explode(':', substr($attachment_file[$i]['base64'], 0, strpos($attachment_file[$i]['base64'], ';')))[1])[1];
                        $base64_str = substr($attachment_file[$i]['base64'], strpos($attachment_file[$i]['base64'], ",") + 1);

                        $image = str_replace($base64_str, '', $attachment_file[$i]);
                        $image = str_replace(' ', '+', $image);
                        $image = base64_decode($base64_str);
                        $imageName = Str::random(10).'.'.$extension;  
                        file_put_contents(public_path().$name,  $image );
                        // Storage::disk('local')->put($name , $image);
                        // FacadesFileVault::encrypt($name);

                    }
                }
                DB::commit();
                $result['data']['success'] = 1;
                $result['data'] = $re;
            } catch (\Exception $e) {
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
        $goods = Goods::with('attachment_file')->findOrFail($id);
        
        $result = array();
        $result['data'] =  array();
        $result['data'] = $goods;
        $result['data']['success'] = 1;
        if (!$goods) {
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
        $goods = Goods::with('attachment_file')->findOrFail($id);

      
        $result = array();
        $result['data'] =  array();
        $result['data'] = $goods;
        $result['data']['success']  = 1;
         if (!$goods) {
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
        $validator = Validator::make($request->all(), [
            'code' =>'required|max:10',
            'name' => 'required|max:150',
            'goodunit_id' => 'required',

        ]);

        $failed = $validator->fails();
        $isErr = false;
        $fields = $request->all();
        if($request->goodunit_id){
            $dep_temp = Goods::where('goodunit_id',$request->goodunit_id)
                                    ->where('code',$request->code)->first();
             
            if($dep_temp && $dep_temp->id != $id ){
                $result['data']['message']  = "Trùng mã, vui lòng nhập mã khác";
                $validator->errors()->add('code', 'Trùng mã, vui lòng nhập mã khác');
                $isErr = true;
            }
        }
        if ($failed) {
            $result['data']['errors']  = $validator->errors();
        } else {
            $goods = Goods::findOrFail($id);
            $goods->name = $request->input('name');
            $goods->code = $request->input('code');
            $goods->description = $request->input('description');
            $goods->goodunit_id = $request->input('goodunit_id');
            $goods->size = $request->input('size');
            $goods->color = $request->input('color');
            $goods->weight = $request->input('weight');
            $goods->open_stock = $request->input('open_stock');
  
            if($goods->save()){
                $result['data']['success']  = 1;
                $result['data']  = $goods;
            }
            try {

                if ($goods) {
                    DB::beginTransaction();

                    $user = new User();
                    $user = auth()->user();
                    $fields['user_id'] = $user->id;
                    $fields['id'] = $id;

                    // $contract->fill($request->all());

                    // $contract->save();

                    // $contract_terms = $fields['contract_terms'];
                    // for ($i = 0; $i < count($contract_terms); $i++) {
                    //     $contract_terms[$i]['contract_id'] = $contract->id;
                    //     if (isset($contract_terms[$i]['id']) && $contract_terms[$i]['id'] != 0) {
                    //         $term = ContractTerm::find($contract_terms[$i]['id']);
                    //         $term->fill($contract_terms[$i]);
                    //         $term->save();
                    //     } else {

                    //         ContractTerm::create($contract_terms[$i]);
                    //     }
                    // }

                    //Kiểm tra các điều khoản thanh lý của hợp đồng cha nếu có
                   

                    
                    $attachment_file = $fields['attachment_file'];
                    // dd($attachment_file);
                    for ($i = 0; $i < count($attachment_file); $i++) {

                        //Chỉ lưu file mới
                        if (!isset($attachment_file[$i]["id"])) {

                            $file = new File();

                        $file->name = $attachment_file[$i]["name"];
                        //$ext = end(explode('.', $filename));
                        // $file->ext = $attachment_file[$i]["ext"];
                        $file->size = $attachment_file[$i]["size"];
                        $file->user_id = $user->id;

                        $ext = substr($attachment_file[$i]["name"], strrpos($attachment_file[$i]["name"], '.') + 1);
                        $name = "/goods/" . $file->name;

                        $file->ext = $ext;
                        $file->url = $name;
                        $goods->attachment_file()->save($file);

                        //$name = "/avatars/" . $user->username . '.' . $request->file['ext'];
                        
                        // $image = base64_decode($base64_str);
                        $extension = explode('/', explode(':', substr($attachment_file[$i]['base64'], 0, strpos($attachment_file[$i]['base64'], ';')))[1])[1];
                        $base64_str = substr($attachment_file[$i]['base64'], strpos($attachment_file[$i]['base64'], ",") + 1);

                        $image = str_replace($base64_str, '', $attachment_file[$i]);
                        $image = str_replace(' ', '+', $image);
                        $image = base64_decode($base64_str);
                        $imageName = Str::random(10).'.'.$extension;  
                        file_put_contents(public_path().$name,  $image );
                        // Storage::disk('local')->put($name , $image);
                        // FacadesFileVault::encrypt($name);
                        }

                    }
                    $attachment_file_del = $fields['attachment_file_del'];
                    for ($i = 0; $i < count($attachment_file_del); $i++) {
                        if (isset($attachment_file_del[$i]["id"])) {
                            $file = File::find($attachment_file_del[$i]["id"]);
                            if ($file) {

                                Storage::delete($file->url . ".enc");
                                $file->delete();
                            }
                        }
                    }

                    DB::commit();
                    $result['data']['success'] = 1;
                    $result['data'] = $goods;
                }

            } catch (\Exception $e) {
                DB::rollback();
                $result['data']['errors'] = $e->getMessage();
            }
            
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

    /**
     * Remove the spe
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       
        $goods = Goods::findOrFail($id);
        $result = array();
        $result['data'] = array();
        $result['data']['success']  = 0;
        if( $goods->delete() ){
            $result['data']['success']  = 1;
        }else {
            try {
                // dd($contract->attachment_file());
                DB::beginTransaction();
                //$contract->load('attachment_file');
                foreach ($goods->attachment_file as $file) {

                    //  dd($file);
                    // Storage::delete($file->url.".enc");
                    Storage::delete($file->url . ".enc");
                    $file->delete();
                }
                // dd('khong load file được');
                foreach ($goods->contract_terms as $term) {

                    $term->delete();
                }
                $goods->delete();
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
