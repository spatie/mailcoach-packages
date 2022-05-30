<?php

use Spatie\MailcoachSesSetup\MailcoachSes;
use Spatie\MailcoachSesSetup\MailcoachSesConfig;

beforeEach(function () {
    $config = new MailcoachSesConfig(
        $this->key,
        $this->secret,
        $this->region,
        'https://spatie.be/ses-feedback',
    );

    $this->mailcoachSes = new MailcoachSes($config);

    $this->mailcoachSes->uninstall();
});

it('can configure an AWS account for use with Mailcoach', function () {
    $this->mailcoachSes->install();

    expect($this->mailcoachSes->aws()->configurationSetExists('mailcoach'))->toBeTrue();
    expect($this->mailcoachSes->aws()->snsTopicExists('mailcoach'))->toBeTrue();
});

it('can remove the Mailcoach configuration for an AWS account', function () {
    $this->mailcoachSes->install();

    $this->mailcoachSes->uninstall();
    expect($this->mailcoachSes->aws()->configurationSetExists('mailcoach'))->toBeFalse();
    expect($this->mailcoachSes->aws()->snsTopicExists('mailcoach'))->toBeFalse();
});
