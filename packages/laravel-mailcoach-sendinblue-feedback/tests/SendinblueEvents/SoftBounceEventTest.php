<?php

namespace Spatie\MailcoachSendinblueFeedback\Tests\SendinblueEvents;

use Illuminate\Support\Facades\Event;
use Spatie\Mailcoach\Database\Factories\SendFactory;
use Spatie\Mailcoach\Domain\Audience\Models\Subscriber;
use Spatie\Mailcoach\Domain\Campaign\Events\SoftBounceRegisteredEvent;
use Spatie\MailcoachSendinblueFeedback\SendinblueEvents\SoftBounceEvent;
use Spatie\MailcoachSendinblueFeedback\Tests\TestCase;

class SoftBounceEventTest extends TestCase
{
    /** @test */
    public function it_can_handle_a_soft_bounce_event()
    {
        Event::fake();

        $event = new SoftBounceEvent([
            'event' => 'soft_bounce',
        ]);

        $this->assertTrue($event->canHandlePayload());

        $send = SendFactory::new()
            ->for(Subscriber::factory()->state(['email' => 'example@spatie.be']))
            ->create();

        $event->handle($send);

        Event::assertDispatched(SoftBounceRegisteredEvent::class);
    }

    /**
     * @test
     */
    public function it_cannot_handle_soft_bounces()
    {
        Event::fake();

        $event = new SoftBounceEvent([
            'event' => 'hard_bounce',
        ]);

        $this->assertFalse($event->canHandlePayload());
    }
}
