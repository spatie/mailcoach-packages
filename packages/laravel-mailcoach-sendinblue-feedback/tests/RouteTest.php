<?php

namespace Spatie\MailcoachSendinblueFeedback\Tests;

use Illuminate\Support\Facades\Route;

class RouteTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Route::sendinblueFeedback('sendinblue-feedback');
    }

    /** @test */
    public function it_provides_a_route_macro_to_handle_webhooks()
    {
        $invalidPayload = $this->getStub('complaintWebhookContent');

        $validPayload = $this->addValidSignature($invalidPayload);

        $this
            ->post('sendinblue-feedback', $validPayload)
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
