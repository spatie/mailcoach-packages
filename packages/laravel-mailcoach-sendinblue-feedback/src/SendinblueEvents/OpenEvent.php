<?php

namespace Spatie\MailcoachSendinblueFeedback\SendinblueEvents;

use Spatie\Mailcoach\Domain\Shared\Models\Send;

class OpenEvent extends SendinblueEvent
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
