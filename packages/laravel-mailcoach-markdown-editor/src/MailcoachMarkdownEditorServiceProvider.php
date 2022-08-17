<?php

namespace Spatie\MailcoachMarkdownEditor;

use Livewire\Livewire;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Spatie\Mailcoach\Mailcoach;

class MailcoachMarkdownEditorServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('mailcoach-markdown-editor')
            ->hasViews();

        Livewire::component('mailcoach-markdown-editor::editor', Editor::class);
    }

    public function bootingPackage()
    {
        Mailcoach::editorScript(Editor::class, 'https://cdn.jsdelivr.net/gh/highlightjs/cdn-release@11.6.0/build/highlight.min.js');
        Mailcoach::editorScript(Editor::class, 'https://cdn.jsdelivr.net/npm/marked/marked.min.js');
        Mailcoach::editorScript(Editor::class, 'https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.js');
        Mailcoach::editorStyle(Editor::class, 'https://cdn.jsdelivr.net/gh/highlightjs/cdn-release@11.6.0/build/styles/default.min.css');
        Mailcoach::editorStyle(Editor::class, 'https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.css');
    }
}
