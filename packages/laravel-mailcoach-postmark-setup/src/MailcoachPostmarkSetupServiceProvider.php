<?php

namespace Spatie\MailcoachPostmarkSetup;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class MailcoachPostmarkSetupServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-mailcoach-postmark-setup');
    }
}
