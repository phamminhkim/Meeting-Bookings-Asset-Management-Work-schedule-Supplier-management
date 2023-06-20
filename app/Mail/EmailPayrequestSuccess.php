<?php

namespace App\Mail;

use App\Notifications\NotiBaseModel;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailPayrequestSuccess extends Mailable
{
    use Queueable, SerializesModels;

    public $data =null;
    public $userData = null;
    public $payRequest = null;
    public $info = null;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(NotiBaseModel $data, $user,$payRequest,$infoFormat=null)
    {
        $this->userData = new User();
        $this->data = $data;
        $this->userData = $user;
        $this->payRequest = $payRequest;
        if($infoFormat == null){
            $this->info = array();
            $this->info['decimalpoint'] = ',';
            $this->info['separator'] = '.';
            if($payRequest->currency == 'VND'){
                $this->info['decimal']  = 0;
            }   
            else{
                $this->info['decimal']  = 2;
                // $info['decimalpoint'] = '.';
                // $info['separator'] = ',';
            
            }
        }else{
            $this->info = $infoFormat;
        }
      // dd( $this->userData);
       
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
        $payRequest = $this->payRequest;
        $info = $this->info;
       
        return $this->subject( $this->data->title_prefix . $this->data->title)
        ->markdown('email.payrequest_success', ['user' => $userData, 'data'=>$data,'payrequest'=>$payRequest,'info'=>$info]);
        // return $this->markdown('email.payrequest');
    }
}
