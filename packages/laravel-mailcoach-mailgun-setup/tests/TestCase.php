<?php

namespace Spatie\MailcoachMailgunSetup\Tests;

use Dotenv\Dotenv;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\MailcoachMailgunSetup\MailcoachMailgunSetupServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadEnvironmentVariables();

        $this->key = env('MAILGUN_API_KEY');
        $this->domain = env('MAILGUN_DOMAIN');
    }

    protected function getPackageProviders($app)
    {
        return [
            MailcoachMailgunSetupServiceProvider::class,
        ];
    }

    protected function loadEnvironmentVariables()
    {
        if (! file_exists(__DIR__ . '/../../../.env')) {
            return;
        }

        $dotEnv = Dotenv::createImmutable(__DIR__ . '/../../..');

        $dotEnv->load();
    }
}
