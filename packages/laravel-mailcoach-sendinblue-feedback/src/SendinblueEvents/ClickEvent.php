<?php

namespace Spatie\MailcoachSendinblueFeedback\SendinblueEvents;

use Illuminate\Support\Arr;
use Spatie\Mailcoach\Domain\Shared\Models\Send;

class ClickEvent extends SendinblueEvent
{
    public function canHandlePayload(): bool
    {
        return $this->event === 'click';
    }

    public function handle(Send $send)
    {
        $url = Arr::get($this->payload, 'link');

        $send->registerClick($url, $this->getTimestamp());
    }
}
