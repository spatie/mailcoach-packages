<?php

namespace Spatie\MailcoachMailgunFeedback\MailgunEvents;

use Spatie\Mailcoach\Domain\Shared\Models\Send;

class OpenEvent extends MailgunEvent
{
    public function canHandlePayload(): bool
    {
        return $this->event === 'opened';
    }

    public function handle(Send $send)
    {
        return $send->registerOpen($this->getTimestamp());
    }
}
