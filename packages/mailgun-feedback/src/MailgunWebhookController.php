<?php

namespace Spatie\MailcoachMailgunFeedback;

use Illuminate\Http\Request;
use Spatie\WebhookClient\WebhookProcessor;

class MailgunWebhookController
{
    public function __invoke(Request $request)
    {
        $webhookConfig = MailgunWebhookConfig::get();

        (new WebhookProcessor($request, $webhookConfig))->process();

        return response()->json(['message' => 'ok']);
    }
}
