<?php

namespace App\Http\Controllers\api\asset;

use App\Http\Controllers\Controller;
use App\Models\asset\Asset;
use App\Models\asset\AssetDetail;
use App\Models\asset\AssetField;
use App\Models\asset\AssetTool;
use App\Models\asset\AssetType;
use App\Models\asset\AssetTypeDetail;
use App\Models\asset\AssetWarehouse;
use App\Models\shared\Image;
use App\Models\shared\Vendor;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AssetTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = AssetType::query()->with(['user.groups', 'AssetGroup', 'attachment_image', 'AssetTypeDetails']);

        $result = array();
        $result['data'] = array();
        $role = User::find(auth()->user()->id);
        // $group = $role->groups->pluck('id')->toArray();

        // $groupIds = AssetWarehouse::pluck('group_id')->toArray();
        // $intersect = array_intersect($group, $groupIds);
        try {

            // $query->whereHas('user.groups', function ($q) use ($intersect) {
            //     $q->where('groups.id', $intersect);
            // });
            if ($request->filled('asset_group_id')) {
                $query = $query->where('asset_group_id', $request->asset_group_id);
            }
            $type = $query->get();
            $result['data'] = $type;
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
        $type = AssetType::all();

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
            'name.required' => 'Tên không được để trống',
            'code.required' => 'Mã không được để trống',
            'type.required' => 'Loại tài sản không được để trống',
            'description.max' => 'Tối đa 255 kí tự',
            'name.max' => 'Tối đa 50 kí tự',
            'code.max' => 'Tối đa 50 kí tự',
            'asset_group_id.required' => 'Nhóm không được để trống',

        ];
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:50',
            'code' => 'required|max:50',
            'type' => 'required',
            'asset_group_id' => 'required',

            'description' => 'max:255',
        ], $meesage);
        $fields = $request->all();
        $failed = $validator->fails();
        $isErr = false;
        $role = User::find(auth()->user()->id);

        if ($request->code) {

            $dep_temp = AssetType::where('code', $request->code)->first();

            if ($dep_temp) {
                $result['data']['message'] = "Trùng mã, vui lòng nhập mã khác";
                $validator->errors()->add('code', 'Trùng mã, vui lòng nhập mã khác');
                $isErr = true;
            }
        }

        if ($request->name) {

            $dep_temp = AssetType::where('create_by', $role->id)->where('name', $request->name)->first();
            // dd($dep_temp);
            if ($dep_temp) {
                $result['data']['message'] = "Trùng tên, vui lòng nhập tên khác";
                $validator->errors()->add('name', 'Trùng tên, vui lòng nhập tên khác');
                $isErr = true;
            }
        }
        $item_asset_custom_fields = $fields['asset_type_details'];
        foreach ($item_asset_custom_fields as $item_asset_type) {
            $field = AssetField::where('name', $item_asset_type['name'])->first();
            if ($field == null) {
                $result['data']['message'] = "Thuộc tính " . $item_asset_type['name'] . " chưa được cấu hình";
                $validator->errors()->add('field', 'Thuộc tính ' . $item_asset_type['name'] . ' chưa được cấu hình');
                $isErr = true;

            }
        }
        if ($failed || $isErr) {
            $result['data']['errors'] = $validator->errors();
        } else {
            try {
                DB::beginTransaction();
                $fields['active'] = 1;
                $user = new User();
                $user = auth()->user();
                $fields['create_by'] = $user->id;
                $re = AssetType::create($fields);
                if ($re) {
                    $user = new User();
                    $user = auth()->user();
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
                    }
                    $item_asset_custom_fields = $fields['asset_type_details'];
                    foreach ($item_asset_custom_fields as $item_asset_type) {

                        $field = AssetField::where('name', $item_asset_type['name'])->first();
                        if ($field != null) {
                            AssetTypeDetail::create([
                                'asset_type_id' => $re->id,
                                'name' => $item_asset_type['name'],
                                'value' => $item_asset_type['value'],
                            ]);
                        }

                    }
                }
                DB::commit();
                // sau khi thêm load lại ảnh
                $re->load('AssetTypeDetails');
                $re->load('attachment_image');
                $result['data']['success'] = 1;
                $result['data']['data'] = $re;

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

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $payRequestModel = AssetType::with(['AssetGroup', 'attachment_image', 'AssetTypeDetails'])->find($id);

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
        $result = array();
        $result['data'] = array();
        $result['data']['success'] = 0;
        date_default_timezone_set("Asia/Ho_Chi_Minh");
        $meesage = [
            'name.required' => 'Tên không được để trống',
            'code.required' => 'Mã không được để trống',
            'type.required' => 'Loại tài sản không được để trống',
            'asset_group_id.required' => 'Nhóm không được để trống',

            'description.max' => 'Tối đa 255 kí tự',
            'name.max' => 'Tối đa 50 kí tự',
            'code.max' => 'Tối đa 50 kí tự',
        ];
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:50',
            'code' => 'required|max:50',
            'type' => 'required',
            'asset_group_id' => 'required',
            'description' => 'max:255',
        ], $meesage);
        $fields = $request->all();
        $failed = $validator->fails();
        $isErr = false;
        $role = User::find(auth()->user()->id);

        if ($request->code) {
            $dep_temp = AssetType::where('code', $request->code)->first();

            if ($dep_temp && $dep_temp->id != $id) {
                $result['data']['message'] = "Trùng mã, vui lòng nhập mã khác";
                $validator->errors()->add('code', 'Trùng mã, vui lòng nhập mã khác');
                $isErr = true;
            }
        }
        if ($request->name) {
            $dep_temp = AssetType::where('create_by', $role->id)->where('name', $request->name)->first();

            if ($dep_temp && $dep_temp->id != $id) {
                $result['data']['message'] = "Trùng tên, vui lòng nhập tên khác";
                $validator->errors()->add('name', 'Trùng tên, vui lòng nhập tên khác');
                $isErr = true;
            }
        }
        $item_asset_custom_fields = $fields['asset_type_details'];
        foreach ($item_asset_custom_fields as $item_asset_type) {
            $field = AssetField::where('name', $item_asset_type['name'])->first();
            if ($field == null) {
                $result['data']['message'] = "Thuộc tính " . $item_asset_type['name'] . " chưa được cấu hình";
                $validator->errors()->add('field', 'Thuộc tính ' . $item_asset_type['name'] . ' chưa được cấu hình');
                $isErr = true;

            }
        }
        if ($failed || $isErr) {
            $result['data']['errors'] = $validator->errors();
        } else {

            try {
                $AssetType = AssetType::with(['AssetGroup', 'attachment_image', 'AssetTypeDetails'])->find($id);

                if ($request->input('type') == 0) {
                    $typee = Asset::class;
                }
                if ($request->input('type') == 1) {
                    $typee = AssetTool::class;
                }

                if ($AssetType) {

                    $AssetType->name = $request->input('name');
                    $AssetType->code = $request->input('code');
                    $AssetType->type = $request->input('type');
                    $AssetType->asset_cat_id = $request->input('asset_cat_id');
                    $AssetType->asset_group_id = $request->input('asset_group_id');
                    $AssetType->amount = $request->input('amount');
                    $AssetType->description = $request->input('description');
                    $AssetType->save();

                }
                if ($AssetType) {

                    $user = new User();
                    $user = auth()->user();
                    $attachment_image = $fields['attachment_image'];
                    for ($i = 0; $i < count($attachment_image); $i++) {

                        //Chỉ lưu ảnh mới
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
                            $AssetType->attachment_image()->save($imgg);
                            $extension = explode('/', explode(':', substr($attachment_image[$i]['base64'], 0, strpos($attachment_image[$i]['base64'], ';')))[1])[1];
                            $base64_str = substr($attachment_image[$i]['base64'], strpos($attachment_image[$i]['base64'], ",") + 1);
                            $image = str_replace($base64_str, '', $attachment_image[$i]);
                            $image = str_replace(' ', '+', $image);
                            $image = base64_decode($base64_str);
                            // Image::ensureDirectoryExists();
                            $imageName = Str::random(10) . '.' . $extension;

                            file_put_contents(public_path() . $name, $image);
                        }
                    }
                    // Xóa ảnh
                    $attachment_image_del = $fields['attachment_image_del'];
                    for ($i = 0; $i < count($attachment_image_del); $i++) {
                        if (isset($attachment_image_del[$i]["id"])) {
                            $imgg = Image::find($attachment_image_del[$i]["id"]);
                            if ($imgg) {
                                //   dd($imgg->url);
                                unlink(public_path() . $imgg->url);
                                // unlink(public_path().$imgg->url.".enc");

                                $imgg->delete();
                                // Storage::delete($imgg->url);
                                // $imgg->delete();
                            }
                        }
                    }
                    $item_asset_custom_fields = $fields['asset_type_details'];

                    foreach ($item_asset_custom_fields as $item_type_field) {
                        if (isset($item_type_field['id']) && $item_type_field['id'] != '') {
                            $TypeDetail = AssetTypeDetail::find($item_type_field['id']);
                            $TypeDetail->fill($item_type_field);
                            $TypeDetail->save();
                            // $waiting = AssetDetail::where('asset_type_id', $AssetType->id)
                            // ->where('name',  $item_type_field['name'])->update(['value' => $item_type_field['value']]);

                        } else {
                            AssetTypeDetail::create([
                                'asset_type_id' => $AssetType->id,
                                'name' => $item_type_field['name'],
                                'value' => $item_asset_type['value'],
                            ]);
                            // dd($typee);
                            $asset_details = AssetDetail::where('asset_type_id', $AssetType->id)->where('objectable_type', $typee)
                                ->get();
                            $usersUnique = $asset_details->unique(['objectable_id']);

                            // dd($asset_details->toArray());
                            foreach ($usersUnique as $value_detail) {
                                if ($value_detail->name != $item_type_field['name']) {

                                    $item_detail = AssetDetail::create([
                                        'objectable_id' => $value_detail->objectable_id,
                                        'asset_type_id' => $AssetType->id,

                                        'objectable_type' => $typee,
                                        'name' => $item_type_field['name'],
                                        'value' => $item_asset_type['value'],

                                    ]);

                                }

                            }

                        }
                    }
                    $item_asset_custom_del = $fields['asset_type_details_del'];
                    foreach ($item_asset_custom_del as $custom_del) {
                        if (isset($custom_del['id']) && $custom_del['id'] != '') {
                            $TypeDetail = AssetTypeDetail::find($custom_del['id']);
                            if ($TypeDetail != null) {
                                $TypeDetail->delete();
                            }
                        }
                    }
                    DB::commit();
                    $AssetType->load("AssetTypeDetails");
                    $AssetType->load("attachment_image");
                    $result['data']['success'] = 1;
                    $result['data']['data'] = $AssetType;
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
        $asset = AssetType::with(['AssetGroup', 'attachment_image', 'AssetTypeDetails'])->find($id);
        $result = array();
        $result['data'] = array();
        $result['data']['success'] = 0;

        $assetss = Asset::where('asset_type_id', $id)->get();
        $assetss_tools = AssetTool::where('asset_type_id', $id)->get();

        if ($assetss->toArray() != [] || $assetss_tools->toArray() != []) {
            $result['data']['errors'] = 'Tồn tại tài sản thuộc Model  , vui lòng không xóa';

        } else {
            try {
                DB::beginTransaction();
                foreach ($asset->attachment_image as $imgg) {
                    $imgg = Image::where('imgable_id', $asset->id)->first();
                    // unlink(public_path().$imgg->url.".enc");

                    unlink(public_path() . $imgg->url);
                    $imgg->delete();

                }
                foreach ($asset->AssetTypeDetails as $item) {
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
    public function taisan(Request $request)
    {
        $query = AssetType::query()->where('type', 0)->with(['AssetGroup', 'attachment_image', 'AssetTypeDetails','user.groups']);
        $result = array();
        $result['data'] = array();
        $role = User::find(auth()->user()->id);
        // $group = $role->groups->pluck('id')->toArray();

        // $groupIds = AssetWarehouse::pluck('group_id')->toArray();
        // $intersect = array_intersect($group, $groupIds);
        try {
            // $query->whereHas('user.groups', function ($q) use ($intersect) {
            //     $q->where('groups.id', $intersect);
            // });
            // if ($request->filled('id')) {
            //     $query = $query->where('id', $request->id);
            // }
            if ($request->filled('name')) {
                $query = $query->where('name', $request->name);
            }
            if ($request->filled('code')) {
                $query = $query->where('code', $request->code);
            }
            if ($request->filled('type')) {
                $query = $query->where('type', $request->type);
            }
            if ($request->filled('asset_group_id')) {
                $query = $query->where('asset_group_id', $request->asset_group_id);
            }
            if ($request->filled('amount')) {
                $query = $query->where('amount', $request->amount);
            }
            if ($request->filled('active')) {
                $query = $query->where('active', $request->active);
            }
            $type = $query->get();
            $result['data'] = $type;
            $result['success'] = "1";
        } catch (Exception $e) {
            $result['data']['errors'] = $e->getMessage();
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    public function congcudungcu(Request $request)
    {
        $query = AssetType::query()->where('type', 1)->with(['AssetGroup', 'attachment_image', 'AssetTypeDetails','user.groups']);
        $result = array();
        $result['data'] = array();
        $role = User::find(auth()->user()->id);
        // $group = $role->groups->pluck('id')->toArray();

        // $groupIds = AssetWarehouse::pluck('group_id')->toArray();
        // $intersect = array_intersect($group, $groupIds);

        try {
            // $query->whereHas('user.groups', function ($q) use ($intersect) {
            //     $q->where('groups.id', $intersect);
            // });
            // if ($request->filled('id')) {
            //     $query = $query->where('id', $request->id);
            // }
            if ($request->filled('name')) {
                $query = $query->where('name', $request->name);
            }
            if ($request->filled('code')) {
                $query = $query->where('code', $request->code);
            }
            if ($request->filled('type')) {
                $query = $query->where('type', $request->type);
            }
            if ($request->filled('asset_group_id')) {
                $query = $query->where('asset_group_id', $request->asset_group_id);
            }
            if ($request->filled('amount')) {
                $query = $query->where('amount', $request->amount);
            }
            if ($request->filled('active')) {
                $query = $query->where('active', $request->active);
            }
            $type = $query->get();
            $result['data'] = $type;
            $result['success'] = "1";
        } catch (Exception $e) {
            $result['data']['errors'] = $e->getMessage();
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    public function gettreeassettype(Request $request)
    {
        $role = User::find(auth()->user()->id);
        // $group = $role->groups->pluck('id')->toArray();
        // $groupIds = AssetWarehouse::pluck('group_id')->toArray();
      
        // $intersect = array_intersect($group, $groupIds);
        $assettype = AssetType::where('type', '1')->get();
        // ->whereHas('user.groups', function ($q) use ($intersect) {
        //     $q->whereIn('groups.id', $intersect);
        // })->get();
        if ($request->filled('type')) {
            $ListTypes = array();
            $assettype->sort();
            foreach ($assettype as $key => $t) {
                $Item = array();
                $Item['label'] = $t->name;
                $Item['id'] = $t->id;
                array_push($ListTypes, $Item);
            }
            $assettype = $ListTypes;
        }
        if ($assettype) {
            $status = "1";
            $message = "Thành công";
        } else {
            $status = "0";
            $message = "Không thành công";
        }
        return json_encode(['success' => $status, 'message' => $message, 'data' => $assettype], JSON_UNESCAPED_UNICODE);
    }
    public function gettreeassettypets(Request $request)
    {
        $role = User::find(auth()->user()->id);
        // $group = $role->groups->pluck('id')->toArray();
        // $groupIds = AssetWarehouse::pluck('group_id')->toArray();
       
        // $intersect = array_intersect($group, $groupIds);
       
        $assettype = AssetType::where('type', '0')->get();
        // ->whereHas('user.groups', function ($q) use ($intersect) {
        //     $q->whereIn('groups.id', $intersect);
        // })->get();
        if ($request->filled('type')) {
            $ListTypes = array();
            $assettype->sort();
            foreach ($assettype as $key => $t) {
                $Item = array();
                $Item['label'] = $t->name;
                $Item['id'] = $t->id;
                array_push($ListTypes, $Item);
            }
            $assettype = $ListTypes;
        }
        if ($assettype) {
            $status = "1";
            $message = "Thành công";
        } else {
            $status = "0";
            $message = "Không thành công";
        }
        return json_encode(['success' => $status, 'message' => $message, 'data' => $assettype], JSON_UNESCAPED_UNICODE);
    }
    public function gettreevendos(Request $request)
    {
        $vendo = Vendor::all();
        $result = array();
        $result['data'] = $vendo;
        if ($request->filled('type')) {
            $Listvendo = array();
            $type = $vendo;
            $type->sort();
            foreach ($type as $key => $t) {
                $Item = array();
                $Item['label'] = $t->comp_name;
                $Item['id'] = $t->id;
                array_push($Listvendo, $Item);
            }
            $vendo = $Listvendo;
        }
        if ($result) {
            $status = "1";
            $message = "Thành công";
        } else {
            $status = "0";
            $message = "Không thành công";
        }
        return json_encode(['success' => $status, 'message' => $message, 'data' => $vendo], JSON_UNESCAPED_UNICODE);
    }

}
