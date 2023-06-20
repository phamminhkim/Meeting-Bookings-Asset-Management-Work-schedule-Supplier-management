<?php

namespace App\Http\Controllers\api\category;

use App\Http\Controllers\Controller;
use App\Models\shared\Vendor;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class VendorController extends Controller
{
    public function index(Request $request)
    {
        // $vendor = Vendor::paginate(10);

        // // return DepartmentResource::collection($department);
        // $result = array();
        // $result['data'] = array();
        // $result['data'] = $vendor;
        // $result['success'] = "1";
        // return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);


        $result = array();
        $result['data'] = array();
        // $group = Group::all();
        $query = Vendor::query();
        $user = User::find(Auth()->user()->id);

        try {

            if($request->filled('company_id')){
                $query = $query->where('company_id', $request->company_id);
            }

            if($request->filled('user')){

                $query = $query->where('company_id', $user->company_id);
            }

            $vendor = $query->orderBy('id','desc')->get();
            $result['data'] = $vendor;
            $result['success'] = "1";
        } catch (Exception $e) {
            $this->errors = $e->getMessage();
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    public function store(Request $request)
    {

        $result = array();
        $result['data'] =  array();
        $result['data']['success']  = 0;
        date_default_timezone_set("Asia/Ho_Chi_Minh");
        //dd( $request);
        $validator = Validator::make($request->all(), [
            'company_id' => 'required',
            'comp_name' => 'required|max:120',
            'signer' => 'sometimes|max:50',

        ]);

        $failed = $validator->fails();
            //dd($failed);
        if ($failed) {
            $result['data']['errors']  = $validator->errors();
        } else {

            try {
                $re = Vendor::create($request->all());
                if ($re) {

                    $result['data']  = $re;
                    // $result['data']['success']  = 1;
                }
            } catch (\Exception $e) {
                $result['data']['errors']  =  $e->getMessage();
            }
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    public function show($id)
    {
        $vendor = Vendor::with('company')->findOrFail($id);

        $result = array();
        $result['data'] =  array();
        $result['data'] = $vendor;


        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    public function update(Request $request, $id)
    {


        $result = array();
        $result['data'] =  array();
        $result['data']['success']  = 0;
        date_default_timezone_set("Asia/Ho_Chi_Minh");

        $validator = Validator::make($request->all(), [
            'company_id' => 'required',
            'comp_name' => 'required|max:120',


        ]);

        $failed = $validator->fails();

        if ($failed) {
            $result['data']['errors']  = $validator->errors();
        } else {


            try {

                $vendor = Vendor::findOrFail($id);
                if ($vendor) {
                    // $vendor->id =  $vendor->id;
                    $vendor->comp_name = $request->input('comp_name');
                    $vendor->signer = $request->input('signer');
                    $vendor->position = $request->input('position');
                    $vendor->tax_code = $request->input('tax_code');
                    $vendor->phone = $request->input('phone');
                    $vendor->email = $request->input('email');
                    $vendor->company_id = $request->input('company_id');
                    $vendor->address = $request->input('address');
                    $vendor->fax = $request->input('fax');
                    $vendor->contact = $request->input('contact');



                    if($vendor->save())
                     $result['data']['success']  = 1;
                }
            } catch (\Exception $e) {
                $result['data']['errors']  =  $e->getMessage();
            }
        }

        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    public function destroy($id)
    {
        // Get article
        $vendor = Vendor::findOrFail($id);
        $result = array();
        $result['data'] = array();
        $result['data']['success']  = 0;
        if( $vendor->delete() ){
            $result['data']['success']  = 1;
        }
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);

    }
}
