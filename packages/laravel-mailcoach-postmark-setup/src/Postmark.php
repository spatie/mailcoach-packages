<?php

namespace Spatie\MailcoachPostmarkSetup;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class Postmark
{
    public function __construct(protected string $serverToken)
    {
    }

    public function hasValidServerToken(): bool
    {
        return $this->callPostmark('/server')->successful();
    }

    protected function callPostmark(
        string $endpoint,
        string $httpVerb = 'get',
        array $payload = []
    ): Response {
        return Http::withHeaders(['X-Postmark-Server-Token' => $this->serverToken])
            ->$httpVerb("https://api.sendgrid.com/$endpoint", $payload);
    }
}
