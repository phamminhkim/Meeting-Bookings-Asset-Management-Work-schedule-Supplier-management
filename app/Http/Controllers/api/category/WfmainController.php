<?php

namespace App\Http\Controllers\api\category;

use App\Http\Controllers\Controller;
use App\Models\shared\Company;
use App\Models\shared\DocumentType;
use App\Models\shared\Wfmain;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Validator;
class WfmainController extends Controller
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
        // $group = Group::all();
        $query = Wfmain::query();

        try {
            if($request->filled('document_type_id')){
                $query = $query->where('document_type_id', $request->document_type_id );
            }
            if($request->filled('company_id')){
                $query = $query->where('company_id', $request->company_id );
            }

            $wfmain = $query->get();
            $result['data'] = $wfmain;
            $result['success'] = "1";
        } catch (Exception $e) {
            $this->errors = $e->getMessage();
        }
        // return DepartmentResource::collection($department);



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
        //dd( $request);
        $validator = Validator::make($request->all(), [
            'document_type_id' => 'required',
            'company_id' => 'required',
            'code' => 'required',

        ]);

        $failed = $validator->fails();
        $isErr = false;
        $team_id = "";
        $company = Company::find($request->company_id);
        $document_type = DocumentType::find($request->document_type_id);

        $isExist = Wfmain::where('document_type_id',$request->document_type_id)
            ->where('company_id',$request->company_id)
            ->first();

        // dd($isExist);
        if($isExist){
            $validator->errors()->add('tontai', 'Loại chứng từ đã được cấu hình');//front-end kiểm tra dựa trên lỗi
            $result['data']['message'] = 'Tuyến duyệt đã được cấu hình';//front-end thông báo
            $isErr = true;
        }

        if ($failed || $isErr) {
            $result['data']['errors']  = $validator->errors();
        } else {

            try {

                $fields = $request->all();
                $re = Wfmain::create($fields);
                if ($re) {
                    // $re->load('team');
                    $result['data']  = $re;
                    // $result['data']['success']  = 1;
                }
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
        $wfmain = Wfmain::findOrFail($id);

        $result = array();
        $result['data'] =  array();
        $result['data'] = $wfmain;
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
        //
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
            'document_type_id' => 'required',
            'company_id' => 'required',
            'code' => 'required',
        ]);

        $failed = $validator->fails();
        $isErr = false;
        if ($failed) {
            $result['data']['errors']  = $validator->errors();
        } else {


            try {

                $company = Company::find($request->company_id);
                $document_type = DocumentType::find($request->document_type_id);
                $wfmain = Wfmain::findOrFail($id);
                // dd( $team);
                if ($wfmain) {
                    // $team->id =  $team->id;
                    $isExist = Wfmain::where('document_type_id',$request->document_type_id)
                    ->where('company_id',$request->group_id)
                    ->first();
                    //  dd($team_id );
                    if( $isExist &&  $isExist->id != $id){
                        $validator->errors()->add('tontai', 'Loại chứng từ đã được cấu hình');//front-end kiểm tra dựa trên lỗi
                        $result['data']['message'] = 'Tuyến duyệt đã được cấu hình';//front-end thông báo
                        $isErr = true;
                    }
                    if(!$isErr){
                        $wfmain->document_type_id = $request->input('document_type_id');
                        $wfmain->company_id = $request->input('company_id');
                        $wfmain->code = $request->input('code');
                        if($wfmain->save()){
                            $result['data']['success']  = 1;
                           // $wfmain->load('team');
                            $result['data']  = $wfmain;
                        }
                    }

                }
            } catch (\Exception $e) {
                $result['data']['errors']  =  $e->getMessage();
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
       // Get article
       $wfmain = Wfmain::findOrFail($id);
       $result = array();
       $result['data'] = array();
       $result['data']['success']  = 0;
       if( $wfmain->delete() ){
           $result['data']['success']  = 1;
       }
       return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
}
