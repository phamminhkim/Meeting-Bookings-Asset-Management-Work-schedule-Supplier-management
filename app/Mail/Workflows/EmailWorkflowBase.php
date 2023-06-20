<?php

namespace App\Mail\Workflows;

use App\Notifications\NotiBaseModel;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class EmailWorkflowBase extends Mailable
{
    use Queueable, SerializesModels;
    public $data = null;

    private $email_template = null;
    private $email_details = null;

    public $recipient_user = null;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(NotiBaseModel $data, array $email_details, User $recipient_user)
    {
        $this->data = $data;
        $this->email_details = $email_details;

        $this->recipient_user = $recipient_user;

        $this->to($recipient_user->email, $recipient_user->name);
    }

    public function setEmailTemplate($template)
    {
        $this->email_template = $template;
    }

    public function setUsersCc($users_cc)
    {
        foreach ($users_cc as $cc) {
            $this->cc($cc->email, $cc->name);
        }
    }
    public function setUsersBcc($users_bcc)
    {
        foreach ($users_bcc as $bcc) {
            $this->bcc($bcc->email, $bcc->name);
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */

    public function build()
    {
        $email_template = 'general';

        return $this->subject($this->data->getFullTitle())
            ->markdown(
                'email.workflow.workflow_' . $email_template,
                [
                    'recipient_id' => $this->recipient_user->username,
                    'recipient_name' => $this->recipient_user->name,
                    'recipient_gender' => $this->recipient_user->gender == 0 ? 'Chị' : 'Anh',

                    'detail_data' => $this->email_details,

                    'title' => $this->data->title,
                    'serial_num' =>  $this->data->content,
                    'content' => $this->data->content_full,
                    'url' => $this->data->url,
                    'uid' => $this->data->UID
                ]
            );
    }
}
