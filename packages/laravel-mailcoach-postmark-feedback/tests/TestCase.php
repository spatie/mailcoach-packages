<?php

namespace Spatie\MailcoachPostmarkFeedback\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Route;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\Mailcoach\MailcoachServiceProvider;
use Spatie\MailcoachPostmarkFeedback\MailcoachPostmarkFeedbackServiceProvider;

class TestCase extends Orchestra
{
    public function setUp(): void
    {
        parent::setUp();

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Spatie\\Mailcoach\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );

        Route::mailcoach('mailcoach');

        $this->setUpDatabase();
    }

    protected function getPackageProviders($app)
    {
        return [
            LivewireServiceProvider::class,
            MailcoachPostmarkFeedbackServiceProvider::class,
            MailcoachServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $app['config']->set('mail.driver', 'log');
    }

    protected function setUpDatabase()
    {
        $createWebhookCalls = require __DIR__.'/../../../vendor/spatie/laravel-mailcoach/database/migrations/2022_02_10_000003_create_webhook_calls_table.php';
        $createWebhookCalls->up();

        $createMailcoachTables = require __DIR__.'/../../../vendor/spatie/laravel-mailcoach/database/migrations/2022_02_10_000001_create_mailcoach_tables.php';
        $createMailcoachTables->up();
    }

    public function getStub(string $name): array
    {
        $content = file_get_contents(__DIR__ . "/stubs/{$name}.json");

        return json_decode($content, true);
    }
}
