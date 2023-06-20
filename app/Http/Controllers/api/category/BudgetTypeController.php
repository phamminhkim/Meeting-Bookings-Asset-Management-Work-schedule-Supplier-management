<?php

namespace App\Http\Controllers\api\category;

use App\Http\Controllers\Controller;
use App\Models\shared\BudgetType;
use Illuminate\Http\Request;

class BudgetTypeController extends Controller
{
    public function index(Request $request)
    {   
        $paginate = $request->paginate;
        $budgetType = BudgetType::all();

        // return DepartmentResource::collection($department);
        $result = array();
        $result['data'] = array();
        $result['data'] = $budgetType;
        $result['success'] = "1";
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
}
