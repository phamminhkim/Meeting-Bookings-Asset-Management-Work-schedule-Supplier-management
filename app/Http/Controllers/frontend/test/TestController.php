<?php

namespace App\Http\Controllers\frontend\test;

use App\Http\Controllers\BaseController\BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TestController extends BaseController
{
    public function index(Request $request)
    {
        return view('test.car');

    }
}
