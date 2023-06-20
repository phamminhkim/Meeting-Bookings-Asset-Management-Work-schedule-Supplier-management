<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController\BaseController;
use App\Models\shared\Menu;
use App\Models\shared\Permission;
use App\Models\shared\Role;
use App\Models\shared\Sloc;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PermissionController extends BaseController
{
	public function index()
	{
		$permissions = Permission::paginate(10);

		return view('admin.permission.index', compact('permissions'));
	}

	public function show($id)
	{
	}

	public function create()
	{
		$menus = Menu::where('parent', 0)->get();
		$permission = Permission::all();
		$variables = [
			'menus' => $menus,
			'permission' => $permission,
		];

		return view('admin.permission.create', $variables);
	}

	public function store(Request $request)
	{
		date_default_timezone_set('Asia/Ho_Chi_Minh');
		 Validator::make($request->all(),[
			'name'=>'required',
			'description'=>'required',
		],
		[
			'required'=>"Rỗng"
		]);	

		$allRequest = $request->all();
		$name = $allRequest['name'];
		$description = $allRequest['description'];

		$permission = new Permission();
		$permission->name = $name;
		$permission->description = $description;
		$insertID = $permission->save();


		if ($insertID) {
			//Session::flash('success', 'Thêm mới thành công!');
			return back()->with('success', 'Thêm mới thành công!');
		} else {
			//Session::flash('error', 'Thêm thất bại!');
			return back()->with('error', 'Thêm thất bại!');
		}

		return redirect('/admin/permission/create');
	}

	public function edit($id)
	{
		$getData = DB::table('permissions')->select('id', 'name', 'description')->where('id', $id)->get();

		return view('admin.permission.update')->with('getPermissionByID', $getData);
	}

	public function update(Request $request, $permissionID)
	{
		date_default_timezone_set("Asia/Ho_Chi_Minh");

		$this->validate($request,[
			'name' => 'required',
			'description' => 'required',
		], 
		[
            'description.required' => 'Mô tả trống',
            'name.required' => 'Tên trống',
        ]);
		$updateData = DB::table('permissions')->where('id', $permissionID)->update([
			'name'  => $request->name,
			'description'  => $request->description,
			'updated_at' => date('Y-m-d H:i:s')
		]);

		if ($updateData) {
			return back()->with('success', 'Cập nhật thành công!');
		} else {
			return back()->with('error', 'Cập nhật thất bại!');
		}

		return redirect('admin/roles/index');
	}

	public function destroy($id)
	{
		$deleteData = DB::table('permissions')->where('id', '=', $id)->delete();

		if ($deleteData) {
			return back()->with('success',  'Xóa thành công!');
		} else {
			return back()->with('error',  'Xóa thất bại!');
		}

		return redirect('admin/permission/index');
	}

	public function search(Request $request)
	{
		$searchTerm  = $request->nav_search_input;
        $permissions = Permission::where('name', 'LIKE', "%{$searchTerm}%")
            ->orWhere('description',  'LIKE', "%{$searchTerm}%")
          
            ->paginate(15)->appends(request()->except('page'));
        
        return view('admin.permission.index')->with(['permissions' => $permissions, 'searchTerm' => $searchTerm]);
	}
}
