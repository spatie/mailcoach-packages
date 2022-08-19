<?php

namespace Spatie\MailcoachPostmarkFeedback;

use Illuminate\Http\Request;
use Spatie\Mailcoach\Domain\Settings\Models\Mailer;
use Spatie\WebhookClient\WebhookProcessor;

class PostmarkWebhookController
{
    public function __invoke(Request $request)
    {
        $this->registerMailerConfig($request->route('mailer'));

        $webhookConfig = PostmarkWebhookConfig::get();
        info("[Postmark feedback] Loaded config:" . json_encode($webhookConfig));

        (new WebhookProcessor($request, $webhookConfig))->process();

        return response()->json(['message' => 'ok']);
    }

    public function registerMailerConfig(?string $mailer): void
    {
        if (! $mailer) {
            info('[Postmark feedback] No mailer given');

            return;
        }

        info("[Postmark feedback] Registering values for mailer: {$mailer} for team: " . app('currentTenant')->id);
        $mailer = cache()->remember(
            "mailcoach-mailer-{$mailer}",
            now()->addMinute(),
            fn () => Mailer::findByConfigKeyName($mailer),
        );

        if (! $mailer) {
            info("[Postmark feedback] Mailer not found.");
        }

        $mailer?->registerConfigValues();
    }
}
