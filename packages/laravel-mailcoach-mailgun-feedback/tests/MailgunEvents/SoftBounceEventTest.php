<?php

namespace Spatie\MailcoachMailgunFeedback\Tests\MailgunEvents;

use Generator;
use Illuminate\Support\Facades\Event;
use Spatie\Mailcoach\Database\Factories\SendFactory;
use Spatie\Mailcoach\Domain\Campaign\Events\SoftBounceRegisteredEvent;
use Spatie\MailcoachMailgunFeedback\MailgunEvents\SoftBounceEvent;
use Spatie\MailcoachMailgunFeedback\Tests\TestCase;

class SoftBounceEventTest extends TestCase
{
    /** @test */
    public function it_can_handle_a_soft_bounce_event()
    {
        Event::fake();

        $event = new SoftBounceEvent([
            'event-data' => [
                'event' => 'failed',
                'severity' => 'temporary',
                'timestamp' => 1610000000,
            ],
            'email' => 'example@spatie.be',
        ]);

        $this->assertTrue($event->canHandlePayload());

        $event->handle(SendFactory::new()->create());

        Event::assertDispatched(SoftBounceRegisteredEvent::class);
    }

    /**
     * @test
     * @dataProvider failures
     */
    public function it_cannot_handle_soft_bounces(array $payload)
    {
        $event = new SoftBounceEvent($payload);

        $this->assertFalse($event->canHandlePayload());
    }

    public function failures(): Generator
    {
        yield 'different event' => [
            [
                'event-data' => [
                    'event' => 'something-else',
                    'severity' => 'temporary',
                ],
            ],
        ];

        yield 'different type' => [
            [
                'event-data' => [
                    'event' => 'failed',
                    'severity' => 'permanent',
                ],
            ],
        ];
    }
}
