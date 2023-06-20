<?php

namespace App\Http\Controllers\api\payment;

use App\Http\Controllers\BaseController\BaseController;
use App\Http\Requests\ContractRequest;
use App\Models\payment\contract\Contract;
use App\Models\payment\contract\ContractTerm;
use App\Models\shared\File;
use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use SoareCostin\FileVault\Facades\FileVault as FacadesFileVault;

class ContractController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $result = array();
        $result['data'] = array();
        $result['success'] = "0";
        $query = Contract::query();
        try {

            if ($request->filled('start_date')) {
                if (!$request->filled('end_date')) {
                    $request->end_date = $request->start_date;
                }
                $start_date = Carbon::create($request->start_date);
                $end_date = Carbon::create($request->end_date);
                $end_date->addHours(23);
                $end_date->addMinutes(59);
                $end_date->addSeconds(59);
                $query = $query->whereBetween('created_at', [$start_date, $end_date]);
            }
            if($request->filled('payment_status')){
                $query = $query->where('payment_status', $request->payment_status);
            }
            if($request->filled('status')){
                $query = $query->where('status', $request->status);
            }
            if($request->filled('contract_num')){
                $query = $query->where('contract_num', $request->contract_num);
            }
            if($request->filled('contract_type')){
                $query = $query->where('contract_type', $request->contract_type);
            }
            if($request->filled('vendor_id')){
                $query = $query->where('vendor_id', $request->vendor_id);
            }
            if($request->filled('company_id')){
                $query = $query->where('company_id', $request->company_id);
            }
            if($request->filled('department_id')){
                $query = $query->where('department_id', $request->department_id);
            }

            $result['success'] = "1";
        } catch (Exception $e) {
            $result['success'] = "0";
        }

        $contracts = $query->orderBy('created_at', 'desc')->with(['vendor', 'parent', 'childs'])->get();
        $result['data'] = $contracts;
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ContractRequest $request)
    {

        $result = array();
        $result['data'] = array();
        $result['data']['success'] = 0;
        date_default_timezone_set("Asia/Ho_Chi_Minh");
        $validator = $request->validator;
        $failed = $validator->fails();
        $fields = $request->all();
        $isErr = false;
        $user = new User();
        $user = auth()->user();
        if(!$user->company ){

            $validator->errors()->add('company', 'User chưa được cấu hình công ty');
            $isErr = true;
         }
         if(!$user->department ){

            $validator->errors()->add('department', 'User chưa được cấu hình phòng ban');
            $isErr = true;
         }

        if ($failed || $isErr) {
            $result['data']['errors'] = $validator->errors();
        } else {

            try {

                DB::beginTransaction();

                $fields['user_id'] = $user->id;
                $fields['company_id'] = $user->company->id;
                // dd(  $user->department_id);
                $fields['department_id'] = $user->department->id;
                //dd($fields);

                $re = Contract::create($fields);

                if ($re) {
                    $contract_terms = $fields['contract_terms'];
                    for ($i = 0; $i < count($contract_terms); $i++) {
                        $contract_terms[$i]['contract_id'] = $re->id;
                        ContractTerm::create($contract_terms[$i]);
                    }

                    //Kiểm tra các điều khoản thanh lý của hợp đồng cha nếu có
                    $parent_contract_terms = $fields['parent_contract_terms'];

                    for ($i = 0; $i < count($parent_contract_terms); $i++) {
                        $parent_term = ContractTerm::find($parent_contract_terms[$i]['contract_term_id']);

                        if ($parent_term && $parent_term->contract_id == $re->parent_id) {
                            //thực hiện cập nhật
                            $parent_term->finalization = $parent_contract_terms[$i]['finalization'];
                            $parent_term->sub_contract_id = $re->id;
                            $parent_term->save();

                        }

                    }

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
                }

                DB::commit();
                $result['data']['success'] = 1;
                $result['data'] = $re;
            } catch (\Exception $e) {
                DB::rollback();
                $result['data']['errors'] = $e->getMessage();
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
        
        $contract = Contract::with('contract_terms','contract_term_plans', 'attachment_file', 'vendor', 'company', 'childs', 'parent','contract_liquidation')->withCount('childs')->find($id);

        $result = array();
        $result['data'] = array();
        $result['data'] = $contract;
        $result['data']['success'] = 1;
        if (!$contract) {
            $result['data']['success'] = 0;
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // $contract = Contract::with('users')->findOrFail($id);

        // $result = array();
        // $result['data'] =  array();
        // $result['data'] = $contract;

        // return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ContractRequest $request, $id)
    {

        $result = array();
        $result['data'] = array();
        $result['data']['success'] = 0;
        date_default_timezone_set("Asia/Ho_Chi_Minh");
        $fields = $request->all();
        $validator = $request->validator;

        $failed = $validator->fails();
        $contract = Contract::findOrFail($id);

        $isErr = false;
        if ($contract->finalization == 1) {
            $validator->errors()->add('finalization', 'Hợp đồng đã tất toán. Vui lòng không cập nhật');
            $isErr = true;
        }
        if ($contract->contract_type != $fields['contract_type']) {
            $validator->errors()->add('contract_type', 'Loại hợp đồng không được sửa đổi');
            $isErr = true;
        }

        $fields = $request->all();
        $isErr = false;
        $user = new User();
        $user = auth()->user();
        if(!$user->company ){

            $validator->errors()->add('company', 'User chưa được cấu hình công ty');
            $isErr = true;
         }
         if(!$user->department ){

            $validator->errors()->add('department', 'User chưa được cấu hình phòng ban');
            $isErr = true;
         }

        if ($failed || $isErr) {
            $result['data']['errors'] = $validator->errors();
        } else {

            try {

                if ($contract) {
                    DB::beginTransaction();

                    // $user = new User();
                    // $user = auth()->user();
                    $fields['user_id'] = $user->id;
                    $fields['id'] = $id;

                    $contract->fill($request->all());

                    $contract->save();

                    $contract_terms = $fields['contract_terms'];
                    for ($i = 0; $i < count($contract_terms); $i++) {
                        $contract_terms[$i]['contract_id'] = $contract->id;
                        if (isset($contract_terms[$i]['id']) && $contract_terms[$i]['id'] != 0) {
                            $term = ContractTerm::find($contract_terms[$i]['id']);
                            $term->fill($contract_terms[$i]);
                            $term->save();
                        } else {

                            ContractTerm::create($contract_terms[$i]);
                        }
                    }

                    //Kiểm tra các điều khoản thanh lý của hợp đồng cha nếu có
                    $parent_contract_terms = $fields['parent_contract_terms'];

                    for ($i = 0; $i < count($parent_contract_terms); $i++) {
                        $parent_term = ContractTerm::find($parent_contract_terms[$i]['contract_term_id']);
                        if ($parent_term && $parent_term->contract_id == $contract->parent_id) {
                            //thực hiện cập nhật
                            $parent_term->finalization = $parent_contract_terms[$i]['finalization'];
                            $parent_term->sub_contract_id = $contract->id;
                            $parent_term->save();
                        }

                    }

                    $contract_terms_del = $fields['contract_terms_del'];
                    for ($i = 0; $i < count($contract_terms_del); $i++) {

                        $term = ContractTerm::find($contract_terms_del[$i]['id']);
                        if ($term) {
                            $term->delete();
                        }
                    }
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
                            $name = "public/contract/" . $user->username . "/" . uniqid() . '.' . $ext; // $attachment_file[$i]["ext"];

                            $file->ext = $ext;
                            $file->url = $name;
                            $contract->attachment_file()->save($file);

                            //$name = "/avatars/" . $user->username . '.' . $request->file['ext'];
                            $base64_str = substr($attachment_file[$i]['base64'], strpos($attachment_file[$i]['base64'], ",") + 1);
                            $image = base64_decode($base64_str);
                            //file_put_contents(public_path().$name,  $image );
                            Storage::disk('local')->put($name, $image);
                            FacadesFileVault::encrypt($name);
                        }

                    }
                    $attachment_file_removed = $fields['attachment_file_removed'];
                    for ($i = 0; $i < count($attachment_file_removed); $i++) {
                        if (isset($attachment_file_removed[$i]["id"])) {
                            $file = File::find($attachment_file_removed[$i]["id"]);
                            if ($file) {

                                Storage::delete($file->url . ".enc");
                                $file->delete();
                            }
                        }
                    }

                    DB::commit();
                    $result['data']['success'] = 1;
                    $result['data'] = $contract;
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

        $result = array();
        $result['data'] = array();
        $result['data']['success'] = 0;

        $contract = Contract::findOrFail($id);
        if (!$contract) {
            abort(404);
        }

        if ($contract->finalization == 1) {
            $result['data']['message'] = "Vui lòng không xoá hợp đồng đã tất toán!";

        } elseif (count($contract->childs) > 0) {
            $result['data']['message'] = "Hợp đồng có nhiều phụ lục. Vui lòng xoá phụ lục tham chiếu trước.";
        } else {
            try {
                // dd($contract->attachment_file());
                DB::beginTransaction();
                //$contract->load('attachment_file');
                foreach ($contract->attachment_file as $file) {

                    //  dd($file);
                    // Storage::delete($file->url.".enc");
                    Storage::delete($file->url . ".enc");
                    $file->delete();
                }
                // dd('khong load file được');
                foreach ($contract->contract_terms as $term) {

                    $term->delete();
                }
                $contract->delete();
                DB::commit();
                $result['data']['success'] = 1;
            } catch (\Exception $e) {
                DB::rollback();
                $result['data']['errors'] = $e->getMessage();
            }
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);

    }
    //Hàm download file
    public function downloadFile($idfile)
    {
        $file = File::findOrFail($idfile);
        if (!$file) {
            abort(404);
        }
        $filepath = $file->url . ".enc";
        if (!Storage::has($filepath)) {
            abort(404);
        }
        // return  Storage::download($filepath);

        $filename = $file->name;
       // dd($filepath);
        return response()->streamDownload(function () use ($filepath) {
            FacadesFileVault::streamDecrypt($filepath);
        }, Str::replaceLast('.enc', '', $filename));
    }
    // public function downloadFile($idfile)
    // {
    //     $file = File::findOrFail($idfile);
    //     if (!$file) {
    //         abort(404);
    //     }
    //     $filepath = $file->url . ".enc";
    //     if (!Storage::has($filepath)) {
    //         abort(404);
    //     }
    //     // return  Storage::download($filepath);

    //     $filename = $file->name;
    //     return response()->streamDownload(function () use ($filepath) {
    //         FacadesFileVault::disk('local')->streamDecrypt($filepath);
    //     }, Str::replaceLast('.enc', '', $filename));
    // }

    public function search_contract(Request $request)
    {

        $searchterm = $request->search;
        if ($searchterm != '') {
            $contractlist = Contract::where('contract_num', 'like', '%' . $searchterm . '%')

                ->orwhere('sign_date', 'like', '%' . $searchterm . '%')
                ->orwhere('a_signer', 'like', '%' . $searchterm . '%')
                ->orwhere('b_signer', 'like', '%' . $searchterm . '%')

                ->orwhere('title', 'like', '%' . $searchterm . '%')
                ->orwhere('content', 'like', '%' . $searchterm . '%')
                ->orwhere('amount', 'like', '%' . $searchterm . '%')
                ->orwhere('amount_paid', 'like', '%' . $searchterm . '%')
                ->with('contract_terms')
                ->paginate(10);
        } else {
            $contractlist = Contract::with('contract_terms')->paginate(10);
        }

        //$userlist = User::paginate(5);
        // return DepartmentResource::collection($department);
        $result = array();
        $result['data'] = array();
        $result['data'] = $contractlist;
        $result['success'] = "1";
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);

    }

    public function contract_finalization(Request $request)
    {

        $result = array();
        $result['data'] = array();

        $result['data']['success'] = 0;
        try {
            DB::beginTransaction();
            $contract = Contract::find($request->id);
            //dd("acheck".$payment_att);

            $contract->finalization = 1;
            foreach ($contract->childs as $key => $child) {
                $child->finalization = 1;
                $child->save();
            }
            $contract->save();

            DB::commit();
            $result['data']['success'] = 1;
        } catch (\Exception $e) {
            DB::rollback();
            $result['data']['errors'] = $e->getMessage();
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    public function contract_term_finalization(Request $request)
    {

        $result = array();
        $result['data'] = array();

        $result['data']['success'] = 0;
        try {
            DB::beginTransaction();
            $contractTerm = ContractTerm::where('id', $request->id)->where('contract_id', $request->contract_id)->first();
            //dd("acheck".$payment_att);
            //dd($contractTerm);
            $contractTerm->finalization = $request->status ? 1 : 0;

            $contractTerm->save();

            DB::commit();
            $result['data']['success'] = 1;

            $result['data']['status'] = $contractTerm->finalization;
        } catch (\Exception $e) {
            DB::rollback();
            $result['data']['errors'] = $e->getMessage();
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

}
