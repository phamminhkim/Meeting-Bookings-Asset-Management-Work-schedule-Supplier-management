<?php

namespace App\Http\Controllers\api\asset;

use App\Exports\ExportExcel;
use App\Http\Controllers\Controller;
use App\Models\asset\Asset;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportExcelAssetController extends Controller
{
    public function exportToExcel(Request $request, $attributes)
    {

        $attributes = explode(',', $attributes);
       

        return Excel::download(new ExportExcel($attributes), 'assets.xlsx', \Maatwebsite\Excel\Excel::XLSX);
    }

}
