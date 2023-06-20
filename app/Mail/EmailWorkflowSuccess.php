<?php

namespace App\Mail;

use App\Notifications\NotiBaseModel;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailWorkflowSuccess extends Mailable
{
    use Queueable, SerializesModels;
    public $data = null;
    public $userData = null;
    public $object = null;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(NotiBaseModel $data, $user, $object)
    {
        $this->userData = new User();
        $this->data = $data;
        $this->userData = $user;
        $this->object = $object;
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

        return $this->subject($this->data->title_prefix . $this->data->title)
            ->markdown('email.workflow_success', ['user' => $userData, 'data' => $data, 'object' => $object]);
        // return $this->markdown('email.object');
    }
}
