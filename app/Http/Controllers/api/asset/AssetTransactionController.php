<?php

namespace App\Http\Controllers\api\asset;

use App\Http\Controllers\Controller;
use App\Mail\EmailNoti;
use App\Models\asset\Asset;
use App\Models\asset\AssetTool;
use App\Models\asset\AssetTransaction;
use App\Models\asset\AssetTransactionItem;
use App\Models\asset\AssetUse;
use App\Models\asset\AssetWarehouse;
use App\Notifications\CommondNotification;
use App\Notifications\NotiBaseModel;
use App\Ultils\Ultils;
use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AssetTransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request,$page)
    {
        $perPage = $request->get('per_page', 10);
        $query = AssetTransaction::query()->with(['AssetTransactionItems.History', 'createBy.groups', 'user', 'AssetTransactionItems.user', 'AssetTransactionItems.Department', 'AssetTransactionItems.objectable', 'AssetTransactionItems.AssetWarehouse.group.users', 'AssetWarehouse', 'Department', 'assetdable.AssetWarehouse.group.users'])->orderByDesc('updated_at');
        $result = array();
        $result['data'] = array();
        $user = auth()->user();
      
        try {
            $array_user_id = explode(',', $request->user_id);

            $query->where(function ($query) use ($user) {
                $query->whereHas('AssetTransactionItems.AssetWarehouse.group.users', function ($q) use ($user) {
                    $q->where('users.id', $user->id);
                })->orWhere(function ($query) use ($user) {
                    $query->whereNotNull('assetdable_id')
                        ->orWhereHasMorph('assetdable', '*', function ($q) use ($user) {
                            $q->whereHas('AssetWarehouse.group.users', function ($subQuery) use ($user) {
                                $subQuery->where('users.id', $user->id);
                            });
                        });
                });
            });
            if ($request->filled('user_id')) {
                $query = $query->whereIn('user_id', $array_user_id);
            }
            if ($request->filled('trans_type')) {
                $query = $query->where('trans_type', $request->trans_type);
            }
            if ($request->filled('create_by')) {
                $query = $query->where('create_by', $request->create_by);
            }
            if ($request->filled('search')) {
                $query = $query->where(function ($subQuery) use ($request) {
                    $subQuery->where('note', 'like', '%' . $request->search . '%')
                        ->orWhere('id', 'like', '%' . $request->search . '%')
                        ->orWhere('date_transaction', 'like', '%' . $request->search . '%')
                        ->orWhereHas('user', function ($q) use ($request) {
                            $q->where('name', 'like', '%' . $request->search . '%');
                        })
                        ->orWhereHas('AssetTransactionItems.user', function ($q) use ($request) {
                            $q->where('name', 'like', '%' . $request->search . '%');
                        })
                        ->orWhereHas('AssetTransactionItems', function ($q) use ($request) {
                            $q->whereHasMorph('objectable', '*', function ($q) use ($request) {
                                $q->where('name', 'like', '%' . $request->search . '%')
                                    ->orWhere('asset_code', 'like', '%' . $request->search . '%')
                                    ->orWhere('sap_code', 'like', '%' . $request->search . '%');
                            });
                        })
                        ->orwhereHasMorph('assetdable', '*', function ($q) use ($request) {
                            $q->where('name', 'like', '%' . $request->search . '%')
                            ->orWhere('asset_code', 'like', '%' . $request->search . '%')
                            ->orWhere('sap_code', 'like', '%' . $request->search . '%')
                            ->orwhere('seri', 'like', '%' . $request->search . '%');
                        });
                });
            }
            if ($request->filled('asset_policy_id')) {
                $query = $query->where('asset_policy_id', $request->asset_policy_id);
            }
            if ($request->filled('note')) {
                $query = $query->where('note', $request->note);
            }
            if ($request->filled('asset_warehouse_id')) {
                $item_warehouse = AssetTransactionItem::query();
                $item_warehouse = $item_warehouse->where('asset_warehouse_id', $request->asset_warehouse_id);
                $asset_transaction_id = $item_warehouse->get()->pluck('asset_transaction_id')->flatten();
                $query = $query->whereIn('id', $asset_transaction_id);

            }
            if ($request->filled('start_date')) {
                $start_date = Carbon::create($request->start_date);
                $query = $query->where('date_transaction', '>=', $start_date->startOfDay());

                if ($request->filled('end_date')) {
                    $end_date = Carbon::create($request->end_date);
                    $query = $query->where('date_transaction', '<=', $end_date->endOfDay());
                } else {

                    $not_enddate = Carbon::create($start_date->year + 1, 1, 1)->subDay();

                    $query = $query->whereBetween('date_transaction', [$start_date, $not_enddate]);
                }
            } elseif ($request->filled('end_date')) {
                $end_date = Carbon::create($request->end_date);
                $not_enddate = Carbon::create($end_date->year, 1, 1)->subDay();
                $query = $query->whereBetween('date_transaction', [$not_enddate, $end_date->endOfDay()]);
            }

            // $transaction = $query->get();
            $transaction = $query->paginate($perPage, ['*'], 'page', $page);
            // $asset = $query->get();

            $result['data'] = $transaction->items();
            $result['paginate'] = [
                'current_page' => $transaction->currentPage(),
                'last_page' => $transaction->lastPage(),
                'total' => $transaction->total(),
            ];

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
        $transaction = AssetTransaction::all();
        $transaction = AssetTransactionItem::all();

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
        $result = array();
        $result['data'] = array();
        $result['data']['success'] = 0;
        date_default_timezone_set("Asia/Ho_Chi_Minh");
        $meesage = [

            // 'user_id.required' => 'Chưa chọn người sử dụng',
            'note.max' => 'Tối đa 255 kí tự',
        ];
        $validator = Validator::make($request->all(), [
            // 'user_id' => 'required',
            'note' => 'max:255',

        ], $meesage);
        $fields = $request->all();
        $failed = $validator->fails();
        $isErr = false;

        if (isset($fields['user_id'])) {
            $check_user_id_active = User::where('id', $fields['user_id'])->where('active', '1')->get();
            if ($check_user_id_active->toArray() == []) {
                $result['data']['message'] = "Người dùng không hoạt động";
                $validator->errors()->add('user_id', "Người dùng không hoạt động");
                $isErr = true;
            }
        }

        if (isset($fields['department_id']) == false && isset($fields['user_id']) == false) {
            $result['data']['message'] = " Chọn người dùng hoặc chọn phòng ban, một trong hai cột không được để trống";
            $validator->errors()->add('department_id', "Chọn người dùng hoặc chọn phòng ban, một trong hai cột không được để trống");
            $isErr = true;
        }
        $vali = $fields['asset_transaction_items'];
        if ($vali == null) {
            $result['data']['message'] = " Vui lòng chọn danh sách ";
            $validator->errors()->add('items', " Vui lòng chọn danh sách");
            $isErr = true;
        }
        foreach ($vali as $value) {
            $use_waiting = AssetUse::where('asset_warehouse_id', $value['asset_warehouse_id'])->
                where('time_complete_inven', '!=', null)->
                where('time_complete_inven', '>', Carbon::now())->get();
            $warehouese = AssetWarehouse::where('id', $value['asset_warehouse_id'])->find($value['asset_warehouse_id']);
            if ($use_waiting->toArray() != []) {
                $result['data']['message'] = "Kho " . $warehouese['name'] . " đang trong qúa trình Kiểm kê";
                $validator->errors()->add('waiting', 'Kho ' . $warehouese['name'] . ' đang trong qúa trình Kiểm kê');
                $isErr = true;
            }
            if ($value['quantity'] < 0) {
                $result['data']['message'] = $value['quantity'] . " Không được nhập số âm";
                $validator->errors()->add('quantity', $value['quantity'] . ' Không được nhập số âm');
                $isErr = true;
            }
            if ($value['quantity'] > $value['sloc']) {
                $result['data']['message'] = "Lỗi, Số lượng bàn giao phải nhỏ hơn hoặc bằng số lượng còn lại";
                $validator->errors()->add('quantity', 'Lỗi, Số lượng bàn giao phải nhỏ hơn hoặc bằng số lượng còn lại');
                $isErr = true;
            }

            $asset_waiting = Asset::where('id', $value['objectable_id'])->get();
            if ($value['type'] == 0) {
                foreach ($asset_waiting as $wai) {
                    if ($wai['waiting'] == 1) {
                        $result['data']['message'] = $value['name'] . " Đang chờ người dùng xác nhận";
                        $validator->errors()->add('type', $value['name'] . " Đang chờ người dùng xác nhận");
                        $isErr = true;
                    }
                    if ($wai['department_id'] !== null) {
                        $result['data']['message'] = $value['name'] . " Đã bàn giao cho phòng ban " . $wai['department']->name;
                        $validator->errors()->add('type', $value['name'] . " Đã bàn giao cho phòng ban " . $wai['department']->name);
                        $isErr = true;
                    }
                }
            }
            $asset_tool_sloc = AssetTool::where('id', $value['objectable_id'])->get();
            if ($value['type'] == 1) {
                foreach ($asset_tool_sloc as $tool_sloc) {
                    $name = $tool_sloc['name'];
                    $quantity = $tool_sloc['quantity'];
                    if ($value['quantity'] > $tool_sloc['quantity'] && $value['type'] == 1) {
                        $result['data']['message'] = "\"$name\" Số lượng khả dụng còn lại: $quantity " . "<br> Vui lòng tải lại trang";
                        $validator->errors()->add('type', " $name Số lượng khả dụng còn lại: $quantity " . "<br>  Vui lòng tải lại trang");
                        $isErr = true;
                    }
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
                $fields['trans_type'] = 1;

                if ($fields['check_confirm'] == true) {
                    $fields['confirm'] = 3;
                } else {
                    $fields['confirm'] = 0;
                }
                $re = AssetTransaction::create($fields);
                $id_item = $re->id;
                $user_use = $re->user_id;
                $create_by = $re->create_by;
                $department = $re->department_id;

                if ($re) {
                    $item = $fields['asset_transaction_items'];

                    foreach ($item as $value) {

                        $type = $value['type'];

                        if ($type == 0) {
                            $classModel = Asset::class;
                            if ($department == null) {
                                $waiting = Asset::where('id', $value['objectable_id'])->update(['waiting' => 1]);
                            }

                            $use = Asset::where('id', $value['objectable_id'])->update(['user_id' => $user_use, 'department_id' => $department]);
                            $quantity_asset = 1;
                        } else if ($type == 1) {
                            $classModel = AssetTool::class;
                            $asset_item = AssetTool::where('id', $value['objectable_id'])->get();
                            foreach ($asset_item as $item_tool) {
                                $quantity_asset = $item_tool['quantity'];
                            }
                        }

                        // dd($classModel );
                        $tran = AssetTransactionItem::create([
                            'asset_status_id' => $value['asset_status_id'],
                            'asset_transaction_id' => $id_item,
                            'objectable_id' => $value['objectable_id'],
                            'objectable_type' => $classModel,
                            'quantity' => $value['quantity'],
                            'department_id' => $department,
                            'user_id' => $user_use,
                            'asset_warehouse_id' => $value['asset_warehouse_id'],
                            'quantity_sloc' => $quantity_asset,

                        ]);
                        $house = AssetTransaction::where('id', $id_item)->update(['asset_warehouse_id' => $value['asset_warehouse_id']]);
                        $asset_item = AssetTool::where('id', $value['objectable_id'])->get();
                        $update_quantity_tool = 0;
                        foreach ($asset_item as $item_tool) {
                            $update_quantity_tool = $item_tool['quantity'] - $value['quantity'];
                            $item_tool->update(['quantity' => $update_quantity_tool]);
                        }
                        if ($department) {
                            $all_usess = AssetUse::all();
                            $sum = 0;
                            foreach ($all_usess as $tess_use) {
                                if ($tess_use['department_id'] == $department && $tess_use['objectable_id'] == $value['objectable_id'] && $tess_use['objectable_type'] == 'App\\Models\\asset\\AssetTool') {
                                    $sum = $tess_use['quantity'] + $value['quantity'];
                                }
                            }

                            // Cập nhật số lượng or tạo mới khi bàn giao giống filed
                            $ums = AssetUse::where('department_id', $department)
                                ->where('objectable_id', $value['objectable_id'])
                                ->where('objectable_type', 'App\\Models\\asset\\AssetTool')->first();
                            if ($ums !== null) {
                                $ums->update(['quantity' => $sum, 'date_transaction' => $re->date_transaction]);
                            } else {
                                $umsc = AssetUse::create([
                                    'user_id' => $user_use,
                                    'objectable_id' => $value['objectable_id'],
                                    'objectable_type' => $classModel,
                                    'asset_warehouse_id' => $value['asset_warehouse_id'],
                                    'quantity' => $value['quantity'],
                                    'department_id' => $department,
                                    'date_transaction' => $re->date_transaction,
                                    'create_by' => $create_by,
                                ]);
                            }

                        }

                    }

                    $result['data']['success'] = 1;
                    $result['data']['data'] = $re;

                    //Gui email cho user nhận

                    if ($re->user) {
                        $data = new NotiBaseModel;
                        $data->title = "Xác nhận tài sản/CCDC ";
                        $data->icon = "fas fa-briefcase";
                        $data->content = "Xác nhận tài sản/CCDC";
                        $data->content_full = "Xác nhận tài sản/CCDC";
                        $data->url = URL('/') . '/' . Ultils::$URL_ASSET_CONFIRM . $re->id;
                        $data->object_type = AssetTransaction::class;
                        $data->object_id = $re->id;
                        // if($re->user == null){
                        //     $re->user = "demo";
                        // }
                        $email = new EmailNoti($data, $re->user);
                        $re->createBy->notify(new CommondNotification($data, $re->user, $email));
                        // dd($email);
                    }

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
        $AssetTransaction = AssetTransaction::with(['user', 'AssetPolicy', 'AssetTransactionItem'])->find($id);
        $result = array();
        $result['data'] = array();
        $result['data'] = $AssetTransaction;
        $result['data']['success'] = 1;

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $AssetTransaction = AssetTransaction::with(['user', 'AssetPolicy', 'AssetTransactionItems'])->find($id);
        foreach ($AssetTransaction->AssetTransactionItems as $value) {

            $value->name = $value->objectable->name;
            $value->type = $value->objectable->assetType->type;
            // $value->name Lấy tên trong tính đa hình objectable ( tìm hiểu)
            // $value->type tạo cột mới căn cứ theo bảng b-table điều kiện type chưa có trong DB
        }
        $result = array();
        $result['data'] = array();
        $result['data'] = $AssetTransaction;
        $result['data']['success'] = 1;
        if (!$AssetTransaction) {
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
        $result = array();
        $result['data'] = array();
        $result['data']['success'] = 0;
        date_default_timezone_set("Asia/Ho_Chi_Minh");
        $meesage = [

            'user_id.required' => 'Chưa chọn người sử dụng',
            'note.max' => 'Tối đa 255 kí tự',
        ];
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'note' => 'max:255',

        ], $meesage);
        $fields = $request->all();
        $failed = $validator->fails();
        $isErr = false;
        if ($failed || $isErr) {
            $result['data']['errors'] = $validator->errors();
        } else {
            try {
                $AssetTransaction = AssetTransaction::with(['user', 'AssetPolicy', 'AssetTransactionItems'])->find($id);
                $item_id = $AssetTransaction->id;
                if ($AssetTransaction) {
                    $AssetTransaction->id = $AssetTransaction->id;
                    $AssetTransaction->user_id = $request->input('user_id');
                    $AssetTransaction->create_by = $request->create_by;
                    $AssetTransaction->asset_policy_id = $request->input('asset_policy_id');
                    $AssetTransaction->note = $request->input('note');
                    if ($AssetTransaction->save()) {
                        $result['data']['success'] = 1;
                    }

                }
                if ($AssetTransaction) {
                    $item_transaction = $fields['asset_transaction_items'];
                    $item_del = $fields['asset_transaction_items_del'];

                    foreach ($item_del as $value) {
                        if (isset($value['id']) && $value['id'] != '') {
                            $tran_item = AssetTransactionItem::find($value['id']);
                            // dd($tran_item);
                            if ($tran_item != null) {
                                $tran_item->delete();
                            }
                        }
                    }
                    foreach ($item_transaction as $value) {
                        if (isset($value['id']) && $value['id'] != '') {
                            $item = AssetTransactionItem::find($value['id']);
                            $item->fill($value);
                            $item->save();
                        } else {
                            $type = $value['type'];

                            if ($type == 0) {
                                $classModel = Asset::class;

                            } else if ($type == 1) {
                                $classModel = AssetTool::class;
                            };
                            $abc = AssetTransactionItem::create([
                                'asset_status_id' => $value['asset_status_id'],
                                'asset_transaction_id' => $item_id,
                                'objectable_id' => $value['objectable_id'],
                                'objectable_type' => $classModel,
                                'quantity' => $value['quantity'],

                            ]);
                        };
                    }
                    $result['data'] = $AssetTransaction;
                }
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

        $AssetTransaction = AssetTransaction::with(['user', 'AssetPolicy', 'AssetTransactionItems'])->find($id);

        $result = array();
        $result['data'] = array();
        // $result['data'] =   $payrequestBase;
        $result['data']['success'] = 0;
        $isErr = false;
        $message = '';

        if ($AssetTransaction->confirm == 1) {
            $result['data']['errors'] = 'Người dùng đã xác nhận, Vui lòng tải lại trang';
            $isErr = true;

        } else {

            try {
                DB::beginTransaction();

                foreach ($AssetTransaction->AssetTransactionItems as $term) {
                    // dd($term->objectable_id);
                    $term->delete();
                    if ($term['objectable_type'] == Asset::class) {
                        $aset_use = Asset::where('id', $term->objectable_id)->update(['waiting' => 0, 'user_id' => null]);
                        // $aset_use->delete();
                    }
                    if ($term['objectable_type'] == AssetTool::class) {
                        $asset_tool = AssetTool::where('id', $term->objectable_id)->get();
                        $update_quantity_tool = 0;
                        foreach ($asset_tool as $item_tool) {
                            $update_quantity_tool = $item_tool['quantity'] + $term['quantity'];
                            $item_tool->update(['quantity' => $update_quantity_tool]);
                        }
                        // $aset_use->delete();
                    }

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
