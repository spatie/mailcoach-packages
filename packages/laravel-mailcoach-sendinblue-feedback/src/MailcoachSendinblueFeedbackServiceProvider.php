<?php

namespace Spatie\MailcoachSendinblueFeedback;

use Illuminate\Mail\Events\MessageSent;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Mailer\Bridge\Sendinblue\Transport\SendinblueTransportFactory;
use Symfony\Component\Mailer\Transport\Dsn;

class MailcoachSendinblueFeedbackServiceProvider extends ServiceProvider
{
    public function register()
    {
        Route::macro('sendinblueFeedback', fn (string $url) => Route::post("{$url}/{mailerConfigKey?}", '\\' . SendinblueWebhookController::class));

        Event::listen(MessageSent::class, StoreTransportMessageId::class);
    }

    public function boot()
    {
        Mail::extend('sendinblue', function (array $config) {
            $key = $config['key'] ?? config('services.sendinblue.key');

            return (new SendinblueTransportFactory())->create(
                Dsn::fromString("sendinblue+api://{$key}@default")
            );
        });
    }
}
