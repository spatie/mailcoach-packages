<?php

use Spatie\MailcoachSendgridSetup\EventType;
use Spatie\MailcoachSendgridSetup\Sendgrid;
use Spatie\MailcoachSendgridSetup\Tests\TestCase;

uses(TestCase::class);

beforeEach(function () {
    $this->sendGrid = new Sendgrid($this->key);
});

it('can determine an api key is valid', function () {
    $result = $this->sendGrid->isValidApiKey();

    expect($result)->toBeTrue();
});

it('can determine an api key is invalid', function () {
    $result = (new Sendgrid('invalid-key'))->isValidApiKey();

    expect($result)->toBeFalse();
});

it('can update the webhook settings', function () {
    $url = "https://test-url.com/first";
    $this->sendGrid->setupWebhook($url, [EventType::Open]);

    $webhookSettings = $this->sendGrid->getWebhook();
    expect($webhookSettings['url'])->toBe($url)
        ->and($webhookSettings['open'])->toBeTrue()
        ->and($webhookSettings['click'])->toBeFalse();

    expect($this->sendGrid->openTrackingEnabled())->toBeTrue();
    expect($this->sendGrid->clickTrackingEnabled())->toBeFalse();

    $url = "https://test-url.com/second";
    $this->sendGrid->setupWebhook($url, [EventType::Click]);

    $webhookSettings = $this->sendGrid->getWebhook();
    expect($webhookSettings['url'])->toBe($url)
        ->and($webhookSettings['open'])->toBeFalse()
        ->and($webhookSettings['click'])->toBeTrue();

    expect($this->sendGrid->openTrackingEnabled())->toBeFalse();
    expect($this->sendGrid->clickTrackingEnabled())->toBeTrue();
});

it('can enable and disable open tracking on the account', function () {
    $this->sendGrid->enableOpenTracking();
    expect($this->sendGrid->openTrackingEnabled())->toBeTrue();

    $this->sendGrid->enableOpenTracking(false);
    expect($this->sendGrid->openTrackingEnabled())->toBeFalse();
});

it('can enable and disable click tracking on the account', function () {
    $this->sendGrid->enableClickTracking();
    expect($this->sendGrid->clickTrackingEnabled())->toBeTrue();

    $this->sendGrid->enableClickTracking(false);
    expect($this->sendGrid->clickTrackingEnabled())->toBeFalse();
});

it('can get webhook settings', function () {
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
