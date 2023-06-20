<?php

namespace App\Http\Controllers\api\category;

use App\Http\Controllers\Controller;
use App\Models\shared\DirectType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;
use Symfony\Component\HttpFoundation\Response;

class DirectTypeController extends Controller
{
    public function canCUD()
    {
        $user = User::find(auth()->user()->id);
        return $user->hasPermission('config_directtype');
    }

    public function index()
    {
        $result = array();

        $directTypes = DirectType::all();
        if ($directTypes !== null) {
            $result['statusCode'] = Response::HTTP_OK;
            $result['data'] = $directTypes;
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
            ]);

            $result = array();
            $result['data'] = array();

            $isFailed = $validator->fails();
            $isError = false;
            $existingData = DirectType::where('code', $request->code)->first();
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
                    $created = DirectType::create($fields);
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

        $directType = DirectType::find($id);
        if ($directType !== null) {
            $result['statusCode'] = Response::HTTP_OK;
            $result['data'] = $directType;
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
            ]);

            $isFailed = $validator->fails();
            $isError = false;
            $existingData = DirectType::where('code', $request->code)->first();
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
                    $existed = DirectType::find($id);
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
            $directType = DirectType::find($id);

            if ($directType) {
                if ($directType->delete()) {
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
