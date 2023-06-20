<?php

namespace App\Http\Controllers\api\asset;

use App\Http\Controllers\Controller;
use App\Models\asset\AssetWarehouse;
use App\Models\shared\Company;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = AssetWarehouse::query()->with(['user', 'group.users']);
        $result = array();
        $result['data'] = array();
        $user = auth()->user();

        try {
            // chỉ có những user_id trong group mới được thấy
            $query->whereHas('group.users', function ($q) use ($user) {
                $q->where('users.id', $user->id);
            });

            if ($request->filled('name')) {
                $query = $query->where('name', 'LIKE', "%$request->name%");
            }
            if ($request->filled('user_id')) {
                $query = $query->where('user_id', $request->user_id);
            }
            $goodtypes = $query->get();

            $result['data'] = $goodtypes;

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
        $department = AssetWarehouse::all();

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
            'name.required' => 'Tên kho hàng không được để trống',
            'group_id.required' => 'Group không được để trống',
            'name.max' => 'Tối đa 50 kí tự',
            'user_id.required' => 'Chưa chọn người quản lý',
            'company_id.required' => 'Công ty không được để trống',
            'code.required' => 'Mã kho hàng không được để trống',
            'code.max' => 'Tối đa 50 kí tự',
            'address.max' => 'Tối đa 255 kí tự',
            // 'phone.integer' => 'Nhập số nguyên',
            'phone.max' => 'Tối đa 11 kí tự',

        ];
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:50',
            'user_id' => 'required',
            'company_id' => 'required',
            'group_id' => 'required',
            'code' => 'required|max:50',
            'address' => 'max:255',
            'phone' => 'max:11',

        ], $meesage);
        $fields = $request->all();
        $failed = $validator->fails();
        $isErr = false;
        if ($request->code) {
            $dep_temp = AssetWarehouse::where('code', $request->code)->first();

            if ($dep_temp) {
                $result['data']['message'] = "Trùng mã, vui lòng nhập mã khác";
                $validator->errors()->add('code', 'Trùng mã, vui lòng nhập mã khác');
                $isErr = true;
            }
        }

        $check_user_id_active = User::where('id', $request->user_id)->where('active', '1')->get();
        if ($check_user_id_active->toArray() == []) {
            $result['data']['message'] = "Người dùng không hoạt động";
            $validator->errors()->add('user_id', "Người dùng không hoạt động");
            $isErr = true;
        }

        if ($failed || $isErr) {
            $result['data']['errors'] = $validator->errors();
        } else {
            try {
                $user_id = new User();
                $user_id = auth()->user();
                $fields['create_by'] = $user_id->id;
                $re = AssetWarehouse::create($fields);
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
        $warehouse = AssetWarehouse::with('company')->findOrFail($id);

        $result = array();
        $result['data'] = array();
        $result['data'] = $warehouse;

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
        $result = array();
        $result['data'] = array();
        $result['data']['success'] = 0;
        date_default_timezone_set("Asia/Ho_Chi_Minh");
        $meesage = [
            'name.required' => 'Tên kho hàng không được để trống',
            'name.max' => 'Tối đa 50 kí tự',
            'user_id.required' => 'Chưa chọn người quản lý',
            'company_id.required' => 'Công ty không được để trống',
            'code.required' => 'Mã kho hàng không được để trống',
            'code.max' => 'Tối đa 50 kí tự',
            'address.max' => 'Tối đa 255 kí tự',
            // 'phone.integer' => 'Nhập số nguyên',
            'phone.max' => 'Tối đa 11 kí tự',

        ];
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:50',
            'user_id' => 'required',
            'company_id' => 'required',
            'code' => 'required|max:50',
            'address' => 'max:255',
            'phone' => 'max:11',

        ], $meesage);

        $failed = $validator->fails();
        $isErr = false;
        if ($request->code) {
            $dep_temp = AssetWarehouse::where('code', $request->code)->first();

            if ($dep_temp && $dep_temp->id != $id) {
                $result['data']['message'] = "Trùng mã, vui lòng nhập mã khác";
                $validator->errors()->add('code', 'Trùng mã, vui lòng nhập mã khác');

                $isErr = true;
            }
        }
        if ($request->user_id) {
            $check_user_id_active = User::where('id', $request->user_id)->where('active', '1')->get();
            if ($check_user_id_active->toArray() == []) {
                $result['data']['message'] = "Người dùng không hoạt động";
                $validator->errors()->add('user_id', "Người dùng không hoạt động");
                $isErr = true;
            }
        }
        if ($failed || $isErr) {
            $result['data']['errors'] = $validator->errors();
        } else {
            try {
                $warehouse = AssetWarehouse::findOrFail($id);
                $warehouse->name = $request->input('name');
                $warehouse->company_id = $request->input('company_id');
                $warehouse->code = $request->input('code');
                $warehouse->group_id = $request->input('group_id');
                $warehouse->user_id = $request->input('user_id');
                $warehouse->phone = $request->input('phone');
                $warehouse->address = $request->input('address');
                if ($warehouse->save()) {
                    $result['data']['success'] = 1;
                    $result['data'] = $warehouse;
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
        $warehouse = AssetWarehouse::findOrFail($id);
        $result = array();
        $result['data'] = array();
        $result['data']['success'] = 0;
        if ($warehouse->delete()) {
            $result['data']['success'] = 1;
        }
        return json_encode($result, JSON_UNESCAPED_UNICODE);
    }
    public function gettreewarehouse(Request $request)
    {
        $user = auth()->user();
        $detai = AssetWarehouse::whereHas('group.users', function ($q) use ($user) {
            $q->where('users.id', $user->id);
        })->get();
    
        $result = array();
        if ($detai->isNotEmpty()) {
            if ($request->filled('type')) {
                $ListTools = array();
                $companies = $detai->pluck('company_id')->unique();
                $companies->sort();
                $companies = Company::whereIn('id', $companies)->get();
                foreach ($companies as $company) {
                    $companyItem = array();
                    $companyItem['label'] = $company->id . "-" . $company->name;
                    $companyItem['id'] = "c" . $company->id;
                    $companyItem['children'] = array();
    
                    foreach ($detai->where('company_id', $company->id) as $child) {
                        $childItem = array();
                        $childItem['label'] = $child->name . ' (' . $child->code . ')';
                        $childItem['id'] = $child->id;
                        array_push($companyItem['children'], $childItem);
                    }
    
                    array_push($ListTools, $companyItem);
                }
                $detai = $ListTools;
            }
            $result['success'] = "1";
            $result['message'] = "Thành công";
        } else {
            $result['success'] = "0";
            $result['message'] = "Không có kho nào được tìm thấy";
        }
    
        $result['data'] = $detai;
        return json_encode($result, JSON_UNESCAPED_UNICODE);
    }
}
