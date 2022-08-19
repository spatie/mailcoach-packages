<?php

namespace Spatie\MailcoachPostmarkFeedback;

use Illuminate\Http\Request;
use Spatie\WebhookClient\SignatureValidator\SignatureValidator;
use Spatie\WebhookClient\WebhookConfig;

class PostmarkSignatureValidator implements SignatureValidator
{
    public function isValid(Request $request, WebhookConfig $config): bool
    {
        info("[Postmark feedback] Validating signature for signing secret: {$config->signingSecret}");

        if (empty($config->signingSecret)) {
            info("[Postmark feedback] Not valid: not set");

            return false;
        }

        info("[Postmark feedback] Not valid: header " . $request->header('mailcoach-signature') . " is not equal to " . $config->signingSecret);

        return $request->header('mailcoach-signature') === $config->signingSecret;
    }
}
