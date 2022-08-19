<div class="form-grid">
    <script>
        function debounce(func, timeout = 300){
            let timer;
            return (...args) => {
                clearTimeout(timer);
                timer = setTimeout(() => { func.apply(this, args); }, timeout);
            };
        }

        window.init = function() {
            marked.setOptions({
                highlight: function(code, lang) {
                    if (lang) {
                        return hljs.highlight(code, { language: lang, ignoreIllegals: true }).value;
                    } else {
                        return hljs.highlightAuto(code).value;
                    }
                },
            });

            let editor = new EasyMDE({
                autoDownloadFontAwesome: false,
                element: this.$refs.editor,
                uploadImage: true,
                placeholder: '{{ __('mailcoach - Start writing…') }}',
                initialValue: this.markdown,
                spellChecker: false,
                autoSave: false,
                status: [{
                            className: "upload-image",
                            defaultValue: ''
                        }],
                toolbar: [
                    "heading", "bold", "italic", "link",
                    "|",
                    "quote", "unordered-list", "ordered-list", "table",
                    "|",
                    {
                        name: "upload-image",
                        action: EasyMDE.drawUploadedImage,
                        className: "fa fa-image", 
                    },
                    "undo",
                    { // When FontAwesome is not auto downloaded, this loads the correct icon
                        name: "redo",
                        action: EasyMDE.redo,
                        className: "fa fa-redo",
                        title: "Redo",
                    },
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
        <x-mailcoach::template-chooser />
    @endif

    @foreach($template?->fields() ?? [['name' => 'html', 'type' => 'editor']] as $field)
        <x-mailcoach::editor-fields :name="$field['name']" :type="$field['type']" :label="$field['name'] === 'html' ? 'Markdown' : null">
            <x-slot name="editor">
                <div class="markup markup-lists markup-links markup-code"
                    wire:ignore x-data="{
                    html: @entangle('templateFieldValues.' . $field['name'] . '.html'),
                    markdown: @entangle('templateFieldValues.' . $field['name'] . '.markdown'),
                    init: init,
                }">
                    <textarea x-ref="editor"></textarea>
                </div>
            </x-slot>
        </x-mailcoach::editor-fields>
    @endforeach

    <div class="-mt-4 flex gap-4">
        <x-mailcoach::replacer-help-texts :model="$model" />
        <a class="link-dimmed" href="https://www.markdownguide.org/basic-syntax/" target="_blank">Markup syntax</a>
    </div>
    <x-mailcoach::editor-buttons :preview-html="$fullHtml" :model="$model" />
</div>
