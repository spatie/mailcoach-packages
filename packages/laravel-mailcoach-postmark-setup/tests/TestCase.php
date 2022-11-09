<?php

namespace Spatie\MailcoachPostmarkSetup\Tests;

use Dotenv\Dotenv;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\MailcoachPostmarkSetup\MailcoachPostmarkSetupServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadEnvironmentVariables();

        $this->token = env('POSTMARK_SERVER_TOKEN');
    }

    protected function getPackageProviders($app)
    {
        return [
            MailcoachPostmarkSetupServiceProvider::class,
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
