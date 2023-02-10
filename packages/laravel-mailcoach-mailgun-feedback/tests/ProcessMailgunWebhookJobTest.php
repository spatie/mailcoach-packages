<?php

namespace Spatie\MailcoachMailgunFeedback\Tests;

use Carbon\Carbon;
use Illuminate\Support\Facades\Event;
use Spatie\Mailcoach\Database\Factories\SendFactory;
use Spatie\Mailcoach\Domain\Campaign\Enums\SendFeedbackType;
use Spatie\Mailcoach\Domain\Campaign\Events\WebhookCallProcessedEvent;
use Spatie\Mailcoach\Domain\Campaign\Models\CampaignClick;
use Spatie\Mailcoach\Domain\Campaign\Models\CampaignLink;
use Spatie\Mailcoach\Domain\Campaign\Models\CampaignOpen;
use Spatie\Mailcoach\Domain\Shared\Models\Send;
use Spatie\Mailcoach\Domain\Shared\Models\SendFeedbackItem;
use Spatie\MailcoachMailgunFeedback\ProcessMailgunWebhookJob;
use Spatie\WebhookClient\Models\WebhookCall;

class ProcessMailgunWebhookJobTest extends TestCase
{
    private WebhookCall $webhookCall;

    private Send $send;

    public function setUp(): void
    {
        parent::setUp();

        $this->webhookCall = WebhookCall::create([
            'name' => 'mailgun',
            'payload' => $this->getStub('bounceWebhookContent'),
        ]);

        $this->send = SendFactory::new()->create([
            'transport_message_id' => '20130503192659.13651.20287@mg.craftremote.com',
        ]);
    }

    /** @test */
    public function it_processes_a_mailgun_bounce_webhook_call()
    {
        (new ProcessMailgunWebhookJob($this->webhookCall))->handle();

        $this->assertEquals(1, SendFeedbackItem::count());

        tap(SendFeedbackItem::first(), function (SendFeedbackItem $sendFeedbackItem) {
            $this->assertEquals(SendFeedbackType::Bounce, $sendFeedbackItem->type);
            $this->assertEquals(Carbon::createFromTimestamp(1521233195), $sendFeedbackItem->created_at);
            $this->assertTrue($this->send->is($sendFeedbackItem->send));
        });
    }

    /** @test */
    public function it_processes_a_mailgun_complaint_webhook_call()
    {
        $this->webhookCall->update(['payload' => $this->getStub('complaintWebhookContent')]);
        (new ProcessMailgunWebhookJob($this->webhookCall))->handle();

        $this->assertEquals(1, SendFeedbackItem::count());
        tap(SendFeedbackItem::first(), function (SendFeedbackItem $sendFeedbackItem) {
            $this->assertEquals(SendFeedbackType::Complaint, $sendFeedbackItem->type);
            $this->assertEquals(Carbon::createFromTimestamp(1521233123), $sendFeedbackItem->created_at);
            $this->assertTrue($this->send->is($sendFeedbackItem->send));
        });
    }

    /** @test */
    public function it_processes_a_mailgun_click_webhook_call()
    {
        $this->webhookCall->update(['payload' => $this->getStub('clickWebhookContent')]);
        (new ProcessMailgunWebhookJob($this->webhookCall))->handle();

        $this->assertEquals(1, CampaignLink::count());
        $this->assertEquals('http://example.com/signup', CampaignLink::first()->url);
        $this->assertCount(1, CampaignLink::first()->clicks);
        tap(CampaignLink::first()->clicks->first(), function (CampaignClick $campaignClick) {
            $this->assertEquals(Carbon::createFromTimestamp(1377075564), $campaignClick->created_at);
        });
    }

    /** @test */
    public function it_can_process_a_mailgun_open_webhook_call()
    {
        $this->webhookCall->update(['payload' => $this->getStub('openWebhookContent')]);
        (new ProcessMailgunWebhookJob($this->webhookCall))->handle();

        $this->assertCount(1, $this->send->campaign->opens);
        tap($this->send->campaign->opens->first(), function (CampaignOpen $campaignOpen) {
            $this->assertEquals(Carbon::createFromTimestamp(1377047343), $campaignOpen->created_at);
        });
    }

    /** @test */
    public function it_fires_an_event_after_processing_the_webhook_call()
    {
        Event::fake(WebhookCallProcessedEvent::class);

        $this->webhookCall->update(['payload' => $this->getStub('openWebhookContent')]);
        (new ProcessMailgunWebhookJob($this->webhookCall))->handle();

        Event::assertDispatched(WebhookCallProcessedEvent::class);
    }

    /** @test */
    public function it_will_not_handle_unrelated_events()
    {
        $this->webhookCall->update(['payload' => $this->getStub('otherWebhookContent')]);
        (new ProcessMailgunWebhookJob($this->webhookCall))->handle();

        $this->assertEquals(0, CampaignLink::count());
        $this->assertEquals(0, CampaignOpen::count());
        $this->assertEquals(0, SendFeedbackItem::count());
    }

    /** @test */
    public function it_does_nothing_when_it_cannot_find_the_transport_message_id()
    {
        $data = $this->webhookCall->payload;
        $data['event-data']['message']['headers']['message-id'] = 'some-other-id';

        $this->webhookCall->update([
            'payload' => $data,
        ]);

        $job = new ProcessMailgunWebhookJob($this->webhookCall);

        $job->handle();

        $this->assertEquals(0, SendFeedbackItem::count());
    }
}
