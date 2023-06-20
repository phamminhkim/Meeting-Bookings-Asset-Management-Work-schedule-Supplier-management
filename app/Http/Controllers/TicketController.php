<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends BaseController
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        return view('ticket');
    }
    public function test(Request $request)
    {
        return view('test');
    }
}
