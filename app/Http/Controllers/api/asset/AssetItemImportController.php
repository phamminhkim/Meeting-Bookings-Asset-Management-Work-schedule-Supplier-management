<?php

namespace App\Http\Controllers\api\asset;

use App\Http\Controllers\Controller;
use App\Imports\AssetItemImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AssetItemImportController extends Controller
{

    public function store(Request $request)
    {
        try {
            $file = $request->file('file');

            Excel::import(new AssetItemImport, $file);
        } catch (\Exception $e) {
            return response()->json([
                'success' => 0,
                'message' => $e->getMessage(),
            ]);
            exit();
        }
    }
}
