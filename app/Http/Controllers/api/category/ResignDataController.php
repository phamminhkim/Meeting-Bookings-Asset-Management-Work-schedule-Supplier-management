<?php

namespace App\Http\Controllers\api\category;

use App\Http\Controllers\Controller;
use App\Models\shared\ResignData;
use App\Models\shared\ResignType;
use App\Models\shared\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;
use Symfony\Component\HttpFoundation\Response;

class ResignDataController extends Controller
{
    public function canCUD()
    {
        $user = User::find(auth()->user()->id);
        return $user->hasPermission('manage_resigndata');
    }

    public function index()
    {
        $result = array();

        $ResignDatas = ResignData::all();
        if ($ResignDatas !== null) {
            $result['statusCode'] = Response::HTTP_OK;
            $result['data'] = $ResignDatas;
        } else {
            $result['statusCode'] = Response::HTTP_NOT_FOUND;
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    public function store(Request $request)
    {
        $result = array();

        if (!$this->canCUD()) {
            $result['statusCode'] = Response::HTTP_FORBIDDEN;
        } else {
            $fields = $request->all();
            $validator = Validator::make($fields, [
                'staff_id' => 'required|max:50',
                'resign_type' => 'required|max:50'
            ]);

            $result = array();
            $result['data'] = array();

            $isFailed = $validator->fails();
            $isError = false;
            $existingUser = Employee::where('staff_id', $request->staff_id)->first();
            if (!$existingUser) {
                $result['data']['message']  = "Không tồn tại thông tin công/nhân viên";
                $validator->errors()->add('staff_id', 'Không tồn tại thông tin công/nhân viên');
                $isError = true;
            }
            $existingResignType = ResignType::find($request->resign_type);
            if (!$existingResignType) {
                $result['data']['message']  = "Không tồn tại loại nghỉ việc";
                $validator->errors()->add('resign_type', 'Không tồn tại loại nghỉ việc');
                $isError = true;
            }

            if ($isFailed || $isError) {
                $result['statusCode'] = Response::HTTP_NOT_MODIFIED;
                $result['data']['errors']  = $validator->errors();
            } else {

                try {
                    $created = ResignData::create($fields);
                    if ($created) {
                        $result['statusCode'] = Response::HTTP_CREATED;
                        $result['data'] = $created;
                    }
                } catch (\Exception $e) {
                    $result['statusCode'] = Response::HTTP_NOT_MODIFIED;
                    $result['data']['errors'] = $e->getMessage();
                }
            }
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    public function show($id)
    {
        $result = array();

        $ResignData = ResignData::find($id);
        if ($ResignData !== null) {
            $result['statusCode'] = Response::HTTP_OK;
            $result['data'] = $ResignData;
        } else {
            $result['statusCode'] = Response::HTTP_NOT_FOUND;
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    public function update(Request $request, $id)
    {
        $result = array();

        if (!$this->canCUD()) {
            $result['statusCode'] = Response::HTTP_FORBIDDEN;
        } else {
            $result['data'] = array();

            $fields = $request->all();
            $validator = Validator::make($fields, [
                'id' => 'required'
            ]);

            $isFailed = $validator->fails();
            $isError = false;
            $existingUser = Employee::where('staff_id', $request->staff_id)->first();
            if (!$existingUser) {
                $result['data']['message']  = "Không tồn tại thông tin công/nhân viên";
                $validator->errors()->add('staff_id', 'Không tồn tại thông tin công/nhân viên');
                $isError = true;
            }
            $existingResignType = ResignType::find($request->resign_type);
            if (!$existingResignType) {
                $result['data']['message']  = "Không tồn tại loại nghỉ việc";
                $validator->errors()->add('resign_type', 'Không tồn tại loại nghỉ việc');
                $isError = true;
            }

            if ($isFailed || $isError) {
                $result['statusCode'] = Response::HTTP_NOT_MODIFIED;
                $result['data']['errors']  = $validator->errors();
            } else {
                try {
                    $existed = ResignData::find($id);
                    if ($existed) {
                        if ($request->input('resign_type')) {
                            $existed->resign_type = $request->input('resign_type');
                        }
                        if ($request->input('estimate_resigntime')) {
                            $existed->estimate_resigntime = $request->input('estimate_resigntime');
                        }
                        if ($request->input('is_resigned')) {
                            $existed->is_resigned = $request->input('is_resigned');
                        }
                        if ($request->input('official_resigntime')) {
                            $existed->official_resigntime = $request->input('official_resigntime');
                        }

                        if ($existed->save()) {
                            $result['statusCode'] = Response::HTTP_OK;
                        }
                    } else {
                        $result['statusCode'] = Response::HTTP_NOT_FOUND;
                    }
                } catch (\Exception $e) {
                    $result['statusCode'] = Response::HTTP_NOT_MODIFIED;
                    $result['data']['errors'] = $e->getMessage();
                }
            }
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE);
    }

    public function destroy($id)
    {
        $result = array();

        if (!$this->canCUD()) {
            $result['statusCode'] = Response::HTTP_FORBIDDEN;
        } else {
            $ResignData = ResignData::find($id);

            if ($ResignData) {
                $ResignData->is_available = false;

                if ($ResignData->save()) {
                    $result['statusCode'] = Response::HTTP_OK;
                } else {
                    $result['statusCode'] = Response::HTTP_NOT_MODIFIED;
                }
            } else {
                $result['statusCode'] = Response::HTTP_NOT_FOUND;
            }
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE);
    }
}
