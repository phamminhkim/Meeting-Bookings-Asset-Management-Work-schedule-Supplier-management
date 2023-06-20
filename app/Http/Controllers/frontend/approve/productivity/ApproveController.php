<?php

namespace App\Http\Controllers\frontend\approve\productivity;

use App\Http\Controllers\BaseController\BaseController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\frontend\document\DocumentFrontend;
use App\Http\Controllers\frontend\productivity\ProductivityFrontend;
use App\Models\document\Document;
use App\Models\payment\Payrequest;
use App\Models\shared\DocumentType;
use App\Repositories\document\DocumentBase;
use App\Repositories\document\DocumentPDNS;
use App\Ultils\Ultils;
use Illuminate\Http\Request;

class ApproveController extends BaseController
{
    public function index(Request $request)
    {
     
      $documentFrontend = new ProductivityFrontend;
     // $documentFrontend->index($request);
      $arrayData = $documentFrontend->index($request);
      
        return view('approve.productivity.index',$arrayData);
      
        
    }
}
