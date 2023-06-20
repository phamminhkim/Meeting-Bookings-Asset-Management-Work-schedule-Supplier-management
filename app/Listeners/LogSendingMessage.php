<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Events\MessageSending;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use App\Models\shared\MailLog;
use Illuminate\Support\Facades\Hash;

class LogSendingMessage
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
     * @param  MessageSending  $event
     * @return void
     */
    public function handle(MessageSending $event)
    {
        $message = $event->message;
        $data = $event->data;

        if (gettype(($data['data'])) == 'object') { //Gửi mới, ko phải resend
            $maillog = new MailLog;
            $maillog->date = date('Y-m-d H:i:s');
            $maillog->subject = $message->getSubject();
            $maillog->body = $message->getBody();
            $maillog->to = $this->formatAddressField($message, 'To');
            $maillog->cc = $this->formatAddressField($message, 'Cc');
            $maillog->bcc = $this->formatAddressField($message, 'Bcc');
            $from = htmlspecialchars($this->formatAddressField($message, 'From')) ;
            $maillog->from =  $from==""?"System":$from;
            $maillog->headers =  (string)$message->getHeaders();

            $maillog->hash = $data['data']->UID;
            $maillog->sent_success = false;
            $maillog->save();
        }

    }
     /**
     * Format address strings for sender, to, cc, bcc.
     *
     * @param $message
     * @param $field
     * @return null|string
     */
    function formatAddressField($message, $field)
    {
        $headers = $message->getHeaders();

        if (!$headers->has($field)) {
            return null;
        }

        $mailboxes = $headers->get($field)->getFieldBodyModel();

        $strings = [];
        foreach ($mailboxes as $email => $name) {
            $mailboxStr = $email;
            if (null !== $name) {
                // $mailboxStr = $name . ' <' . $mailboxStr . '>';
                $mailboxStr = $name . ' ' . $mailboxStr . '';
            }
            $strings[] = $mailboxStr;
        }
        return implode(', ', $strings);
    }
}
