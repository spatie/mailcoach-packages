<?php

namespace Spatie\MailcoachSendgridSetup;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class Sendgrid
{
    public function __construct(protected string $apiKey)
    {

    }

    public function isValidApiKey(): bool
    {
        $response = $this->callSendGrid("v3/user/webhooks/event/settings");

        return $response->successful();
    }

    public function setupWebhook(string $url, array $events): array
    {

        $events = collect(EventType::cases())
            ->mapWithKeys(function(EventType $eventType) use ($events) {
                return [$eventType->value => in_array($eventType, $events)];
            })
            ->toArray();

        $payload = array_merge([
            'enabled' => true,
            'url' => $url,
        ], $events);

        $response = $this->callSendGrid(
            'v3/user/webhooks/event/settings',
            'patch',
            $payload
        );

        return $response->json();
    }

    public function getWebhook(): array
    {
        return $this->callSendGrid('v3/user/webhooks/event/settings')->json();
    }

    protected function callSendGrid(string $endpoint, string $httpVerb = 'get', array $payload = []): Response
    {
        return Http::withToken($this->apiKey)->$httpVerb("https://api.sendgrid.com/$endpoint", $payload);
    }
}
