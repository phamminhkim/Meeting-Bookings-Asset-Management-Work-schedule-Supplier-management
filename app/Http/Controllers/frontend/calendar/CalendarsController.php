<?php

namespace App\Http\Controllers\frontend\calendar;

use App\Http\Controllers\BaseController\BaseController;
use App\Http\Controllers\Controller;
use App\Models\calendar\CalendarDetails;
use App\Repositories\approve\ApproveMain;
use App\Ultils\ReadCurrency;
use App\Ultils\Ultils;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class CalendarsController extends BaseController
{
    public function index(Request $request)
    {


        $PayrequestFrontend = new CalendarsFrontend;
        $arrayData = $PayrequestFrontend->index($request);
        return view('calendar.calendars',$arrayData);

    }
    public function print(Request $request,$id){


       
       $info = array();

       $total = 0;
       $info['list_file'] = array();
       $list_file = array();
    }
}