<?php

namespace Spatie\MailcoachMarkdownEditor;

use Illuminate\Support\Facades\Vite;
use Livewire\Livewire;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Spatie\Mailcoach\Mailcoach;
use Spatie\ShikiPhp\Shiki;

class MailcoachMarkdownEditorServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('mailcoach-markdown-editor')
            ->hasRoute('api')
            ->hasAssets()
            ->hasViews();

        Livewire::component('mailcoach-markdown-editor::editor', Editor::class);
    }

    public function bootingPackage()
    {
        Mailcoach::editorScript(Editor::class, Vite::asset('resources/js/editor.js', 'vendor/mailcoach-markdown-editor'));
        Mailcoach::editorStyle(Editor::class, 'https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.css');
    }
}
