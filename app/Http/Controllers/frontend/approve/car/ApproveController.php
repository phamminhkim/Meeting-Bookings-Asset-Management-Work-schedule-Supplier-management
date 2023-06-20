<?php

namespace App\Http\Controllers\frontend\approve\car;

use App\Http\Controllers\BaseController\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\frontend\car\SystemCarFrontend;
use App\Ultils\Ultils;
use Illuminate\Http\Request;

class ApproveController extends BaseController
{
    public function index(Request $request)
    {
            $SystemCarFrontend = new SystemCarFrontend;
            $SystemCarFrontend->index($request);
            $arrayData = $SystemCarFrontend->index($request);
            return view('approve.car.index',$arrayData);

    }
}
