<?php

namespace Spatie\MailcoachSendinblueFeedback;

use Illuminate\Mail\Events\MessageSent;

class StoreTransportMessageId
{
    public function handle(MessageSent $event)
    {
        if (! isset($event->data['send'])) {
            return;
        }

        if (! $event->message->getHeaders()->has('X-Sendinblue-Message-ID')) {
            return;
        }

        /** @var \Spatie\Mailcoach\Domain\Shared\Models\Send $send */
        $send = $event->data['send'];

        $transportMessageId = $event->message->getHeaders()->get('X-Sendinblue-Message-ID')->getBodyAsString();

        $transportMessageId = ltrim($transportMessageId, '<');
        $transportMessageId = rtrim($transportMessageId, '>');

        $send->storeTransportMessageId($transportMessageId);
    }
}
