<?php

namespace App\Http\Controllers\frontend\issue;

use App\Http\Controllers\BaseController\BaseController;
use App\Http\Controllers\Controller;
use App\Models\issue\Issue;
use App\Models\shared\DocumentType;
use App\Repositories\issue\IssueBase;
use App\Repositories\issue\IssueYCDV;
use App\Ultils\Ultils;
use Illuminate\Http\Request;

class IssueController extends IssueFrontend
{
    public function index(Request $request)
    {
        $arrayData = parent::index($request);
        // $documentFrontend = new DocumentFrontend;
        // $documentFrontend->index($request);
        // $arrayData = $documentFrontend->index($request);

        return view('issue.index', $arrayData);

    }
}
