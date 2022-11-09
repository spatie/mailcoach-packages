<?php

namespace Spatie\MailcoachSesSetup\Tests;

use Dotenv\Dotenv;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\MailcoachSesSetup\MailcoachSesSetupServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadEnvironmentVariables();

        $this->key = env('AWS_ACCESS_KEY_ID');
        $this->secret = env('AWS_SECRET_ACCESS_KEY');
        $this->region = env('AWS_DEFAULT_REGION');
    }

    protected function getPackageProviders($app)
    {
        return [
            MailcoachSesSetupServiceProvider::class,
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
