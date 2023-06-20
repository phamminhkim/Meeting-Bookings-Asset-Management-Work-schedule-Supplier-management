<?php

namespace App\Http\Controllers\frontend\help;

use App\Http\Controllers\BaseController\BaseController;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HelpController extends BaseController
{
    public function index(){
        return view('help.index');
    }
   
}
