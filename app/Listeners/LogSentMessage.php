<?php

namespace App\Listeners;

use App\Models\shared\MailLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Events\MessageSent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class LogSentMessage
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  MessageSent  $event
     * @return void
     */
    public function handle(MessageSent $event)
    {
        $data = $event->data;
        if (gettype($data['data']) == 'string') {
            $hash = $data['UID'];
        }
        else if (is_a($data['data'], 'App\Notifications\NotiBaseModel')) {
            $hash = $data['data']->getUID();
        }
        $maillog = MailLog::where('hash', $hash)->first();
        $maillog->sent_success = true;
        $maillog->save();
    }
}
