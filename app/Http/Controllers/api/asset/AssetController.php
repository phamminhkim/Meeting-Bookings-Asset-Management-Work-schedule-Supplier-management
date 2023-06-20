<?php

namespace App\Http\Controllers\api\asset;

use App\Http\Controllers\Controller;
use App\Models\asset\Asset;
use App\Models\asset\AssetDetail;
use App\Models\asset\AssetTransaction;
use App\Models\asset\AssetTransactionItem;
use App\Models\asset\AssetType;
use App\Models\asset\AssetWarehouse;
use App\Models\shared\Company;
use App\Models\shared\Department;
use App\Models\shared\File;
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
use SoareCostin\FileVault\Facades\FileVault as FacadesFileVault;

class AssetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     *
     */

    public function index(Request $request)
    {
        // dd($page);
        $query = Asset::query()->with(['attachment_image', 'attachment_file', 'AssetGroup', 'AssetType', 'user', 'AssetDetails', 'vendor', 'Department', 'AssetWarehouse.group.users']);
        $result = array();
        $result['data'] = array();
        $user = auth()->user();
        try {

            $query->whereHas('AssetWarehouse.group.users', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            });
            $asset = $query->get();
            $result['data'] = $asset;

            $result['success'] = "1";
        } catch (Exception $e) {
            $result['data']['errors'] = $e->getMessage();
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);

    }
    public function getPage(Request $request, $page)
    {
        $perPage = $request->get('per_page', 10);
        $query = $request->input('q');
        $query = Asset::query()->with(['attachment_image', 'attachment_file', 'AssetGroup', 'AssetType', 'user', 'AssetDetails', 'vendor', 'Department', 'AssetWarehouse.group.users','AssetFixedHistory.objectable','AssetFixedHistory.NewComponent','AssetFixedHistory.OldComponent']);
        $result = array();
        $result['data'] = array();
        $user = auth()->user();
        try {
            $query->whereHas('AssetWarehouse.group.users', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            });
            if ($request->filled('search')) {
                $query->where(function ($q) use ($request) {
                    $q->where('name', 'LIKE', '%' . $request->search . '%')
                        ->orwhere('seri', 'LIKE', '%' . $request->search . '%')
                        ->orwhere('asset_code', 'LIKE', '%' . $request->search . '%')
                        ->orwhere('sap_code', 'LIKE', '%' . $request->search . '%')
                        ->orWhereHas('user', function ($q) use ($request) {
                            $q->where('name', 'LIKE', '%' . $request->search . '%');
                        })
                        ->orWhereHas('Department', function ($q) use ($request) {
                            $q->where('name', 'LIKE', '%' . $request->search . '%');
                        })
                        ->orWhereHas('AssetDetails', function ($q) use ($request) {
                            $q->where('value', 'LIKE', '%' . $request->search . '%');
                        });
                });
            }
            if ($request->filled('asset_warehouse_id')) {
                $query = $query->where('asset_warehouse_id', $request->asset_warehouse_id);
            }

            if ($request->filled('asset_group_id')) {
                $query = $query->where('asset_group_id', $request->asset_group_id);
            }

            if ($request->filled('asset_status_id')) {
                $query = $query->where('asset_status_id', $request->asset_status_id);
            }
            if ($request->filled('asset_type_id')) {
                $query = $query->where('asset_type_id', $request->asset_type_id);
            }

            $asset = $query->paginate($perPage, ['*'], 'page', $page);
            // $asset = $query->get();

            $result['data'] = $asset->items();
            $result['paginate'] = [
                'current_page' => $asset->currentPage(),
                'last_page' => $asset->lastPage(),
                'total' => $asset->total(),
            ];

            $result['success'] = "1";
        } catch (Exception $e) {
            $result['data']['errors'] = $e->getMessage();
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);

    }
    public function getDataExcel(Request $request)
    {
        // dd($page);

        $query = Asset::query()->with(['attachment_image', 'attachment_file', 'AssetGroup', 'AssetType', 'user', 'AssetDetails', 'vendor', 'Department', 'AssetWarehouse.group.users']);
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

            if ($request->filled('asset_status_id')) {
                $query = $query->where('asset_status_id', $request->asset_status_id);
            }

            $asset = $query->get();
            $newasset = array();
            foreach ($asset as $valueasset) {
                $data_asset = Asset::where('id', $valueasset->id)->select('id')->get();
                foreach ($data_asset as $val) {
                    array_push($newasset, $val);
                }
            }
            $data_excel = AssetDetail::where('objectable_type', 'App\\Models\\asset\\Asset')->get();
            $data_excel = $data_excel->map(function ($item, $key) {
                $item->name = strtolower($item->name);
                return $item;
            });
            $listUnique = $data_excel->unique(['name']);
            $newlist = array();
            foreach ($newasset as $value) {
                $we = array();
                foreach ($listUnique as $value2) {
                    $t = $value2->name;
                    $Detail = AssetDetail::where('objectable_type', 'App\\Models\\asset\\Asset')->where('objectable_id', $value->id)
                        ->where('name', $t)->get();
                    foreach ($Detail as $value3) {
                        $value->$t = $value3->value;
                        array_push($we, $t);
                    }
                    $Detail_null = $listUnique->whereNotIn('name', $we);

                    foreach ($Detail_null as $value4) {
                        $ts = $value4->name;
                        $value->$ts = null;

                    }
                }
                array_push($newlist, $value);
            }

            $result = [
                'data' => $asset,
                'data_excel' => $newlist,
                'data_department' => Department::all(),
                'data_user' => User::all(),
                'success' => '1',
            ];
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
        $ass = Asset::all();

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
            'asset_warehouse_id.required' => 'Kho hàng không được để trống',
            'name.required' => 'Tên không được để trống',
            'asset_group_id.required' => 'Nhóm tài sản không được để trống',
            'seri.required' => 'Số seri không được để trống',
            'amount.max' => 'Tối đa 11 kí tự',
            'description.max' => 'Tối đa 255 kí tự',
            'seri.max' => 'Tối đa 100 kí tự',
            'producer.max' => 'Tối đa 150 kí tự',
            'hashtag.max' => 'Tối đa 255 kí tự',
            'sap_code.max' => 'Tối đa 50 kí tự',

        ];
        $validator = Validator::make($request->all(), [
            'asset_type_id' => 'required',
            'asset_warehouse_id' => 'required',
            'name' => 'required',
            'asset_group_id' => 'required',
            'seri' => 'required|max:100',
            'amount' => 'max:11',
            'description' => 'max:255',
            'producer' => 'max:150',
            'hashtag' => 'max:255',
            'sap_code' => 'max:50',
        ], $meesage);

        $failed = $validator->fails();
        $isErr = false;
        $fields = $request->all();
        // if($request->amount == 0){
        //     $result['data']['message']  = " Chưa nhập giá mua";
        //     $validator->errors()->add('amount',' Chưa nhập giá mua');
        //     $isErr = true;
        // }

        $item_custom_field_type = $fields['asset_details'];

        foreach ($item_custom_field_type as $item) {
            if ($item['asset_type_id'] !== $request->asset_type_id) {
                $asset_detail = AssetType::find($item['id']);
                $result['data']['message'] = "Bạn đang lưu cấu hình của Model tài sản: " . $asset_detail->name . ".<br> Vui lòng chọn lại";
                $validator->errors()->add('custom_field', "Bạn đang lưu cấu hình của Model tài sản: " . $asset_detail->name . ".<br> Vui lòng chọn lại");
                $isErr = true;
            }
        }
        if ($request->name) {

            $dep_temp = Asset::where('name', $request->name)->first();

            if ($dep_temp) {
                $result['data']['message'] = "Trùng seri hoặc model, vui lòng nhập lại";
                $validator->errors()->add('seri', 'Trùng seri hoặc model, vui lòng nhập lại');
                $validator->errors()->add('asset_type_id', 'Trùng seri hoặc model, vui lòng nhập lại');

                $isErr = true;
            }
        }
        if ($failed || $isErr) {
            $result['data']['errors'] = $validator->errors();
        } else {
            try {
                DB::beginTransaction();

                $fields['asset_status_id'] = 1; // Trạng thái
                $fields['quantity'] = 1; // Mặc định số lượng = 1
                $fields['waiting'] = 0; // Đang chờ
                $user = new User();
                $user = auth()->user();
                $fields['create_by'] = $user->id;

                $company_id = AssetWarehouse::find($fields['asset_warehouse_id']);

                $serial_num = DocumentSerials::getSerial(Ultils::$MODULE_ASSET, 'TSAN', $company_id->company_id, Ultils::$MODULE_FORMAT_SERIAL_NUMBER, true);
                $fields['asset_code'] = $serial_num;
                $re = Asset::create($fields);
                $re->quantity = 1;
                if ($re) {

                    $attachment_file = $fields['attachment_file'];

                    for ($i = 0; $i < count($attachment_file); $i++) {
                        $file = new File();
                        $file->name = $attachment_file[$i]["name"];
                        $file->size = $attachment_file[$i]["size"];
                        $file->user_id = $user->id;
                        $ext = substr($attachment_file[$i]["name"], strrpos($attachment_file[$i]["name"], '.') + 1);
                        $name = "/assetFile/" . $user->username . "/" . uniqid() . '.' . $ext; // $attachment_file[$i]["ext"];
                        $file->ext = $ext;
                        $file->url = $name;
                        $re->attachment_file()->save($file);
                        $base64_str = substr($attachment_file[$i]['base64'], strpos($attachment_file[$i]['base64'], ",") + 1);
                        $image = base64_decode($base64_str);
                        Storage::disk('local')->put($name, $image);
                        FacadesFileVault::encrypt($name);
                    }

                    $attachment_image = $fields['attachment_image'];
                    for ($i = 0; $i < count($attachment_image); $i++) {
                        $imgg = new Image();
                        $imgg->name = $attachment_image[$i]["name"];
                        $imgg->size = $attachment_image[$i]["size"];
                        $imgg->user_id = $user->id;
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

                        //   Storage::disk('local')->put($name, $image);
                        // FacadesFileVault::encrypt($name);
                        // file_put_contents(storage_path() . "/app" . $name,  $image);
                    }
                    $item_model_asset = $fields['asset_details'];

                    foreach ($item_model_asset as $item) {
                        AssetDetail::create([
                            'objectable_id' => $re->id,
                            'asset_type_id' => $re->asset_type_id,

                            'objectable_type' => Asset::class,
                            'name' => $item['name'],
                            'value' => $item['value'],
                        ]);
                    }
                }
                DB::commit();
                $re->load('AssetDetails');
                $re->load('attachment_image');
                $re->load("attachment_file");

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
        $asset = Asset::with(['warehouses', 'vendors', 'attachment_image', 'attachment_file', 'status', 'group'])->findOrFail($id);
        $result = array();
        $result['data'] = array();
        $result['data'] = $asset;
        $result['success'] = 1;
        if (!$asset) {
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
        $AssetModel = Asset::with(['attachment_image', 'attachment_file', 'AssetGroup', 'AssetType', 'user', 'AssetDetails'])->find($id);

        $result = array();
        $result['data'] = array();
        $result['data'] = $AssetModel;
        $result['data']['success'] = 1;
        if (!$AssetModel) {
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
            'asset_type_id.required' => 'Model tài sản không được để trống',
            'asset_warehouse_id.required' => 'Kho hàng không được để trống',
            'name.required' => 'Tên không được để trống',
            'asset_group_id.required' => 'Nhóm tài sản không được để trống',
            'seri.required' => 'Số seri không được để trống',
            'amount.max' => 'Tối đa 11 kí tự',
            'description.max' => 'Tối đa 255 kí tự',
            'seri.max' => 'Tối đa 100 kí tự',
            'producer.max' => 'Tối đa 150 kí tự',
            'hashtag.max' => 'Tối đa 255 kí tự',
            'sap_code.max' => 'Tối đa 50 kí tự',

        ];
        $validator = Validator::make($request->all(), [
            'asset_type_id' => 'required',
            'asset_warehouse_id' => 'required',
            'name' => 'required',
            'asset_group_id' => 'required',
            'seri' => 'required|max:100',
            'amount' => 'max:11',
            'description' => 'max:255',
            'producer' => 'max:150',
            'hashtag' => 'max:255',
            'sap_code' => 'max:50',
        ], $meesage);
        $failed = $validator->fails();
        $isErr = false;
        $fields = $request->all();
        // if($request->amount == 0){
        //     $result['data']['message']  = " Chưa nhập giá mua";
        //     $validator->errors()->add('amount',' Chưa nhập giá mua');
        //     $isErr = true;
        // }
        if ($request->name) {
            $dep_temp = Asset::where('name', $request->name)->first();

            if ($dep_temp && $dep_temp->id != $id) {
                $result['data']['message'] = "Trùng seri hoặc model, vui lòng nhập lại";
                $validator->errors()->add('seri', 'Trùng seri hoặc model, vui lòng nhập lại');
                $validator->errors()->add('asset_type_id', 'Trùng seri hoặc model, vui lòng nhập lại');

                $isErr = true;
            }
        }
        $check_user_department = Asset::where('id', $request->id)->get();
        foreach ($check_user_department as $value_check) {
            if ($value_check->user_id != null || $value_check->department_id != null) {
                $result['data']['message'] = "Tài sản " . $value_check->name . " đang được bàn giao, vui lòng không cập nhật";
                $validator->errors()->add('check_user_department', 'Tài sản ' . $value_check->name . ' đang được bàn giao, vui lòng không cập nhật');
                $isErr = true;
            }
        }
        if ($failed || $isErr) {
            $result['data']['errors'] = $validator->errors();
        } else {

            try {
                $asset = Asset::with(['attachment_image', 'attachment_file', 'AssetGroup', 'user'])->find($id);
                $item_id = $asset->id;
                if ($asset) {
                    $asset->asset_type_id = $request->input('asset_type_id');
                    $asset->asset_warehouse_id = $request->input('asset_warehouse_id');
                    $asset->name = $request->input('name');
                    $asset->asset_group_id = $request->input('asset_group_id');
                    $asset->vendor_id = $request->input('vendor_id');
                    $asset->seri = $request->input('seri');
                    $asset->hashtag = $request->input('hashtag');
                    $asset->amount = $request->input('amount');
                    $asset->producer = $request->input('producer');
                    $asset->sap_code = $request->input('sap_code');

                    $asset->department_id = $request->input('department_id');

                    $asset->add_date = $request->input('add_date');
                    $asset->end_date = $request->input('end_date');
                    $asset->asset_status_id = $request->input('asset_status_id');
                    $asset->description = $request->input('description');
                    $asset->save();
                }
                if ($asset) {

                    $user = new User();
                    $user = auth()->user();
                    $attachment_file = $fields['attachment_file'];
                    for ($i = 0; $i < count($attachment_file); $i++) {
                        if (!isset($attachment_file[$i]["id"])) {
                            $file = new File();
                            $file->name = $attachment_file[$i]["name"];
                            $file->size = $attachment_file[$i]["size"];
                            $file->user_id = $user->id;
                            $ext = substr($attachment_file[$i]["name"], strrpos($attachment_file[$i]["name"], '.') + 1);
                            $name = "/assetFile/" . $user->username . "/" . uniqid() . '.' . $ext; // $attachment_file[$i]["ext"];
                            $file->ext = $ext;
                            $file->url = $name;
                            $asset->attachment_file()->save($file);
                            $base64_str = substr($attachment_file[$i]['base64'], strpos($attachment_file[$i]['base64'], ",") + 1);
                            $image = base64_decode($base64_str);
                            Storage::disk('local')->put($name, $image);
                            FacadesFileVault::encrypt($name);
                        }
                    }
                    $attachment_file_del = $fields['attachment_file_del'];
                    for ($i = 0; $i < count($attachment_file_del); $i++) {
                        if (isset($attachment_file_del[$i]["id"])) {
                            $file = File::find($attachment_file_del[$i]["id"]);
                            if ($file) {
                                Storage::delete($file->url . ".enc");

                                $file->delete();
                            }
                        }
                    }

                    $attachment_image = $fields['attachment_image'];
                    for ($i = 0; $i < count($attachment_image); $i++) {
                        //Chỉ lưu file mới
                        if (!isset($attachment_image[$i]["id"])) {
                            $imgg = new Image();
                            $imgg->name = $attachment_image[$i]["name"];
                            $imgg->size = $attachment_image[$i]["size"];
                            $imgg->user_id = $user->id;
                            $strDate = date("Y") . "/" . date("m") . "/" . date("d");
                            $unique = uniqid();
                            $ext = substr($attachment_image[$i]["name"], strrpos($attachment_image[$i]["name"], '.') + 1);
                            // $name = "/public/assetImg/" . $strDate . "/" . $unique . '.' . $ext;
                            $name = "/imageData/" . $unique . '.' . $ext;

                            $imgg->ext = $ext;
                            $imgg->url = $name;
                            // $imgg->ext = $ext;
                            // $imgg->url = $name;
                            $asset->attachment_image()->save($imgg);
                            $extension = explode('/', explode(':', substr($attachment_image[$i]['base64'], 0, strpos($attachment_image[$i]['base64'], ';')))[1])[1];
                            $base64_str = substr($attachment_image[$i]['base64'], strpos($attachment_image[$i]['base64'], ",") + 1);
                            $image = str_replace($base64_str, '', $attachment_image[$i]);
                            $image = str_replace(' ', '+', $image);
                            $image = base64_decode($base64_str);
                            // Image::ensureDirectoryExists();
                            $imageName = Str::random(10) . '.' . $extension;

                            file_put_contents(public_path() . $name, $image);

                            //   Storage::disk('local')->put($name, $image);
                            // FacadesFileVault::encrypt($name);
                            // file_put_contents(storage_path() . "/app" . $name,  $image);
                        }
                    }
                    $attachment_image_del = $fields['attachment_image_del'];
                    for ($i = 0; $i < count($attachment_image_del); $i++) {
                        if (isset($attachment_image_del[$i]["id"])) {
                            $imgg = Image::find($attachment_image_del[$i]["id"]);
                            if ($imgg) {
                                unlink(public_path() . $imgg->url);
                                // unlink(public_path() . $imgg->url . ".enc");

                                $imgg->delete();
                            }
                        }
                    }
                    $item_model_asset = $fields['asset_details'];

                    foreach ($item_model_asset as $item) {
                        $asset_detail = AssetDetail::find($item['id']);

                        if (isset($asset_detail->id) && $asset_detail->id != '') {
                            $asset_detail->fill($item);
                            $asset_detail->save();
                        } else {
                            $item_detail = AssetDetail::create([
                                'objectable_id' => $asset->id,
                                'asset_type_id' => $asset->asset_type_id,

                                'objectable_type' => Asset::class,
                                'name' => $item['name'],
                                'value' => $item['value'],
                            ]);
                        }
                    }
                    $item_model_asset_del = $fields['asset_details_del'];
                    foreach ($item_model_asset_del as $item_del) {
                        if (isset($item_del['id']) && $item_del['id'] != '') {
                            $TypeDetail = AssetDetail::find($item_del['id']);
                            if ($TypeDetail != null) {
                                $TypeDetail->delete();
                            }
                        }
                    }
                    DB::commit();
                    $asset->load("AssetDetails");
                    $asset->load("attachment_image");
                    $asset->load("attachment_file");

                    $result['data']['success'] = 1;
                    $result['data']['data'] = $asset;

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
        $asset = Asset::with(['attachment_image', 'attachment_file', 'AssetGroup', 'AssetType', 'AssetDetails'])->find($id);
        $result = array();

        $result['data'] = array();
        $result['data']['success'] = 0;
        $user_id = Asset::where('id', $id)->where('user_id', '!=', null)->get();
        $department_id = Asset::where('id', $id)->where('department_id', '!=', null)->get();
        $isErr = false;
        $find_transaction = AssetTransactionItem::where('objectable_id', $id)
            ->where('objectable_type', Asset::class)->with(['AssetTransaction'])->get();
        if ($find_transaction->toArray() != []) {

            $result['data']['errors'] = 'Tài sản đã có lần giao dịch, vui lòng không xóa';
            $isErr = true;
        }
        if ($user_id->toArray() != [] || $department_id->toArray() != []) {
            $result['data']['errors'] = 'Tài sản đã được giao dịch  , vui lòng không xóa';
            $isErr = true;
        }
        if ($user_id->toArray() == [] && $department_id->toArray() == [] && $find_transaction->toArray() == []) {
            try {
                DB::beginTransaction();
                foreach ($asset->attachment_image as $imgg) {

                    $imgg = Image::where('imgable_id', $asset->id)->first();
                    // unlink(public_path() . $imgg->url . ".enc");
                    unlink(public_path() . $imgg->url);
                    $imgg->delete();
                }
                foreach ($asset->attachment_file as $file) {
                    Storage::delete($file->url . ".enc");

                    $file->delete();
                }
                foreach ($asset->AssetDetails as $item) {
                    $item = AssetDetail::where('objectable_id', $asset->id)->first();
                    $item->delete();
                }
                $result['data']['success'] = 1;
                $asset->delete();
                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
                $result['data']['errors'] = $e->getMessage();
            }

        }

        return json_encode($result, JSON_UNESCAPED_UNICODE);
    }
    public function chuabangiao(Request $request, $page)
    {
        $perPage = $request->get('per_page', 10);
        $query = $request->input('q');
        $query = Asset::query()->with(['attachment_image', 'attachment_file', 'AssetGroup', 'AssetType', 'user', 'AssetDetails', 'Department', 'AssetWarehouse.group.users'])
            ->where('user_id', null)->where('department_id', null);
        $result = array();
        $result['data'] = array();
        $user = auth()->user();

        try {
            $query->whereHas('AssetWarehouse.group.users', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            });
            if ($request->filled('search')) {
                $query->where(function ($q) use ($request) {
                    $q->where('name', 'LIKE', '%' . $request->search . '%')
                        ->orwhere('seri', 'LIKE', '%' . $request->search . '%')
                        ->orwhere('asset_code', 'LIKE', '%' . $request->search . '%')
                        ->orwhere('sap_code', 'LIKE', '%' . $request->search . '%')
                        ->orWhereHas('user', function ($q) use ($request) {
                            $q->where('name', 'LIKE', '%' . $request->search . '%');
                        })
                        ->orWhereHas('Department', function ($q) use ($request) {
                            $q->where('name', 'LIKE', '%' . $request->search . '%');
                        })
                        ->orWhereHas('AssetDetails', function ($q) use ($request) {
                            $q->where('value', 'LIKE', '%' . $request->search . '%');
                        });
                });
            }
            if ($request->filled('asset_warehouse_id')) {
                $query = $query->where('asset_warehouse_id', $request->asset_warehouse_id);
            }
            if ($request->filled('asset_group_id')) {
                $query = $query->where('asset_group_id', $request->asset_group_id);
            }
            if ($request->filled('asset_status_id')) {
                $query = $query->where('asset_status_id', $request->asset_status_id);
            }
            $asset = $query->paginate($perPage, ['*'], 'page', $page);
            // $asset = $query->where('user_id', null)->where('department_id', null)->get();
            $result['data'] = $asset->items();
            $result['paginate'] = [
                'current_page' => $asset->currentPage(),
                'last_page' => $asset->lastPage(),
                'total' => $asset->total(),
            ];
            $result['success'] = "1";
        } catch (Exception $e) {
            $result['data']['errors'] = $e->getMessage();
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    public function dabangiao(Request $request, $page)
    {
        $perPage = $request->get('per_page', 10);
        $query = Asset::query()->with(['attachment_image', 'attachment_file', 'AssetGroup', 'AssetType', 'user', 'AssetDetails', 'Department', 'AssetWarehouse.group.users'])
            ->where('user_id', '!=', null)->orWhere('department_id', '!=', null);
        // $test= Asset::where('user_id', '!=', null)->orWhere('department_id', '!=', null)->get();
        // dd( $test->toArray());
        $result = array();
        $result['data'] = array();
        $user = auth()->user();
        try {
            $query->whereHas('AssetWarehouse.group.users', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            });
            if ($request->filled('search')) {
                $query->where(function ($q) use ($request) {
                    $q->where('name', 'LIKE', '%' . $request->search . '%')
                        ->orwhere('seri', 'LIKE', '%' . $request->search . '%')
                        ->orwhere('asset_code', 'LIKE', '%' . $request->search . '%')
                        ->orwhere('sap_code', 'LIKE', '%' . $request->search . '%')
                        ->orWhereHas('user', function ($q) use ($request) {
                            $q->where('name', 'LIKE', '%' . $request->search . '%');
                        })
                        ->orWhereHas('Department', function ($q) use ($request) {
                            $q->where('name', 'LIKE', '%' . $request->search . '%');
                        })
                        ->orWhereHas('AssetDetails', function ($q) use ($request) {
                            $q->where('value', 'LIKE', '%' . $request->search . '%');
                        });
                });
            }
            if ($request->filled('asset_warehouse_id')) {
                $query = $query->where('asset_warehouse_id', $request->asset_warehouse_id);
            }
            if ($request->filled('asset_group_id')) {
                $query = $query->where('asset_group_id', $request->asset_group_id);
            }
            if ($request->filled('asset_status_id')) {
                $query = $query->where('asset_status_id', $request->asset_status_id);
            }
            $asset = $query->paginate($perPage, ['*'], 'page', $page);
            // $asset = $query->where('user_id', '!=', null)->orWhere('department_id', '!=', null)->get();

            $result['data'] = $asset->items();
            $result['paginate'] = [
                'current_page' => $asset->currentPage(),
                'last_page' => $asset->lastPage(),
                'total' => $asset->total(),
            ];
            $result['success'] = "1";
        } catch (Exception $e) {
            $result['data']['errors'] = $e->getMessage();
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    public function lichsubangiaotaisan(Request $request)
    {
        DB::beginTransaction();
        $list = array();
        $list_ts = array();
        $items_asset = Asset::select('id', 'create_by', 'created_at', 'description')->get();
        foreach ($items_asset as $value_asset) {
            $value_asset->giaodich = 'Tạo tài sản';
            $value_asset->created_by = $value_asset->create_by;
            $value_asset->created_att = $value_asset->created_at;
            $value_asset->note = $value_asset->description;
            array_push($list_ts, $value_asset);
        }
        $items = AssetTransactionItem::with(['History'])->where("objectable_type", 'App\\Models\\asset\\Asset')
                                        ->orwhere("objectable_type", 'App\\Models\\asset\\AssetTool')->get();
        // dd($items);
        foreach ($items as $tl) {
            $trans = AssetTransaction::where("id", $tl->asset_transaction_id)->get();
            foreach ($trans as $ts) {
                $tl->giaodich = $ts->trans_type;
                $tl->created_by = $ts->create_by;
                $tl->user_id = $ts->user_id;
                $tl->created_att = $ts->created_at;
                $tl->confirm = $ts->confirm;
                $tl->note = $ts->note;
                $tl->department_id = $ts->department_id;
            }

            array_push($list, $tl);
        }
        $result = array();
        $result['data'] = array();
        $result['data_ts'] = $list_ts;

        $result['data'] = $list;
        $result['success'] = "1";

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);

    }
    public function gettreeDepartment(Request $request)
    {

        $detai = Department::all();

        $result = array();
        $result['data'] = $detai;

        if ($request->filled('type')) {

            $ListTools = array();
            $tool = $detai->pluck('company_id')->flatten();
            $tool->sort();
            $tool = array_unique($tool->toArray());
            $tool = Company::whereIn('id', $tool)->get();
            foreach ($tool as $key => $t) {
                $Item = array();

                $Item['label'] = $t->id . "-" . $t->name;
                $Item['id'] = "c" . $t->id;
                $Item['children'] = array();
                foreach ($detai as $key => $chil) {
                    if ($chil->company_id == $t->id) {
                        $item['label'] = $chil->name . ' (' . $chil->code . ')';
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
    public function thanhly(Request $request, $page)
    {
        $perPage = $request->get('per_page', 10);
        $group = $request->group;
        $items_asset = Asset::with(['attachment_image', 'attachment_file', 'AssetGroup', 'AssetType', 'user', 'AssetDetails', 'vendor', 'Department', 'AssetWarehouse.group.users'])
            ->where('asset_status_id', '!=', 3)->where('user_id', null)->where('department_id', null);

        if ($request->filled('name')) {
            $items_asset = $items_asset->where('name', 'LIKE', '%' . $request->name . '%');
        }
        if ($request->filled('groups')) {

            $items_asset = $items_asset->where('asset_group_id', $request->groups);
        }
        $result = array();
        $asset = $items_asset->paginate($perPage, ['*'], 'page', $page);
        $result['data'] = $asset->items();
        $result['paginate'] = [
            'current_page' => $asset->currentPage(),
            'last_page' => $asset->lastPage(),
            'total' => $asset->total(),
        ];

        $result['success'] = "1";

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);

    }
    public function search(Request $request)
    {
        $search = $request->get('search', '');
        $user = auth()->user();
        $result = array();
        $assets = Asset::query()
            ->with(['attachment_image', 'attachment_file', 'AssetGroup', 'AssetType', 'user', 'AssetDetails', 'vendor', 'Department', 'AssetWarehouse.group.users'])
            ->where('asset_status_id', '!=', 3)
            ->whereNull('user_id')
            ->whereNull('department_id')
            ->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('seri', 'like', '%' . $search . '%')
                    ->orWhere('sap_code', 'like', '%' . $search . '%')
                    ->orWhereHas('AssetDetails', function ($q) use ($search) {
                        $q->where('value', 'like', '%' . $search . '%');
                    });
            })
            ->whereHas('AssetWarehouse.group.users', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            })
            ->take(10)->get();

        $result['data'] = $assets;

        $result['success'] = "1";

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

    public function historyAssetTransactionV_2(Request $request)
    {
        $result = array();
        $result['data'] = array();
        $query = AssetTransaction::query()->with(['createBy','user','Department','AssetTransactionItems']);
        $asset = Asset::where('id', $request->asset_id)->first();
        if($request->filled('asset_id')) {
            $query = $query->whereHas('AssetTransactionItems', function ($q) use ($request) {
                $q->where('objectable_type', Asset::class);
                $q->where('objectable_id', $request->asset_id);
            });
        }
        
        $asset = $query->get();
        $result['data'] = $asset->map(function ($q) use ($asset) 
        {
            $q['trans_type'] = 'Tạo tài sản';

            return $q;
        });
        $result['success'] = "1";
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
}
