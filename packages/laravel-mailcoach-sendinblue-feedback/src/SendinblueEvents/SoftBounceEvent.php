<?php

namespace Spatie\MailcoachSendinblueFeedback\SendinblueEvents;

use Spatie\Mailcoach\Domain\Shared\Models\Send;
use Spatie\MailcoachSendinblueFeedback\Enums\BounceType;

class SoftBounceEvent extends SendinblueEvent
{
    public function canHandlePayload(): bool
    {
        return $this->event === BounceType::Soft->value;
    }

    public function handle(Send $send)
    {
        $send->registerBounce($this->getTimestamp(), softBounce: true);
    }
}
