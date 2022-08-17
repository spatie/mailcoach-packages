<div>
    <style>
        .EasyMDEContainer button.table {
            display: inline-block !important;
            min-width: auto !important;
            margin-top: 0 !important;
        }
    </style>
    <script>
        function debounce(func, timeout = 300){
            let timer;
            return (...args) => {
                clearTimeout(timer);
                timer = setTimeout(() => { func.apply(this, args); }, timeout);
            };
        }

        window.init = function() {
            let editor = new EasyMDE({
                element: this.$refs.editor,
                uploadImage: true,
                sideBySideFullscreen: false,
                placeholder: '{{ __('mailcoach - Start writing…') }}',
                toolbar: [
                    "undo", "redo",
                    "|",
                    "bold", "italic", "heading",
                    "|",
                    "quote", "unordered-list", "ordered-list", "horizontal-rule", "table",
                    "|",
                    "link", "image", {
                        name: "upload-image",
                        action: EasyMDE.drawUploadedImage,
                        className: "fa fa-upload", // Default icon is same as image
                        title: "Upload image",
                    },
                    "|",
                    "preview", "side-by-side", "fullscreen",
                    "|",
                    "guide",
                ],
                imageAccept: 'image/png, image/jpeg, image/gif, image/avif',
                imageUploadFunction: function(file, onSuccess, onError) {
                    if (file.size > 1024 * 1024 * 2) {
                        return onError('File cannot be larger than 2MB.');
                    }

                    if (file.type.split('/')[0] !== 'image') {
                        return onError('File must be an image.');
                    }

                    const data = new FormData();
                    data.append('file', file);

                    fetch('{{ action(\Spatie\Mailcoach\Http\Api\Controllers\UploadsController::class) }}', {
                        method: 'POST',
                        body: data,
                        credentials: 'same-origin',
                        headers: {
                            'X-CSRF-Token': '{{ csrf_token() }}',
                        },
                    })
                        .then(response => response.json())
                        .then(({ success, file }) => {
                            if (! success) {
                                return onError();
                            }

                            onSuccess(file.url);
                        });
                },
            });

            editor.codemirror.on("change", debounce(() => {
                this.markdown = editor.value();
                this.html = marked.parse(this.markdown);
            }));
        }
    </script>
    @if ($model->hasTemplates())
        <div class="mb-6">
            <x-mailcoach::template-chooser />
        </div>
    @endif

    <div>
        @if($template?->containsPlaceHolders())
            <div>
                @foreach($template->placeHolderNames() as $placeHolderName)
                    <div class="form-field max-w-full mb-6" wire:key="{{ $placeHolderName }}">
                        <label class="label" for="field_{{ $placeHolderName }}">
                            {{ \Illuminate\Support\Str::of($placeHolderName)->snake(' ')->ucfirst() }}
                        </label>

                        <div wire:ignore x-data="{
                            html: @entangle('templateFieldValues.' . $placeHolderName . '.html'),
                            markdown: @entangle('templateFieldValues.' . $placeHolderName . '.markdown'),
                            init: init,
                        }">
                            <textarea x-ref="editor"></textarea>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div>
                <label class="label" for="field_html">
                    HTML
                </label>

                <div wire:ignore x-data="{
                    html: @entangle('templateFieldValues.html.html'),
                    markdown: @entangle('templateFieldValues.html.markdown'),
                    init: init,
                }">
                    <textarea x-ref="editor"></textarea>
                </div>
            </div>
        @endif
    </div>

    <x-mailcoach::replacer-help-texts :model="$model" />
    <x-mailcoach::editor-buttons :preview-html="$fullHtml" :model="$model" />
</div>
