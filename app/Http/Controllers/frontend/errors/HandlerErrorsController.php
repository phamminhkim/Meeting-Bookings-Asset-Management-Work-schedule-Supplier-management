<?php

namespace App\Http\Controllers\frontend\errors;

use App\Http\Controllers\BaseController\BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HandlerErrorsController extends BaseController
{
    public function index(Request $request)
    {
        $statuscode = $request->statuscode;
        $message =   $request->message;
        if(!$statuscode){
           $statuscode = '404';
        }
        $view = 'errors.'.$statuscode;
        return view($view );
      
    }
}
