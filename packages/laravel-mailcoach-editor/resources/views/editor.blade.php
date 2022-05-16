@push('endhead')
    <style>
        #editor-js h1 {
            font-weight: 800;
            font-size: 36px;
            margin-bottom: 32px;
            line-height: 40px;
        }

        #editor-js h2 {
            font-size: 24px;
            font-weight: 700;
            margin-top: 48px;
            margin-bottom: 24px;
            line-height: 32px;
        }

        #editor-js h3 {
            font-size: 20px;
            font-weight: 600;
            margin-top: 32px;
            margin-bottom: 12px;
            line-height: 32px;
        }

        #editor-js h4 {
            font-weight: 600;
            margin-top: 24px;
            margin-bottom: 8px;
            line-height: 24px;
        }

        .cdx-input {
            padding: 5px 10px;
            font-size: 15px;
        }

        .ce-block__content .table-row {
            display: flex !important;
            flex-direction: row !important;
        }
    </style>
@endpush

<div>
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

        const init = function() {
            const editor = new EditorJS({
                holder: this.$refs.editor,
                data: this.json,
                tools: {
                    header: Header,
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

    <div class="mb-6">
        <x-mailcoach::template-chooser />
    </div>
    <div class="prose border rounded-md bg-gray-100 px-8 py-8" style="max-width: 50rem; padding-top: 2rem; padding-bottom: 2rem;">

        <div>
            @if($template?->containsPlaceHolders())
                <div>
                    @foreach($template->placeHolderNames() as $placeHolderName)
                        <div class="form-field max-w-full mb-6" wire:key="{{ $placeHolderName }}">
                            <label class="label" for="field_{{ $placeHolderName }}">
                                {{ \Illuminate\Support\Str::of($placeHolderName)->snake(' ')->ucfirst() }}
                            </label>

                            <div class="bg-white shadow-md min-h-full py-6 rounded-md">
                                <div wire:ignore x-data="{
                                    html: @entangle('templateFieldValues.' . $placeHolderName . '.html'),
                                    json: @entangle('templateFieldValues.' . $placeHolderName . '.json'),
                                    init: init,
                                }">
                                    <div x-ref="editor"></div>
                                </div>
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
                        json: @entangle('templateFieldValues.html.json'),
                        init: init,
                    }">
                        <div x-ref="editor"></div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="form-buttons">
        <x-mailcoach::button-secondary x-on:click.prevent="$store.modals.open('preview')" :label="__('mailcoach - Preview')"/>
        <x-mailcoach::preview-modal name="preview" :html="$fullHtml" :title="__('mailcoach - Preview') . ' - ' . $sendable->subject" />

        <x-mailcoach::button wire:click="save" :label="__('mailcoach - Save content')"/>

        <x-mailcoach::button x-on:click.prevent="$wire.save() && $store.modals.open('send-test')" class="ml-2" :label="__('mailcoach - Save and send test')"/>
        <x-mailcoach::modal name="send-test">
            <livewire:mailcoach::send-test :model="$sendable" />
        </x-mailcoach::modal>
    </div>
</div>
