<?php

namespace Spatie\MailcoachMailgunFeedback\Tests;

use Illuminate\Support\Facades\Route;

class RouteTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Route::mailgunFeedback('mailgun-feedback');
    }

    /** @test */
    public function it_provides_a_route_macro_to_handle_webhooks()
    {
        $invalidPayload = $this->getStub('complaintWebhookContent');

        $validPayload = $this->addValidSignature($invalidPayload);

        $this
            ->post('mailgun-feedback', $validPayload)
            ->assertSuccessful();
    }

    /** @test */
    public function it_fails_when_using_an_invalid_payload()
    {
        $invalidPayload = $this->getStub('complaintWebhookContent');

        $this
            ->post('mailgun-feedback', $invalidPayload)
            ->assertStatus(500);
    }
}
