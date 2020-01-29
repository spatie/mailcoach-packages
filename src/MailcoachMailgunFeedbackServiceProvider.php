<?php

namespace Spatie\MailcoachMailgunFeedback;

use Illuminate\Mail\Events\MessageSent;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class MailcoachMailgunFeedbackServiceProvider extends ServiceProvider
{
    public function register()
    {
        Route::macro('mailgunFeedback', fn (string $url) => Route::post($url, '\\' . MailgunWebhookController::class));

        Event::listen(MessageSent::class, StoreTransportMessageId::class);
    }
}
