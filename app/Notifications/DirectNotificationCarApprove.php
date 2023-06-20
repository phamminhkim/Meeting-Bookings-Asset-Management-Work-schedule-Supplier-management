<?php

namespace App\Notifications;

use App\Mail\EmailNotiCarApprove;
use App\Mail\WelcomeMail;
use App\Models\shared\Approved;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class DirectNotificationCarApprove extends Notification
{
    // use Queueable;
     public $data = null;
     public $user = null;
     public $emailNoti = null;
     

    /**
     * Create a new notification instance.
     * emailNoti: phải kế thừa từ  Mailable
     * @return void
     */
    public function __construct(NotiBaseModel $data,User  $user,$emailNoti = null)
    {
        $this->data = $data;
        $this->user = $user;
        $this->emailNoti = $emailNoti;
        
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database','broadcast','mail'];
    }
    public function toDatabase($notifiable){
        return [
            'data'=> $this->data,
           

        ];
    }
    public function toBroadcast($notifiable){
        return new BroadcastMessage([
            'data'=> $this->data,
            
        ]);
    }
    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        
        $user = $this->user;
        $data = $this->data;
        
        if($this->emailNoti && $this->emailNoti instanceof Mailable){
            return ($this->emailNoti)->to($this->user->email);
        }else{
            return (new EmailNotiCarApprove($data,$user))->to($this->user->email);
        }
        // return (new MailMessage)
        // ->line('The introduction to the notification.')
        // ->action('Notification Action', url('/'))
        // ->line('Thank you for using our application!');
    
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
