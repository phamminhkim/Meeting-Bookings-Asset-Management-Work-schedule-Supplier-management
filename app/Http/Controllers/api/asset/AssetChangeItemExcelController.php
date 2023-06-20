<?php

namespace App\Http\Controllers\api\asset;

use App\Http\Controllers\Controller;
use App\Imports\AssetChangeItemImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AssetChangeItemExcelController extends Controller
{

    public function store(Request $request)
    {
        try {
            $file = $request->file('file');

            Excel::import(new AssetChangeItemImport, $file);
        } catch (\Exception $e) {
            return response()->json([
                'success' => 0,
                'message' => $e->getMessage(),
            ]);
            exit();
        }
    }

}
