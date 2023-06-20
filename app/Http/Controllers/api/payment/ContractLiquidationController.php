<?php

namespace App\Http\Controllers\api\payment;

use App\Http\Controllers\Controller;
use App\Models\payment\contract\Contract;
use App\Models\payment\contract\ContractLiquidation;
use App\Models\shared\File;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use SoareCostin\FileVault\Facades\FileVault as FacadesFileVault;

class ContractLiquidationController extends Controller
{
    public function index(Request $request)
    {
        $contract_liquid = ContractLiquidation::all();
        // return DepartmentResource::collection($department);
        $result = array();
        $result['data'] = array();
        $result['data'] = $contract_liquid;
        $result['success'] = "1";
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    public function store(Request $request)
    {
        $result = array();
        $result['data'] =  array();
        $result['data']['success']  = 0;
        date_default_timezone_set("Asia/Ho_Chi_Minh");

        $validator = Validator::make($request->all(), [
            'contract_id' => 'required',
            'date_liquid' => 'required|date',
            'content' => 'required|max:255',
        ]);
        $failed = $validator->fails();
        $isErr = false;

        $contract = Contract::find($request->contract_id);
        //dd($contract->contract_liquidation);
        if (!$contract) {
            $validator->errors()->add('hopndong', 'Không tìm thấy thông tin hợp đồng');
            $isErr = true;
        }
       
            //dd($failed);
        if ($failed || $isErr) {
            $result['data']['errors']  = $validator->errors();
        } else {

            try {
                DB::beginTransaction();
                $fields = $request->all();
                $user = new User();
                $user = auth()->user();
                $fields['user_id'] = $user->id;
                
                $re = ContractLiquidation::create($fields);
                if ($re) {
                  
                    $result['data']  = $re;

                     //xử lý file
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
                         $name = "public/contract/" . $user->username . "/" . uniqid() . '.' . $ext; // $attachment_file[$i]["ext"];
 
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
                    // $result['data']['success']  = 1;
                }

                //Cập nhật trạng thái thanh lý hợp đồng
                $contract->status = 3;
                $contract->save();
                
                DB::commit();
                $result['data']['success'] = 1;
                $result['data'] = $re;
            } catch (\Exception $e) {
                DB::rollback();
                $result['data']['errors']  =  $e->getMessage();
            }
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    public function show($id)
    {
        $contractLiquidation = ContractLiquidation::findOrFail($id);
        
        $result = array();
        $result['data'] =  array();
        $result['data'] = $contractLiquidation;
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    public function edit($id)
    {

    }
    public function update(Request $request, $id)
    {

    }
    public function destroy($id)
    {
         // Get article
         $contract_liquid = ContractLiquidation::findOrFail($id);
         $result = array();
         $result['data'] = array();
         $result['data']['success']  = 0;
 
         try {
            DB::beginTransaction();
            foreach ($contract_liquid->attachment_file as $file) {
                Storage::delete($file->url . ".enc");
                $file->delete();
            }
            if( $contract_liquid->delete() ){
                $result['data']['success']  = 1;
            }
         }  catch (\Exception $e) {
            DB::rollback();
            $result['data']['errors']  =  $e->getMessage();
         }
        
         return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
}
