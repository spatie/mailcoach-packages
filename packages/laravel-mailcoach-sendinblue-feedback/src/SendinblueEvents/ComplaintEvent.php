<?php

namespace Spatie\MailcoachSendinblueFeedback\SendinblueEvents;

use Spatie\Mailcoach\Domain\Shared\Models\Send;

class ComplaintEvent extends SendinblueEvent
{
    public function canHandlePayload(): bool
    {
        return $this->event === 'complaint' || $this->event === 'spam';
    }

    public function handle(Send $send)
    {
        $send->registerComplaint($this->getTimestamp());
    }
}
