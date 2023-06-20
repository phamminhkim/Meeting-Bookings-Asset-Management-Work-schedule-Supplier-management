<?php

namespace App\Http\Controllers\api\tmdt;

use App\Http\Controllers\BaseController\BaseController;
use App\Http\Controllers\Controller;
use App\Models\tmdt\DeliveryNote;
use App\Models\tmdt\SaleOrders;
use App\Models\tmdt\SalesOrdersItem;
use Illuminate\Database\Events\TransactionBeginning;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Stmt\TryCatch;

class SalesOrderController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


        $so_hdr = SaleOrders::all();
        foreach ($so_hdr as $value) {
            $value->load('items');
        }

        $result = array();
        $result['data'] =  array();
        $result['data'] = $so_hdr;
        // $result['data']['item'] = $value->items();


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
        $result = array();
        $result['data'] =  array();
        $result['data']['success']  = 0;
        date_default_timezone_set("Asia/Ho_Chi_Minh");

        $validator = Validator::make($request->all(), [
            'masan' => 'required|max:4',
            'madonhangsan' => 'required|max:100',

        ]);

        $failed = $validator->fails();

        if ($failed) {
            $result['data']['errors']  = $validator->errors();
        } else {

            try {
                $re = SaleOrders::create($request->all());
                if ($re) {
                    $result['data']['success']  = 1;
                }
            } catch (\Exception $e) {
                $result['data']['errors']  =  $e->getMessage();
            }
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($mavanchuyen)
    {


        $so_hdr = SaleOrders::where('mavanchuyen', $mavanchuyen)->first();
        if ($so_hdr != null) {
            $so_hdr->load('items');
        }

        $result = array();
        $result['data'] =  array();
        $result['data'] = $so_hdr;


        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
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
    public function getSalesOrderSearch(Request $request)
    {
        // $ma = "madonhang";
        // $madh = $request->get($ma);
        $salesorder = SaleOrders::query();
        if ($request->has('madonhang')) {
            $salesorder->where('madonhang', $request->madonhang);
        }
        if ($request->has('mavanchuyen')) {
            $salesorder->where('mavanchuyen', $request->mavanchuyen);
        }
        if ($request->has('madonhangsan')) {
            $salesorder->where('madonhangsan', $request->madonhangsan);
        }
        if ($request->has('userid')) {
            $salesorder->where('userid', $request->userid);
        }
        if ($request->has('created_at')) {
            $salesorder->where('created_at', $request->created_at);
        }

        $result = array();
        $result['data'] =  array();
        $result['data']  = $salesorder;

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    public function createSalesOrder(Request $request)
    {
        $result = array();
        $result['data'] =  array();
        $result['data']['success']  = 0;
        date_default_timezone_set("Asia/Ho_Chi_Minh");


        $listso = $request->input('data');

        try {
            DB::beginTransaction();

            foreach ($listso as  $value) {

                $order = SaleOrders::where('masan', $value['masan'])
                    ->where('madonhangsan', $value['madonhangsan'])->first();
                if (!$order) {
                    $order = new SaleOrders();
                }
                $order =  SaleOrders::create([
                    'madonhang' => $value['madonhang'],
                    'mavanchuyen' => $value['mavanchuyen'],
                    'madonhangsan' => $value['madonhangsan'],
                    'username' => $value['username'],
                    'ghichu' => $value['ghichu'],
                    'trangthai' => $value['trangthai'],
                    'nguoisoan' => $value['nguoisoan'],
                    'nguoigiao' => $value['nguoigiao'],
                    'nguoinhan' => $value['nguoinhan'],
                    'companycode' => $value['companycode'],
                    'masan' => $value['masan'],
                ]);
                $insertedId = $order->id;
                $items =  $value['item'];
                foreach ($items as $product) {
                    SalesOrdersItem::create([
                        'tmdt_saleorders_id' =>  $insertedId,
                        'mavt' => $product['mavt'],
                        'tenvt' => $product['tenvt'],
                        'soluong' => $product['soluong'],
                        'soluong_qd' => $product['soluong_qd'],
                        'mahangsan' => $product['mahangsan'],

                        'dvt' => $product['dvt']
                    ]);
                }
            }

            $result['data']['success']  = 1;
            $result['data']['mess'] = "";

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            $result['data']['success']  = 0;
            $result['data']['mess']  = $e->getMessage();
        }
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    public function SetStatus(Request $request)
    {
        $result = array();
        $result['data'] =  array();
        $result['data']['success']  = 0;
        // dd($request->trangthai);
        $validator = Validator::make($request->all(), [
            'ids' => 'required',
            'trangthai' => 'required'
        ]);

        $failed = $validator->fails();
        if ($failed) {
            $result['data']['errors']  = $validator->errors();
        } else {

            try {


                $re = SaleOrders::whereIn('id', explode(',', $request->ids))
                    ->where('trangthai', '!=', 3)
                    ->update(['trangthai' => $request->trangthai]);


                if ($request->trangthai == 3) {
                    //Tạo phiếu giao
                    $deli = new DeliveryNote();
                    $deli->donghang_ids = $request->ids;
                    $deli->username = Auth()->user()->username;
                    $deli->companycode = Auth()->user()->companycode;
                    $deli->save();
                }

                if ($re)
                    $result['data']['success']  = "1";
                else {
                    $result['data']['errors']  = "Đơn đã giao xong";
                }
            } catch (\Exception $e) {
                $result['data']['errors']  =  $e->getMessage();
            }
        }
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
}
