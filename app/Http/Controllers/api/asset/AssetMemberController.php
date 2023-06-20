<?php

namespace App\Http\Controllers\api\asset;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\asset\Asset;
use App\Models\asset\AssetTool;
use App\Models\asset\AssetUse;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Traits\HasPermissions;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AssetMemberController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // $AssetUse = AssetUse::groupBy('user_id','objectable_type')
        // ->select('user_id','objectable_type',DB::raw('count(objectable_type) as DS_asset'),DB::raw('count(quantity) as sl_asset'))
        // ->get();
        $total_asset = 0;
        $total_tool = 0;
        $use = AssetUse::where('objectable_type','App\\Models\\asset\\AssetTool')->get();
        $use = AssetUse::where('objectable_type','App\\Models\\asset\\Asset')->get();
        $AssetUse= AssetUse::groupBy('user_id','objectable_type')->select('user_id','objectable_type',DB::raw('count(objectable_id) as count_danhsach'),DB::raw('sum(quantity) as soluong_danggiu'))->get();
        $asset= AssetUse::groupBy('user_id','objectable_type','objectable_id')->select('user_id','objectable_type','objectable_id',DB::raw('count(objectable_type) as DS_Asset'),)
        ->where('objectable_type','App\\Models\\asset\\Asset')
        ->get();
      
        
        $result = array();
        $result['data'] = array();
        $result = [
            'user_asset_tool' => $AssetUse,
            'user_asset' => $use
        ];
        $result['success'] = "1";
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
