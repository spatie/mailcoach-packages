<?php

namespace Spatie\MailcoachSendinblueSetup;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Spatie\MailcoachSendinblueSetup\Exceptions\CouldNotAccessAccountSetting;

class Sendinblue
{
    public function __construct(protected string $apiKey)
    {
    }

    public function isValidApiKey(): bool
    {
        return $this->callSendinblue("webhooks")->successful();
    }

    public function setupWebhook(string $url): void
    {
        $existingWebhook = $this->getWebhook($url);

        $payloadEvents = [
            EventType::Spam->value,
            EventType::Bounce->value,
            EventType::Open->value,
            EventType::Click->value,
        ];

        if ($existingWebhook) {
            $response = $this->callSendinblue("webhooks/{$existingWebhook['id']}", 'put', [
                'url' => $url,
                'events' => $payloadEvents,
            ]);

            if (! $response->successful()) {
                throw new \Exception('Could not update webhook: ' . $response->json('message'));
            }
        } else {
            $response = $this->callSendinblue('webhooks', 'post', [
                'type' => 'transactional',
                'url' => $url,
                'events' => $payloadEvents,
            ]);

            if (! $response->successful()) {
                throw new \Exception('Could not create webhook: ' . $response->json('message'));
            }
        }
    }

    public function getWebhook(string $url): ?array
    {
        $webhooks = $this->callSendinblue('webhooks')->json('webhooks');

        return collect($webhooks)->where('url', $url)->first();
    }

    public function deleteWebhook(string $url): void
    {
        $webhook = $this->getWebhook($url);

        if (! $webhook) {
            return;
        }

        $response = $this->callSendinblue("webhooks/{$webhook['id']}", 'delete');

        if (! $response->successful()) {
            throw new \Exception("Could not delete webhook: {$response->json('message')}");
        }
    }

    protected function callSendinblue(string $endpoint, string $httpVerb = 'get', array $payload = []): Response
    {
        return Http::withHeaders(['api-key' => $this->apiKey])
            ->$httpVerb("https://api.sendinblue.com/v3/$endpoint", $payload);
    }
}
