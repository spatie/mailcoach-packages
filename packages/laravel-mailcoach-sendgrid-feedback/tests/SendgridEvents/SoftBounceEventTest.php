<?php

namespace Spatie\MailcoachSendgridFeedback\Tests\SendgridEvents;

use Generator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Spatie\Mailcoach\Database\Factories\SendFactory;
use Spatie\Mailcoach\Domain\Audience\Models\Subscriber;
use Spatie\Mailcoach\Domain\Campaign\Events\SoftBounceRegisteredEvent;
use Spatie\MailcoachSendgridFeedback\SendgridEvents\SoftBounceEvent;
use Spatie\MailcoachSendgridFeedback\Tests\TestCase;

class SoftBounceEventTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_handle_a_soft_bounce_event()
    {
        Event::fake();

        $event = new SoftBounceEvent([
            'event' => 'Blocked',
            'type' => 'blocked',
            'email' => 'example@spatie.be',
            'timestamp' => 1610000000,
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
     * @dataProvider failures
     */
    public function it_cannot_handle_soft_bounces(array $payload)
    {
        Event::fake();

        $event = new SoftBounceEvent($payload);

        $this->assertFalse($event->canHandlePayload());
    }

    public function failures(): Generator
    {
        yield 'different event' => [
            [
                'event' => 'Bounce',
                'type' => 'blocked',
            ],
        ];

        yield 'different type' => [
            [
                'event' => 'Blocked',
                'type' => 'Bounce',
            ],
        ];
    }
}
