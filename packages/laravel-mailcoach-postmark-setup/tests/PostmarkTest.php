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

it('can configure a webhook', function() {
    $triggers = PostMarkTrigger::cases();

   $this->postmark->configureWebhook('https://example.com', $triggers);
});
