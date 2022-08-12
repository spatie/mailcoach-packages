<?php

namespace Spatie\MailcoachSendinblueFeedback;

use Illuminate\Http\Request;
use Spatie\Mailcoach\Domain\Settings\Models\Mailer;
use Spatie\WebhookClient\WebhookProcessor;

class SendinblueWebhookController
{
    public function __invoke(Request $request)
    {
        $this->registerMailerConfig($request->route('mailer'));

        $webhookConfig = SendinblueWebhookConfig::get();

        (new WebhookProcessor($request, $webhookConfig))->process();

        return response()->json(['message' => 'ok']);
    }

    public function registerMailerConfig(?string $mailer): void
    {
        if (! $mailer) {
            return;
        }

        $mailer = cache()->remember(
            "mailcoach-mailer-{$mailer}",
            now()->addMinute(),
            fn () => Mailer::findByConfigKeyName($mailer),
        );

        $mailer?->registerConfigValues();
    }
}
