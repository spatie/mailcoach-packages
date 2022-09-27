<?php

namespace Spatie\MailcoachSendinblueFeedback\SendinblueEvents;

use Spatie\Mailcoach\Domain\Shared\Models\Send;

class PermanentBounceEvent extends SendinblueEvent
{
    public function canHandlePayload(): bool
    {
        return $this->event === 'hard_bounce';
    }

    public function handle(Send $send)
    {
        $send->registerBounce($this->getTimestamp());
    }
}
