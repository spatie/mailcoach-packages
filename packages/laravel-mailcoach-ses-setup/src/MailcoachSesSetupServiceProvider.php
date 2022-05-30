<?php

namespace Spatie\MailcoachSesSetup;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Spatie\MailcoachSesSetup\Commands\InstallCommand;
use Spatie\MailcoachSesSetup\Commands\UninstallCommand;

class MailcoachSesSetupServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-mailcoach-ses-setup')
            ->hasCommands(
                InstallCommand::class,
                UninstallCommand::class,
            );
    }
}
