<?php

namespace App\Http\Controllers\api\category;

use App\Http\Controllers\Controller;
use App\Models\shared\ApprovedLimit;
use App\Models\shared\DocumentType;
use App\Repositories\shared\FactoryCode;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Gate;
use App\User;

class ApproveLimitController extends Controller
{
    public function index(Request $request)
    {
        $approvedLimit = ApprovedLimit::all();
        // dd($request->document_type_id);
        // return DepartmentResource::collection($department);
        $result = array();
        $result['data'] = array();
        $result['data'] = $approvedLimit;
        $result['success'] = "1";
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

    public function index_form(Request $request)
    {
        if (!auth()->user()->hasPermission('config_category')) {
            return response('Access denied', 403);
        }

        $approvedLimit = ApprovedLimit::where('document_type_id', $request->document_type_id)
            ->where('company_id', $request->company_id)
            ->where('budget_type', $request->budget_type)
            ->where('payment_type_id', $request->payment_type_id)
            ->where('currency', $request->currency)
            ->orderBy('name')
            ->get();
        // dd($request->document_type_id);
        // return DepartmentResource::collection($department);
        $result = array();
        $result['data'] = array();
        $result['data'] = $approvedLimit;
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
        if (!auth()->user()->hasPermission('config_category')) {
            return response('Access denied', 403);
        }

        $result = array();
        $result['data'] =  array();
        $result['data']['success']  = 0;
        date_default_timezone_set("Asia/Ho_Chi_Minh");

        $validator = Validator::make($request->all(), [
            'document_type_id' => 'required',
            'payment_type_id' => 'required',
            'company_id' => 'required',
            'budget_type' => 'required',
            'currency' => 'required',
            'limits.*.from' => 'integer|required',
            'limits.*.to' => 'integer|required',
            'limits.*.active' => 'required',

        ], [
            'required' => "(*) rỗng",
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

                //$approvdLimit = collect($fields['limits'])->sortBy('from_sub')->sortBy('from')->values()->toArray() ;
                $approvdLimitAll =  collect($fields['limits'])->values();
                $approvdLimitUniq = collect($fields['limits'])->unique('from')->values()->toArray();
                $approvdLimit = array();
                for ($i = 0; $i < count($approvdLimitUniq); $i++) {

                    $sub_arr =  $approvdLimitAll->where('from', $approvdLimitUniq[$i]['from'])->where('to', $approvdLimitUniq[$i]['to']);
                    $sub_arr = $sub_arr->sortBy('from_sub')->values()->toArray();
                    $approvdLimit = array_merge($approvdLimit, $sub_arr);


                    # code...
                }
                //  dd( $approvdLimit );
                // dd($approvdLimit);
                //kiểm tra cấu hình
                //$approvdLimit = $approvdLimit->sortBy('from')->values();

                $documentType = DocumentType::findOrFail($fields['document_type_id']);
                $documentCode = $documentType->code;
                $factorycode = new FactoryCode;
                $factorycode->documentCode = $documentCode;
                $factorycode->company_id = $fields['company_id'];
                $factorycode->budget_type = $fields['budget_type'];
                $factorycode->payment_type_id = $fields['payment_type_id'];
                $factorycode->currency = $fields['currency'];

                // $preCode = $documentCode."_".$fields['company_id']."_".$fields['budget_type']."_".$fields['payment_type_id']."_".$fields['currency'];
                $index = 0;
                $name = "";
                $sub_index = '';
                if (count($approvdLimit) > 0) {
                    $firstLimit = $approvdLimit[0];
                    if ($approvdLimit[0]['from_sub'] > 0 || $approvdLimit[0]['to_sub'] > 0) {
                        $sub_index = "1";
                    }
                    $name = "H" . ++$index . $sub_index;
                    // dd($name);
                    $factorycode->name = $name;
                    $approvdLimit[0]['name'] =  $name;
                    // $approvdLimit[0]['code'] =  $preCode."_". $name;
                    $approvdLimit[0]['code'] =  $factorycode->create();
                    if ($approvdLimit[0]['from'] > $approvdLimit[0]['to'] || $approvdLimit[0]['from_sub'] > $approvdLimit[0]['to_sub']) {

                        $result['data']['message']  = "Vui lòng kiểm tra dữ liệu nhập chưa đúng";
                        $validator->errors()->add('checkdata', 'Vui lòng kiểm tra dữ liệu nhập chưa đúng');
                        $isErr = true;
                    }
                    if ($firstLimit['to_sub'] == null) {
                        $firstLimit['to_sub'] = 0;
                    }
                    if ($firstLimit['from_sub'] == null) {
                        $firstLimit['from_sub'] = 0;
                    }

                    for ($i = 1; $i < count($approvdLimit); $i++) {

                        if ($approvdLimit[$i]['from_sub'] == null) {
                            $approvdLimit[$i]['from_sub'] = 0;
                        }
                        if ($approvdLimit[$i]['to_sub'] == null) {
                            $approvdLimit[$i]['to_sub'] = 0;
                        }
                        //dd( $firstLimit['to_sub'] <= $approvdLimit[$i]['from_sub']);
                        // if($firstLimit['to'] < $approvdLimit[$i]['from'] && $approvdLimit[$i]['from'] < $approvdLimit[$i]['to']){
                        if (($firstLimit['from'] == $approvdLimit[$i]['from'] && $firstLimit['to'] == $approvdLimit[$i]['to'] &&
                                $firstLimit['to_sub'] <= $approvdLimit[$i]['from_sub'] && $approvdLimit[$i]['from_sub'] <= $approvdLimit[$i]['to_sub'])
                            ||
                            ($firstLimit['to'] < $approvdLimit[$i]['from'] && $approvdLimit[$i]['from'] < $approvdLimit[$i]['to'])
                            && ($approvdLimit[$i]['from_sub'] == 0 && $approvdLimit[$i]['to_sub'] == 0)
                        ) {
                            // $approvdLimit[$i-1]['name'] = "XXXX";
                            //Kiểm tra sub limit
                            if ($firstLimit['to'] == $approvdLimit[$i]['to'] && $firstLimit['from'] == $approvdLimit[$i]['from']) {
                                if ($sub_index == "") {
                                    $sub_index = "1";
                                } else {
                                    $sub_index =  $sub_index + 1;
                                }
                            } else {
                                $sub_index = "";
                                ++$index;
                            }

                            $name  = "H" . $index . $sub_index;
                            $approvdLimit[$i]['name'] = $name;
                            $factorycode->name = $name;
                            $code =  $factorycode->create(); // $preCode."_".$name;
                            $approvdLimit[$i]['code'] = $code;
                            $firstLimit  =  $approvdLimit[$i];;
                        } else {
                            //  dd($approvdLimit);

                            $result['data']['message']  = "Vui lòng kiểm tra dữ liệu nhập chưa đúng";
                            $validator->errors()->add('checkdata', 'Vui lòng kiểm tra dữ liệu nhập chưa đúng');
                            $isErr = true;
                        }
                    }
                }

                if (!$isErr) {
                    for ($i = 0; $i < count($approvdLimit); $i++) {
                        $approvdLimit[$i]['document_type_id'] = $fields['document_type_id'];
                        $approvdLimit[$i]['payment_type_id'] = $fields['payment_type_id'];
                        $approvdLimit[$i]['company_id'] = $fields['company_id'];
                        $approvdLimit[$i]['budget_type'] = $fields['budget_type'];
                        $approvdLimit[$i]['currency'] = $fields['currency'];

                        if ($approvdLimit[$i]['to_sub'] == null) {
                            $approvdLimit[$i]['to_sub'] = 0;
                        }
                        if ($approvdLimit[$i]['from_sub'] == null) {
                            $approvdLimit[$i]['from_sub'] = 0;
                        }
                        //  dd($approvdLimit[$i]);
                        if (isset($approvdLimit[$i]['id']) && $approvdLimit[$i]['id'] != 0) {
                            $limit = ApprovedLimit::find($approvdLimit[$i]['id']);
                            $limit->fill($approvdLimit[$i]);

                            $limit->save();
                            //dd($limit);
                        } else {


                            ApprovedLimit::create($approvdLimit[$i]);
                        }
                    }

                    $approvdLimit_del = $fields['limits_del'];

                    for ($i = 0; $i < count($approvdLimit_del); $i++) {
                        if (isset($approvdLimit_del[$i]['id'])) {
                            $limit = ApprovedLimit::find($approvdLimit_del[$i]['id']);
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
                $result['data']['errors']  =  $e->getMessage();
            }
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);

    }
}
