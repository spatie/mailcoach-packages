<?php

use Spatie\MailcoachPostmarkSetup\Postmark;

beforeEach(function () {
    $this->postmark = new Postmark($this->token);
});

it('can validate the server token', function () {
    expect($this->postmark->hasValidServerToken())->toBeTrue();

    expect(new Postmark('invalid-token'))->toBeFalse();
});
