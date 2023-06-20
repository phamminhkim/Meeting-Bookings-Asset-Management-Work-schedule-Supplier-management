<?php

namespace App\Http\Controllers\api\sloc;

use App\Http\Controllers\Controller;
use App\Models\sloc\Gooddocu;
use App\Models\sloc\GooddocusDetail;
use App\Models\shared\Approved;
use App\Models\shared\DocumentType;
use App\Models\asset\AssetWarehouse;
use App\Models\asset\AssetTool;
use App\Models\asset\Asset;
use App\Models\asset\AssetType;
use Illuminate\Database\Eloquent\Builder;
use App\Repositories\payment\PayrequestMain;
use App\Models\payment\PaymentAttached;
use App\Repositories\DocumentSerials;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Support\Facades\DB;
use Exception;


class GoodDocusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query= Gooddocu::query()->with(['user','GooddocusDetails']);
        $result = array();
        $result['data'] = array();

        $role = User::find(auth()->user()->id);
        // if($request->filled('type')){
               

        try{
            
            if($request->filled('sloc_id')){
                $query = $query->where('sloc_id', $request->sloc_id );
            }  
            if($request->filled('serial_num')){
                // dd($query->get('serial_num'));
                $query = $query->where('serial_num', $request->serial_num );
            } 
            if ($request->filled('user_id') && $request->user_id != "undefined" && $request->user_id != "null") {
                $query = $query->whereIn('user_id', explode(",", $request->user_id));
            }
            // if($request->filled('user_id')){
            //     $query = $query->where('user_id', $request->user_id );
            // } 
            if($request->filled('type')){
                $query = $query->where('type', $request->type );
            }               
            $goods = $query->get();              
            $result['data'] = $goods;
            $result['success'] = "1";
        }catch (Exception $e) {
            $this->errors = $e->getMessage();
        }
        
     
        
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    public function create()
    {
        $department = GooddocusDetail::all();

        $department = Gooddocu::all();

        $result = array();
        $result['data'] =  array();
        $result['data']['success']  = 0;
        date_default_timezone_set("Asia/Ho_Chi_Minh");
        
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

      $messages=[
        'sloc_id.required' => 'Kho Không được để trống',
        'shipper_name.required' => 'Người giao hàng không được để trống',
      ];
        $validator = Validator::make($request->all(), [
            'sloc_id' => 'required|integer',
            'shipper_name' => 'required',
        ], $messages);
        
        $failed = $validator->fails();
        $fields = $request->all();
        $isErr = false;
      

        $call_tooll = AssetTool::all();
        $goods = $fields['gooddocus_details'];
        for ( $i = 0; $i < count($goods); $i++){
           
            foreach ($call_tooll as $tool){
                if($fields['type'] == 1 ) {

                if($tool->id == $goods[$i]["objectable_id"]){
                    if($goods[$i]["quantity"]>$tool->quantity){
                        $result['data']['message']  = "Vui lòng nhập lại";
                        $validator->errors()->add('quantity',"Số lượng của $tool->name vượt mức số lượng tồn");
                        $isErr = true;
                    }
    
                }
            }
            }
        }

       
           
        if ($failed || $isErr) {
            $result['data']['errors']  = $validator->errors();
        } else {

            try {
                $user_id = new User();
                $user_id = auth()->user();
                $fields['user_id'] = $user_id->id;
                $fields['serial_num'] = "Tạo Mới Chứng Từ";
              
                $re = Gooddocu::create($fields);  
                $warehouse=$re->sloc_id;

               
                $insertid = $re -> id;
                $input = $re->type;
                $sloc = $re->sloc_id;
                if ($re) {
     
                    $goods = $fields['gooddocus_details'];
                         

                    foreach ($goods as $value) {
                        $type = $value['type'];
                        if($type == 1){
                            $classModel = Asset::class;
                           
                         }else if($type == 0){
                            $classModel = AssetTool::class;
                         }
                        GooddocusDetail::create([
                            'good_id' => $value['good_id'],
                            'goodunit_id' => $value['goodunit_id'],
                            'quantity' => $value['quantity'],
                            'unit_price' => $value['unit_price'],
                            'amount' => $value['quantity'],
                            'end_date' => $value['end_date'],
                            'objectable_id' => $value['objectable_id'],

                            'objectable_type' => $classModel,

                            'gooddocu_id' => $insertid

                        ]);
                        $total = 0;
                            $call_tool = AssetTool::all();
                            foreach ($call_tool as $tool){
                                if($tool->id == $value['objectable_id']){
                                    if($input == 0 ) {
                                        $total = $value['quantity'] + $tool->quantity;
                                       
                                       } else {
                                        $total = $tool->quantity - $value['quantity'] ;
                                        
                                       }
                                 $tool_quantity=AssetTool::where('id',$value['objectable_id'])->update(['quantity' => $total]);
                                } 
                        }
                        // $kho = AssetTool::where('id',$value['objectable_id'])->update(['asset_warehouse_id' => $sloc ]);
                        $call_warehouse=AssetTool::where('id',$value['objectable_id'])->update(['asset_warehouse_id'=>$warehouse]);
                        
                    }
                    $result['data']  = $re;
                    //
                    // $result['data']['success']  = 1;
                }
            } catch (\Exception $e) {
                $result['data']['errors']  =  $e->getMessage();
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
       
        // $payRequestModel = Gooddocu::findOrFail($id)->with(['goods','GooddocusDetails'])->get();
        $payRequestModel = Gooddocu::with(['goods','GooddocusDetails','user','goodunit','sloc','warehouses'])->find($id);
        $result = array();
        $result['data'] =  array();
        $result['data'] = $payRequestModel;
        $result['data']['success']  = 1;


        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,$id)
    {

        $payRequestModel = Gooddocu::with(['goods','GooddocusDetails'])->find($id);
        foreach ($payRequestModel->GooddocusDetails as   $value) {
            
            $value->name =  $value->objectable->name;
            $value->type = $value->objectable->assetType->type; 
            // $value->name Lấy tên trong tính đa hình objectable ( tìm hiểu)
            // $value->type tạo cột mới căn cứ theo bảng b-table điều kiện type chưa có trong DB
         }
        $result = array();
        $result['data'] =  array();
        $result['data'] = $payRequestModel;
        $result['data']['success']  = 1;
         if (!$payRequestModel) {
            $result['data']['success']  = 0;
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
        $payRequestModel = Gooddocu::with(['goods','GooddocusDetails'])->find($id);
        
        $result = array();
        $result['data'] = array();
        $result['data'] = $payRequestModel;
        $result['data']['success'] = 0;
        date_default_timezone_set("Asia/Ho_Chi_Minh");
        $messages=[
            'sloc_id.required' => 'Kho Không được để trống',
            'shipper_name.required' => 'Người giao hàng không được để trống',
        ];
        $validator = Validator::make($request->all(), [
            'sloc_id' => 'required|integer',
            'shipper_name' => 'required',
        ], $messages);
       
       
        $failed = $validator->fails();
        $fields = $request->all();
        $isErr = false;
         $call_tooll = AssetTool::all();
        $goods = $fields['gooddocus_details'];
        for ( $i = 0; $i < count($goods); $i++){
           
            foreach ($call_tooll as $tool){
                
                if($fields['type'] == 1 ) {

                if($tool->id == $goods[$i]["objectable_id"]){

                    if($goods[$i]["quantity"]>$tool->quantity){
                        $result['data']['message']  = "Vui lòng nhập lại";
                        $validator->errors()->add('quantity',"Số lượng của $tool->id vượt mức số lượng tồn");
                        $isErr = true;
                    }
    
                }
            }
            }
        }

        if ($failed || $isErr) {
            
            $result['data']['errors']  = $validator->errors();
        } else {  
       
            try {

                $department = Gooddocu::with(['goods','GooddocusDetails'])->find($id);
                $insertid = $department -> id;
                $input = $department->type;
            
                if ($department) {
                    $department->id =  $department->id;
                    $department->sloc_id = $request->input('sloc_id');
                    $department->serial_num = 'Cập Nhật Chứng Từ';
                    $department->shipper_name= $request->input('shipper_name');
                    $department->user_id= $request->input('user_id');
                    $department->type= $request->input('type');
                    $department->reason= $request->input('reason');
                    if($department->save())
                     $result['data']['success']  = 1;
                }
                if ($department){
                    $goods = $fields['gooddocus_details'];
                    $goods_del = $fields['gooddocus_details_del'];

                foreach( $goods_del as $value){
                    if(isset($value['id']) && $value['id'] != '' ){
                        $goodDetail = GooddocusDetail::find($value['id']);
                       if($goodDetail != null) {
                        
                        $goodDetail->delete();
                       }
                        

                     }
                }
            
                foreach ($goods as $value) {
                    $type = $value['type'];
                     
                     if($type == 1){
                        $classModel = Asset::class;
                       
                     }else if($type == 0){
                        $classModel = AssetTool::class;
                     }
                     if (isset($value['id']) && $value['id'] != ''){
                       
                        $gooddoc = GooddocusDetail::find($value['id']);
                        $bandau = $gooddoc->quantity;
                        $dau = $value['quantity'];
                        $ouput = 0;
                        $gooddoc->fill($value);
                        $gooddoc->save();
                        $ware_house=AssetTool::where('id',$value['objectable_id'])->update(['asset_warehouse_id' => $request->input('sloc_id')]);

                       $ouput = $bandau - $dau;
                       
                        $total = 0;
                       
                            $call_tool = AssetTool::all();
                            foreach ($call_tool as $tool){
                                if($tool->id == $value['objectable_id']){
                                    if($input == 0 ) {
                                        // dd($ouput);
                                        $total = $tool->quantity-$ouput;
                                      
                                       } else {
                                        if($ouput + $tool->quantity<0){
                                            
                                                dd("Error");
                                        }else{
                                            $total = $ouput + $tool->quantity;

                                        }

                                     
                                        // $output = $total
                                        
                                       }
                                 $tool_quantity=AssetTool::where('id',$value['objectable_id'])->update(['quantity' => $total]);
                                } 
                              
                        }

                     }else{
                        GooddocusDetail::create([
                            'good_id' => $value['good_id'],
                            'goodunit_id' => $value['goodunit_id'],
                            'quantity' => $value['quantity'],
                            'unit_price' => $value['unit_price'],
                            'amount' => $value['amount'],
                            'end_date' => $value['end_date'],
                            'objectable_id' => $value['objectable_id'],

                            'objectable_type' =>  $classModel ,

                            'gooddocu_id' => $insertid,
                        ]);
                        $total = 0;
                            $call_tool = AssetTool::all();
                           
                            foreach ($call_tool as $tool){

                                if($tool->id == $value['objectable_id']){
                                    if($input == 0 ) {
                                        $total = $value['quantity'] + $tool->quantity;
                                    //    dd($total);
                                       } else {
                                        $total = $tool->quantity - $value['quantity'] ;
                                        
                                       }
                                 $tool_quantity=AssetTool::where('id',$value['objectable_id'])->update(['quantity' => $total]);
                                } 
                              
                        }
                     };
                     
                     
                }
            
                $result['data']  = $department;
            }
                
            } catch (\Exception $e) {
                $result['data']['errors']  =  $e->getMessage();
            }
        }

        // dd("test class");
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    public function destroy($id)
    {

        $payrequestBase = Gooddocu::with(['goods','GooddocusDetails','user'])->find($id);
        $result = array();
        $result['data'] =  array();
        // $result['data'] =   $payrequestBase;
        $result['data']['success'] = 0;

        try {
            
            DB::beginTransaction();
           
           
            foreach ($payrequestBase->GooddocusDetails as $term) {
              
                $input = $payrequestBase->type;
            

                $total = 0;
                $call_tool = AssetTool::all();
               
                foreach ($call_tool as $tool){

                    if($tool->id == $term->objectable_id){
                        if($input == 0 ) {
                            $total =$tool->quantity - $term->quantity;
                           } else {
                            $total = $tool->quantity + $term->quantity ;

                           }
                     $tool_quantity=AssetTool::where('id',$term->objectable_id)->update(['quantity' => $total]);
                    } 
                  
                }
                $term->delete();
            }
        $result['data']['success']  = 1 ;
        $payrequestBase->delete();
        DB::commit();

    } catch (\Exception $e) {
        DB::rollback();
        $result['data']['errors'] = $e->getMessage();
    }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    
}

