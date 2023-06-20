<?php

namespace App\Http\Controllers\api\category;

use App\Http\Controllers\BaseController\BaseController;
use App\Http\Controllers\Controller;
use App\Models\shared\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;

class RoleController extends BaseController
{
    public function canCUD()
    {
        $user = User::find(auth()->user()->id);
        return true; // $user->hasRole('admin');
    }


    public function index(Request $request)
    {
        $roles = Role::with(['permissions', 'users'])->get()
            ->map(function ($role) {
                $role->permissions_list = $role->permissions->pluck('id')->flatten();
                unset($role->permissions);

                $role->users_list = $role->users->pluck('id')->flatten();
                unset($role->users);
                return $role;
            });;

        $result = array();
        $result['data'] = $roles;
        $result['success'] = "1";
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

    public function validate_store($fields)
    {
        $validator = Validator::make($fields, [
            'name' => 'required',
            'description' => 'required',
        ]);

        return $validator;
    }

    public function store(Request $request)
    {
        if (!$this->canCUD()) {
            return $this->sendError("Không có quyền hạn", [], Response::HTTP_FORBIDDEN);
        } else {
            $fields = $request->all();
            $validator = $this->validate_store($fields);
            $is_failed = $validator->fails();

            if ($is_failed) {
                return $this->sendError("Dữ liệu không hợp lệ", $validator->errors(), Response::HTTP_BAD_REQUEST);
            } else {
                try {
                    $created_role = Role::create($fields);
                    if ($created_role) {
                        return  $this->sendResponse($created_role, "Tạo mới vai trò thành công");
                    }
                } catch (\Exception $e) {
                    return $this->sendError($e->getMessage());
                }
            }
        }
    }

    public function validate_update($id, $fields)
    {
        $validator = Validator::make($fields, [
            'name' => 'required',
            'description' => 'required',
        ]);

        return $validator;
    }

    public function update(Request $request, $id)
    {
        if (!$this->canCUD()) {
            return $this->sendError("Không có quyền hạn", [], Response::HTTP_FORBIDDEN);
        } else {
            $fields = $request->all();
            $validator = $this->validate_update($id, $fields);
            $is_failed = $validator->fails();

            if ($is_failed) {
                return $this->sendError("Dữ liệu không hợp lệ", $validator->errors(), Response::HTTP_BAD_REQUEST);
            } else {
                try {
                    DB::beginTransaction();

                    $existing_role = Role::find($id);
                    if ($existing_role) {
                        $existing_role->name = $fields['name'];
                        $existing_role->description = $fields['description'];

                        foreach ($fields['permissions_add_list'] as $permission_id) {
                            DB::table('role_permission')->updateOrInsert(
                                ['role_id' => $existing_role->id, 'permission_id' => $permission_id]
                            );
                        }
                        DB::table('role_permission')->where('role_id', $existing_role->id)
                            ->whereIn('permission_id', $fields['permissions_remove_list'])->delete();

                        foreach ($fields['users_add_list'] as $user_id) {
                            DB::table('role_user')->updateOrInsert(
                                ['role_id' => $existing_role->id, 'user_id' => $user_id]
                            );
                        }
                        DB::table('role_user')->where('role_id', $existing_role->id)
                        ->whereIn('user_id', $fields['users_remove_list'])->delete();
                        

                        if ($existing_role->save()) {
                            DB::commit();

                            $existing_role->load('permissions', 'users');
                            $existing_role->permissions_list = $existing_role->permissions->pluck('id')->flatten();
                            unset($existing_role->permissions);

                            $existing_role->users_list = $existing_role->users->pluck('id')->flatten();
                            unset($existing_role->users);

                            return $this->sendResponse($existing_role, "Cập nhật vai trò thành công");
                        } else {
                            return $this->sendFailedWithStatusCode("Cập nhật vai trò thất bại", Response::HTTP_NOT_MODIFIED);
                        }
                    } else {
                        return $this->sendFailedWithStatusCode("Không tìm thấy vai trò", Response::HTTP_NOT_FOUND);
                    }
                } catch (\Exception $e) {
                    DB::rollBack();
                    return $this->sendError($e->getMessage());
                }
            }
        }
    }

    public function destroy($id)
    {
        if (!$this->canCUD()) {
            return $this->sendError("Không có quyền hạn", [], Response::HTTP_FORBIDDEN);
        } else {
            $role = Role::find($id);

            if ($role) {
                if ($role->delete()) {
                    return $this->sendSuccess("Xóa vai trò thành công");
                } else {
                    return $this->sendFailedWithStatusCode("Xóa vai trò thất bại", Response::HTTP_NOT_MODIFIED);
                }
            } else {
                return $this->sendFailedWithStatusCode("Không tìm thấy vai trò", Response::HTTP_NOT_FOUND);
            }
        }
    }
}
