<?php

namespace App\Http\Controllers\frontend\car;

use App\Http\Controllers\BaseController\BaseController;
use App\Http\Controllers\Controller;
use App\Ultils\Ultils;
use Illuminate\Http\Request;


class SystemCarController extends SystemCarFrontend
{
    public function index(Request $request)
    { 
        //$type ='add';
        //return view('car.index',compact('type'));
       // dd($request->all());
        $arrayData = parent::index($request);
        return view('car.index', $arrayData);

    }
    public function statistical(Request $request)
    { 
        $arrayData = parent::index($request);
        return view('car.statistical', $arrayData);

    }
    public function statistic_status(Request $request)
    { 
        $arrayData = parent::index($request);
        return view('car.statistical', $arrayData);

    }
    public function carlog(Request $request)
    { 
        $arrayData = parent::index($request);
        return view('car.carlog', $arrayData);

    }
    
}
