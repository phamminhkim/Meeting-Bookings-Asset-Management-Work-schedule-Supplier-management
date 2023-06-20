<?php

namespace App\Http\Controllers\api\asset;

use App\Http\Controllers\Controller;
use App\Models\asset\AssetTool;
use App\Models\asset\AssetTransaction;
use App\Models\asset\AssetTransactionItem;
use App\Models\asset\AssetType;
use App\Models\asset\AssetDetail;
use App\Models\asset\AssetWarehouse;
use App\Models\shared\Image;
use App\Repositories\DocumentSerials;
use App\Ultils\Ultils;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AssetStockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     *
     */
    public function index(Request $request)
    {
        $query = AssetTool::query()->with(['attachment_image', 'AssetType', 'AssetGroup', 'AssetType','AssetDetails','vendor','department','AssetWarehouse.group.users']);
      
        $user = auth()->user(); 
        $result = array();
        $result['data'] = array();
        try {
            
            $query->whereHas('AssetWarehouse.group.users', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            });
            $stock = $query->get();
            $result['data'] = $stock;
            $result['success'] = "1";
        } catch (Exception $e) {
            $result['data']['errors'] = $e->getMessage();
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    public function getPage(Request $request, $page)
    {
        $perPage = $request->get('per_page', 10);
        $query = AssetTool::query()->with(['attachment_image', 'AssetType', 'AssetGroup', 'AssetType','AssetDetails','vendor','department','AssetWarehouse.group.users']);
      
        $user = auth()->user(); 
        $result = array();
        $result['data'] = array();
        try {
            
            $query->whereHas('AssetWarehouse.group.users', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            });
            if($request->filled('search')){
                $query = $query->where('name', 'like', '%' . $request->search . '%');
            }
            if ($request->filled('asset_warehouse_id')) {
                $query = $query->where('asset_warehouse_id', $request->asset_warehouse_id);
            }
            if ($request->filled('asset_group_id')) {
                $query = $query->where('asset_group_id', $request->asset_group_id);
            }
            if ($request->filled('asset_type_id')) {
                $query = $query->where('asset_type_id', $request->asset_type_id);
            }
            $stock = $query->paginate($perPage, ['*'], 'page', $page);
            // $stock = $query->get();
            $newstock= array();
            foreach ($stock as  $valuestock) {
                $data_tool= AssetTool::where('id',$valuestock->id)->select('id')->get();
                foreach ($data_tool as $val) {
                    array_push($newstock,$val);
                }
            }
            $data_excel= AssetDetail::where('objectable_type','App\\Models\\asset\\AssetTool')->get();
            // $data_tool= AssetTool::select('id')->get();
            $data_user=User::all();
            $listUnique = $data_excel->unique(['name']);
          
            $newlist = array();
            foreach ($newstock as  $value) {
                $we=array();
                foreach ($listUnique as  $value2) {
                    $t= $value2->name;
                    $Detail= AssetDetail::where('objectable_type','App\\Models\\asset\\AssetTool')->where('objectable_id',$value->id)
                    ->where('name',$t)->get();
                   foreach ($Detail as  $value3) {
                            $value->$t=$value3->value;
                            array_push($we,$t);
                    }
                    $Detail_null= $listUnique->whereNotIn('name',$we);
                  
                    foreach ($Detail_null as  $value4) {
                        $ts= $value4->name;        
                        $value->$ts=null;
            
                    }
                }
                array_push($newlist,$value);
                }
               
                $result['data'] = $stock->items();
                $result['paginate'] = [
                    'current_page' => $stock->currentPage(),
                    'last_page' => $stock->lastPage(),
                    'total' => $stock->total(),
                ];
            $result['data_excel'] = $newlist;
            $result['data_user'] = $data_user;

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
        $stock = AssetTool::all();

        $result = array();
        $result['data'] = array();
        $result['data']['success'] = 0;
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
        $user = new User();
        $user = auth()->user();
        $result = array();
        $result['data'] = array();
        $result['data']['success'] = 0;
        date_default_timezone_set("Asia/Ho_Chi_Minh");
        $meesage = [
            'asset_type_id.required' => 'Model tài sản không được để trống',

            'name.required' => 'Tên không được để trống',
            'asset_warehouse_id.required' => 'Kho hàng không được để trống',
            'asset_group_id.required' => 'Nhóm tài sản không được để trống',
            'sap_code.max' => 'Tối đa 50 kí tự',

            'name.max' => 'Tối đa 150 kí tự',
            'hashtag.max' => 'Tối đa 255 kí tự',
            'producer.max' => 'Tối đa 150 kí tự',
            'amount.max' => 'Tối đa 11 kí tự',
            'quantity.integer' => 'Vui lòng nhập số',
            'description.max' => 'Tối đa 255 kí tự',

        ];
        $validator = Validator::make($request->all(), [
            'asset_type_id' => 'required',
            'sap_code' => 'max:50',

            'name' => 'required|max:150',
            'asset_warehouse_id' => 'required',
            'asset_group_id' => 'required',
            'hashtag' => 'max:255',
            'producer' => 'max:150',
            'amount' => 'max:11',
            'quantity' => 'integer',
            'description' => 'max:255',

        ], $meesage);

        $failed = $validator->fails();
        $isErr = false;
        $fields = $request->all();
        // if ($request->amount == 0) {
        //     $result['data']['message'] = " Chưa nhập giá mua";
        //     $validator->errors()->add('amount', ' Chưa nhập giá mua');
        //     $isErr = true;
        // }
     
        if ($failed || $isErr) {
            $result['data']['errors'] = $validator->errors();
        } else {
            try {

                DB::beginTransaction();

                $fields = $request->all();
                $fields['asset_status_id'] = 1;
                $user = new User();
                $user = auth()->user();
                $fields['sloc_tool'] = $fields['quantity'];
                $fields['create_by'] = $user->id;
               
                // $car->serial_num = DocumentSerials::getSerial(Ultils::$MODULE_CARS, $documentType->code,$car->company_id,
                // Ultils::$MODULE_FORMAT_SERIAL_NUMBER, true);
          
                $company_id=AssetWarehouse::find($fields['asset_warehouse_id']);
              
                $serial_num = DocumentSerials::getSerial(Ultils::$MODULE_ASSET,'CCDC',$company_id->company_id,Ultils::$MODULE_FORMAT_SERIAL_NUMBER, true);
                $fields['asset_code']=$serial_num;
               
                $re = AssetTool::create($fields);
                if ($re) {
                    
                 
                    $attachment_image = $fields['attachment_image'];
                    // php artisan storage:link
                    for ($i = 0; $i < count($attachment_image); $i++) {
                        $imgg = new Image();
                        $imgg->name = $attachment_image[$i]["name"];
                        $imgg->size = $attachment_image[$i]["size"];
                        $imgg->user_id = $user->id;
                        $te = date("Y");
                        $t = date("m");

                        Storage::makeDirectory("/stockImg/$te/$t/");
                        $isXe = false;
                        $strDate = date("Y") . "/" . date("m") . "/" . date("d");
                        $unique = uniqid();
                        $ext = substr($attachment_image[$i]["name"], strrpos($attachment_image[$i]["name"], '.') + 1);
                        // $name = "/public/assetImg/" . $strDate . "/" . $unique . '.' . $ext;
                        $name = "/imageData/" . $unique . '.' . $ext;

                        $imgg->ext = $ext;
                        $imgg->url = $name;
                        // $imgg->ext = $ext;
                        // $imgg->url = $name;
                        $re->attachment_image()->save($imgg);
                        $extension = explode('/', explode(':', substr($attachment_image[$i]['base64'], 0, strpos($attachment_image[$i]['base64'], ';')))[1])[1];
                        $base64_str = substr($attachment_image[$i]['base64'], strpos($attachment_image[$i]['base64'], ",") + 1);
                        $image = str_replace($base64_str, '', $attachment_image[$i]);
                        $image = str_replace(' ', '+', $image);
                        $image = base64_decode($base64_str);
                        // Image::ensureDirectoryExists();
                        $imageName = Str::random(10) . '.' . $extension;

                        file_put_contents(public_path() . $name, $image);

                    }
                    $item_model_asset = $fields['asset_details'];
                   
                    foreach($item_model_asset as $item){
                        AssetDetail::create([
                            'objectable_id' => $re->id,
                            'asset_type_id' => $re->asset_type_id,
                            'objectable_type' => AssetTool::class,
                            'name' => $item['name'],
                            'value' => $item['value']
                        ]);
                    }
                }DB::commit();
                $re->load("AssetDetails");
                $re->load('attachment_image');

                $result['data']['success'] = 1;
                $result['data'] = $re;
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
        $stock = AssetTool::with(['sloc', 'vendor', 'attachment_image', 'AssetWarehouse','department'])->findOrFail($id);

        $result = array();
        $result['data'] = array();
        $result['data'] = $stock;
        $result['success'] = 1;
        if (!$stock) {
            $result['data']['success'] = 0;
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

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
        $result = array();
        $result['data'] = array();
        $result['data']['success'] = 0;
        date_default_timezone_set("Asia/Ho_Chi_Minh");
        $meesage = [
            'asset_type_id.required' => 'Model tài sản không được để trống',

            'name.required' => 'Tên không được để trống',
            'asset_warehouse_id.required' => 'Kho hàng không được để trống',
            'asset_group_id.required' => 'Nhóm tài sản không được để trống',

            'name.max' => 'Tối đa 150 kí tự',
            'hashtag.max' => 'Tối đa 255 kí tự',
            'producer.max' => 'Tối đa 150 kí tự',
            'amount.max' => 'Tối đa 11 kí tự',
             'quantity.integer' => 'Vui lòng nhập số',
            'description.max' => 'Tối đa 255 kí tự',

        ];
        $validator = Validator::make($request->all(), [
            'asset_type_id' => 'required',

            'name' => 'required|max:150',
            'asset_warehouse_id' => 'required',
            'asset_group_id' => 'required',
            'hashtag' => 'max:255',
            'producer' => 'max:150',
            'amount' => 'max:11',
            'quantity' => 'integer',
            'description' => 'max:255',

        ], $meesage);

        $failed = $validator->fails();
        $isErr = false;
        $fields = $request->all();
        // if ($request->amount == 0) {
        //     $result['data']['message'] = " Chưa nhập giá mua";
        //     $validator->errors()->add('amount', ' Chưa nhập giá mua');
        //     $isErr = true;
        // }
        //     $attachment_image = $fields['attachment_image'];

        //     for ( $i = 0; $i < count($attachment_image); $i++)
        //     if (!isset($attachment_image[$i]["id"])) {
        //         $imgg = new Image();
        //         $imgg->name = $attachment_image[$i]["name"];
        //         $name = "/stockImg/" . $imgg->name;
        //         $imagenew = Image::where('url', $name)->first();

        //    if ($imagenew) {
        //        $result['data']['message']  = "Vui lòng đổi tên khác";
        //        $validator->errors()->add('image', "Hình ảnh $imgg->name đã được sử dụng");
        //        $isErr = true;
        //    }
        //     }
        $check_quan = AssetTool::where('id',$request->id)->get();
        foreach ($check_quan as $value_quan) {
           if($value_quan->quantity != $value_quan->sloc_tool){
            $result['data']['message'] = "Một lượng công cụ dụng cụ ".$value_quan->name." đã được bàn giao, vui lòng không cập nhật";
            $validator->errors()->add('checkquantity', 'Một lượng công cụ dụng cụ '.$value_quan->name.' đã được bàn giao, vui lòng không cập nhật');
            $isErr = true;
           }
        }
        

        if ($failed || $isErr) {
            $result['data']['errors'] = $validator->errors();
        } else {
            // $sloc_quantity= AssetTransactionItem::where('objectable_type','App\Models\asset\AssetTool')->where('objectable_id',$id)->where('user_id','!=',null);
            // $sloc_quantity_null= AssetTransactionItem::where('objectable_type','App\Models\asset\AssetTool')->where('objectable_id',$id)->where('user_id',null);
            // $total_quantity=$sloc_quantity->sum('quantity')-$sloc_quantity_null->sum('quantity');
            DB::beginTransaction();
            $stock = AssetTool::findOrFail($id);
            $stock->asset_type_id = $request->input('asset_type_id');
            $stock->asset_warehouse_id = $request->input('asset_warehouse_id');
            $stock->name = $request->input('name');
            $stock->asset_group_id = $request->input('asset_group_id');
            $stock->vendor_id = $request->input('vendor_id');
            $stock->hashtag = $request->input('hashtag');
            $stock->producer = $request->input('producer');
            $stock->amount = $request->input('amount');
            $stock->quantity = $request->input('quantity');
            $stock->add_date = $request->input('add_date');
            $stock->sap_code = $request->input('sap_code');
            $stock->department_id = $request->input('department_id');
            $stock->sloc_tool = $request->input('quantity');

            $stock->save();
            try {
                if ($stock) {
                    $user = new User();
                    $user = auth()->user();
                    $attachment_image = $fields['attachment_image'];
                    for ($i = 0; $i < count($attachment_image); $i++) {
                        if (!isset($attachment_image[$i]["id"])) {
                            $imgg = new Image();
                            $imgg->name = $attachment_image[$i]["name"];
                            $imgg->size = $attachment_image[$i]["size"];
                            $strDate = date("Y") . "/" . date("m") . "/" . date("d");
                            $unique = uniqid();
                            $imgg->user_id = $user->id;
                            $ext = substr($attachment_image[$i]["name"], strrpos($attachment_image[$i]["name"], '.') + 1);
                            // $name = "/public/assetImg/" . $strDate . "/" . $unique . '.' . $ext;
                            $name = "/imageData/" . $unique . '.' . $ext;

                            $imgg->ext = $ext;
                            $imgg->url = $name;
                            // $imgg->ext = $ext;
                            // $imgg->url = $name;
                            $stock->attachment_image()->save($imgg);
                            $extension = explode('/', explode(':', substr($attachment_image[$i]['base64'], 0, strpos($attachment_image[$i]['base64'], ';')))[1])[1];
                            $base64_str = substr($attachment_image[$i]['base64'], strpos($attachment_image[$i]['base64'], ",") + 1);
                            $image = str_replace($base64_str, '', $attachment_image[$i]);
                            $image = str_replace(' ', '+', $image);
                            $image = base64_decode($base64_str);
                            // Image::ensureDirectoryExists();
                            $imageName = Str::random(10) . '.' . $extension;

                            file_put_contents(public_path() . $name, $image);}
                    }
                    $attachment_image_del = $fields['attachment_image_del'];
                    for ($i = 0; $i < count($attachment_image_del); $i++) {
                        if (isset($attachment_image_del[$i]["id"])) {
                            $imgg = Image::find($attachment_image_del[$i]["id"]);
                            if ($imgg) {
                                unlink(public_path() . $imgg->url);

                                $imgg->delete();

                            }
                        }
                    }
                    $item_model_asset = $fields['asset_details'];
        
                    foreach($item_model_asset as $item){
                        $asset_detail = AssetDetail::find($item['id']);
                      
                        if(isset($asset_detail->id) && $asset_detail->id != ''){
                            $asset_detail->fill($item);
                            $asset_detail->save();
                        } else {
                           $item_detail = AssetDetail::create([
                                'objectable_id' => $stock->id,
                                'objectable_type' => AssetTool::class,
                                'name' => $item['name'],
                                'asset_type_id' => $stock->asset_type_id,

                                'value' => $item['value']
                            ]);
                        }
                    }
                    $item_model_asset_del = $fields['asset_details_del'];
                    foreach($item_model_asset_del as $item_del){
                        if(isset($item_del['id']) && $item_del['id'] != '' ){
                            $TypeDetail = AssetDetail::find($item_del['id']);
                           if($TypeDetail != null) {      
                            $TypeDetail->delete();
                           }
                         }
                    }
                    DB::commit();
                    $stock->load("AssetDetails");
                    $stock->load("attachment_image");
                    $result['data']['success'] = 1;
                    $result['data']['data'] = $stock;
                }

            } catch (\Exception $e) {
                DB::rollback();
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
        $stock = AssetTool::with(['attachment_image','AssetDetails'])->find($id);
        $result = array();
        $result['data'] = array();
        $result['data']['success'] = 0;
        $check_quan = AssetTool::where('id',$id)->get();
        $find_transaction = AssetTransactionItem::where('objectable_id',$id)->where('objectable_type',AssetTool::class)->with(['AssetTransaction'])->get();
        foreach ($check_quan as $value_quan) {
           if($value_quan->quantity != $value_quan->sloc_tool){
            $result['data']['errors'] = 'Một lượng công cụ dụng cụ đã được bàn giao, vui lòng không xóa';
           }
           if($find_transaction->toArray() !=[]){
           
            $result['data']['errors'] = 'CCDC đã có lần giao dịch, vui lòng không xóa';
           
            }
            if($value_quan->quantity == $value_quan->sloc_tool && $find_transaction->toArray() ==[]){
            try {
                // dd($contract->attachment_image());
                DB::beginTransaction();
                foreach ($stock->attachment_image as $imgg) {
                    $imgg = Image::where('imgable_id', $stock->id)->first();
    
                    unlink(public_path() . $imgg->url);
                    $imgg->delete();
    
                }
                foreach($stock->AssetDetails as $item){
                    $item = AssetDetail::where('objectable_id', $stock->id)->first();
                    $item->delete();
                }
                $result['data']['success'] = 1;
                $stock->delete();
                DB::commit();
    
            } catch (\Exception $e) {
                DB::rollback();
                $result['data']['errors'] = $e->getMessage();
            }
           }
        }
      

        return json_encode($result, JSON_UNESCAPED_UNICODE);

    }
    public function gettree(Request $request)
    {
        $detai = AssetTool::all();
        $result = array();
        $result['data'] = $detai;
        if ($request->filled('type')) {

            $ListTools = array();
            $tool = $detai->pluck('asset_type_id')->flatten();
            $tool->sort();
            $tool = array_unique($tool->toArray());
            $tool = AssetType::whereIn('id', $tool)->get();
            foreach ($tool as $key => $t) {
                $Item = array();
                $Item['label'] = $t->id . "-" . $t->name;
                $Item['id'] = "c" . $t->id;
                $Item['children'] = array();
                foreach ($detai as $key => $chil) {
                    if ($chil->asset_type_id == $t->id) {
                        $item['label'] = $chil->name . ' (' . $chil->producer . ')';
                        $item['id'] = $chil->id;
                        array_push($Item['children'], $item);
                    }

                }

                array_push($ListTools, $Item);
                // dd($ListTools);

            }
            $detai = $ListTools;
        }
        if ($result) {
            $status = "1";
            $message = "Thành công";
        } else {
            $status = "0";
            $message = "Không thành công";
        }
        return json_encode(['success' => $status, 'message' => $message, 'data' => $detai], JSON_UNESCAPED_UNICODE);
    }
    public function cothebangiao(Request $request, $page)
    {
        $perPage = $request->get('per_page', 10);
        $query = AssetTool::query()->where('quantity', '>', 0)->with(['attachment_image', 'AssetType', 'AssetGroup','AssetWarehouse.group.users']);

        $result = array();
        $result['data'] = array();
        $user = auth()->user(); 
        try {
            $query->whereHas('AssetWarehouse.group.users', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            });
            if ($request->filled('asset_warehouse_id')) {
                $query = $query->where('asset_warehouse_id', $request->asset_warehouse_id);
            }
            if ($request->filled('asset_group_id')) {
                $query = $query->where('asset_group_id', $request->asset_group_id);
            }
            $stock = $query->paginate($perPage, ['*'], 'page', $page);
            // $stock = $query->get();

            $result['data'] = $stock->items();
            $result['paginate'] = [
                'current_page' => $stock->currentPage(),
                'last_page' => $stock->lastPage(),
                'total' => $stock->total(),
            ];
            $result['success'] = "1";
        } catch (Exception $e) {
            $result['data']['errors'] = $e->getMessage();
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);

    }
    public function dahet(Request $request,$page)
    {
        $perPage = $request->get('per_page', 10);
        $query = AssetTool::query()->where('quantity', 0)->with(['attachment_image', 'AssetType', 'AssetGroup','AssetWarehouse.group.users']);

        $result = array();
        $result['data'] = array();
        $user = auth()->user(); 
        try {
            $query->whereHas('AssetWarehouse.group.users', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            });
            if ($request->filled('asset_warehouse_id')) {
                $query = $query->where('asset_warehouse_id', $request->asset_warehouse_id);
            }
            if ($request->filled('asset_group_id')) {
                $query = $query->where('asset_group_id', $request->asset_group_id);
            }
            $stock = $query->paginate($perPage, ['*'], 'page', $page);
            // $stock = $query->get();

            $result['data'] = $stock->items();
            $result['paginate'] = [
                'current_page' => $stock->currentPage(),
                'last_page' => $stock->lastPage(),
                'total' => $stock->total(),
            ];
            $result['success'] = "1";
        } catch (Exception $e) {
            $result['data']['errors'] = $e->getMessage();
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);

    }
    public function lichsubangiao(Request $request)
    {
        DB::beginTransaction();

        $items = AssetTransactionItem::where("objectable_type", 'App\\Models\\asset\\AssetTool')->with(['Department']);
        $list = array();
        $list_ts = array();
        $items_asset=AssetTool::select('id','create_by','created_at','description','sloc_tool')->get();
        foreach ($items_asset as $value_asset) {

            $value_asset->giaodich='Tạo CCDC';
            $value_asset->created_by=$value_asset->create_by;
            $value_asset->created_att=$value_asset->created_at;
            $value_asset->note=$value_asset->description;
            $value_asset->quantityy=$value_asset->sloc_tool;
            array_push($list_ts,$value_asset);
        }
        foreach ($items->get() as $tl) {
            $trans = AssetTransaction::where("id", $tl->asset_transaction_id)->get();
            foreach ($trans as $ts) {
                $tl->giaodich = $ts->trans_type;
                $tl->created_by = $ts->create_by;
                $tl->user_id = $ts->user_id;
                $tl->created_att = $ts->created_at;
                $tl->confirm = $ts->confirm;
                $tl->note = $ts->note;

            }

            $tl->quantityy = $tl->quantity;

            array_push($list, $tl);
        }
        $result = array();
        $result['data'] = array();
        $result['data'] = $list;
        $result['data_ts'] = $list_ts;
        $result['success'] = "1";

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);

    }
    public function changeTool(Request $request, $page)
    {
        $perPage = $request->get('per_page', 10);
        $query = AssetTool::query()->with(['attachment_image', 'AssetType', 'AssetGroup', 'AssetType','AssetDetails','vendor','department','AssetWarehouse.group.users'])
        ->where('quantity','>', 0);
      
        $user = auth()->user(); 
        $result = array();
        $result['data'] = array();
        try {
            
            $query->whereHas('AssetWarehouse.group.users', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            });
            if ($request->filled('name')) {
            
                $query = $query->where('name', $request->name );
            }
            if ($request->filled('groups')) {
            
                $query = $query->where('asset_group_id', $request->groups );
            }
            $stock = $query->paginate($perPage, ['*'], 'page', $page);
            // $stock = $query->get();
            $result['data'] = $stock->items();
            $result['paginate'] = [
                'current_page' => $stock->currentPage(),
                'last_page' => $stock->lastPage(),
                'total' => $stock->total(),
            ];
            $result['success'] = "1";
        } catch (Exception $e) {
            $result['data']['errors'] = $e->getMessage();
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    
}
