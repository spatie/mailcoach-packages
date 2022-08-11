<?php

namespace Spatie\MailcoachSendgridFeedback;

use Illuminate\Http\Request;
use Spatie\Mailcoach\Domain\Settings\Models\Mailer;

class SendgridWebhookController
{
    public function __invoke(Request $request, ?string $mailer = null)
    {
        $this->registerMailerConfig($mailer);

        $webhookConfig = SendgridWebhookConfig::get();

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
