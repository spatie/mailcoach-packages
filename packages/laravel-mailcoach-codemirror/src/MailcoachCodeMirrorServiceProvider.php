<?php

namespace Spatie\MailcoachCodeMirror;

use Livewire\Livewire;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Spatie\Mailcoach\Mailcoach;

class MailcoachCodeMirrorServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('mailcoach-codemirror')
            ->hasAssets()
            ->hasViews();

        Livewire::component('mailcoach-codemirror::editor', Editor::class);
    }

    public function bootingPackage()
    {
        Mailcoach::editorScript(Editor::class, asset('vendor/mailcoach-codemirror/editor.js'));
    }
}
