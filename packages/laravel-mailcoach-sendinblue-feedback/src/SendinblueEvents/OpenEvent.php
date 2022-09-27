<?php

namespace Spatie\MailcoachSendinblueFeedback\SendinblueEvents;

use Spatie\Mailcoach\Domain\Shared\Models\Send;

class OpenEvent extends SendinblueEvent
{
    public function canHandlePayload(): bool
    {
        return $this->event === 'opened' || $this->event === 'proxy_open';
    }

    public function handle(Send $send)
    {
        return $send->registerOpen($this->getTimestamp());
    }
}
