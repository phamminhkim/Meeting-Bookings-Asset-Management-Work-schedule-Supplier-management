<?php

namespace App\Http\Controllers\api\asset;

use App\Http\Controllers\Controller;
use App\Models\asset\Asset;
use App\Models\asset\AssetDetail;
use App\Models\asset\AssetFixedHistory;
use App\Models\asset\AssetTool;
use App\Models\asset\AssetTransaction;
use App\Models\asset\AssetTransactionItem;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AssetTransactionRepairController extends Controller
{

    public function store(Request $request)
    {
        $result = array();
        $result['data'] = array();
        $result['data']['success'] = 0;
        date_default_timezone_set("Asia/Ho_Chi_Minh");
        $meesage = [

        ];
        $validator = Validator::make($request->all(), [

        ], $meesage);
        $fields = $request->all();
        $failed = $validator->fails();
        $isErr = false;
        $validateHistory = $fields['asset_fixed_histories'];
        foreach ($validateHistory as  $value) {
            if (isset($value['action']) && $value['action'] == false && $value['quantity_sloc'] < $value['quantity'] ) {
                $validator->errors()->add('quantity', 'Số lượng nhập vào không được lớn hơn số lượng hiện có');
                $isErr = true;
            }
           
        }
        if ($failed || $isErr) {
            $result['data']['errors'] = $validator->errors();
        } else {
            try {
                $user_id = new User();
                $user_id = auth()->user();

                // $fields['create_by'] = $user_id->id;
                // $fields['trans_type'] = 1;

                $re = AssetTransaction::create([
                    'user_id' => $fields['asset']['user_id'],
                    'trans_type' => 4,
                    'create_by' => $user_id->id,
                    'note' => $fields['description'],
                    'asset_warehouse_id' => $fields['asset']['asset_warehouse_id'],
                    'department_id' => $fields['asset']['department_id'],
                    'date_transaction' => $fields['date_transaction'],
                    'assetdable_id' => $fields['asset_id'],
                    'assetdable_type' => Asset::class,
                    'confirm' => 0,
                ]);
                // nếu tài sản đang
                $asset = Asset::find($fields['asset_id']);
                if ($asset->user_id) {
                    $waiting = 1;
                } else if ($asset->department_id) {
                    $waiting = 0;
                } else {
                    $waiting = 0;
                }
                $update_asset_status = Asset::where('id', $fields['asset_id'])->update([
                    'asset_status_id' => $fields['status'],
                    'waiting' => $waiting,
                ]);
                if ($re) {
                    $fixed_histories = $fields['asset_fixed_histories'];
                    foreach ($fixed_histories as $value) {
                        if ($value['old_component'] == []) {
                            $value['old_component']['id'] = null;
                        }
                        if (isset($value['action']) && $value['action'] == false) {
                            $create_transaction_item = AssetTransactionItem::create([
                                'asset_transaction_id' => $re->id,
                                'objectable_id' => $value['objectable_id'],
                                'objectable_type' => AssetTool::class,
                                'quantity' => $value['quantity'],
                                'asset_status_id' => 1,
                                'user_id' => $re->user_id,
                                'department_id' => $re->department_id,
                                'asset_warehouse_id' => $value['objectable']['asset_warehouse_id'],
                            ]);
                            $history = AssetFixedHistory::create([
                                'asset_id' => $fields['asset_id'],
                                'name' => $value['name'],
                                'quantity' => $value['quantity'],
                                'objectable_id' => $value['objectable_id'],
                                'objectable_type' => AssetTool::class,
                                'asset_status_id' => $fields['status'],
                                'old_content' => $value['old_content'],
                                'old_component' => $value['old_component']['id'],
                                'new_content' => $value['new_content'],
                                'new_component' => $value['new_component']['id'],
                                'description' => $value['description'],
                                'asset_transaction_item_id' => $create_transaction_item->id,
                            ]);
                            $update_quantity_asset_tool = AssetTool::where('id', $value['objectable_id'])->update([
                                'quantity' => $value['objectable']['quantity'] - $value['quantity'],
                                'sloc_tool' => $value['objectable']['sloc_tool'] - $value['quantity'],
                            ]);
                            $update_asset_detail = AssetDetail::where('objectable_id', $fields['asset_id'])
                                ->where('objectable_type', Asset::class)
                                ->where('name', $value['name'])
                                ->update([
                                    'value' => $value['new_content'],
                                ]);
                        }
                        if (isset($value['action']) && $value['action'] == true) {
                            $create_transaction_item = AssetTransactionItem::create([
                                'asset_transaction_id' => $re->id,
                                'objectable_id' => $value['objectable_id'],
                                'objectable_type' => AssetTool::class,
                                'quantity' => $value['quantity'],
                                'asset_status_id' => 1,
                                'user_id' => $re->user_id,
                                'department_id' => $re->department_id,
                                'asset_warehouse_id' => $value['objectable']['asset_warehouse_id'],
                            ]);
                            $history = AssetFixedHistory::create([
                                'asset_id' => $fields['asset_id'],
                                'name' => $value['name'],
                                'quantity' => $value['quantity'],
                                'objectable_id' => $value['objectable_id'],
                                'objectable_type' => AssetTool::class,
                                'asset_status_id' => $fields['status'],
                                'old_content' => $value['old_content'],
                                'old_component' => $value['old_component']['id'],
                                'new_content' => $value['new_content'],
                                'new_component' => $value['new_component']['id'],
                                'description' => $value['description'],
                                'asset_transaction_item_id' => $create_transaction_item->id,
                            ]);
                            $update_quantity_asset_tool = AssetTool::where('id', $value['objectable_id'])->update([
                                'quantity' => $value['objectable']['quantity'] + $value['quantity'],
                                'sloc_tool' => $value['objectable']['sloc_tool'] + $value['quantity'],
                            ]);
                            $update_asset_detail = AssetDetail::where('objectable_id', $fields['asset_id'])
                                ->where('objectable_type', Asset::class)
                                ->where('name', $value['name'])
                                ->update([
                                    'value' => $value['new_content'],
                                ]);
                        }
                    }
                }
                $result['data']['success'] = 1;
                $result['data']['message'] = 'Tạo phiếu sửa chữa thành công';
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
