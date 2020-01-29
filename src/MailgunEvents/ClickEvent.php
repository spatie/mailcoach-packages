<?php

namespace Spatie\MailcoachMailgunFeedback\MailgunEvents;

use Illuminate\Support\Arr;
use Spatie\Mailcoach\Models\Send;

class ClickEvent extends MailgunEvent
{
    public function canHandlePayload(): bool
    {
        return $this->event === 'clicked';
    }

    public function handle(Send $send)
    {
        $url = Arr::get($this->payload, 'event-data.url');

        $send->registerClick($url);
    }
}
