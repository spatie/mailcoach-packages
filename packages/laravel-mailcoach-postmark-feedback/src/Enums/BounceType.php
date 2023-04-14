<?php

namespace Spatie\MailcoachPostmarkFeedback\Enums;

// reference: https://postmarkapp.com/developer/api/bounce-api#bounce-types
enum BounceType: string
{
    case DnsError = 'DnsError';
    case Transient = 'Transient';
    case SpamNotification = 'SpamNotification';
    case SoftBounce = 'SoftBounce/Undeliverable';
    case HardBounce = 'HardBounce';

    public static function softBounces(): array
    {
        return [
            self::DnsError,
            self::Transient,
            self::SpamNotification,
            self::SoftBounce,
        ];
    }
}
