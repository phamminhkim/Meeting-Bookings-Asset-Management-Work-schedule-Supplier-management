<?php

namespace App\Http\Controllers\api\asset;

use App\Http\Controllers\Controller;
use App\Mail\EmailNoti;
use App\Models\asset\Asset;
use App\Models\asset\AssetTool;
use App\Models\asset\AssetTransaction;
use App\Models\asset\AssetTransactionItem;
use App\Models\asset\AssetUse;
use App\Notifications\CommondNotification;
use App\Notifications\NotiBaseModel;
use App\Ultils\Ultils;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AssetMyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        // return $user->hasRole($permission->roles());
        // if (!auth()->user()->hasPermission('review_my')) {
        //     return response('Access denied', 403);
        // }
        $user_id = new User();
        $user_id = auth()->user();
        $sanphams = AssetTransaction::where('user_id', $user_id->id)->get();
        $total = 0;
        foreach ($sanphams as $sanpham) {
            // dd($sanpham->user_id);
            //    dd($user_id->id);
            // if($user_id->id==$sanpham->user_id){

            //$sanpham->item = AssetTransactionItem::where('asset_transaction_id', $sanpham['id'])->get();
            $sanpham->total = $sanpham->AssetTransactionItems->count();
            //dd($sanpham->AssetTransactionItems);
            foreach ($sanpham->AssetTransactionItems as $value) {

                switch ($value->objectable_type) {
                    case Asset::class;
                        if ($value->objectable) {
                            $sanpham->danhsachAsset++;
                            $total = $total + $value->objectable->amount * $value->quantity;
                        }

                        break;

                    case AssetTool::class:
                        if ($value->objectable) {
                            $sanpham->danhsachCCDC++;
                            $total = $total + $value->objectable->amount * $value->quantity;
                        }

                        break;
                }
                $sanpham->tongtien = $total;
                $sanpham->total_amount_asset = $value->quantity;
                // $sanpham->asset = Asset::where('id', $value['objectable_id'])->get();
                // $sanpham->tool = AssetTool::where('id', $value['objectable_id'])->get();

                // // $sanpham->danhsachCCDC = $sanpham->tool->count();
                // $sanpham->danhsachAsset = $sanpham->asset->count();

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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       
        $assetTransaction = AssetTransaction::with(['assetdable','user', 'Department', 'AssetTransactionItems.History', 'AssetTransactionItems.objectable'])->find($id);
        $result = array();
        $result['data'] =  array();
        $result['data'] = $assetTransaction;
        $result['data']['success']  = 1;


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
        $payRequestModel = AssetTransaction::with(['user','Department','assetdable','AssetTransactionItems.History'])->find($id);
       
        foreach ($payRequestModel->AssetTransactionItems as $value) {
            $total = 0;
            switch (get_class($value->objectable)) {
                case Asset::class:

                    break;

                case AssetTool::class:

                    break;
            }
            // $payRequestModel->tongtien = $total;
            // $sanpham->total_amount_asset = $value->quantity;
            // dd($value);
            // $value->name =  $value->objectable->name;
            // $value->type = $value->objectable->assetType->type;
            // $value->name Lấy tên trong tính đa hình objectable ( tìm hiểu)
            // $value->type tạo cột mới căn cứ theo bảng b-table điều kiện type chưa có trong DB
        }
        $result = array();
        $result['data'] = array();
        $result['data'] = $payRequestModel;
        $result['data']['success'] = 1;
        if (!$payRequestModel) {
            $result['data']['success'] = 0;
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
        $payRequestModel = AssetTransaction::with(['user'])->find($id);
        $result = array();
        $result['data'] = array();
        $result['data'] = $payRequestModel;
        $result['data']['success'] = 0;
        date_default_timezone_set("Asia/Ho_Chi_Minh");
        $validator = Validator::make($request->all(), [

        ]);

        $failed = $validator->fails();
        $fields = $request->all();
        $isErr = false;
        if ($payRequestModel->confirm == 1) {
            $result['data']['message'] = "Đã xác nhận";
            $validator->errors()->add('confirm', 'Đã xác nhận');
            $isErr = true;
          
            // $failed = $validator->fails();
            // $result['data']['errors'] = $validator->errors();
            // $isErr = true;
        }

        if ($failed || $isErr) {
            $result['data']['errors'] = $validator->errors();
        } else {
            try {
                $transaction = AssetTransaction::with(['user', 'AssetTransactionItems'])->find($id);
                $transaction->save();

                $confirm = AssetTransaction::where('id', $id)->update(['confirm' => 1]);
                if($transaction->trans_type == 4){
                  Asset::where('id', $transaction->assetdable_id)->update(['waiting' => 0]); 
                }
                // Xác nhận
             
                if ($transaction) {
                    $item = $fields['asset_transaction_items'];
                    $update_quantity = 0;
                    foreach ($item as $value) {
                        if (isset($value['id']) && $value['id'] != '' && $fields['trans_type'] == 1) { // Bàn giao
                            // $asset_item = AssetTransactionItem::find($value['id']);
                            // $update_quantity = $asset_item->objectable->quantity - $asset_item->quantity;
                            // $tools = AssetTool::where('id', $value['objectable_id'])->update(['quantity' => $update_quantity]);

                            // Cập nhật số lượng khi đã xác nhận bàn giao

                            $use = Asset::where('id', $value['objectable_id'])->update(['user_id' => $transaction->user_id, 'waiting' => 0]); //Cập nhật user_id lúc bàn giao
                            $all_usess = AssetUse::all();
                            $sum = 0;
                            foreach ($all_usess as $tess_use) {
                                if ($tess_use['user_id'] == $transaction->user_id && $tess_use['objectable_id'] == $value['objectable_id'] && $tess_use['objectable_type'] == 'App\\Models\\asset\\AssetTool') {
                                    $sum = $tess_use->quantity + $value['quantity'];
                                }
                            }

                            // Cập nhật số lượng or tạo mới khi bàn giao giống filed
                            $ums = AssetUse::where('user_id', $transaction->user_id)
                                ->where('objectable_id', $value['objectable_id'])
                                ->where('objectable_type', 'App\\Models\\asset\\AssetTool')->first();
                            if ($ums !== null) {

                                $ums->update(['quantity' => $sum, 'date_transaction' => $transaction->date_transaction]);

                            } else {

                                $umsc = AssetUse::create([
                                    'id' => $value['id'],
                                    'user_id' => $transaction->user_id,
                                    'objectable_id' => $value['objectable_id'],
                                    'objectable_type' => $value['objectable_type'],
                                    'asset_warehouse_id' => $value['asset_warehouse_id'],
                                    'quantity' => $value['quantity'],
                                    'date_transaction' => $transaction->date_transaction,
                                    'create_by' => $transaction->create_by
                                ]);
                            }
                        }
                        if (isset($value['id']) && $value['id'] != '' && $fields['trans_type'] == 2) {
                            $quanti = 0;
                            $asset_item = AssetTransactionItem::find($value['id']);
                            if ($asset_item->objectable_type == AssetTool::class) {
                                $quanti = $asset_item->objectable->quantity + $asset_item->quantity;
                                $update = AssetTool::where('id', $value['objectable_id'])->update(['quantity' => $quanti]);
                            }
                            // Cập nhật số lượng khi đã xác nhận thu hồi
                            $all = AssetUse::all();
                            $total = 0;
                            foreach ($all as $alluse) {
                                if ($alluse->objectable_id == $value['objectable_id']) {
                                    $total = $alluse->quantity - $asset_item->quantity;
                                    $alluse->quantity = $total;
                                    $use = AssetUse::where('objectable_id', $value['objectable_id'])
                                        ->where('user_id', $transaction->user_id)
                                        ->where('objectable_type', $value['objectable_type'])
                                        ->update(['quantity' => $alluse->quantity]);
                                    // Cập nhật số lượng trong asset_uses
                                    $asset_use = AssetUse::where('objectable_id', $value['objectable_id'])
                                        ->where('quantity', 0)
                                        ->where('user_id', $transaction->user_id);
                                    $asset_use->delete();
                                    // Cập nhật số lượng asset_uses, nếu bằng 0 -> delete
                                }
                            }
                            if ($asset_item->objectable_type == Asset::class) {
                                $use = Asset::where('user_id', $transaction->user_id)
                                    ->where('id', $value['objectable_id'])
                                    ->update(['user_id' => null]);
                                // Xác nhận thu hồi tài sản user_id ( Còn thiếu điều kiện)
                            }

                        }
                    }

                }
                $result['data']['success'] = 1;
                $result['data']['obj'] = $transaction;
                $transaction->load('AssetTransactionItems');

                $data = new NotiBaseModel;
                $data->title = "Confirm tài sản/CCDC ";
                $data->icon = "fas fa-briefcase";
                $data->content = "Xác nhận tài sản/CCDC";
                $data->content_full = "Xác nhận tài sản/CCDC";
                $data->url = URL('/') . '/' . Ultils::$URL_ASSET_CONFIRM . $transaction->id;
                $data->type = AssetTransaction::class;
                $data->objectid = $transaction->id;

                $email = new EmailNoti($data, $transaction->user);
                //dd( $email);
                $transaction->user->notify(new CommondNotification($data, $transaction->createBy, $email));

            } catch (\Exception $e) {
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
        //
    }
    public function tai_san_dang_nam(Request $request)
    {
        $user_id = new User();
        $user_id = auth()->user();
        $sanphams = AssetUse::where('user_id', $user_id->id)->groupBy('user_id', 'objectable_id', 'quantity', 'objectable_type')->select('user_id', 'quantity', 'objectable_id', 'objectable_type')->get();
        $asset = AssetUse::all();
        $new = array();
        $total = 0;
        foreach ($sanphams as $sanpham) {
           
            switch ($sanpham->objectable_type) {
                case Asset::class:

                    break;

                case AssetTool::class:
                    if ($sanpham->objectable) {
                        $sanpham->quantity = $asset->where('objectable_id', $sanpham->objectable_id)
                            ->where('user_id', $user_id->id)
                            ->sum('quantity');
                    }
                    break;
            }
            if ($sanpham->objectable) {
                $sanpham->objectable->load("attachment_image");
            }

            // $sanpham->image = $sanpham->objectable->with(['attachment_image'])->where('id',$sanpham->objectable_id)->get();
            // $sanpham->tongtien = $total;
            // $sanpham->total_amount_asset = $sanpham->quantity;
            array_push($new, $sanpham);
        }
        $result = array();
        $result['data'] = array();
        $result['data'] = $new;
        $result['success'] = "1";
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

}
