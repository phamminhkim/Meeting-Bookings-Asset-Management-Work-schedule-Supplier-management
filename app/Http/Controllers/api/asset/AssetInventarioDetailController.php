<?php

namespace App\Http\Controllers\api\asset;

use App\Http\Controllers\Controller;
use App\Models\asset\Asset;
use App\Models\asset\AssetInventario;
use App\Models\asset\AssetInventarioDetail;
use App\Models\asset\AssetUse;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AssetInventarioDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $AssetInventario = AssetInventario::all();
        $newlist=array();
        $role = User::find(auth()->user()->id);

        foreach ($AssetInventario as $value) {
            $value->role_id=$role->id;
            array_push($newlist,$value);

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
        $vali = $fields['asset_inventario_details'];
        foreach ($vali as $value) {
            $tion = AssetInventarioDetail::where('objectable_id', $value['objectable_id'])->with(['objectable'])->get();
            foreach ($tion as $house) {

                if ($house->objectable['asset_warehouse_id'] == $value['asset_warehouse_id']) {
                    $result['data']['message'] = "Đã Lưu";
                    $validator->errors()->add('objectable_id', " Đã Lưu");
                    $result['data']['success'] = 3;
                    $isErr = true;
                }
            }

        }
        if ($failed || $isErr) {
            $result['data']['errors'] = $validator->errors();
        } else {
            try {
                $user = new User();
                $user = auth()->user();

                // $tess = AssetInventarioDetail::create($fields);
                $item = $fields['asset_inventario_details'];

                foreach ($item as $value) {

                    $item_inven = AssetInventarioDetail::create([
                        'asset_inventario_id' => $value['asset_inventario_id'],
                        'user_id' => $value['user_id'],
                        'objectable_id' => $value['objectable_id'],
                        'objectable_type' => $value['objectable_type'],
                        'asset_status_id' => $value['asset_status_id'],
                        'user_confirm_status' => $value['user_confirm_status'],
                        'stocker_confirm_status' => $value['stocker_confirm_status'],
                        'user_confirm_quantity' => $value['user_confirm_quantity'],
                        'stocker_confirm_quantity' => $value['stocker_confirm_quantity'],
                        'comment' => $value['comment'],
                    ]);
                }
                $result['data']['data'] = $item;
                $result['data']['success'] = 1;

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
    public function edit(Request $request, $inven)
    {
        $payRequestModel = AssetInventario::with(['AssetInventarioDetails'])->find($inven);

        // foreach ($payRequestModel->GooddocusDetails as   $value) {

        //     $value->name =  $value->objectable->name;
        //     $value->type = $value->objectable->assetType->type;
        //     // $value->name Lấy tên trong tính đa hình objectable ( tìm hiểu)
        //     // $value->type tạo cột mới căn cứ theo bảng b-table điều kiện type chưa có trong DB
        //  }
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
    public function update(Request $request, $inven)
    {
        $payRequestModel = AssetInventario::with(['AssetInventarioDetails'])->find($inven);

        $result = array();
        $result['data'] = array();
        $result['data'] = $payRequestModel;
        $result['data']['success'] = 0;
        date_default_timezone_set("Asia/Ho_Chi_Minh");
        $messages = [

        ];
        $validator = Validator::make($request->all(), [

        ], $messages);

        $failed = $validator->fails();
        $fields = $request->all();
        $isErr = false;
        $er = $fields['asset_inventario_details'];
        
        foreach ($er as  $value) {
            $use=AssetUse::where('objectable_id', $value['objectable_id'])->where('user_id', $value['user_id'])->where('objectable_type',$value['objectable_type'])->get();
            foreach ($use as $u) {
                if($value['objectable_type'] == 'App\\Models\\asset\\AssetTool' && $u->quantity<$value['user_confirm_quantity']){
                
                        $result['data']['message']  = "Số lượng người dùng nhập phải bé hơn số lượng người dùng sở hữu (Bàn giao) ";
                $validator->errors()->add('quantityuser','Số lượng người dùng nhập phải bé hơn số lượng người dùng sở hữu (Bàn giao) ');
                $isErr = true;
                }
                if($value['objectable_type'] == 'App\\Models\\asset\\AssetTool' && $u->quantity<$value['stocker_confirm_quantity']){
                  
                          $result['data']['message']  = "Số lượng thủ kho nhập phải bé hơn số lượng người dùng sở hữu (Bàn giao)";
                $validator->errors()->add('quantitystocker','Số lượng thủ kho nhập phải bé hơn số lượng người dùng sở hữu (Bàn giao)');
                $isErr = true;
                    }
            }
           
           
           
           
           
            // if($value['user_confirm_quantity']!=$value['stocker_confirm_quantity']){
            //     $result['data']['message']  = "Phát hiện chênh lệch số lượng đánh giá giữa người dùng và thủ kho";
            //     $validator->errors()->add('quantity','Phát hiện chênh lệch số lượng đánh giá giữa người dùng và thủ kho');
            //     $isErr = true;
            // }
          
        }
       
        if ($failed || $isErr) {

            $result['data']['errors'] = $validator->errors();
        } else {

            try {

                $department = AssetInventario::with(['AssetInventarioDetails'])->find($inven);
              

                $goods = $fields['asset_inventario_details'];
                // dd($goods);
                if ($department) {

                    foreach ($goods as $value) {
                        // dd($value);
                        $ums = AssetInventarioDetail::where('objectable_id', $value['objectable_id'])
                            ->where('user_id', $value['user_id'])->first();
                            $use=AssetUse::where('objectable_id', $value['objectable_id'])->where('user_id', $value['user_id'])->get();
                            foreach ($use as $key ) {
                            //  dd($value['quantity_use']);
                            // }  
                            // if($value['quantity_use']){
                                
                            // }
                        if ($ums !== null) {
                            // dd($value);
                            if($value['objectable_type'] == 'App\\Models\\asset\\AssetTool'){
                                $ums->update(['user_confirm_status' => null,
                                'user_confirm_quantity' => $value['user_confirm_quantity'],
                                'stocker_confirm_status' => null,
                                'stocker_confirm_quantity' => $value['stocker_confirm_quantity'],
                            ]);
                            } else {
                                $ums->update(['user_confirm_status' => $value['user_confirm_status'],
                                'user_confirm_quantity' => null,
                                'stocker_confirm_status' => $value['stocker_confirm_status'],
                                'stocker_confirm_quantity' => null,
                            ]);
                            }
                            // $gooddoc = AssetInventarioDetail::find($value['id']);
                            // $gooddoc->fill($value);
                            // // dd($gooddoc);
                            // $gooddoc->save();
                        } else {

                            if ($value['objectable_type'] == 'App\\Models\\asset\\AssetTool') {
                                AssetInventarioDetail::create([
                                    'asset_inventario_id' => $inven,
                                    'user_id' => $value['user_id'],
                                    'objectable_id' => $value['objectable_id'],
                                    'objectable_type' => $value['objectable_type'],
                                    'asset_status_id' => $value['asset_status_id'],
                                    'user_confirm_status' => null,
                                    'stocker_confirm_status' => null,
                                    'user_confirm_quantity' => $value['user_confirm_quantity'],
                                    'stocker_confirm_quantity' => $value['stocker_confirm_quantity'],
                                    'comment' => $value['comment'],
                                    'quantity_use'=>$key->quantity,
                                ]);
                                
                            } else {
                                AssetInventarioDetail::create([
                                    'asset_inventario_id' => $inven,
                                    'user_id' => $value['user_id'],
                                    'objectable_id' => $value['objectable_id'],
                                    'objectable_type' => $value['objectable_type'],
                                    'asset_status_id' => $value['asset_status_id'],
                                    'user_confirm_status' => $value['user_confirm_status'],
                                    'stocker_confirm_status' => $value['stocker_confirm_status'],
                                    'user_confirm_quantity' => null,
                                    'stocker_confirm_quantity' => null,
                                    'comment' => $value['comment'],
                                    'quantity_use'=>$key->quantity,
                                ]);
                            }
                          

                        }
                    }
                    }

                    $result['data'] = $department;
                }

            } catch (\Exception $e) {
                $result['data']['errors'] = $e->getMessage();
            }
        }

        // dd("test class");
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
