<?php

namespace App\Http\Controllers\api\work\workflow\define;

use App\Http\Controllers\BaseController\BaseController;
use App\Http\Controllers\Controller;
use App\Models\work\workflow\Wlphase;
use App\Repositories\work\workflow\define\DataObjectProcess;
use App\Repositories\work\workflow\define\WorkflowDefineMain;
use App\Repositories\work\workflow\runtime\WorkflowMain;
use Illuminate\Http\Request;
use App\Ultils\Ultils;

class DataObjectController extends BaseController
{
    public function index(Request $request)
    {
        $dataObjectProcess = new DataObjectProcess($request);
        $listObjects = $dataObjectProcess->index($request);

        $result = array();
        if ($listObjects !== null) {
            $result['success'] = 1;
            $result['data'] = $listObjects;
        } else {
            $result['success'] = 0;
            $result['errors']  = $dataObjectProcess->errors;
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    public function store(Request $request)
    {
        $dataObjectProcess = new DataObjectProcess($request);
        $createdObject = $dataObjectProcess->store();

        $result = array();
        if ($createdObject) {
            $result['success'] = 1;
            $result['data']  = $createdObject;
        } else {
            $result['success'] = 0;
            $result['errors'] = $dataObjectProcess->errors;
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

    public function update(Request $request, $id)
    {
        $dataObjectProcess = new DataObjectProcess($request);
        $updatedObject = $dataObjectProcess->update($id);

        $result = array();
        if ($updatedObject) {
            $result['success'] = 1;
            $result['data']  = $updatedObject;
        } else {
            $result['success'] = 0;
            $result['errors'] = $dataObjectProcess->errors;
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    public function destroy(Request $request, $id)
    {
        try {
            $dataObjectProcess = new DataObjectProcess($request);
            $isDeleted = $dataObjectProcess->destroy($id);

            $result = array();
            if ($isDeleted) {
                $result['success'] = 1;
            } else {
                $result['success'] = 0;
                $result['errors'] = $dataObjectProcess->errors;
            }
        } catch (\Throwable $th) {
            $result['data']['errors'] = $th->getMessage();
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    public function previewDataObjects(Request $request)
    {
        try {
            $documentBase = WorkflowMain::create($request);
            $controls = $documentBase->convertObjectsToControls($request->objects, false);

            $result = array();
            if ($controls) {
                $result['success'] = 1;
                $result['data']  = $controls;
            } else {
                $result['success'] = 0;
                $result['errors'] = $documentBase->errors;
            }
        } catch (\Throwable $th) {
                $result['success'] = 0;
                $result['errors'] = $th->getMessage();
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
}
