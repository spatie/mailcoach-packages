<?php

namespace Spatie\MailcoachSendinblueFeedback;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\WebhookClient\SignatureValidator\SignatureValidator;
use Spatie\WebhookClient\WebhookConfig;

class SendinblueSignatureValidator implements SignatureValidator
{
    public function isValid(Request $request, WebhookConfig $config): bool
    {
        return $request->get('secret') === $config->signingSecret;
    }
}
