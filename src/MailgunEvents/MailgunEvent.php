<?php

namespace Spatie\MailcoachMailgunFeedback\MailgunEvents;

use Illuminate\Support\Arr;
use Spatie\Mailcoach\Models\Send;

abstract class MailgunEvent
{
    protected array $payload;

    protected string $event;

    public function __construct(array $payload)
    {
        $this->payload = $payload;

        $this->event = Arr::get($payload, 'event-data.event');
    }

    abstract public function canHandlePayload(): bool;

    abstract public function handle(Send $send);
}
