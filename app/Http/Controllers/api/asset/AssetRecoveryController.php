<?php

namespace App\Http\Controllers\api\asset;

use App\Http\Controllers\Controller;
use App\Models\asset\Asset;
use App\Models\asset\AssetTool;
use App\Models\asset\AssetTransaction;
use App\Models\asset\AssetTransactionItem;
use App\Models\asset\AssetUse;
use App\Models\shared\Department;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AssetRecoveryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $sanphams = AssetTransaction::all();

        foreach ($sanphams as $sanpham) {

            foreach ($sanpham->AssetTransactionItems as $value) {

                switch (get_class($value->objectable)) {
                    case Asset::class:

                        break;

                    case AssetTool::class:

                        break;
                }

            }
            // }
        }
        $result = array();

        $result['data'] = array();
        $result['data'] = $sanphams;
        $result['success'] = "1";
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
        $result['data'] = array();
        $result['data']['success'] = 0;
        date_default_timezone_set("Asia/Ho_Chi_Minh");
        $meesage = [

            'note.max' => 'Tối đa 255 kí tự',
        ];
        $validator = Validator::make($request->all(), [

            'note' => 'max:255',

        ], $meesage);
        $fields = $request->all();
        $failed = $validator->fails();
        $isErr = false;

        if (isset($fields['department_id']) == false && isset($fields['user_id']) == false) {
            $result['data']['message'] = " Chọn người dùng hoặc chọn phòng ban, một trong hai cột không được để trống";
            $validator->errors()->add('department_id', "Chọn người dùng hoặc chọn phòng ban, một trong hai cột không được để trống");
            $isErr = true;
        }
        $ims = $fields['asset_transaction_items'];

        if ($ims == null) {
            $result['data']['message'] = " Vui lòng chọn danh sách đã bàn giao";
            $validator->errors()->add('items', " Vui lòng chọn danh sách đã bàn giao");
            $isErr = true;
        }
        foreach ($ims as $value) {
            if ($value['quantity'] < 0) {
                $result['data']['message'] = $value['quantity'] . " Không được nhập số âm";
                $validator->errors()->add('quantity', $value['quantity'] . ' Không được nhập số âm');
                $isErr = true;
            }
            $item = AssetUse::where('objectable_id', $value['objectable_id'])
                ->where('objectable_type', $value['objectable_type'])
                ->where('user_id', $fields['user_id'])
                ->where('quantity', '<', $value['quantity'])
                ->where('department_id', null)
                ->first();
            if ($item) {
                $result['data']['message'] = "Số lượng thu hồi phải nhỏ hơn hoặc bằng số lượng bàn giao";
                $validator->errors()->add('quantity', 'Số lượng thu hồi phải nhỏ hơn hoặc bằng số lượng bàn giao');
                $isErr = true;
            }
            $validate_department = AssetUse::where('objectable_id', $value['objectable_id'])
                ->where('objectable_type', $value['objectable_type'])
                ->where('department_id', $value['department_id'])
                ->where('quantity', '<', $value['quantity'])
                ->where('user_id', null)
                ->first();
            if ($validate_department) {
                $result['data']['message'] = "Số lượng thu hồi phải nhỏ hơn hoặc bằng số lượng bàn giao";
                $validator->errors()->add('quantity', 'Số lượng thu hồi phải nhỏ hơn hoặc bằng số lượng bàn giao');
                $isErr = true;
            }
            
            if ($fields['check'] == false) {
                $find_id_user_id = AssetUse::where('objectable_id', $value['objectable_id'])
                ->where('objectable_type', $value['objectable_type'])
                ->where('user_id', $value['user_id'])
                ->first();
                if ($find_id_user_id == null) {
                    $result['data']['message'] = " Đã thu hồi hết, Vui lòng tải lại trang";
                    $validator->errors()->add('isset_use', ' Đã thu hồi hết, Vui lòng tải lại trang');
                    $isErr = true;
                }
              
                if($fields['user_id'] !== $value['user_id'] && $value['user_id'] !== null){
                    $result['data']['message'] = " Danh sách thu hồi chỉ thu hồi được 1 người dùng, vui lòng kiểm tra lại ";
                    $validator->errors()->add('user_id_recovery', " Danh sách thu hồi chỉ thu hồi được 1 người dùng, vui lòng kiểm tra lại  ");
                    $isErr = true;
                }
                if($value['department_id'] !== null){
                    $result['data']['message'] = " Bạn đang ở chế độ thu hồi người dùng, vui lòng xóa những tài sản có phòng ban và chọn lại ";
                    $validator->errors()->add('user_id_recovery', " Bạn đang ở chế độ thu hồi người dùng,  vui lòng xóa những tài sản có phòng ban và chọn lại");
                    $isErr = true;
                }
            } else {
                $find_id = AssetUse::where('objectable_id', $value['objectable_id'])
                ->where('objectable_type', $value['objectable_type'])
                ->where('department_id', $value['department_id'])
                ->first();
                if ($find_id == null) {
                    $result['data']['message'] = " Đã thu hồi hết, Vui lòng tải lại trang";
                    $validator->errors()->add('isset_use', ' Đã thu hồi hết, Vui lòng tải lại trang');
                    $isErr = true;
                }
               
                // $fin_id_tool = AssetTool::find($value['objectable_id']);
                // $find_department = Department::find($fields['department_id']);
              
                if($fields['department_id'] !== $value['department_id'] && $value['department_id'] !== null){
                    $result['data']['message'] = " Danh sách thu hồi chỉ thu hồi được 1 phòng ban, vui lòng kiểm tra lại";
                    $validator->errors()->add('department_recovery', " Danh sách thu hồi chỉ thu hồi được 1 phòng ban, vui lòng kiểm tra lại");
                    $isErr = true;
                    
                }
                if($value['user_id'] !== null){
                    $result['data']['message'] = " Bạn đang ở chế độ thu hồi phòng ban, vui lòng xóa những tài sản không có phòng ban và chọn lại ";
                    $validator->errors()->add('depart_id_recovery', " Bạn đang ở chế độ thu hồi phòng ban, vui lòng xóa những tài sản không có phòng ban và chọn lại");
                    $isErr = true;
                }
            }

        }

        if ($failed || $isErr) {
            $result['data']['errors'] = $validator->errors();
        } else {
            try {
                $user_id = new User();
                $user_id = auth()->user();
                $fields['create_by'] = $user_id->id;
                $fields['trans_type'] = 2;
                if ($fields['confirm'] == true) {
                    $fields['confirm'] = 3;
                } else {
                    $fields['confirm'] = 0;
                }
                $re = AssetTransaction::create($fields);
                $id_item = $re->id;
                $usee = $re->user_id;
                $create_by = $re->create_by;
                $department = $re->department_id;
                if ($re) {
                    $item = $fields['asset_transaction_items'];

                    foreach ($item as $value) {
                        if ($value['objectable_type'] == "App\\Models\\asset\\AssetTool") {
                            $asset_tool_update_quantity = AssetTool::where('id', $value['objectable_id'])->first();
                            if ($asset_tool_update_quantity != null) {
                                $quantity_sloc = $asset_tool_update_quantity->quantity;
                            }
                        } else {
                            $quantity_sloc = 0;
                        }
                        $tran = AssetTransactionItem::create([
                            'asset_status_id' => $value['asset_status_id'],
                            'asset_transaction_id' => $id_item,
                            'objectable_id' => $value['objectable_id'],
                            'objectable_type' => $value['objectable_type'],
                            'quantity' => $value['quantity'],
                            'department_id' => $value['department_id'],
                            'asset_warehouse_id' => $value['asset_warehouse_id'],
                            'quantity_sloc' => $quantity_sloc,
                            'user_id' => $usee
                        ]);
                        if ($fields['confirm'] == true) {
                           
                            $asset_my = AssetUse::where('objectable_id', $value['objectable_id'])->where('objectable_type', $value['objectable_type'])
                            ->where('user_id', $value['user_id'])->where('department_id', null)->first();
                            if ($asset_my != null) {
                                $asset_my->update(['quantity' => $asset_my->quantity - $value['quantity']]);
                            }
                            $zero = AssetUse::where('quantity', 0)->first();
                            if ($zero != null) {
                                $zero->delete();
                            }
                            if ($value['objectable_type'] == "App\\Models\\asset\\AssetTool") {
                                $asset_tool_update_quantity = AssetTool::where('id', $value['objectable_id'])->first();
                                if ($asset_tool_update_quantity != null) {
                                    $asset_tool_update_quantity->update(['quantity' => $asset_tool_update_quantity->quantity + $value['quantity']]);
                                }
                            } else {
                                $asset_update_user_id = Asset::where('id', $value['objectable_id'])->first();
                                if ($asset_update_user_id != null) {
                                    $asset_update_user_id->update(['user_id' => null]);
                                }
                            }
                        }
                        if ($department) {
                            $asset_my = AssetUse::where('objectable_id', $value['objectable_id'])->where('objectable_type', $value['objectable_type'])->where('department_id', $value['department_id'])->first();
                            if ($asset_my != null) {
                                $asset_my->update(['quantity' => $asset_my->quantity - $value['quantity']]);
                            }
                            $zero = AssetUse::where('quantity', 0)->first();
                            if ($zero != null) {
                                $zero->delete();
                            }
                            if ($value['objectable_type'] == "App\\Models\\asset\\AssetTool") {
                                $asset_tool_update_quantity = AssetTool::where('id', $value['objectable_id'])->first();
                                if ($asset_tool_update_quantity != null) {
                                    $asset_tool_update_quantity->update(['quantity' => $asset_tool_update_quantity->quantity + $value['quantity']]);
                                }
                            } else {
                                $asset_update_user_id = Asset::where('id', $value['objectable_id'])->first();
                                if ($asset_update_user_id != null) {
                                    $asset_update_user_id->update(['department_id' => null, 'waiting' => 0]);
                                }
                            }
                        }

                    }

                    $result['data']['success'] = 1;
                    $result['data']['data'] = $re;

                }
                $re->load('AssetTransactionItems');
                $re->load('Asset');
                $re->load('AssetTool');
            } catch (\Exception $e) {

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
        //
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

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $AssetTransaction = AssetTransaction::with(['user', 'AssetPolicy', 'AssetTransactionItems'])->find($id);
        $result = array();
        $result['data'] = array();
        // $result['data'] =   $payrequestBase;
        $result['data']['success'] = 0;

        if ($AssetTransaction->confirm == 1) {
            $result['data']['errors'] = 'Người dùng đã xác nhận, Vui lòng tải lại trang';
            $isErr = true;
        } else {
            try {

                DB::beginTransaction();

                foreach ($AssetTransaction->AssetTransactionItems as $term) {

                    $term->delete();
                    //    if($AssetTransaction->confirm == 3) {
                    //     if($term['objectable_type'] == Asset::class){
                    //         $update_quantity = Asset::where('id',$term['objectable_id'])->first();
                    //         if($update_quantity != null){
                    //             $update_quantity->update(['user_id' => $AssetTransaction->user_id]);
                    //         }
                    //         $update_asset_use = AssetUse::create([
                    //             'user_id' => $AssetTransaction->user_id,
                    //             'objectable_id' => $term['objectable_id'],
                    //             'objectable_type' => $term['objectable_type'],
                    //             'quantity' => $term['quantity'],
                    //             'asset_warehouse_id' => $term['asset_warehouse_id'],
                    //             'create_by' => $AssetTransaction->create_by,
                    //         ]);
                    //     } else {
                    //         $update_quantity_tool = AssetTool::where('id',$term['objectable_id'])->first();
                    //         if($update_quantity_tool != null){
                    //             $update_quantity_tool->update(['quantity' => $update_quantity_tool->quantity - $term['quantity']]);
                    //         }
                    //         $update_tool_use = AssetUse::where('objectable_id',$term['objectable_id'])->first();
                    //         if($update_tool_use != null){
                    //             $update_tool_use->update(['quantity' => $update_tool_use->quantity + $term['quantity']]);
                    //         } else {
                    //             $update_asset_use = AssetUse::create([

                    //                 'user_id' => $AssetTransaction->user_id,
                    //                 'objectable_id' => $term['objectable_id'],
                    //                 'objectable_type' => $term['objectable_type'],
                    //                 'quantity' => $term['quantity'],
                    //                 'asset_warehouse_id' => $term['asset_warehouse_id'],
                    //                 'create_by' => $AssetTransaction->create_by,
                    //             ]);
                    //         }
                    //     }
                    //    }

                }
                $result['data']['success'] = 1;
                $AssetTransaction->delete();
                DB::commit();

            } catch (\Exception $e) {
                DB::rollback();
                $result['data']['errors'] = $e->getMessage();
            }
        }
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
}
