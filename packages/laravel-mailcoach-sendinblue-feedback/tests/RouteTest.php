<?php

namespace Spatie\MailcoachSendinblueFeedback\Tests;

use Illuminate\Support\Facades\Route;

class RouteTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Route::sendinblueFeedback('sendinblue-feedback');

        config()->set('mailcoach.sendinblue_feedback.signing_secret', 'secret');
    }

    /** @test */
    public function it_provides_a_route_macro_to_handle_webhooks()
    {
        $payload = $this->getStub('complaintWebhookContent');

        $this
            ->post('sendinblue-feedback?secret=secret', $payload)
            ->assertSuccessful();
    }

    /** @test */
    public function it_fails_when_using_an_invalid_payload()
    {
        $invalidPayload = $this->getStub('complaintWebhookContent');

        $this
            ->post('sendinblue-feedback', $invalidPayload)
            ->assertStatus(500);
    }
}
