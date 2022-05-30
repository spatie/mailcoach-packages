<?php

namespace Spatie\MailcoachSendgridSetup\Tests;

use Dotenv\Dotenv;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\MailcoachSendgridSetup\MailcoachSendgridSetupServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadEnvironmentVariables();
    }

    protected function getPackageProviders($app)
    {
        return [
            MailcoachSendgridSetupServiceProvider::class,
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
