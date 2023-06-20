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

class RoleController extends BaseController
{
	public function index()
	{
		$roles = Role::paginate(10);

		return view('admin.role.index', compact('roles'));
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

		return view('admin.role.create', $variables);
	}

	public function store(Request $request)
	{
		date_default_timezone_set('Asia/Ho_Chi_Minh');
		 Validator::make($request->all(),[
			'name'=>'required',
			'description'=>'required',
			'active'=>'required',
		],
		[
			'required'=>"Rỗng"
		]);	
		//$this->authorize('create', new Role());

		$allRequest = $request->all();
		$name = $allRequest['name'];
		$description = $allRequest['description'];
		$active = $allRequest['active'];

		$menuids = $request->menuids;
		$permissionids = $request->quyenids;
		$slocids = $request->slocids;

		$role = new Role();
		$role->name = $name;
		$role->description = $description;
		$role->active = $active;
		$insertID = $role->save();

		if ($menuids) {
			foreach ($menuids as  $mn) {
				DB::table('role_menu')->insert(
					['role_id' => $role->id, 'menu_id' => $mn]
				);
			}
		}

		if ($permissionids) {
			foreach ($permissionids as  $per) {
				DB::table('role_permission')->insert(
					['role_id' => $role->id, 'permission_id' => $per]
				);
			}
		}
		if ($slocids) {
			foreach ($slocids as  $per) {
				DB::table('role_sloc')->insert(
					['role_id' => $role->id, 'sloc_id' => $per]
				);
			}
		}
		if ($insertID) {
			//Session::flash('success', 'Thêm mới thành công!');
			return back()->with('success', 'Thêm mới thành công!');
		} else {
			//Session::flash('error', 'Thêm thất bại!');
			return back()->with('error', 'Thêm thất bại!');
		}

		return redirect('/admin/roles/create');
	}

	public function edit($id)
	{
		$menus = Menu::where('parent', 0)->get();
		$permission = Permission::all();

		$rolemenu = DB::table('role_menu')->select('menu_id')->where('role_id', $id)->get();

		$rolepermission = DB::table('role_permission')
			->join('permissions', 'permissions.id', '=', 'role_permission.permission_id')
			->select('permissions.id', 'permissions.name', 'permissions.description')->where('role_id', $id)->get();

		$rolesloc = DB::table('role_sloc')
			->join('slocs', 'slocs.id', '=', 'role_sloc.sloc_id')
			->select('slocs.id', 'slocs.name', 'slocs.description')->where('role_id', $id)->get();



		$variables = [
			'menus' => $menus,
			'permission' => $permission,
			'rolemenu' => $rolemenu,
			'rolepermission' => $rolepermission,
			'rolesloc' => $rolesloc,
		];
		$getData = DB::table('roles')->select('id', 'name', 'description', 'active')->where('id', $id)->get();

		return view('admin.role.update', $variables)->with('getRolesById', $getData);
	}

	public function update(Request $request, $roleid)
	{


		date_default_timezone_set("Asia/Ho_Chi_Minh");
		// Validator::make($request->all(),[
		// 	'name'=>'required',
		// 	'description'=>'required',
		// 	'active'=>'required',
		// ],
		// [
		// 	'required'=>"Rỗng"
		// ]);	
		$this->validate($request,[
			'name' => 'required',
			'description' => 'required',
			'active' => 'required',
		], 
		[
            'description.required' => 'Mô tả trống',
            'name.required' => 'Tên trống',
          
        ]);
		$updateData = DB::table('roles')->where('id', $roleid)->update([
			'name'  => $request->name,
			'description'  => $request->description,
			'active' => $request->active,
			'updated_at' => date('Y-m-d H:i:s')
		]);
		$allRequest  = $request->all();
		$menuids = $request->menuids;
		$slocids = $request->slocids;
		$permissionids = $request->quyenids;




		DB::table('role_menu')->where('role_id', $roleid)->delete();
		if ($menuids) {
			foreach ($menuids as  $mn) {

				DB::table('role_menu')->updateOrInsert(
					['role_id' => $roleid, 'menu_id' => $mn]
				);
			}
		}
		DB::table('role_permission')->where('role_id', $roleid)->delete();
		if ($permissionids) {
			foreach ($permissionids as  $per) {

				DB::table('role_permission')->updateOrInsert(
					['role_id' => $roleid, 'permission_id' => $per]
				);
			}
		}
		DB::table('role_sloc')->where('role_id', $roleid)->delete();
		if ($slocids) {
			foreach ($slocids as  $sloc) {

				DB::table('role_sloc')->updateOrInsert(
					['role_id' => $roleid, 'sloc_id' => $sloc]
				);
			}
		}

		if ($updateData) {
			return back()->with('success', 'Cập nhật thành công!');
		} else {
			return back()->with('error', 'Cập nhật thất bại!');
		}

		return redirect('admin/roles/index');
	}

	public function destroy($id)
	{


		$deleteData = DB::table('roles')->where('id', '=', $id)->delete();

		if ($deleteData) {
			return back()->with('success',  'Xóa thành công!');
		} else {
			return back()->with('error',  'Xóa thất bại!');
		}

		return redirect('admin/roles/index');
	}

	/* Ajax quyền */
	public function get_quyen(Request $request)
	{
		if ($request->ajax()) {
			$id = $request->input('idquyen');
			$quyenlist = Permission::where('id', $id)->paginate();

			//$userlist = User::paginate(5);

			$result['success'] = '1';
			$result['errors'] = [];
			$result['list'] = [];
			$result['message'] = 'Lưu thành công';
			$result['paging'] = ''; //(string) $userlist->links();
			$result['list'] = json_encode($quyenlist);

			return response()->json(['data' => $result], 200);
		}
	}

	public function search_quyen(Request $request)
	{
		if ($request->ajax()) {
			$searchterm = $request->input('data');
			$permissionlist = Permission::where('name', 'like', '%' . $searchterm . '%')
				->orwhere('description', 'like', '%' . $searchterm . '%')
				->paginate(5);

			//$userlist = User::paginate(5);
			$result = [];
			$result['success'] = '1';
			$result['errors'] = [];
			$result['list'] = [];
			$result['message'] = 'Lưu thành công';
			$result['paging'] = (string) $permissionlist->links();
			$result['list'] = json_encode($permissionlist);

			return response()->json(['data' => $result], 200);
		}
	}

	/** Ajax SLoc */
	public function get_sloc(Request $request)
	{
		if ($request->ajax()) {
			$id = $request->input('idsloc');
			$sloclist = Sloc::where('id', $id)->paginate();

			//$sloclist = Sloc::paginate(5);

			$result['success'] = '1';
			$result['errors'] = [];
			$result['list'] = [];
			$result['message'] = 'Lưu thành công';
			$result['paging'] = ''; //(string) $userlist->links();
			$result['list'] = json_encode($sloclist);

			return response()->json(['data' => $result], 200);
		}
	}

	public function search_sloc(Request $request)
	{
		if ($request->ajax()) {
			$searchterm = $request->input('data');
			$sloclist = Sloc::where('name', 'like', '%' . $searchterm . '%')
				->orwhere('description', 'like', '%' . $searchterm . '%')
				->paginate(5);

			//$userlist = User::paginate(5);
			$result = [];
			$result['success'] = '1';
			$result['errors'] = [];
			$result['list'] = [];
			$result['message'] = 'Lưu thành công';
			$result['paging'] = (string) $sloclist->links();
			$result['list'] = json_encode($sloclist);

			return response()->json(['data' => $result], 200);
		}
	}
	public function search_role(Request $request)
    {
         
        $searchTerm  = $request->nav_search_input;
        $role = Role::where('name', 'LIKE', "%{$searchTerm}%")
            ->orWhere('description',  'LIKE', "%{$searchTerm}%")
          
            ->paginate(15)->appends(request()->except('page'));
        
        return view('admin.role.index')->with(['roles' => $role, 'searchTerm' => $searchTerm]);
    }
}
