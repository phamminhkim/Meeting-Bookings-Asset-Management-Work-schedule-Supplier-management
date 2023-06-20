<?php

namespace App\Mail;

use App\Notifications\NotiBaseModel;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
 

class EmailNoti extends Mailable
{
    use Queueable, SerializesModels;

    public $data =null;
    public $user = null;
   
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(NotiBaseModel $data,User $user)
    {
        $this->data = $data;
        $this->user = $user;
       
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
         
         
        $user = $this->user;
        $data = $this->data;
       
        return $this->subject($this->data->title_prefix . $this->data->title)
               ->markdown('email.email_noti', ['user' => $user, 'data'=>$data]);

        // return $this->markdown('email.email_noti');
        
            ;
    }
}
