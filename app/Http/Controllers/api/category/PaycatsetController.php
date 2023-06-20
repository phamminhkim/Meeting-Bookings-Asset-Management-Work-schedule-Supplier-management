<?php

namespace App\Http\Controllers\api\category;

use App\Http\Controllers\Controller;
use App\Models\payment\Payrequest;
use App\Models\shared\Paycatset;
use Illuminate\Http\Request;

class PaycatsetController extends Controller
{
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $paycatset = Paycatset::paginate(10);

        // return DepartmentResource::collection($department);
        $result = array();
        $result['data'] = array();
        $result['data'] = $paycatset;
        $result['success'] = "1";
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }

    public function search_paycatset(Request $request)
	{
		 
			$searchterm = $request->search;
            if($searchterm != '' ){
                $paycatsetlist = Paycatset::where('name', 'like', '%' . $searchterm . '%')
			
                ->orwhere('description', 'like', '%' . $searchterm . '%')
               
                ->with('paycattypes')
				->paginate(10);
            }else{
                $paycatsetlist = Paycatset::with('paycattypes')->paginate(10);
            }
		

			//$userlist = User::paginate(5);
                // return DepartmentResource::collection($department);
            $result = array();
            $result['data'] = array();
            $result['data'] = $paycatsetlist;
            $result['success'] = "1";
            return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);

			 
		 
	}
}
