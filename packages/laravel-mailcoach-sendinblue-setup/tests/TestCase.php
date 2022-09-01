<?php

namespace Spatie\MailcoachSendinblueSetup\Tests;

use Dotenv\Dotenv;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\MailcoachSendinblueSetup\MailcoachSendinblueSetupServiceProvider;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->loadEnvironmentVariables();

        $this->key = env('SENDINBLUE_API_KEY');
    }

    protected function getPackageProviders($app)
    {
        return [
            MailcoachSendinblueSetupServiceProvider::class,
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
