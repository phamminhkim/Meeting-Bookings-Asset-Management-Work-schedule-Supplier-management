<?php

namespace App\Http\Controllers\frontend\payment;

use App\Http\Controllers\BaseController\BaseController;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\sloc\Goods;
use Illuminate\Http\Request;

class GoodsController extends BaseController
{
    public function index(Request $request)
    {
        $arrayData = parent::index($request);
        // $documentFrontend = new DocumentFrontend;
        // $documentFrontend->index($request);
        // $arrayData = $documentFrontend->index($request);
       
        return view('goods.index', $arrayData);
      
    }
    
}
