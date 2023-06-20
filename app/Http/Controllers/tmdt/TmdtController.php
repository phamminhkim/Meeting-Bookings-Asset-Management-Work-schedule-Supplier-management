<?php

namespace App\Http\Controllers\tmdt;

use App\Http\Controllers\BaseController\BaseController;
use App\Imports\DataImport;
use App\Imports\SaleOrdersImport;
use App\Imports\SalesOrderMaVanChuyenImport;
use App\Models\shared\Group;
use App\Models\tmdt\DeliveryNote;
use App\Models\tmdt\DMSan;
use App\Models\tmdt\SaleOrders;
use App\Models\tmdt\SalesOrdersItem;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class TmdtController extends BaseController
{
    public function upload_mavanchuyen()
    {
        // $this->authorize('upload_mvc', new SaleOrders());
        $data = DB::table('tmdt_saleorders_mavanchuyen')->orderByDesc('updated_at')->paginate(15);

        return view('tmdt.upload.uploadmavanchuyen', ['data' => $data]);
    }
    public function upload_user()
    {
        // $this->authorize('upload_mvc', new SaleOrders());
        // $data = DB::table('tmdt_saleorders_mavanchuyen')->orderByDesc('updated_at')->paginate(15);

        return view('tmdt.upload.uploaduser');
    }
    public function upload_donhang()
    {

        // $this->authorize('upload_don_hang', new SaleOrders());
        $masan = DMSan::where('Active', '1')->get();
        $arr_masan = [];

        foreach ($masan as $key => $value) {
            array_push($arr_masan, $value->Ma);
        }

        return view('tmdt.upload.uploaddonhang', ['masan' => implode(',', $arr_masan)]);
    }
    public function import_user(Request $request)
    {
        $madh = '';
        $mamvc = '';
        $num = 0;
        $sheet = 0;

        $this->validate($request, [
            'select_file' => 'required|mimes:xls,xlsx',
        ]);
        $array = Excel::toArray(new DataImport(), request()->file('select_file'));

        try {

            for ($i = 1; $i <= count($array[$sheet]); ++$i) {

                $user = New User();
                $user->company_id  = $array[$sheet][$i][$num++];
                $user->department_id  = $array[$sheet][$i][$num++];
                $user->name  = $array[$sheet][$i][$num++];
                $user->username  = $array[$sheet][$i][$num++];
                $user->gender  = strtolower($array[$sheet][$i][$num++])=="nam"?1:0;
                $user->email  = $array[$sheet][$i][$num++];
                $group_name =$array[$sheet][$i][$num++];

                $re = User::where('username',  $user->username)->first();


                if ($re) {
                    return back()->with('error', 'MaNV đã tồn tại: ' . $user->username);
                }else {
                    //tạo nhân viên
                     $user->save();
                     $group = Group::where('name',$group_name)->where('company_id',$user->company_id )->first();
                     //add nhân viên vào phòng ban
                     if ($group) {
                        $group->users()->attach($user);
                     }else {
                        return back()->with('error', 'Group name không tồn tại: ' .  $group_name );
                     }

                }
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Import lỗi: ' .  $user->username . ' ' . $e->getMessage());
        }

        return back()->with('success', 'Excel Data Imported successfully.');
    }
    public function import_mavanchuyen(Request $request)
    {
        $this->authorize('upload_mvc', new SaleOrders());
        $madh = '';
        $mamvc = '';
        $sheet = 0;
        $this->validate($request, [
            'select_file' => 'required|mimes:xls,xlsx',
        ]);
        $array = Excel::toArray(new SalesOrderMaVanChuyenImport(), request()->file('select_file'));
        try {
            for ($i = 1; $i < count($array[$sheet]); ++$i) {
                $masan = $array[$sheet][$i][0];
                $madonhangsan = $array[$sheet][$i][1];
                $mavanchuyen = $array[$sheet][$i][2];

                $re = SaleOrders::where('masan', `'` . $masan . `'`)
                    ->where('madonhangsan', `'` . $madonhangsan . `'`)
                    ->update(['mavanchuyen' => `'` . $mavanchuyen . `'`]);

                if ($re != 1) {
                    return back()->with('error', 'Error update: ' . $madonhangsan);
                }
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Error update: ' . $madonhangsan . ' ' . $e->getMessage());
        }

        return back()->with('success', 'Excel Data Imported successfully.');
    }

    public function import_donhang(Request $request)
    {
        $this->authorize('upload_don_hang', new SaleOrders());
        $sheet = 0;
        $this->validate($request, [
            'select_file' => 'required|mimes:xls,xlsx',
        ]);
        $array = Excel::toArray(new SaleOrdersImport(), request()->file('select_file'));

        $list_header = [];
        $list_item = [];
        $j = 0;
        $k = 0;
        for ($i = 0; $i < count($array[$sheet]); ++$i) {
            $masan = $array[$sheet][$i]['masan'];

            $san = DMSan::find($masan);

            if ($san != null && $san->Active == '1') {
                $list_header[$j]['masan'] = $array[$sheet][$i]['masan'];
                $list_header[$j]['madonhangsan'] = $array[$sheet][$i]['ma_donhang_online'];
                $list_header[$j]['mavanchuyen'] = $array[$sheet][$i]['madonvan'];
                $list_header[$j]['ghichu'] = $array[$sheet][$i]['ghichu'];
                $list_header[$j]['company'] = $array[$sheet][$i]['company'];
                ++$j;

                $list_item[$k]['masan'] = $array[$sheet][$i]['masan'];
                $list_item[$k]['madonhangsan'] = $array[$sheet][$i]['ma_donhang_online'];
                $list_item[$k]['mahangsan'] = $array[$sheet][$i]['ma_hang_san'];
                $list_item[$k]['mavt'] = $array[$sheet][$i]['ma_hang_sap'];
                $list_item[$k]['tenvt'] = $array[$sheet][$i]['ten_mat_hang'];
                $list_item[$k]['soluong'] = $array[$sheet][$i]['soluong'];
                $list_item[$k]['soluong_qd'] = $array[$sheet][$i]['soluong_qd'];
                $list_item[$k]['dvt'] = $array[$sheet][$i]['dvt'];
                ++$k;
            }
        }

        $list_header = array_values(array_unique($list_header, SORT_REGULAR));
        $data = [];
        array_multisort(
            array_column($list_item, 'masan'),
            SORT_ASC,
            array_column($list_item, 'madonhangsan'),
            SORT_ASC,
            $list_item
        );
        for ($i = 0; $i < count($list_header); ++$i) {
            $hdr = new SaleOrders();
            $hdr->masan = $list_header[$i]['masan'];
            $hdr->madonhangsan = $list_header[$i]['madonhangsan'];
            $hdr->mavanchuyen = $list_header[$i]['mavanchuyen'];
            $hdr->ghichu = $list_header[$i]['ghichu'];
            $hdr->companycode = $list_header[$i]['company'];
            $hdr->trangthai = 0;
            $hdr->username = Auth()->user()->username;
            try {
                DB::beginTransaction();
                $re = $hdr->save();

                if ($re) {
                    $items = [];
                    $items = array_filter($list_item, function ($item) use ($hdr) {
                        return $item['masan'] == $hdr->masan && $item['madonhangsan'] == $hdr->madonhangsan;
                    });

                    foreach ($items as $item) {
                        $so_itm = new SalesOrdersItem();
                        $so_itm->tmdt_saleorders_id = $hdr->id;
                        $so_itm->mahangsan = $item['mahangsan'];
                        $so_itm->mavt = $item['mavt'];
                        $so_itm->tenvt = $item['tenvt'];
                        $so_itm->soluong = $item['soluong'];
                        $so_itm->soluong_qd = $item['soluong_qd'];
                        $so_itm->dvt = $item['dvt'];
                        $so_itm->save();
                    }
                    DB::commit();
                }
            } catch (\Exception $e) {
                DB::rollBack();

                return back()->with('error', 'Erros ' . $hdr->madonhangsan . ':' . $e->getMessage());
            }
        }

        return back()->with('success', 'Excel Data Imported successfully.');
    }

    public function get_phieugiaohang(Request $request)
    {
        $this->authorize('in_phieu_giao_hang', new SaleOrders());
        $fromdate = date('2020-10-09 00:00:00');
        $todate = date('2020-12-30 00:00:00');
        $list_phieugiao = DeliveryNote::whereBetween('created_at', [$fromdate, $todate])->orderBy('id', 'desc')->paginate(10);
        //dd($list_phieugiao);
        return view('tmdt.phieugiao.danhsachphieugiao', ['dsphieugiao' => $list_phieugiao]);
    }

    public function in_phieugiao(Request $request)
    {
        //dd( $request->ids);
        $ids = $request->ids;
        $this->validate(
            $request,
            [
                'ids' => 'required',
            ],
            [
                'ids.required' => 'Chưa chọn phiếu In',
            ]
        );

        // if ($ids == null) {
        //     return back()->with('error', 'Erros '.'Vui lòng chọn phiếu in');
        // }

        $delis = DeliveryNote::whereIn('id', $ids)->get();

        $list_all = [];

        foreach ($delis as $key => $deli) {
            $deli->print = $deli->print + 1;

            $list_so = SaleOrders::whereIn('id', explode(',', $deli->donghang_ids))->get();
            array_push($list_all, $list_so);
            $deli->save();
        }

        // dd($list_so);
        return view('tmdt.print.inphieugiao', ['list_all' => $list_all]);
    }
}
