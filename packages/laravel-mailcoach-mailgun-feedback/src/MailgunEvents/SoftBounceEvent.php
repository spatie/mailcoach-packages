<?php

namespace Spatie\MailcoachMailgunFeedback\MailgunEvents;

use Illuminate\Support\Arr;
use Spatie\Mailcoach\Domain\Shared\Models\Send;

class SoftBounceEvent extends MailgunEvent
{
    public function canHandlePayload(): bool
    {
        if ($this->event !== 'failed') {
            return false;
        };

        if (Arr::get($this->payload, 'event-data.severity') !== 'temporary') {
            return false;
        }

        return true;
    }

    public function handle(Send $send)
    {
        $send->registerBounce($this->getTimestamp(), softBounce: true);
    }
}
