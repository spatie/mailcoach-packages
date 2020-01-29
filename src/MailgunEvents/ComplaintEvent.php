<?php

namespace Spatie\MailcoachMailgunFeedback\MailgunEvents;

use Spatie\Mailcoach\Models\Send;

class ComplaintEvent extends MailgunEvent
{
    public function canHandlePayload(): bool
    {
        return $this->event === 'complained';
    }

    public function handle(Send $send)
    {
        $send->registerComplaint();
    }
}
