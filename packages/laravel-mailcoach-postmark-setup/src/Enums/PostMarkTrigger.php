<?php

namespace Spatie\MailcoachPostmarkSetup\Enums;

enum PostMarkTrigger: string
{
    case Open = 'Open';
    case Click = 'Click';
    case Bounce = 'Delivery';
    case SpamComplaint = 'SpamComplaint';
}
