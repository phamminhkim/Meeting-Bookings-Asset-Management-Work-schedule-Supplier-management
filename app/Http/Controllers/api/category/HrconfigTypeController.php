<?php

namespace App\Http\Controllers\api\category;

use App\Http\Controllers\Controller;
use App\Models\productivity\HrconfigType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
class HrconfigTypeController extends Controller
{
    public function index(Request $request)
    {
        $hrconfigType = HrconfigType::all();
        // dd($request->document_type_id);
        // return DepartmentResource::collection($department);
        $result = array();
        $result['data'] = array();
        $result['data'] = $hrconfigType;
        $result['success'] = "1";
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    public function index_form(Request $request)
    {
        // if (!auth()->user()->hasPermission('config_category')) {
        //     return response('Access denied', 403);
        // }

        $hrconfigType = HrconfigType::where('company_id', $request->company_id)->orderBy('from')                       
            ->get();
        // dd($request->document_type_id);
        // return DepartmentResource::collection($department);
        $result = array();
        $result['data'] = array();
        $result['data'] = $hrconfigType;
        $result['success'] = "1";
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // if (!auth()->user()->hasPermission('config_category')) {
        //     return response('Access denied', 403);
        // }

        $result = array();
        $result['data'] =  array();
        $result['data']['success']  = 0;
        date_default_timezone_set("Asia/Ho_Chi_Minh");

        $validator = Validator::make($request->all(), [
            'company_id' => 'required',
            'limits.*.from' => 'required',
            'limits.*.to' => 'required',
            'limits.*.value' => 'required',

        ], [
            'company_id.required' => "Công ty rỗng",
            'limits.*.from.required' => "HM Từ rỗng",
            'limits.*.to.required'  => "HM Đến rỗng",
            'limits.*.value.required' => "Xếp loại rỗng",
        ]);
        $isErr = false;
        $failed = $validator->fails();
        //dd($failed);
        if ($failed) {
            $result['data']['errors']  = $validator->errors();
        } else {

            try {
        
                DB::beginTransaction();
                $fields = $request->all();

                $approvdLimitAll =  collect($fields['limits'])->values();
                $approvdLimit = array();
                $approvdLimit = $approvdLimitAll->sortBy('from')->values()->toArray();
                $firstLimit = $approvdLimit[0];
               
                $index = 0;
                for ($i = 1; $i < count($approvdLimit); $i++) {
                    if($firstLimit['to'] < $approvdLimit[$i]['from'] && $approvdLimit[$i]['from'] < $approvdLimit[$i]['to'])                     
                    {                        
                            ++$index;
                            $firstLimit  =  $approvdLimit[$i];
 
                    }else {
                        //  dd($approvdLimit);

                        $result['data']['message']  = "Vui lòng kiểm tra dữ liệu nhập chưa đúng: " . $approvdLimit[$i]['from'];
                        $validator->errors()->add('checkdata', 'Vui lòng kiểm tra dữ liệu nhập chưa đúng');
                        $isErr = true;
                    }


                }
             
                if (!$isErr) {
                 
                    for ($i = 0; $i < count($approvdLimit); $i++) {
                     
                        $approvdLimit[$i]['company_id'] = $fields['company_id'];
                       
                        if (isset($approvdLimit[$i]['id']) && $approvdLimit[$i]['id'] != 0) {
                           
                            $limit = HrconfigType::find($approvdLimit[$i]['id']);
                            $limit->fill($approvdLimit[$i]);
                          
                            $limit->save();
                            //dd($limit);
                        } else {

                          
                            HrconfigType::create($approvdLimit[$i]);
                        }
                    }

                    $approvdLimit_del = $fields['limits_del'];
                   
                    for ($i = 0; $i < count($approvdLimit_del); $i++) {
                        if (isset($approvdLimit_del[$i]['id'])) {
                            $limit = HrconfigType::find($approvdLimit_del[$i]['id']);
                            if ($limit) {
                                $limit->delete();
                            }
                        };
                    }
                   
                    DB::commit();
                    $result['data']['success'] = 1;
                } else {
                  
                    $result['data']['errors']  = $validator->errors();
                }
                
            
            } catch (\Exception $e) {
                DB::rollBack();
                
                $validator->errors()->add('save',  $e->getMessage());
                $result['data']['message']  =  $e->getMessage();
  
            }
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);

    }
}
