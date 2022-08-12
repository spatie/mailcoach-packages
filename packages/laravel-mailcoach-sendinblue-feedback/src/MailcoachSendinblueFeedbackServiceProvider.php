<?php

namespace Spatie\MailcoachSendinblueFeedback;

use Illuminate\Mail\Events\MessageSent;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class MailcoachSendinblueFeedbackServiceProvider extends ServiceProvider
{
    public function register()
    {
        Route::macro('sendinblueFeedback', fn (string $url) => Route::post("{$url}/{mailer?}", '\\' . SendinblueWebhookController::class));

        Event::listen(MessageSent::class, StoreTransportMessageId::class);
    }
}
