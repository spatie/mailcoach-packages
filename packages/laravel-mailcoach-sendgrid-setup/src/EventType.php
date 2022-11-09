<?php

namespace Spatie\MailcoachSendgridSetup;

enum EventType: string
{
    case Open = 'open';
    case Click = 'click';
    case Bounce = 'bounce';
    case Spam = 'spam_report';
}
