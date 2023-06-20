<?php

namespace App\Http\Controllers\api\asset;

use App\Http\Controllers\Controller;
use App\Models\asset\Asset;
use App\Models\asset\AssetInventario;
use App\Models\asset\AssetInventarioHistory;
use App\Models\asset\AssetUse;
use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AssetInventarioHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $AssetInventario = AssetInventarioHistory::all();

       
        $result = array();
        $result['data'] = array();
        $result['data'] = $AssetInventario;
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
        // $invent = AssetInventario::with(['AssetInventarioHistorys'])->find($id);
        $invent = AssetInventario::with(['AssetInventarioDetails','AssetInventarioHistorys'])->find($id);
       
        $result = array();
        $result['data'] = array();
        $result['data'] = $invent;
        $result['data']['success'] = 1;
        if (!$invent) {
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
        $payRequestModel = AssetInventario::with(['AssetInventarioDetails'])->find($id);
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
        // $er = $fields['asset_inventario_details'];
        // foreach ($er as  $value) {
        //     if($value['user_confirm_quantity']!=$value['stocker_confirm_quantity']){
        //         $result['data']['message']  = "Phát hiện chênh lệch số lượng đánh giá giữa người dùng và thủ kho";
        //         $validator->errors()->add('quantity','Phát hiện chênh lệch số lượng đánh giá giữa người dùng và thủ kho');
        //         $isErr = true;
        //     }
          
        // }
       
        if ($failed || $isErr) {
            $result['data']['errors'] = $validator->errors();
        } else {
            try {
                $transaction = AssetInventario::with(['AssetInventarioDetails'])->find($id);
               
                $time= Carbon::now();
                // dd();
                $transaction->save();
                $complete = AssetInventario::where('id', $id)->update(['complete' => 1]);
                $time_complete = AssetInventario::where('id', $id)->update(['time_confirm' => $time]);
             $update_use= AssetUse::where('asset_warehouse_id',$transaction->asset_warehouse_id)
                        ->update(['time_complete_inven' => null]);
            
                // dd($time);
                // Xác nhận
                if ($transaction) {
                    $item = $fields['asset_inventario_details'];
                    foreach ($item as $value) {
                        if($value['objectable_type']=='App\\Models\\asset\\Asset'){
                            $valuediscrepancy=0;
                            $update_asset=Asset::where('id',$value['objectable_id'])->update(['asset_status_id' => $value['stocker_confirm_status']]);

                        }
                        if($value['objectable_type']=='App\\Models\\asset\\AssetTool'){
                            $valuediscrepancy=$value['quantity_use']-$value['stocker_confirm_quantity'];
                            $update_use=AssetUse::where('user_id',$value['user_id'])->where('objectable_type','App\\Models\\asset\\AssetTool')
                            ->where('objectable_id',$value['objectable_id'])->update(['quantity' => $value['stocker_confirm_quantity']]);
                        }
                        
                                $his = AssetInventarioHistory::create([
                                    'id' => $value['id'],

                                    'asset_inventario_id' =>$id,
                                    'user_id' => $value['user_id'],
                                    'objectable_id' => $value['objectable_id'],
                                    'objectable_type' => $value['objectable_type'],
                                    'asset_status_id' => $value['asset_status_id'],
                                    'user_confirm_status' => $value['user_confirm_status'],
                                    'stocker_confirm_status' => $value['stocker_confirm_status'],
                                    'user_confirm_quantity' => $value['user_confirm_quantity'],
                                    'stocker_confirm_quantity' => $value['stocker_confirm_quantity'],
                                    'comment' => $value['comment'],
                                    'quantity_use'=>$value['quantity_use'],
                                    'discrepancy'=>$valuediscrepancy,
                                ]);
                                // dd($value['objectable_id']);
                                // if($value['objectable_type']=='App\\Models\\asset\\Asset'){
        
                                // }
                                // else{
                                // }

                               
                        }
                        

                }
                $result['data']['success'] = 1;
                $result['data']['obj'] = $transaction;
                $transaction->load('AssetInventarioDetails');

              

             
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
}
