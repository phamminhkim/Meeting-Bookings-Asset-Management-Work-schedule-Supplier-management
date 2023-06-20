<?php

namespace App\Mail;

use App\Notifications\NotiBaseModel;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailDocumentRequest extends Mailable
{
    use Queueable, SerializesModels;
    public $data =null;
    public $userData = null;
    public $object = null;
    public $info = null;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(NotiBaseModel $data, $user,$object,$infoFormat=null)
    {
        $this->userData = new User();
        $this->data = $data;
        $this->userData = $user;
        $this->object = $object;
        if($infoFormat == null){
            $this->info = array();
            $this->info['decimalpoint'] = ',';
            $this->info['separator'] = '.';
            if($object->currency == 'VND'){
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
        $object = $this->object;
        $info =$this->info;
        
        return $this->subject($this->data->title_prefix . $this->data->title)
        ->markdown('email.document_request', ['user' => $userData, 'data'=>$data,'object'=>$object,'info'=>$info]);
        // return $this->markdown('email.object');
    }
}
