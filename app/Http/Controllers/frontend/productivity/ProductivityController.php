<?php

namespace App\Http\Controllers\frontend\productivity;

use App\Http\Controllers\BaseController\BaseController;
use App\Http\Controllers\Controller;

use App\Models\shared\Currency;
use App\Models\shared\DocumentType;
use App\Models\work\workflow\runtime\WlworkflowDoc;
use App\Models\work\workflow\Wlworkflow;
use App\Repositories\approve\ApproveMain;
use App\Repositories\document\DocumentBase;
use App\Repositories\work\workflow\runtime\WorkflowBase;
use App\Ultils\ReadCurrency;
use App\Ultils\Ultils;
use Illuminate\Http\Request;

class ProductivityController extends BaseController
{
    public function getRecordList(Request $request)
    {
        $type = $request->type;
        $form_title = 'Dữ liệu năng suất';
        if ($type == 'view') {
            $filter = array();

            $filter['record_type'] = $request->record_type;
            $filter['department_id'] = $request->department_id;
            $filter['party_id'] = $request->party_id;
            $filter['year'] = $request->year;
            $filter['month'] = $request->month;

            return view('productivity.listRecord.index', compact('type', 'form_title'))->with('filter', $filter);
        }

        return view('productivity.listRecord.index', compact('type', 'form_title'));
    }

    public function getDocument(Request $request) 
    {
        $id = $request->id;
        $type = $request->type;
        $form_title = 'Trình duyệt năng suất';

        return view('productivity.document.index', compact('id', 'type', 'form_title'));
    }

    public function getFinal(Request $request) 
    {
        $input_filter = array();

        $input_filter['company_id'] = $request->company_id;
        $input_filter['year'] = $request->year;
        $input_filter['month'] = $request->month;

        $form_title = 'Bảng lương năng suất';

        return view('productivity.final.index', compact('input_filter', 'form_title'));
    }
}
