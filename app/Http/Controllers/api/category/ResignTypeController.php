<?php

namespace App\Http\Controllers\api\category;

use App\Http\Controllers\Controller;
use App\Models\shared\ResignType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;
use Symfony\Component\HttpFoundation\Response;

class ResignTypeController extends Controller
{
    public function canCUD()
    {
        $user = User::find(auth()->user()->id);
        return $user->hasPermission('config_resigntype');
    }

    public function index()
    {
        $result = array();

        $ResignTypes = ResignType::all();
        if ($ResignTypes !== null) {
            $result['statusCode'] = Response::HTTP_OK;
            $result['data'] = $ResignTypes;
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
                'code' => 'required|max:50',
                'name' => 'required|max:255',
                'is_has_performance' => 'required|boolean',
            ]);

            $result = array();
            $result['data'] = array();

            $isFailed = $validator->fails();
            $isError = false;
            $existingData = ResignType::where('code', $request->code)->first();
            if ($existingData) {
                $result['data']['message']  = "Trùng mã, vui lòng nhập mã khác";
                $validator->errors()->add('code', 'Trùng mã, vui lòng nhập mã khác');
                $isError = true;
            }

            if ($isFailed || $isError) {
                $result['statusCode'] = Response::HTTP_NOT_MODIFIED;
                $result['data']['errors']  = $validator->errors();
            } else {

                try {
                    $created = ResignType::create($fields);
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

        $ResignType = ResignType::find($id);
        if ($ResignType !== null) {
            $result['statusCode'] = Response::HTTP_OK;
            $result['data'] = $ResignType;
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
                'id' => 'required',
                'code' => 'required|max:50',
                'name' => 'required|max:255',
                'is_has_performance' => 'required|boolean',
            ]);

            $isFailed = $validator->fails();
            $isError = false;
            $existingData = ResignType::where('code', $request->code)->first();
            if ($existingData && $existingData->id != $id) {
                $result['data']['message']  = "Trùng mã, vui lòng nhập mã khác";
                $validator->errors()->add('code', 'Trùng mã, vui lòng nhập mã khác');
                $isError = true;
            }

            if ($isFailed || $isError) {
                $result['statusCode'] = Response::HTTP_NOT_MODIFIED;
                $result['data']['errors']  = $validator->errors();
            } else {
                try {
                    $existed = ResignType::find($id);
                    if ($existed) {
                        $existed->code = $request->input('code');
                        $existed->name = $request->input('name');

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
            $ResignType = ResignType::find($id);

            if ($ResignType) {
                if ($ResignType->delete()) {
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
