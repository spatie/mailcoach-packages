<?php

namespace Spatie\MailcoachPostmarkFeedback;

use Spatie\MailcoachPostmarkFeedback\PostmarkEvents\ClickEvent;
use Spatie\MailcoachPostmarkFeedback\PostmarkEvents\ComplaintEvent;
use Spatie\MailcoachPostmarkFeedback\PostmarkEvents\OpenEvent;
use Spatie\MailcoachPostmarkFeedback\PostmarkEvents\OtherEvent;
use Spatie\MailcoachPostmarkFeedback\PostmarkEvents\PermanentBounceEvent;
use Spatie\MailcoachPostmarkFeedback\PostmarkEvents\PostmarkEvent;
use Spatie\MailcoachPostmarkFeedback\PostmarkEvents\SoftBounceEvent;
use Spatie\MailcoachPostmarkFeedback\PostmarkEvents\SubscriptionChangeEvent;

class PostmarkEventFactory
{
    protected static array $postmarkEvents = [
        ClickEvent::class,
        ComplaintEvent::class,
        OpenEvent::class,
        PermanentBounceEvent::class,
        SoftBounceEvent::class,
        SubscriptionChangeEvent::class,
    ];

    public static function createForPayload(array $payload): PostmarkEvent
    {
        $postmarkEvent = collect(static::$postmarkEvents)
            ->map(fn (string $postmarkEventClass) => new $postmarkEventClass($payload))
            ->first(fn (PostmarkEvent $postmarkEvent) => $postmarkEvent->canHandlePayload());

        return $postmarkEvent ?? new OtherEvent($payload);
    }
}
