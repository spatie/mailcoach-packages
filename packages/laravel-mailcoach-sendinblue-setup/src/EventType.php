<?php

namespace Spatie\MailcoachSendinblueSetup;

enum EventType: string
{
    case Open = 'opened';
    case Click = 'click';
    case Bounce = 'hardBounce';
    case Spam = 'spam';
}
