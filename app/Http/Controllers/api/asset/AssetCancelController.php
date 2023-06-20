<?php

namespace App\Http\Controllers\api\asset;

use App\Http\Controllers\Controller;
use App\Models\asset\Asset;
use App\Models\asset\AssetTool;
use App\Models\asset\AssetTransaction;
use App\Models\asset\AssetTransactionItem;
use App\Models\asset\AssetUse;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AssetCancelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = AssetTransaction::query()->with(['user','Asset' ,'AssetPolicy', 'AssetTransactionItems','AssetWarehouse']);
        $result = array();
        $result['data'] = array();
        $role = User::find(auth()->user()->id);
        try {
            if ($request->filled('user_id')) {
                $query = $query->where('user_id', $request->user_id);
            }
            if ($request->filled('trans_type')) {
                $query = $query->where('trans_type', $request->trans_type);
            }
            if ($request->filled('create_by')) {
                $query = $query->where('create_by', $request->create_by);
            }
            if ($request->filled('asset_policy_id')) {
                $query = $query->where('asset_policy_id', $request->asset_policy_id);
            }
            if ($request->filled('note')) {
                $query = $query->where('note', $request->note);
            }
            if ($request->filled('asset_warehouse_id')) {
                $item_warehouse = AssetTransactionItem::query();
                $item_warehouse = $item_warehouse->where('asset_warehouse_id','like', '%'. $request->asset_warehouse_id. '%' );
                $asset_transaction_id= $item_warehouse->get()->pluck('asset_transaction_id')->flatten();
                $query = $query->whereIn('id', $asset_transaction_id);
               
            }
            $transaction = $query->get();
            $result['data'] = $transaction;
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
           
            'note.max' => 'Tối đa 255 kí tự', 
          
        ];
        $validator = Validator::make($request->all(), [
            
            'note' => 'max:255',

        ], $meesage);
        $fields = $request->all();
        $failed = $validator->fails();
        $isErr = false;
        $item = $fields['asset_transaction_items'];
        foreach ($item as $value) {
            if($value['type']==1){
                $check_cancel= AssetTool::where('id',$value['objectable_id'])->where('quantity','>=',$value['quantity'])->get();
                
                if($check_cancel->toArray()==[]){
                         $result['data']['message'] = "Số lượng nhập đã vượt quá số lượng trong kho, Vui lòng nhập lại";
                    $validator->errors()->add('input', 'Số lượng nhập đã vượt quá số lượng trong kho, Vui lòng nhập lại');
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
                $fields['trans_type'] = 3;
                $fields['user_id'] = null;
                $re = AssetTransaction::create($fields);
                $id_item = $re->id;
                $usee = $re->user_id;

                if ($re) {
                    $item = $fields['asset_transaction_items'];
                    foreach ($item as $value) {
                        $type = $value['type'];
                        $quantity_cancel='';
                        if ($type == 0) {
                            $classModel = Asset::class;

                        } else if ($type == 1) {
                            $classModel = AssetTool::class;
                            $asset_item = AssetTool::where('id', $value['objectable_id'])->get();
                            foreach ($asset_item as $item_tool) {
                                $quantity_cancel = $item_tool['quantity'] ;
                              
                            }
                            $use_tools= AssetTool::where('id', $value['objectable_id'])->update(['quantity' => $quantity_cancel- $value['quantity']]);

                        }
                        $tran = AssetTransactionItem::create([
                            'asset_status_id' => $value['asset_status_id'],
                            'asset_transaction_id' => $id_item,
                            'objectable_id' => $value['objectable_id'],
                            'objectable_type' => $classModel,
                            'quantity' => $value['quantity'],
                            'asset_warehouse_id' => $value['asset_warehouse_id'],
                        ]);
                        $use= Asset::where('user_id', $usee)->where('id', $value['objectable_id'])->update(['asset_status_id' => 3 ]);
                       
                    }

                    $result['data']['success'] = 1;
                    $result['data']['data'] = $re;

                }
                $re->load('AssetTransactionItems');
                $re->load('Asset');
                $re->load('user');
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
        $AssetTransaction = AssetTransaction::with(['user', 'AssetPolicy', 'AssetTransactionItems'])->find($id);
        $result = array();
        $result['data'] = array();
        // $result['data'] =   $payrequestBase;
        $result['data']['success'] = 0;

        try {

            DB::beginTransaction();
            foreach ($AssetTransaction->AssetTransactionItems as $term) {
                $quantity_recancel='';

                $use= Asset::where('id', $term['objectable_id'])->update(['asset_status_id' => 1 ]);
                $asset_items = AssetTool::where('id', $term['objectable_id'])->get();
                
                foreach ($asset_items as $item_tool) {
                    $quantity_recancel = $item_tool['quantity'] ;
                    $use_tools= AssetTool::where('id', $term['objectable_id'])->update(['quantity' => $quantity_recancel+ $term['quantity']]);

                }


                $term->delete();
                
            }
            $result['data']['success'] = 1;
            $AssetTransaction->delete();
            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            $result['data']['errors'] = $e->getMessage();
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
}
