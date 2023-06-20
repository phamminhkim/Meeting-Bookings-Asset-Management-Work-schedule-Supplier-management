<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\BaseController\BaseController;
use App\Http\Controllers\Controller;
use App\Models\shared\Company;
use App\Models\shared\Role;
use App\Models\shared\Sloc;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Admin\UserRequest;
use App\Http\Resources\Users;
use App\Models\shared\Department;
use App\Mail\EmailUserRegisterSuccess;
use App\Mail\EmailUserDisable;
use App\Mail\EmailUserChangePass;
use App\Models\shared\Group;
use App\Notifications\CommondNotification;
use App\Notifications\NotiBaseModel;

class UserController extends BaseController
{

    public function index()
    {

        $user = User::paginate(15);



        return view('admin.user.index')->with('users', $user);
    }
    public function create()
    {

        $company = Company::all();
        $department = Department::all();
        $sloc = Sloc::all();
        $list_user = User::all();
        return view('admin.user.create', ['company' => $company, 'department' => $department, 'sloc' => $sloc, 'list_user' => $list_user]);
    }

    public function store(UserRequest $request)
    {
        $this->validate(
            $request,
            [
                'username' => 'required|unique:users',
                'email' => 'required',
                // 'email' => 'required|unique:users',
                'company_id' => 'required',
                'department_id' => 'required',
                'gender' => 'required',
            ],
            [
                'required' => ':attribute Không được để trống',
                'min' => ':attribute Không được nhỏ hơn :min',
                'max' => ':attribute Không được lớn hơn :max',
                'unique' => ':attribute đã tồn tại',

            ]
        );
        // dd($request['username']);
        $user =  User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'username' => $request['username'],
            'company_id' => $request['company_id'],
            'department_id' => $request['department_id'],
            'sloc_id' => $request['sloc_id'],
            'active' => $request['active'],
            'gender' => $request['gender'],
            'password' => Hash::make($request['password']),
        ]);
        $accessToken = $user->createToken('authToken')->accessToken;

        $role = Role::select('id')->where('name', 'User')->first();
        $user->roles()->attach($role);
        $request->session()->flash('success', $user->name . ' has been created');

        if ($request['notify']) {
            $data = new NotiBaseModel;
            $data->title = "Đăng ký thành công";
            $data->url = URL('/');
            $data->password = $request['password'];
            $email = new EmailUserRegisterSuccess($data, $user);
            $user->notify(new CommondNotification($data, $user, $email));
        }

        return redirect()->route('adminusers.index');
    }
    public function edit(User $user)
    {

        if (Gate::denies('manage-systems')) {
            return redirect(route('adminusers.index'));
        }

        $company = Company::all();
        $department = Department::all();
        $sloc = Sloc::all();
        $roles = Role::all();
        $list_user = User::all();
        return view('admin.user.edit')->with([
            'user' => $user,
            'roles' => $roles,
            'company' => $company,
            'department' => $department,
            'sloc' => $sloc,
            'list_user' => $list_user
        ]);
    }

    public function update(Request $request, User $user)
    {

        $this->validate(
            $request,
            [
                'username' => 'required|unique:users,username,' . $user->id,
                'email' => 'required',
                // 'email' => 'required|unique:users,email,'.$user->id,
                'company_id' => 'required',
                'department_id' => 'required',
                'gender' => 'required',
            ],
            [
                'required' => ':attribute Không được để trống',
                'min' => ':attribute Không được nhỏ hơn :min',
                'max' => ':attribute Không được lớn hơn :max',
                'unique' => ':attribute đã tồn tại',

            ]
        );

        $user->roles()->sync($request->roles);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->company_id = $request->company_id;
        $user->department_id = $request->department_id;
        $user->sloc_id = $request->sloc_id;
        $oldactive = $user->active;
        $user->active = $request->active;
        $user->manager = $request->manager;
        $user->gender = $request->gender;

        $accessToken = $user->createToken('authToken')->accessToken;
        if ($request->doimatkhau) {
            $user->password = Hash::make($request['password']);
        }


        if ($user->save()) {
            $request->session()->flash('success', $user->name . ' has been updated');

            if (!$request->active && $oldactive != $request->active) {
                $data = new NotiBaseModel;
                $data->title = "Vô hiệu hóa tài khoản";
                $email = new EmailUserDisable($data, $user);
                $user->notify(new CommondNotification($data, $user, $email));
            }
            if ($request->doimatkhau) {
                $data = new NotiBaseModel;
                $data->title = "Reset mật khẩu";
                $data->password = $request->password;
                $data->url = URL('/') . '/profile';
                $email = new EmailUserChangePass($data, $user);
                $user->notify(new CommondNotification($data, $user, $email));
            }
        } else {
            $request->session()->flash('error', 'There was an error updating the user');
        }

        return redirect()->route('adminusers.index');
    }
    public function show(User $user)
    {
    }
    public function destroy(Request $request, User $user)
    {

        if (Gate::denies('manage-systems')) {
            return redirect(route('adminusers.index'));
        }
        try {
            $user->roles()->detach();


            if ($user->delete()) {
                $request->session()->flash('success', 'Deleted successfully');
            } else {
                $request->session()->flash('error', 'There was an error delete the user');
            }
        } catch (\Throwable $th) {
            $request->session()->flash('error', 'There was an error delete the user');
        }



        return redirect()->route('adminusers.index');
    }
    public function search_user(Request $request)
    {

        $searchTerm  = $request->nav_search_input;
        $user = User::where('name', 'LIKE', "%{$searchTerm}%")
            ->orWhere('username',  'LIKE', "%{$searchTerm}%")
            ->orWhere('company_id',  'LIKE', "%{$searchTerm}%")
            ->orWhere('email',  'LIKE', "%{$searchTerm}%")
            ->paginate(15)->appends(request()->except('page'));

        return view('admin.user.index')->with(['users' => $user, 'searchTerm' => $searchTerm]);
    }


    public function disable_user(Request $request)
    {
        if ($request->usersid) {
            $list_users  = explode(',', $request->usersid);
            $success = 0;

            if ($list_users) {

                if (count($list_users) > 0) {
                    foreach ($list_users as $userid) {
                        $old_user =  User::where('username', $userid)->first();
                        if ($old_user) {
                            $oldactive = $old_user->active;
                            $old_user->active = $request->active;
                            $old_user->save();

                            if (!$request->active && $oldactive != $request->active) {
                                $data = new NotiBaseModel;
                                $data->title = "Vô hiệu hóa tài khoản";
                                $email = new EmailUserDisable($data, $old_user);
                                $old_user->notify(new CommondNotification($data, $old_user, $email));
                            }
                            $success++;
                        }
                    }
                    if ($success == count($list_users)) {
                        $request->session()->flash('success', 'Cập nhật thành công ' . $success . '/' . count($list_users) . ' users');
                    } else {
                        $request->session()->flash('warning', 'Cập nhật thành công ' . $success . '/' . count($list_users) . ' users');
                    }
                }
            } else {
                $request->session()->flash('error', 'Đã có lỗi xảy ra khi cập nhật hàng loạt.');
            }
        }

        return view('admin.user.disable');
    }

    public function view_groups($username)
    {
        $group_query = Group::query();
        $user_query = User::query();
        $user_query = $user_query->where('username', 'like', '%' . $username . '%');

        $userlist = $user_query->get()->pluck('id')->flatten();

        $group_query = $group_query->whereHas('users', function ($q) use ($userlist) {
            $q->whereIn('id', $userlist);
        });
        $allgroups = $group_query->get();
        $companys = Company::all();
        $departments = Department::all();

        foreach ($allgroups as $group) {
            foreach ($companys as $company) {
                if ($group->company_id == $company->id) {
                    $group->company_id = $company->name;
                    foreach ($departments as $department) {
                        if ($department->company_id == $company->id) {
                            $group->department_id = $department->name;
                        }
                    }
                }

            }
        }

        $user = User::where('username', $username)->first();
        $user->department_id = Department::where('id', $user->department_id)->first()->name;

        return view('admin.user.view')
            ->with('groups', $allgroups)
            ->with('user', $user);
    }
}
