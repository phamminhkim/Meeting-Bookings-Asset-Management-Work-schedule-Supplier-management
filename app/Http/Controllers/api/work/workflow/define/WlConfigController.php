<?php

namespace App\Http\Controllers\api\work\workflow\define;

use App\Http\Controllers\BaseController\BaseController;
use App\Http\Controllers\Controller;
use App\Models\work\workflow\Wlphase;
use App\Repositories\work\workflow\define\WlConfigProcess;
use App\Repositories\work\workflow\define\WorkflowDefineMain;
use App\Repositories\work\workflow\runtime\WorkflowMain;
use Illuminate\Http\Request;
use App\Ultils\Ultils;

class WlConfigController extends BaseController
{
    public function index(Request $request)
    {
        $configProcess = new WlConfigProcess($request);
        $listObjects = $configProcess->index($request);

        $result = array();
        if ($listObjects !== null) {
            $result['success'] = 1;
            $result['data'] = $listObjects;
        } else {
            $result['success'] = 0;
            $result['errors']  = $configProcess->errors;
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

    public function update(Request $request, $id)
    {
        $configProcess = new WlConfigProcess($request);
        $updatedObject = $configProcess->update($id);

        $result = array();
        if ($updatedObject) {
            $result['success'] = 1;
            $result['data']  = $updatedObject;
        } else {
            $result['success'] = 0;
            $result['errors'] = $configProcess->errors;
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
}
