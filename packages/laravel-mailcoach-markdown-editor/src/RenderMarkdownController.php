<?php

namespace Spatie\MailcoachMarkdownEditor;

use Illuminate\Http\Request;
use League\CommonMark\Extension\Table\TableExtension;
use Spatie\LaravelMarkdown\MarkdownRenderer;

class RenderMarkdownController
{
    public function __invoke(Request $request, MarkdownRenderer $renderer): string
    {
        $data = $request->validate([
            'markdown' => ['required'],
            'theme' => ['nullable'],
        ]);

        if (in_array(\Spatie\SidecarShiki\Functions\HighlightFunction::class, config('sidecar.functions', []))) {
            $renderer
                ->highlightCode(false)
                ->addExtension(new \Spatie\SidecarShiki\Commonmark\HighlightCodeExtension($data['theme']));
        }

        return $renderer
            ->disableAnchors()
            ->addExtension(new TableExtension())
            ->highlightTheme($data['theme'] ?? 'nord')
            ->toHtml($data['markdown']);
    }
}
