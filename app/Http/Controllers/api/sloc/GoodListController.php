<?php

namespace App\Http\Controllers\api\sloc;

use App\Http\Controllers\Controller;
use App\Models\asset\AssetTool;
use Illuminate\Http\Request;
use App\Models\sloc\Gooddocu;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use App\Models\sloc\GooddocusDetail;
use App\Models\sloc\Goods;
use Illuminate\Support\Facades\Storage;
use App\User;
use Exception;

class GoodListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * 
     */

    private function getAllDetails($objectable_id){

        $docu = DB::select("select b.objectable_id, b.quantity, a.type 
                            from gooddocus as a, gooddocus_details as b, asset_tools  as c
                            where a.id = b.gooddocu_id and c.id = b.objectable_id and c.id=$objectable_id");

        return  $docu;
    }
    
    public function index()
    {
        // $sanphams = Goods::all();

        $sanphams = AssetTool::all();
        // $query= AssetTool::query();
        $newlist = array();
        foreach ($sanphams as  $sanpham) {
            $details = $this->getAllDetails($sanpham->id);
            foreach ($details as  $detail) {
                if($detail->type == 0){
                    $sanpham->tongnhap += $detail->quantity;
                }
                
                else  if($detail->type == 1){ 
                    $sanpham->tongxuat += $detail->quantity;
                }
                $sanpham->ton =  $sanpham->tongnhap - $sanpham->tongxuat;
            }
            array_push($newlist,$sanpham);
             
        }
        $variables = [
            'data' => $newlist,
            'success'=> "1",
           
        ];
        
        return json_encode($variables, JSON_UNESCAPED_UNICODE); //Response($result);
      
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
