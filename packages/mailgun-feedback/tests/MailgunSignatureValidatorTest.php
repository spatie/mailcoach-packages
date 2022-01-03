<?php

namespace Spatie\MailcoachMailgunFeedback\Tests;

use Illuminate\Http\Request;
use Spatie\MailcoachMailgunFeedback\MailgunSignatureValidator;
use Spatie\MailcoachMailgunFeedback\MailgunWebhookConfig;
use Spatie\WebhookClient\WebhookConfig;

class MailgunSignatureValidatorTest extends TestCase
{
    private WebhookConfig $config;

    private MailgunSignatureValidator $validator;

    public function setUp(): void
    {
        parent::setUp();

        $this->config = MailgunWebhookConfig::get();

        $this->validator = new MailgunSignatureValidator();
    }

    private function validParams(array $overrides = []): array
    {
        return array_merge($this->addValidSignature([]), $overrides);
    }

    /** @test */
    public function it_requires_signature_data()
    {
        $request = new Request($this->validParams());

        $this->assertTrue($this->validator->isValid($request, $this->config));
    }

    /** @test * */
    public function it_fails_if_signature_is_missing()
    {
        $request = new Request($this->validParams([
            'signature' => [],
        ]));

        $this->assertFalse($this->validator->isValid($request, $this->config));
    }

    /** @test * */
    public function it_fails_if_data_is_missing()
    {
        $request = new Request($this->validParams([
            'event-data' => [],
        ]));

        $this->assertFalse($this->validator->isValid($request, $this->config));
    }

    /** @test * */
    public function it_fails_if_signature_is_invalid()
    {
        $request = new Request($this->validParams([
            "signature" => [
                "timestamp" => "1529006854",
                "token" => "a8ce0edb2dd8301dee6c2405235584e45aa91d1e9f979f3de0",
                "signature" => hash_hmac(
                    'sha256',
                    sprintf('%s%s', '1529006854', 'a8ce0edb2dd8301dee6c2405235584e45aa91d1e9f979f3de0'),
                    'a-wrong-signing-secret'
                ),
            ],
        ]));

        $this->assertFalse($this->validator->isValid($request, $this->config));
    }
}
