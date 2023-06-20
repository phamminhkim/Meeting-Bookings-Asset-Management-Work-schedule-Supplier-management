<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController\BaseController;
use Illuminate\Http\Request;

class HomeController extends BaseController
{
    public function index(Request $request)
    {
        return view('admin.dashboard.index');
    }
}
