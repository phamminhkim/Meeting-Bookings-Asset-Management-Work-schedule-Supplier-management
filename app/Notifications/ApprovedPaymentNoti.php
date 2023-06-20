<?php

namespace App\Notifications;

use App\Models\shared\Approved;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class ApprovedPaymentNoti extends Notification  implements ShouldQueue
{
    use Queueable;
     public $data = null;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(NotiBaseModel $data)
    {
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database','broadcast'];
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
        Log::info("ApprovedPaymentNoti");
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
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
