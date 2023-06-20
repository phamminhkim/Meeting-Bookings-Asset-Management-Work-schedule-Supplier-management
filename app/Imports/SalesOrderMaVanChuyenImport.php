<?php

namespace App\Imports;

use App\Models\tmdt\SalesOrderMaVanChuyen;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;

class SalesOrderMaVanChuyenImport implements ToModel
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // $so = new SalesOrderMaVanChuyen();
        // if (!empty($row[0])) {

        //     $so->madonhang = $row[0];
        //     $so->mavanchuyen = $row[1];
        //     $so->userid = Auth::user()->id;
        // } else {
        //     return null;
        // }

        return  $row;
    }
}
