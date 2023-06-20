<?php

namespace App\Console\Commands;

use App\Mail\EmailCarRequest;
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

class RunWfapproveCar extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:runwfapprovecar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send info to user approve';

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
        $dateapprove =  Carbon::now()->format('Y-m-d H:i:s');
        $wfapproved = Wfapproved::select('id','user_id','checkout','finished','updated_at','wfapprovedable_id','online','expected_time','step')
        ->where('online','X')
        ->where('checkout',null)
        ->where('is_reminder',1)
        ->where('expected_time','<>',null)
        ->get();

        for($j=0;$j<count($wfapproved);$j++){
            $car = Car::find($wfapproved[$j]['wfapprovedable_id']);
            $documentType = DocumentType::find($car->document_type_id);
          // dd($car);
            //dd(count($wfapproved));
            //var_export($car[$j]['id']);
                    $approveDate =   strtotime ( '+1 day' , strtotime ($wfapproved[$j]['expected_time']) ) ;
                    //$approveDate = strtotime($wfapproved[$j]['expected_time']) ;
                    $approveDate =  date('Y-m-d', $approveDate);

                    if($approveDate==$datenow){
                       // var_dump($approveDate);
                       $updateData = Wfapproved::where('id',$wfapproved[$j]['id'])->update(['checkin'=>$dateapprove,'checkout'=>$dateapprove,'release'=>2,'finished'=>1]);
                       if($updateData) {
                        if($documentType->code=='SCAR' && $wfapproved[$j]['step']==1){
                            Car::where('id',$car->id)->update(['car_error_id'=>0]);
                        }
                        if($documentType->code=='SCAR' && $wfapproved[$j]['step']==3){
                            Car::where('id',$car->id)->update(['status'=>2,'cause'=>0,'risk'=>0]);
                        }
                        if($documentType->code=='PCAR' && $wfapproved[$j]['step']==1){
                            Car::where('id',$car->id)->update(['car_error_id'=>7,'is_cause_measure'=>0]);
                        }
                        if($documentType->code=='PCAR' && $wfapproved[$j]['step']==4){
                            Car::where('id',$car->id)->update(['status'=>2,'cause'=>0,'risk'=>0]);
                        }
                        $data = new NotiBaseModel;
                        $data->title = __('form.reminder') ;
                        $data->icon = "far fa-bell";
                        $data->content = $car->serial_num;
                        $data->url = URL('/').'/'.Ultils::$URL_CAR_VIEW  . $car->id;
                        $data->object_type =  Car::class;
                        $data->object_id = $car->id;
                        //Gởi thông báo đến người đã quá hạn duyệt
                        Notification::sendNow($wfapproved[$j]->user,new DirectNotificationCarApprove($data,$wfapproved[$j]->user) );
                       //Gởi thông báo cho người tạo
                        $data1 = new NotiBaseModel;
                            $data1->title = "Đã duyệt " . Str::lower($documentType->name);
                            $data1->icon = "fas fa-briefcase";
                            $data1->content = $car->serial_num;
                            $data1->content_full = $documentType->name;

                            $data1->url = URL('/').'/'. Ultils::$URL_CAR_VIEW . $car->id;
                            $data->object_type =  Car::class;
                            $data->object_id = $car->id;

                            $email = new EmailCarSuccess($data1,$car->user,$car);
                            $car->user->notify(new CommondNotification($data1,$car->user,$email));
                            if($car->user_id != $car->proposer){
                                $car->proposer = User::find($car->proposer);
                                $email = new EmailCarSuccess($data1,$car->proposer,$car);
                                $car->proposer->notify(new CommondNotification($data1,$car->proposer,$email) );
                              }
                            //Gởi thông báo đến người duyệt tiếp theo
                            $next_wfapproved = Wfapproved::where('wfapprovedable_id',$car->id)
                                                            ->where('online','X')
                                                            ->where('checkout',null)
                                                            ->first();

                           //var_export($next_wfapproved);
                            if($next_wfapproved){
                                $data = new NotiBaseModel;
                                $data->title = "Yêu cầu duyệt " .Str::lower($documentType->name);
                                $data->icon = "fas fa-briefcase";
                                $data->content = $car->serial_num;
                                $data->url = URL('/').'/'.Ultils::$URL_CAR_VIEW . $car->id;
                                $data->object_type =  Car::class;
                                $data->object_id = $car->id;

                                $email = new EmailCarRequest($data, $next_wfapproved->user,$car);
                                $next_wfapproved->user->notify(new CommondNotification($data,$next_wfapproved->user,$email));
                            }
                         }

                    }
        }
    }
}
