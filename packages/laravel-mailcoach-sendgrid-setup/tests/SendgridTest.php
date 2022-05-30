<?php

use Spatie\MailcoachSendgridSetup\EventType;
use Spatie\MailcoachSendgridSetup\Sendgrid;
use Spatie\MailcoachSendgridSetup\Tests\TestCase;

uses(TestCase::class);

beforeEach(function() {
    $this->sendGrid = new Sendgrid($this->key);
});

it('can determine an api key is valid', function() {
    $result = $this->sendGrid->isValidApiKey();

    expect($result)->toBeTrue();
});

it('can determine an api key is invalid', function() {
    $result = (new Sendgrid('invalid-key'))->isValidApiKey();

    expect($result)->toBeFalse();
});

it('can update the webhook settings', function() {
    $url = "https://test-url.com/first";
    $this->sendGrid->setupWebhook($url, [EventType::Open]);

    $webhookSettings = $this->sendGrid->getWebhook();
    expect($webhookSettings['url'])->toBe($url)
        ->and($webhookSettings['open'])->toBeTrue()
        ->and($webhookSettings['click'])->toBeFalse();

    $url = "https://test-url.com/second";
    $this->sendGrid->setupWebhook($url, [EventType::Click]);

    $webhookSettings = $this->sendGrid->getWebhook();
    expect($webhookSettings['url'])->toBe($url)
        ->and($webhookSettings['open'])->toBeFalse()
        ->and($webhookSettings['click'])->toBeTrue();
});

it('can get webhook settings', function() {
   $webhookSettings = $this->sendGrid->getWebhook();

   expect($webhookSettings)->toHaveKeys([
       'url',
       'open',
       'click',
       'bounce',
       'spam_report',
       'enabled',
   ]);
});




