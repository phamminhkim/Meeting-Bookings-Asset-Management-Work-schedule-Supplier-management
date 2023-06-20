<?php

namespace App\Http\Controllers\api\category;

use App\Http\Controllers\Controller;
use App\Models\shared\Bank;
use Illuminate\Http\Request;

class BankController extends Controller
{
    public function index()
    {
        $bank = Bank::paginate(10);

        // return DepartmentResource::collection($department);
        $result = array();
        $result['data'] = array();
        $result['data'] = $bank;
        $result['success'] = "1";
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
}
