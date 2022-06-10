<?php

namespace Spatie\MailcoachPostmarkSetup;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Spatie\MailcoachPostmarkSetup\Enums\PostMarkTrigger;

class Postmark
{
    public function __construct(protected string $serverToken)
    {
    }

    public function hasValidServerToken(): bool
    {
        return $this->callPostmark('/server')->successful();
    }

    /**
     * @param string $url
     * @param array<int, \Spatie\MailcoachPostmarkSetup\Enums\PostMarkTrigger> $triggers
     *
     * @return void
     */
    public function configureWebhook(string $url, array $triggers = []): void
    {
        // TODO: prevent creating duplicate webhooks by listing webhooks and updating if it already exists.

        $triggers = collect(PostMarkTrigger::cases())
            ->mapWithKeys(function (PostMarkTrigger $trigger) use ($triggers) {
                $triggerProperties = [
                    'enabled' => in_array($trigger, $triggers),
                ];

                if ($trigger === PostMarkTrigger::Open) {
                    $triggerProperties['PostFirstOpenOnly'] = false;
                }

                if ($trigger === PostMarkTrigger::Bounce) {
                    $triggerProperties['IncludeContent'] = false;
                }

                if ($trigger === PostMarkTrigger::SpamComplaint) {
                    $triggerProperties['IncludeContent'] = false;
                }

                return [$trigger->value => $triggerProperties];
            })
            ->toArray();


        $response = $this->callPostmark('/webhooks', 'post', [
            'Url' => $url,
            'Triggers' => $triggers,
        ]);

        dd($response->body());

        // TODO: enable tracking on server level
    }

    protected function callPostmark(
        string $endpoint,
        string $httpVerb = 'get',
        array  $payload = []
    ): Response {
        return Http::withHeaders([
            'X-Postmark-Server-Token' => $this->serverToken,
            'Content-Type' => 'application/json',
        ])
            ->$httpVerb("https://api.postmarkapp.com/$endpoint", $payload);
    }
}
