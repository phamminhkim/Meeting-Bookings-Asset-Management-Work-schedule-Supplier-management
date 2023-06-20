<?php

namespace App\Imports;

use App\Models\tmdt\SaleOrders;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class SaleOrdersImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {

        // $so = new SaleOrders;
        // $col = 0;
        // if (!empty($row[0])) {

        //     $so->madonhang = $row[$col++];
        //     $so->mavanchuyen = $row[$col++];
        //     $so->userid = Auth::user()->id;
        // } else {
        //     return null;
        // }

        return   $row;
    }
}
