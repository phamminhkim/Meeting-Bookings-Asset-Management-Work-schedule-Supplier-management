<?php

namespace App\Http\Controllers\api\asset;

use App\Http\Controllers\Controller;
use App\Models\asset\Asset;
use App\Models\asset\AssetUse;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AssetUseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $AssetUse = AssetUse::with(['objectable','AssetWarehouse','Department'])->get();

        // $result['data'] = $newlist;
        $result['data'] = $AssetUse;

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
    public function waiting()
    {

        $newlist = array();
        $AssetUse = AssetUse::where('time_complete_inven', null)->get();
        $AssetUsenow = AssetUse::where('time_complete_inven', '!=', null)->where('time_complete_inven', '<', Carbon::now())->get();
        foreach ($AssetUsenow as $value) {
            array_push($newlist, $value);

        }
        foreach ($AssetUse as $valuee) {
            array_push($newlist, $valuee);

        }

        $result = array();
        $result['data'] = array();
        $result['data'] = $newlist;

        $result['success'] = "1";
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
}
