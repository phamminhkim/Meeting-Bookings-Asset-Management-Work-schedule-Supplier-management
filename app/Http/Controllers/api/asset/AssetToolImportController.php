<?php

namespace App\Http\Controllers\api\asset;

use App\Http\Controllers\Controller;
use App\Imports\AssetToolImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class AssetToolImportController extends Controller
{
    
    public function store(Request $request)
    {
        try {
            $file = $request->file('file');
            Excel::import(new AssetToolImport, $file);
        } catch (\Exception $e) {
            return response()->json([
                'success' => 0,
                'message' => $e->getMessage(),
            ]);
            exit();
        }
    }

   
}
