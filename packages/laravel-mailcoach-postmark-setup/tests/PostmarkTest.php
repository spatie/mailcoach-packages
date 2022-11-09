<?php

use Spatie\MailcoachPostmarkSetup\Enums\PostMarkTrigger;
use Spatie\MailcoachPostmarkSetup\Postmark;
use Spatie\MailcoachPostmarkSetup\Tests\TestCase;

uses(TestCase::class);

beforeEach(function () {
    $this->postmark = new Postmark($this->token);
});

it('can validate the server token', function () {
    expect($this->postmark->hasValidServerToken())->toBeTrue();

    $hasValidToken = (new Postmark('invalid-token'))->hasValidServerToken();
    expect($hasValidToken)->toBeFalse();
});

it('can retrieve message streams', function () {
    expect($this->postmark->getStreams()->count())->toBeGreaterThan(0);
});

it('can configure a webhook for a stream', function () {
    $triggers = PostMarkTrigger::cases();
    /** @var \Spatie\MailcoachPostmarkSetup\MessageStream $stream */
    $stream = $this->postmark->getStreams()->first();

    $this->postmark->configureWebhook('https://example.com', $stream->id, $triggers);

    expect($this->postmark->getWebhook('https://example.com', $stream->id))->not()->toBeNull();
    expect($this->postmark->clickTrackingEnabled())->toBeTrue();
    expect($this->postmark->openTrackingEnabled())->toBeTrue();
});

it('can delete a webhook for a stream', function () {
    $triggers = PostMarkTrigger::cases();
    /** @var \Spatie\MailcoachPostmarkSetup\MessageStream $stream */
    $stream = $this->postmark->getStreams()->first();

    $this->postmark->configureWebhook('https://example.com', $stream->id, $triggers);

    expect($this->postmark->getWebhook('https://example.com', $stream->id))->not()->toBeNull();

    $this->postmark->deleteWebhook('https://example.com', $stream->id);

    expect($this->postmark->getWebhook('https://example.com', $stream->id))->toBeNull();
});

it('can enable open tracking', function () {
    $this->postmark->enableOpenTracking();

    expect($this->postmark->openTrackingEnabled())->toBeTrue();

    $this->postmark->enableOpenTracking(false);

    expect($this->postmark->openTrackingEnabled())->toBeFalse();
});

it('can enable click tracking', function () {
    $this->postmark->enableClickTracking();

    expect($this->postmark->clickTrackingEnabled())->toBeTrue();

    $this->postmark->enableClickTracking(false);

    expect($this->postmark->clickTrackingEnabled())->toBeFalse();
});
