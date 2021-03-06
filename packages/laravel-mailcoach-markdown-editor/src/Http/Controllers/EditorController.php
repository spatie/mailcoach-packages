<?php

namespace Spatie\MailcoachMarkdownEditor\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\MailcoachMarkdownEditor\Models\Upload;

class EditorController
{
    public function upload(Request $request)
    {
        $data = $request->validate([
            'file' => ['nullable', 'required_without:url', 'image'],
            'url' => ['nullable', 'url', 'required_without:file'],
        ]);

        if (isset($data['file'])) {
            $upload = Upload::create();
            $media = $upload
                ->addMediaFromRequest('file')
                ->toMediaCollection(
                    config('mailcoach-markdown-editor.collection_name', 'default'),
                    config('mailcoach-markdown-editor.disk_name'),
                );
        }

        if (isset($data['url'])) {
            /** @var Upload $upload */
            $upload = Upload::create();
            $media = $upload
                ->addMediaFromUrl($data['url'])
                ->toMediaCollection(
                    config('mailcoach-markdown-editor.collection_name', 'default'),
                    config('mailcoach-markdown-editor.disk_name'),
                );
        }

        if (! isset($media)) {
            return response()->json([
                'success' => 0,
            ]);
        }

        return response()->json([
            'success' => 1,
            'file' => [
                'url' => $media->getFullUrl('image'),
            ],
        ]);
    }
}
