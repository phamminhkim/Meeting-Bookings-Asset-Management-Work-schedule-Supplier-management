<?php

namespace App\Http\Controllers\api\upload;

use App\Http\Controllers\BaseController\BaseController;
use App\Http\Controllers\Controller;
use App\Imports\DataImport;
use App\Models\shared\Group;
use App\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class UploadController extends BaseController
{
    public function index( )
    {
        // $this->authorize('upload_mvc', new SaleOrders());
        // $data = DB::table('tmdt_saleorders_mavanchuyen')->orderByDesc('updated_at')->paginate(15);

        $list = array('import_user');

        return view('upload.index',compact('list'));
    }
    public function upload(Request $request)
    {

        $type = $request->type;
        return view('upload.index',compact('type'));
    }
    public function import(Request $request)
    {


        $type = $request->type;
        switch ($type) {
            case 'import_user':
                 $this->import_user($request);
                break;

            default:
                # code...
                break;
        }
        return view('upload.index');
    }
    private function import_user(Request $request)
    {

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
}
