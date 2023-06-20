<?php

namespace App\Mail;

use App\Notifications\NotiBaseModel;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailComment extends Mailable
{
    use Queueable, SerializesModels;
    public $data =null;
    public $userData = null;
    public $object = null;
   // public $info = null;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(NotiBaseModel $data, $user,$object)
    {
        $this->userData = new User();
        $this->data = $data;
        $this->userData = $user;
        $this->object = $object;
      // dd( $this->userData->id);
       
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    
    public function build()
    {
        $userData  = User::find($this->userData->id);
        // dd($userData);
        $data = $this->data;
        $object = $this->object;
        //$info =$this->info;
        
        return $this->subject($this->data->title_prefix . $this->data->title)
        ->markdown('email.car_comment', ['user' => $userData, 'data'=>$data,'object'=>$object]);
        // return $this->markdown('email.object');
    }
}
