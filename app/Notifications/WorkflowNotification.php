<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Mail;

class WorkflowNotification extends Notification implements ShouldQueue
{
    use Queueable;
    public $notification_data = null;

    public $email_data = null;

    /**
     * Create a new notification instance.
     * emailNoti: phải kế thừa từ  Mailable
     * @return void
     */
    public function __construct(NotiBaseModel $notification_data, $email_data)
    {
        $this->notification_data = $notification_data;
        $this->email_data = $email_data;
    }
    // protected $casts = [
    //     'data' => 'array'
    // ];
    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast', 'mail'];
    }
    public function toDatabase($notifiable)
    {
        return [
            'data' => $this->notification_data,
        ];
    }
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'data' => $this->notification_data,
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
        if ($this->email_data && $this->email_data instanceof Mailable) {
            return ($this->email_data);
        }
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
