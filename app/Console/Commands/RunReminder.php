<?php

namespace App\Console\Commands;

use App\Jobs\SendEmail;
use App\Mail\EmailNoti;
use App\Models\shared\Reminder;
use App\Notifications\CommondNotification;
use App\Notifications\DirectNotification;
use App\Notifications\EmailMessageModel;
use App\Notifications\NotiBaseModel;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class RunReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:runreminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reminder for user';

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

       // dd("test");
      $datenow =   Carbon::now()->format('Y-m-d');

      $list =  Reminder::where('active',1)
                         ->where('type_id','!=', 0 )
                         ->where('date_due', $datenow)->get();
      foreach ($list as $item) {
        $data = new NotiBaseModel;
        $data->title = __('form.reminder') ;
        $data->icon = "far fa-bell";
        $data->content = $item->content;
        $data->url =  URL('/').'/'. $item->url;
        $data->object_type = $item->reminderable_type;
        $data->object_id =  $item->reminderable_id;
        //var_dump( $data->url);
        //$data->url = URL('/').'/'.Ultils::$URL_PAYMENT_REQUEST_VIEW  . $payRequest->id;


        // if($item->reminderable)
        if(  $item->reminderable->user->id != $item->user->id  &&  method_exists($item->reminderable,'user')){

          //Gửi cho người tạo chứng từ
          // $email = new EmailNoti($data, $item->reminderable->user);
          //   $sendEmailJob = new SendEmail($email , $item->reminderable->user->email );
          //   dispatch($sendEmailJob);

          // $item->reminderable->user->notify(new CommondNotification($data,$item->reminderable->user) );

           Notification::sendNow($item->reminderable->user,new DirectNotification($data,$item->reminderable->user) );

      }

      //Gửi cho người tạo nhắc nhở
      // $email = new EmailNoti($data, $item->user);
      // $sendEmailJob = new SendEmail($email , $item->user->email );
      // dispatch($sendEmailJob);

        // $item->user->notify(new CommondNotification($data,$item->user) );

         Notification::sendNow($item->user,new DirectNotification($data,$item->user) );
        //Cập nhật trạng thái reminder
        //dd("tesst");
        if($item->type_id == 1){
            $item->active = 0;
            $item->save();

        }
       // print_r("$item->type_id=".$item->type_id);

      }

    }
}
