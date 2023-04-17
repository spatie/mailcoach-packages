<?php

namespace Spatie\MailcoachSesFeedback\SesEvents;

use Spatie\Mailcoach\Domain\Shared\Models\Send;
use Spatie\MailcoachSesFeedback\Enums\BounceType;

class PermanentBounce extends SesEvent
{
    public function canHandlePayload(): bool
    {
        if ($this->payload['eventType'] !== 'Bounce') {
            return false;
        }

        if ($this->payload['bounce']['bounceType'] !== BounceType::Permanent->value) {
            return false;
        }

        return true;
    }

    public function handle(Send $send)
    {
        $send->registerBounce($this->getTimestamp());
    }
}
