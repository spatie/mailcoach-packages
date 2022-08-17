<div class="form-grid">
    <script>
        function upload(data) {
            return fetch('{{ action(\Spatie\Mailcoach\Http\Api\Controllers\UploadsController::class) }}', {
                method: 'POST',
                body: data,
                credentials: 'same-origin',
                headers: {
                    'X-CSRF-Token': '{{ csrf_token() }}',
                },
            }).then(response => response.json());
        }

        window.init = function() {
            const editor = new EditorJS({
                holder: this.$refs.editor,
                data: this.json,
                autofocus: true,
                placeholder: '{{ __('Write something awesome!') }}',
                logLevel: 'ERROR',
                tools: {
                    header: {
                        class: Header,
                        config: {
                            levels: [1, 2, 3],
                        }
                    },
                    list: {
                        class: List,
                        inlineToolbar: true,
                    },
                    image: {
                        class: ImageTool,
                        config: {
                            uploader: {
                                uploadByFile(file) {
                                    const data = new FormData();
                                    data.append('file', file);

                                    return upload(data);
                                },

                                uploadByUrl(url) {
                                    const data = new FormData();
                                    data.append('url', url);

                                    return upload(data);
                                }
                            }
                        }
                    },
                    quote: Quote,
                    delimiter: Delimiter,
                    raw: RawTool,
                    table: {
                        class: Table,
                    },
                    code: CodeTool,
                    //button: Button,
                    inlineCode: {
                        class: InlineCode,
                        shortcut: 'CMD+SHIFT+M',
                    },
                },

                onChange: () => {
                    const self = this;
                    editor.save().then((outputData) => {
                        self.json = outputData;

                        fetch('{{ action(\Spatie\MailcoachEditor\Http\Controllers\RenderEditorController::class) }}', {
                            method: 'POST',
                            body: JSON.stringify(outputData),
                            credentials: 'same-origin',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-Token': '{{ csrf_token() }}',
                            }
                        })
                        .then(response => response.json())
                        .then(({ html }) => this.html = html);
                    });
                }
            });
        }
    </script>

    @if ($model->hasTemplates())
        <x-mailcoach::template-chooser />
    @endif


            @if($template?->containsPlaceHolders())
                @foreach($template->placeHolderNames() as $placeHolderName)
                    <div class="form-field max-w-full" wire:key="{{ $placeHolderName }}">
                        <label class="label" for="field_{{ $placeHolderName }}">
                            {{ \Illuminate\Support\Str::of($placeHolderName)->snake(' ')->ucfirst() }}
                        </label>

                        <div class="markup markup-lists markup-links markup-code pr-16 max-w-[750px]">
                            <div class="px-6 py-4 input bg-white" wire:ignore x-data="{
                                html: @entangle('templateFieldValues.' . $placeHolderName . '.html'),
                                json: @entangle('templateFieldValues.' . $placeHolderName . '.json'),
                                init: init,
                            }">
                                <div x-ref="editor"></div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="form-field max-w-full">
                    <label class="label" for="field_html">
                        Content
                    </label>

                    <div class="markup markup-lists markup-links markup-code pr-16 max-w-[750px]">
                        <div class="px-6 py-4 input bg-white" wire:ignore x-data="{
                            html: @entangle('templateFieldValues.html.html'),
                            json: @entangle('templateFieldValues.html.json'),
                            init: init,
                        }">
                            <div x-ref="editor"></div>
                        </div>
                    </div>
                </div>
            @endif

    <x-mailcoach::replacer-help-texts :model="$model" />

    <x-mailcoach::editor-buttons :preview-html="$fullHtml" :model="$model" />
</div>
