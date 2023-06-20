<?php

namespace App\Http\Controllers\api\asset;

use App\Http\Controllers\Controller;
use App\Models\asset\Asset;
use App\Models\asset\AssetInventario;
use App\Models\asset\AssetInventarioDetail;
use App\Models\asset\AssetInventarioHistory;
use App\Models\asset\AssetUse;
use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AssetInventarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = AssetInventario::query()->with(['user', 'AssetWarehouse','AssetInventarioHistorys']);
        $result = array();
        $result['data'] = array();
        try {

            if ($request->filled('name')) {
                $query = $query->where('name', 'LIKE', "%$request->name%");
            }
            $AssetInventario = $query->get();
            $result['data'] = $AssetInventario;
            $result['success'] = "1";
        } catch (Exception $e) {
            $result['data']['errors'] = $e->getMessage();
        }

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
            'name.required' => 'Tên không được để trống',
            'responsible.required' => 'Người chịu trách nhiệm không được để trống',
            'asset_warehouse_id.required' => 'Kho hàng không được để trống',
            'deadline_confirm.required' => 'Ngày không được để trống',
            'name.max' => 'Tối đa 50 kí tự',
            'description.max' =>'Tối đa 255 kí tự',
        ];
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:50',
            'responsible' => 'required',
            'asset_warehouse_id' =>'required',
            'deadline_confirm' => 'required',
            'description' => 'max:255',
        ],$meesage);
        $fields = $request->all();
        $failed = $validator->fails();
        $isErr = false;
        if ($request->asset_warehouse_id) {
            $dep_temp = AssetInventario::where('asset_warehouse_id', $request->asset_warehouse_id)->first();
            if ($dep_temp) {
                $result['data']['message']  = "Trùng Kho, vui lòng chọn kho khác";
                $validator->errors()->add('asset_warehouse_id', "Trùng Kho, vui lòng chọn kho khác");
                $isErr = true;
            }
        }
        if($request->time == null){
            $result['data']['message']  = "Thời gian không được để trống";
            $validator->errors()->add('time', "Thời gian không được để trống");
            $result['data']['time']  = 0;
            $isErr = true;
        }
        if ($failed || $isErr) {
            $result['data']['errors'] = $validator->errors();
        } else {
            try {
                $user = new User();
                $user = auth()->user();
                $fields['create_by'] = $user->id;
                $date = $fields['time'];
                $newDate = Carbon::createFromFormat('H:i', $date)->format('H:i:s');
                // dd($newDate);
                $mas = $fields['deadline_confirm'] . ' ' . $newDate;
                $update_use= AssetUse::where('asset_warehouse_id',$fields['asset_warehouse_id'])
                ->update(['time_complete_inven' => $mas]);
                $re = AssetInventario::create([
                    'name' => $fields['name'],
                    'responsible' => $fields['responsible'],
                    'asset_warehouse_id' => $fields['asset_warehouse_id'],
                    'deadline_confirm'=> $mas,
                    'create_by' => $user->id,
                    'description' => $fields['description'],
                    'complete'=> 0,
                ]);
               
                // dd($re->deadline_confirm);
                if ($re) {
                    $result['data'] = $re;
                }
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
        $AssetInventario = AssetInventario::find($id);
        $aset_use = AssetUse::where('asset_warehouse_id', $AssetInventario->asset_warehouse_id)->get();
        foreach ($aset_use as $value) {
            // dd($value);
        }
        // foreach ($payRequestModel->AssetTransactionItems as $value) {
        //     $total = 0;
        //     switch (get_class($value->objectable)) {
        //         case Asset::class:

        //             break;

        //         case AssetTool::class:

        //             break;
        //     }
        // }
        $result = array();
        $result['data'] = array();
        $result['data'] = $aset_use;
        $result['data']['success'] = 1;
        if (!$aset_use) {
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
        $AssetInventario = AssetInventario::with(['AssetInventarioHistorys'])->find($id);
        $user_id = User::where('active', 1)->pluck('id')->toArray();
        $tess = AssetUse::whereIn('user_id', $user_id)->where('asset_warehouse_id', $AssetInventario->asset_warehouse_id)->get();
        // $aset_use = AssetUse::groupBy('user_id');
        $new = array();
        $usersUnique = $tess->unique(['user_id']);
        $usersUnique->load('user');
        // dd($usersUnique);
        $item_new = array();
        foreach ($usersUnique as $value) {
            $aler = AssetUse::where('asset_warehouse_id', $AssetInventario->asset_warehouse_id)->where('objectable_type', "App\\Models\\asset\\AssetTool")->where('user_id', $value['user_id'])->get();
            $tss = AssetUse::where('asset_warehouse_id', $AssetInventario->asset_warehouse_id)->where('objectable_type', "App\\Models\\asset\\Asset")->where('user_id', $value['user_id'])->get();
            $all = AssetUse::where('asset_warehouse_id', $AssetInventario->asset_warehouse_id)->get();
            $value->countallUser= count($usersUnique->toArray());
            if($AssetInventario->complete==1){
                $quantity_hisccdc=AssetInventarioHistory::where('asset_inventario_id',$AssetInventario->id)->where('objectable_type', "App\\Models\\asset\\AssetTool")->where('user_id', $value['user_id'])->get();
                $quantity_hists=AssetInventarioHistory::where('asset_inventario_id',$AssetInventario->id)->where('objectable_type', "App\\Models\\asset\\Asset")->where('user_id', $value['user_id'])->get();
                $quantity_hisall=AssetInventarioHistory::where('asset_inventario_id',$AssetInventario->id)->get();

                    $value->dsccdc = $quantity_hisccdc->sum('quantity_use');
                    $value->ts = $quantity_hists->sum('quantity_use');
                    $value->all = $quantity_hisall->sum('quantity_use');

            }
            if($AssetInventario->complete==0){
                $value->dsccdc = $aler->sum('quantity');
                $value->all=$all->sum('quantity');
                $value->ts = $tss->sum('quantity');
            }
            // dd($AssetInventario->id);
            // $value->dsccdc = $aler->sum('quantity');
           
            $value->even = $id;
            $tess = AssetInventarioDetail::where('asset_inventario_id', $id)->get();
            $ware_house = $tess->load('objectable');
            // dd($tess->sum('stocker_confirm_quantity'));
           
           
            foreach ($tess as $confirm) {
                if ($value->asset_warehouse_id == $confirm->objectable['asset_warehouse_id']) {
                    array_push($item_new, $confirm);
                }
                $sum_quantitytoo = AssetInventarioDetail::where('asset_inventario_id', $id)->where('objectable_type', 'App\\Models\\asset\\AssetTool')->get();
                $sum_quantityass = AssetInventarioDetail::where('asset_inventario_id', $id)->where('objectable_type', 'App\\Models\\asset\\Asset')->get();

                $value->allconfirm= $sum_quantityass->count('stocker_confirm_status')+$sum_quantitytoo->sum('stocker_confirm_quantity');
                if ($confirm['objectable_type'] == 'App\\Models\\asset\\AssetTool') {
                    $sum_quantity = AssetInventarioDetail::where('asset_inventario_id', $id)
                        ->where('objectable_type', 'App\\Models\\asset\\AssetTool')
                        ->where('user_id', $value['user_id'])->get();
                    $value->sumtool = $sum_quantity->sum('user_confirm_quantity');
                    $value->sumstockertool = $sum_quantity->sum('stocker_confirm_quantity');
                }
                if ($confirm['objectable_type'] == 'App\\Models\\asset\\Asset') {
                    $sum_quantity = AssetInventarioDetail::where('asset_inventario_id', $id)
                        ->where('objectable_type', 'App\\Models\\asset\\Asset')
                        ->where('user_id', $value['user_id'])
                        ->where('user_confirm_status','!=',null)->get();
                    $value->sumasset = $sum_quantity->count('user_confirm_status');
                    $sum_quantity_stocker = AssetInventarioDetail::where('asset_inventario_id', $id)
                        ->where('objectable_type', 'App\\Models\\asset\\Asset')
                        ->where('user_id', $value['user_id'])
                        ->where('stocker_confirm_status','!=',null)->get();
                    $value->sumstockerasset = $sum_quantity_stocker->count('stocker_confirm_status');
                }
                if ($value->sumasset == null) {
                   
                }
                

            }

            // dd($sum_quantity->sum('user_confirm_quantity'));
            $value->item = $item_new;
            if($value->item == null){
                $value->sumasset = 0;
                $value->sumtool = 0;
                $value->sumstockertool = 0;
                $value->sumstockerasset= 0;
            }
            array_push($new, $value);
            // $new =  $usersUnique;
        }

        $result = array();
        $result['data'] = array();
        $result['data'] = $new;
        $result['success'] = 1;

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
    function list(Request $request, $user_id, $inven, $ware_house) {
        // $AssetInventario = AssetUse::all();

        $tario = AssetInventario::where('id', $inven)->get();
        $role = User::find(auth()->user()->id);
        foreach ($tario as $value) {
            
            $AssetInventario = AssetUse::where('user_id', $user_id)->where('asset_warehouse_id', $value['asset_warehouse_id'])->with(['objectable'])->get();
          
                foreach ($AssetInventario as $item_asset_use) {
                    $item_asset_use->role_id=$role->id;
                    $item_asset_use->foreign_key = $inven;
                    $item_asset_use->AssetInventarioDetails = AssetInventarioDetail::where('asset_inventario_id',$inven)->where('objectable_id',$item_asset_use['objectable_id'])->where('objectable_type',$item_asset_use['objectable_type'])->get();
                  if($item_asset_use->AssetInventarioDetails !== null){
                   
                    if ($item_asset_use['objectable_type'] == 'App\\Models\\asset\\Asset') {
                        $item_asset_use->user_confirm_status = null;
                        $item_asset_use->stocker_confirm_status = null;
                    }
                    if ($item_asset_use['objectable_type'] == 'App\\Models\\asset\\AssetTool') {
                        $item_asset_use->user_confirm_quantity = null;
                        $item_asset_use->stocker_confirm_quantity = null;
                    };
                    foreach ($item_asset_use->AssetInventarioDetails as $show) {
                        if(isset($show['id']) && $show['id'] != ''){
                            if ($item_asset_use['objectable_type'] == 'App\\Models\\asset\\Asset' && $item_asset_use['objectable_id'] == $show['objectable_id'] && $item_asset_use['user_id'] == $show['user_id']) {
                                $item_asset_use->user_confirm_status = $show['user_confirm_status'];
                                $item_asset_use->stocker_confirm_status = $show['stocker_confirm_status'];
                                
                            }
                            if ($item_asset_use['objectable_type'] == 'App\\Models\\asset\\AssetTool' && $item_asset_use['objectable_id'] == $show['objectable_id'] && $item_asset_use['user_id'] == $show['user_id']){
                                $item_asset_use->user_confirm_quantity =  $show['user_confirm_quantity'];
                                $item_asset_use->stocker_confirm_quantity = $show['stocker_confirm_quantity'];
                                $item_asset_use->use_quantity = $show['quantity_use'];

                            }
                           
                        } else {
                            if ($item_asset_use['objectable_type'] == 'App\\Models\\asset\\Asset'  ) {
                                $item_asset_use->user_confirm_status = null;
                                $item_asset_use->stocker_confirm_status = null;
                            }
                            if ($item_asset_use['objectable_type'] == 'App\\Models\\asset\\AssetTool') {
        
                                $item_asset_use->user_confirm_quantity = null;
                                $item_asset_use->stocker_confirm_quantity = null;
                            };
        
                        }
                    }
                  }
            }

        }

        $result = array();
        $result['data'] = array();
        $result['data'] = $AssetInventario;
        $result['success'] = 1;

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    public function detail()
    {
        // $query = AssetInventario::query()->with(['user', 'AssetWarehouse','AssetInventarioDetails']);
        $detail = AssetInventarioDetail::all();
        
        $newlist = array();
        foreach ($detail as  $value) {
            // $value->test = "1";
            // $value->quantity= AssetUse::select('quantity')->where('objectable_id',$value['objectable_id'])->where('objectable_type',$value['objectable_type'])->get();
            // dd($value->quantity);
            array_push($newlist,$value);

        }


        $result['data'] = array();
        $result['data'] = $newlist;
        $result['success'] = "1";
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }


}
