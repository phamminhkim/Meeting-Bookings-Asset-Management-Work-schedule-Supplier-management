<?php

namespace App\Http\Controllers\frontend\productivity;

use App\Http\Controllers\BaseController\BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HrDayoffController extends BaseController
{
      /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        
         return view('productivity.dayoff.index');

    }
}
