<?php

namespace App\Http\Controllers\api\category;

use App\Http\Controllers\BaseController\BaseController;
use App\Http\Controllers\Controller;
use App\Models\shared\Permission;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class PermissionController extends BaseController
{
    public function canCUD()
    {
        $user = User::find(auth()->user()->id);
        return true;// $user->hasPermission('admin');
    }


    public function index(Request $request)
    {
        $permissions = Permission::all();

        $result = array();
        $result['data'] = $permissions;
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
                    $created_permission = Permission::create($fields);
                    if ($created_permission) {
                        return  $this->sendResponse($created_permission, "Tạo mới quyền thành công");
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
                    $existing_permission = Permission::find($id);
                    if ($existing_permission) {
                        $existing_permission->name = $fields['name'];
                        $existing_permission->description = $fields['description'];
                        
                        if ($existing_permission->save()) {
                            return $this->sendResponse($existing_permission, "Cập nhật quyền thành công");
                        } else {
                            return $this->sendFailedWithStatusCode("Cập nhật quyền thất bại", Response::HTTP_NOT_MODIFIED);
                        }
                    } else {
                        return $this->sendFailedWithStatusCode("Không tìm thấy quyền", Response::HTTP_NOT_FOUND);
                    }
                } catch (\Exception $e) {
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
            $permission = Permission::find($id);

            if ($permission) {
                if ($permission->delete()) {
                    return $this->sendSuccess("Xóa quyền thành công");
                } else {
                    return $this->sendFailedWithStatusCode("Xóa quyền thất bại", Response::HTTP_NOT_MODIFIED);
                }
            } else {
                return $this->sendFailedWithStatusCode("Không tìm thấy quyền", Response::HTTP_NOT_FOUND);
            }
        }
    }
}
