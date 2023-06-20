<?php

namespace App\Http\Controllers\api\asset;

use App\Http\Controllers\Controller;
use App\Mail\EmailNoti;
use App\Models\asset\Asset;
use App\Models\asset\AssetMy;
use App\Models\asset\AssetTool;
use App\Models\asset\AssetTransaction;
use App\Models\asset\AssetTransactionItem;
use App\Models\asset\AssetUse;
use App\Models\asset\AssetWarehouse;
use App\Notifications\CommondNotification;
use App\Notifications\NotiBaseModel;
use App\Ultils\Ultils;
use App\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AssetReportController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    $query = AssetUse::query()->with(['Department']);
    $result = array();
    $data_warehouse = AssetWarehouse::all();
    $data_user = User::all();
    $data_asset = Asset::all();
    $data_tool = AssetTool::all();
    $arr = array();
    $index=0;

    $result['data'] = array();
    try {
      $array_warehouse=explode(',', $request->asset_warehouse_id);
      $array_user_id=explode(',', $request->user_id);

      if ($request->filled('asset_warehouse_id')) {
        $query = $query->whereIn('asset_warehouse_id', $array_warehouse);
      }
      if ($request->filled('objectable_type')) {
        $query = $query->where('objectable_type', $request->objectable_type);
      }
      if ($request->filled('user_id')) {
        $query = $query->whereIn('user_id', $array_user_id);
      }
      if ($request->filled('start_date')) {
        if (!$request->filled('end_date')) {
          $not_stardate = Carbon::create($request->start_date)->endOfYear();
          $start_date_not = Carbon::create($request->start_date);
            //  dd($request->start_date . "-" . $request->end_date);
             $start_date_not->addHours(00);
             $start_date_not->addMinutes(00);
             $start_date_not->addSeconds(00);
     
     
             $query = $query->whereBetween('created_at', [$start_date_not, $not_stardate]);
        }else{

       
        $start_date = Carbon::create($request->start_date);
        $end_date = Carbon::create($request->end_date);
        $end_date->addHours(23);
        $end_date->addMinutes(59);
        $end_date->addSeconds(59);

      

        $query = $query->whereBetween('created_at', [$start_date, $end_date]);
      }

    }
      $transaction = $query->get();
      $result['data_asset'] = $data_asset;
      $result['data_tool'] = $data_tool;
      $result['data'] = $transaction;
      $result['data_warehouse'] = $data_warehouse;
      $result['data_user'] = $data_user;
      $result['success'] = "1";
    } catch (Exception $e) {
      $result['data']['errors'] = $e->getMessage();
    }


    return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit(Request $request, $id)
  {
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
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
  }
  public function report_giaodich(Request $request)
  {
    $query = AssetTransactionItem::query()->with(['objectable','user','Department']);
    $data_transaction = AssetTransaction::all();
    $query_trans = AssetTransaction::query()->with(['AssetTransactionItems']);
    $arr = array();
    $result = array();
    // $data_warehouse=AssetWarehouse::all();
    // $data_user=User::all();
    // $data_asset =Asset::all();
    // $data_tool =AssetTool::all();
    $newlist = array();

    $result['data'] = array();
    try {
      $array_warehouse=explode(',', $request->asset_warehouse_id);
      $array_user_id=explode(',', $request->user_id);

      if ($request->filled('trans_type')) {
        $query_trans = $query_trans->where('trans_type', $request->trans_type);
   
          foreach ($query_trans->get() as $value) {
            
            array_push($newlist,$value->id);
          }
          
          $query = $query->whereIn('asset_transaction_id', $newlist);      }
      if ($request->filled('asset_warehouse_id')) {
        $query = $query->whereIn('asset_warehouse_id', $array_warehouse);
      }
      if ($request->filled('objectable_type')) {
        $query = $query->where('objectable_type', $request->objectable_type);
      }
      if ($request->filled('user_id')) {
        $query = $query->whereIn('user_id', $array_user_id);
      }
    
      if ($request->filled('start_date')) {
        if (!$request->filled('end_date')) {
          $not_stardate = Carbon::create($request->start_date)->endOfYear();
          $start_date_not = Carbon::create($request->start_date);
             $start_date_not->addHours(00);
             $start_date_not->addMinutes(00);
             $start_date_not->addSeconds(00);
     

                  $query = $query->whereBetween('created_at', [$start_date_not, $not_stardate]);
        }
        else{
          $start_date = Carbon::create($request->start_date);
          $end_date = Carbon::create($request->end_date);
          $end_date->addHours(23);
          $end_date->addMinutes(59);
          $end_date->addSeconds(59);
  
       
  
          $query = $query->whereBetween('created_at', [$start_date, $end_date]);
         
        }
      

    }
   
        $transaction = $query->get();
    
   
      $result['data'] = $transaction;
      $result['data_transaction'] = $data_transaction;
     
      $result['success'] = "1";
    } catch (Exception $e) {
      $result['data']['errors'] = $e->getMessage();
    }


    return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
  }
  public function report_nhapxuatton(Request $request)
  {
  
  $query=AssetTransactionItem::query()->with(['objectable']);
    
   
  try {
    if ($request->filled('objectable_type')) {
      $query = $query->where('objectable_type', $request->objectable_type);
    }
      if ($request->filled('month')) {
    
      
        $start = Carbon::create($request->month)->startOfMonth();
        $end = Carbon::create($request->month)->endOfMonth();
      
        $query = $query->whereBetween('created_at', [ $start, $end]);
    

    }
    $newlist = array();

    $change_list=$query->get();
    $listUnique = $change_list->unique(['objectable_id']);
    foreach ($listUnique as $value) {
      $total_q=0;
      $suminput= $change_list->where('objectable_id', $value->objectable_id)->where('objectable_type', $value->objectable_type)->where('user_id','!=',null);
      $sumoutput= $change_list->where('objectable_id', $value->objectable_id)->where('objectable_type', $value->objectable_type)->where('user_id',null);
      $tondauky=$change_list->where('objectable_id', $value->objectable_id)->where('objectable_type', $value->objectable_type)->first();
    
      $value->start=$tondauky->quantity_sloc;
      $value->end=$tondauky->quantity_sloc-$suminput->sum('quantity')+$sumoutput->sum('quantity');
      $value->sum_input=$suminput->sum('quantity');
      $value->sum_output=$sumoutput->sum('quantity');
      // $value->type=$value->objectable_type;
      array_push($newlist,$value);

    }


    $result = array();

    $result['data'] = array();
    $result['data'] = $newlist;
    $result['success'] = "1";
  }catch (Exception $e) {
    $result['data']['errors'] = $e->getMessage();
  }
    return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
  }
}
