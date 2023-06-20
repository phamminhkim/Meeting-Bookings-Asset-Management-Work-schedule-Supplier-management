<?php

namespace App\Mail;

use App\Notifications\NotiBaseModel;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailWorkflowComment extends Mailable
{
    use Queueable, SerializesModels;
    public $data = null;
    public $userData = null;
    public $detailData = null;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(NotiBaseModel $generalData, $userData, $detailData)
    {
        $this->data = $generalData;
        $this->userData = $userData;
        $this->detailData = $detailData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */

    public function build()
    {
        $userData  = User::find($this->userData->id);
        $generalData = $this->data;
        $detailData = $this->detailData;
        $prefix = '[eRequest] ';

        return $this->subject($prefix . $generalData->title)
            ->markdown('email.workflow_comment',  
            [
                'userData' => $userData,
                'generalData' => $generalData,
                'detailData' => $detailData,
                'data', $this->data
            ]);
    }
}
