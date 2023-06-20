<?php

namespace App\Http\Controllers\frontend\document;

use App\Http\Controllers\BaseController\BaseController;
use App\Http\Controllers\Controller;
use App\Models\document\Document;
use App\Models\shared\DocumentType;
use App\Repositories\document\DocumentBase;
use App\Repositories\document\DocumentPDNS;
use App\Ultils\Ultils;
use Illuminate\Http\Request;

class DocumentController extends DocumentFrontend
{
    public function index(Request $request)
    {
        $arrayData = parent::index($request);
        // $documentFrontend = new DocumentFrontend;
        // $documentFrontend->index($request);
        // $arrayData = $documentFrontend->index($request);
       
        return view('document.index', $arrayData);
      
    }
}
