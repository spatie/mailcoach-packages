<?php

namespace Spatie\MailcoachSendgridFeedback\Enums;

/** reference: https://docs.sendgrid.com/for-developers/tracking-events/event */
enum DeliveryEventType: string
{
    case Processed = 'Processed';
    case Dropped = 'Dropped';
    case Delivered = 'Delivered';
    case Deferred = 'Deferred';
    case Bounce = 'Bounce';
    case Blocked = 'Blocked';

    public static function softBounces(): array
    {
        return [
            self::Deferred->value,
            self::Blocked->value,
        ];
    }
}
