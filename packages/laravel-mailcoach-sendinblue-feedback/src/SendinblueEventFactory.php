<?php

namespace Spatie\MailcoachSendinblueFeedback;

use Spatie\MailcoachSendinblueFeedback\SendinblueEvents\ClickEvent;
use Spatie\MailcoachSendinblueFeedback\SendinblueEvents\ComplaintEvent;
use Spatie\MailcoachSendinblueFeedback\SendinblueEvents\OpenEvent;
use Spatie\MailcoachSendinblueFeedback\SendinblueEvents\OtherEvent;
use Spatie\MailcoachSendinblueFeedback\SendinblueEvents\PermanentBounceEvent;
use Spatie\MailcoachSendinblueFeedback\SendinblueEvents\SendinblueEvent;
use Spatie\MailcoachSendinblueFeedback\SendinblueEvents\SoftBounceEvent;

class SendinblueEventFactory
{
    protected static array $sendinblueEvents = [
        ClickEvent::class,
        ComplaintEvent::class,
        OpenEvent::class,
        PermanentBounceEvent::class,
        SoftBounceEvent::class,
    ];

    public static function createForPayload(array $payload): SendinblueEvent
    {
        $sendinblueEvent = collect(static::$sendinblueEvents)
            ->map(fn (string $sendinblueEventClass) => new $sendinblueEventClass($payload))
            ->first(fn (SendinblueEvent $sendinblueEvent) => $sendinblueEvent->canHandlePayload());

        return $sendinblueEvent ?? new OtherEvent($payload);
    }
}
