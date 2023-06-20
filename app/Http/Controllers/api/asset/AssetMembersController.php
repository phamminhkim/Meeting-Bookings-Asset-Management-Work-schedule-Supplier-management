<?php

namespace App\Http\Controllers\api\asset;
use App\Models\payment\Payrequest;

use App\Http\Controllers\Controller;
use App\Models\asset\Asset;
use App\Models\asset\AssetUse;
use App\Models\asset\AssetTool;
use App\Models\asset\AssetTransaction;
use App\Models\asset\AssetTransactionItem;
use App\User;
use App\Traits\HasPermissions;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AssetMembersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
     
        $user_id = User::where('active', 1)->pluck('id')->toArray();
        $sanphams = AssetUse::whereIn('user_id', $user_id)->get();   
        // $sanphams = AssetUse::all();
       
        $newlist = array();
        $usersUnique = $sanphams->unique(['user_id']);
        foreach ($usersUnique as $sanpham) {
           

            $total2=0;
            $total1=0;

            $sanpham3= AssetUse::where('user_id', $sanpham->user_id)->where('objectable_type','App\Models\asset\Asset')->get();
            
            $sanpham4= AssetUse::where('user_id', $sanpham->user_id)->where('objectable_type','App\Models\asset\AssetTool')->get();
            $sanpham->sum_ts=$sanpham3->sum('quantity');
            $sanpham->sum_dsccdc=$sanpham4->sum('quantity');
           
            for($i = 0;$i< count($sanpham4);$i++){    
                // $sanpham->namee=$sanpham4[$i]->objectable_id;
                // $sanpham->quantityy=$sanpham4[$i]->quantity;   
                $assets=AssetTool::where('id','=',$sanpham4[$i]->objectable_id)->get();       
                foreach($assets as $ass) {
                    if($sanpham4[$i]->objectable_id===$ass->id){
                        $total2+= $ass->amount*$sanpham4[$i]->quantity;                 
                    }            
                    // $total= $total2; 
                }

            }

            foreach ($sanpham3 as $sanphamss) {
               
              
                        $assets=Asset::where('user_id','=',$sanphamss->user_id)->get();
                        $total1=$assets->sum('amount');
                        
                

                
            }
           
            $sanpham->tongtien=$total1+$total2;

    //   $sanpham2= AssetUse::where('user_id', $sanpham->user_id)->get();


        array_push($newlist,$sanpham);
      
        // }
    }
       



        $result = array();

        $result['data'] = array();
        $result['data'] = $newlist;
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
        // $payRequestModel = AssetTransaction::with(['user'])->find($id);
        // foreach ($payRequestModel->AssetTransactionItems as $value) {
        //     $total = 0;
        //     switch (get_class($value->objectable)) {
        //         case Asset::class:

        //             break;

        //         case AssetTool::class:

        //             break;
        //     }
        //     // $payRequestModel->tongtien = $total;
        //     // $sanpham->total_amount_asset = $value->quantity;
        //     // dd($value);
        //     // $value->name =  $value->objectable->name;
        //     // $value->type = $value->objectable->assetType->type;
        //     // $value->name Lấy tên trong tính đa hình objectable ( tìm hiểu)
        //     // $value->type tạo cột mới căn cứ theo bảng b-table điều kiện type chưa có trong DB
        // }
        // $result = array();
        // $result['data'] = array();
        // $result['data'] = $payRequestModel;
        // $result['data']['success'] = 1;
        // if (!$payRequestModel) {
        //     $result['data']['success'] = 0;
        // }
        // return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
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
        // $payRequestModel = AssetTransaction::with(['user'])->find($id);
        // $result = array();
        // $result['data'] = array();
        // $result['data'] = $payRequestModel;
        // $result['data']['success'] = 0;
        // date_default_timezone_set("Asia/Ho_Chi_Minh");
        // $validator = Validator::make($request->all(), [

        // ]);

        // $failed = $validator->fails();
        // $fields = $request->all();
        // $isErr = false;
        // if ($failed || $isErr) {
        //     $result['data']['errors'] = $validator->errors();
        // } else {
        //     try {
        //         $transaction = AssetTransaction::with(['user', 'AssetTransactionItems'])->find($id);
        //         $transaction->save();
        //         $confirm = AssetTransaction::where('id', $id)->update(['confirm' => 1]);
        //         // Xác nhận
        //         if ($transaction) {
        //             $item = $fields['asset_transaction_items'];
        //             $update_quantity = 0;
        //             foreach ($item as $value) {
        //                 if (isset($value['id']) && $value['id'] != '' && $fields['trans_type'] == 1) {
        //                     $asset_item = AssetTransactionItem::find($value['id']);
        //                     $update_quantity = $asset_item->objectable->quantity - $asset_item->quantity;
        //                     $tools = AssetTool::where('id', $value['objectable_id'])->update(['quantity' => $update_quantity]); 
        //                     // Cập nhật số lượng khi đã xác nhận bàn giao
        //                     $use= Asset::where('id', $value['objectable_id'])->update(['user_id' => $transaction->user_id ]); //Cập nhật user_id lúc bàn giao
        //                     $asset_use = new AssetUse(); // Lưu item vào bảng asset_uses
        //                     $asset_use->id = $value['id'];
        //                     $asset_use->user_id = $transaction->user_id;
        //                     $asset_use->objectable_id = $value['objectable_id'];
        //                     $asset_use->objectable_type = $value['objectable_type'];
        //                     $asset_use->asset_warehouse_id = $value['asset_warehouse_id'];
        //                     $asset_use->quantity =  $value['quantity'];
        //                     $asset_use->save();

        //                 }
        //                 if (isset($value['id']) && $value['id'] != '' && $fields['trans_type'] == 2) {
        //                     $quanti = 0;
        //                     $asset_item = AssetTransactionItem::find($value['id']);
        //                     $quanti = $asset_item->objectable->quantity + $asset_item->quantity;
        //                     $update = AssetTool::where('id', $value['objectable_id'])->update(['quantity' => $quanti]);
        //                     // Cập nhật số lượng khi đã xác nhận thu hồi
        //                     $all = AssetUse::all();
        //                     $total = 0;
        //                     foreach ($all as $alluse) {
        //                         if ($alluse->objectable_id == $value['objectable_id']) {
        //                             $total = $alluse->quantity - $asset_item->quantity;
        //                             $alluse->quantity = $total;
        //                             $use = AssetUse::where('objectable_id', $value['objectable_id'])->update(['quantity' => $alluse->quantity]);
        //                             // Cập nhật số lượng trong asset_uses
        //                             $asset_use = AssetUse::where('objectable_id', $value['objectable_id'])->where('quantity', 0);
        //                             $asset_use->delete();
        //                             // Cập nhật số lượng asset_uses, nếu bằng 0 -> delete
        //                         }
        //                     }
        //                     $use= Asset::where('user_id', $transaction->user_id)->where('id', $value['objectable_id'])->update(['user_id' => null ]);
        //                     // Xác nhận thu hồi tài sản user_id ( Còn thiếu điều kiện)
        //                 }
        //             }
        //         }
        //         $result['data'] = $transaction;
        //         $transaction->load('AssetTransactionItems');
        //     } catch (\Exception $e) {
        //         $result['data']['errors'] = $e->getMessage();
        //     }

        // }
        // return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
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
    public function pbsudung(Request $request)
    {
        
     
        // $user_id = DB::table('users')->where('active', 1)->pluck('id')->toArray();
        $sanphams = AssetUse::where('department_id','!=', null)->get();   
        // $sanphams = AssetUse::all();
       
        $newlist = array();
        $usersUnique = $sanphams->unique(['department_id']);
        foreach ($usersUnique as $sanpham) {
            $users = DB::table('users')
            ->where('id', $sanpham->user_id)
            ->pluck('name')->toArray();
           
            foreach ($users as $name) {
                $sanpham->user_idd=$name;
            } 

            $total2=0;
            $total1=0;

            $sanpham3= AssetUse::where('department_id', $sanpham->department_id)->where('objectable_type','App\Models\asset\Asset')->get();
            
            $sanpham4= AssetUse::where('department_id', $sanpham->department_id)->where('objectable_type','App\Models\asset\AssetTool')->get();
            $sanpham->sum_ts=$sanpham3->sum('quantity');
            $sanpham->sum_dsccdc=$sanpham4->sum('quantity');
           
                
           
            for($i = 0;$i< count($sanpham4);$i++){   
              
                // $sanpham->namee=$sanpham4[$i]->objectable_id;
                // $sanpham->quantityy=$sanpham4[$i]->quantity;   
                $assets=AssetTool::where('id','=',$sanpham4[$i]->objectable_id)->get();       
                foreach($assets as $ass) {
                    if($sanpham4[$i]->objectable_id===$ass->id){
                        $total2+= $ass->amount*$sanpham4[$i]->quantity;                 
                    }            
                    // $total= $total2; 
                }

            }

            foreach ($sanpham3 as $sanphamss) {
               
              
                        $assets=Asset::where('user_id','=',$sanphamss->user_id)->get();
                        $total1=$assets->sum('amount');
                        
                

                
            }
           
            $sanpham->tongtien=$total1+$total2;



        array_push($newlist,$sanpham);
      
    }
       



        $result = array();

        $result['data'] = array();
        $result['data'] = $newlist;
        $result['success'] = "1";
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

}
