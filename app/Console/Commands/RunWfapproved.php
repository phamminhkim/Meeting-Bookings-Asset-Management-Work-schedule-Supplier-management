<?php

namespace App\Console\Commands;

use App\Mail\EmailCarSuccess;
use App\Models\car\Car;
use App\Models\shared\DocumentType;
use App\Models\shared\Wfapproved;
use App\Notifications\CommondNotification;
use App\Notifications\NotiBaseModel;
use Illuminate\Support\Facades\Notification;
use App\Notifications\DirectNotificationCar;
use App\Notifications\DirectNotificationCarApprove;
use App\Ultils\Ultils;
use App\User;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Carbon\Carbon;

class RunWfapproved extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:runwfapproved';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send warning to user approve';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $datenow =  Carbon::now()->format('Y-m-d');
        $wfapproved = Wfapproved::select('id','user_id','checkout','finished','wfapprovedable_id','expected_time')
        ->where('online','X')
        ->where('checkout',null)
        ->where('expected_time','<>',null)
        ->where('is_reminder',null)
        ->get();
        //var_export($wfapproved);
        if($wfapproved){
        for($i=0;$i<count($wfapproved);$i++){
            if($wfapproved[$i]['expected_time']== $datenow){
                $car = Car::find($wfapproved[$i]['wfapprovedable_id']);
                $data = new NotiBaseModel;
                $data->title = __('form.reminder') ;
                $data->icon = "far fa-bell";
                $data->content = $car->serial_num;
                $data->url = URL('/').'/'.Ultils::$URL_CAR_VIEW  . $car->id;
                $data->object_type =  Car::class;
                $data->object_id = $car->id;

                Notification::sendNow($wfapproved[$i]->user,new DirectNotificationCar($data,$wfapproved[$i]->user) );
                Wfapproved::where('id',$wfapproved[$i]['id'])->update(['is_reminder'=>1]);
            }
        }
    }
    }
}
