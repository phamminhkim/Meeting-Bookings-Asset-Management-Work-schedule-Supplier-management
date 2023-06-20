<?php

namespace App\Http\Controllers\api\service_sys;

use App\Http\Controllers\BaseController\BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Expr\FuncCall;

class NotificationController extends BaseController
{
    public function index(Request $request)
    {

        $notification = auth()->user()->notifications;
       // dd($request->document_type_id);
        // return DepartmentResource::collection($department);
        $result = array();
        $result['data'] = array();
        $result['data'] = $notification;
        $result['success'] = "1";
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
    
    public function store(Request $request){
      return  $this->mark_read($request);
    }
    public function mark_read(Request $request){

        //dd($request->ids);
        $result = array();
        $result['data'] = array();
        $result['success'] = "0";
        $validate = Validator::make($request->all(),[
            'ids'=>'required'
        ]);
        $fails  = $validate->fails();
        if($fails){
            $result['data']['errors'] = $validate->errors();
        }else{
           $notis =  auth()->user()->unreadNotifications->whereIn('id',$request->ids);
           foreach ($notis as $key => $noti) {
               $noti->markAsRead();
           }
            $result['success'] = "1";
        }
     //   dd($result);
        return json_encode($result, JSON_UNESCAPED_UNICODE); //Response($result);
    }
}
