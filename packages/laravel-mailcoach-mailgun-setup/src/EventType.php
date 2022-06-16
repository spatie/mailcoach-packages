<?php

namespace Spatie\MailcoachMailgunSetup;

enum EventType: string
{
    case Clicked = 'clicked';
    case Complained = 'complained';
    case Opened = 'opened';
    case PermanentFail = 'permanent_fail';
}
