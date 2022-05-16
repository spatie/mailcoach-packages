<?php

namespace Spatie\MailcoachEditor;

use Illuminate\Contracts\View\View;
use Spatie\Mailcoach\Domain\Campaign\Livewire\EditorComponent;
use Spatie\Mailcoach\Domain\Shared\Support\TemplateRenderer;

class Editor extends EditorComponent
{
    public function render(): View
    {
        if ($this->template?->containsPlaceHolders()) {
            foreach ($this->template->placeHolderNames() as $placeHolderName) {
                if (! is_array($this->templateFieldValues[$placeHolderName] ?? '')) {
                    $this->templateFieldValues[$placeHolderName] = [];
                }

                $this->templateFieldValues[$placeHolderName]['html'] ??= '';
                $this->templateFieldValues[$placeHolderName]['json'] ??= '';
            }
        } else {
            if (! is_array($this->templateFieldValues['html'])) {
                $this->templateFieldValues['html'] = [];
            }

            $this->templateFieldValues['html']['html'] ??= '';
            $this->templateFieldValues['html']['json'] ??= '';
        }

        return view('mailcoach-editor::editor');
    }

    public function renderFullHtml()
    {
        $templateRenderer = (new TemplateRenderer($this->template?->html ?? ''));
        $this->fullHtml = $templateRenderer->render(collect($this->templateFieldValues)->map(function ($values) {
            if (is_string($values)) {
                return $values;
            }

            return $values['html'];
        })->toArray());
    }

    public static function renderBlocks(array $blocks): string
    {
        $html = "";
        foreach ($blocks as $block) {
            $rendererClass = config("mailcoach-editor.renderers.{$block['type']}");

            if ($rendererClass && is_subclass_of($rendererClass, Renderer::class)) {
                $renderer = new $rendererClass($block['data']);
                $html .= $renderer->render();
                $html .= "\n";
            }
        }

        // Replace this in the generated html as Editor.js likes to automatically add the protocol to links
        return str_replace('http://::', '::', $html);
    }
}
