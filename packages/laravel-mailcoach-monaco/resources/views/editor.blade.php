<div class="form-grid">
    <script>
        function debounce(func, timeout = 300){
            let timer;
            return (...args) => {
                clearTimeout(timer);
                timer = setTimeout(() => { func.apply(this, args); }, timeout);
            };
        }

        window.init = function () {
            require.config({ paths: { 'vs': 'https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.21.2/min/vs' }});

            window.MonacoEnvironment = {
                getWorkerUrl: function (workerId, label) {
                    return `data:text/javascript;charset=utf-8,${encodeURIComponent(`
                          self.MonacoEnvironment = {
                            baseUrl: 'https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.21.2/min/'
                          };
                          importScripts('https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.21.2/min/vs/base/worker/workerMain.js');`
                    )}`
                }
            }

            const component = this;
            require(['vs/editor/editor.main'], function () {
                let editor = monaco.editor.create(component.$refs.editor, {
                    value: component.value,
                    language: 'html',
                    minimap: {
                        enabled: false
                    },
                    fixedOverflowWidgets: {},
                    theme: '{!! config('mailcoach-monaco.theme', 'vs-light') !!}',
                    fontFamily: '{!! config('mailcoach-monaco.fontFamily', 'Menlo, Monaco, "Courier New", monospace') !!}',
                    fontSize: '{!! config('mailcoach-monaco.fontSize', '12') !!}',
                    fontWeight: '{!! config('mailcoach-monaco.fontWeight', '400') !!}',
                    fontLigatures: {!! config('mailcoach-monaco.fontLigatures', false) ? 'true' : 'false' !!},
                    lineHeight: '{!! config('mailcoach-monaco.lineHeight', '18') !!}',
                });

                editor.getModel().onDidChangeContent(debounce(() => {
                    component.value = editor.getValue();
                }));
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

                <div wire:ignore x-data="{
                    value: @entangle('templateFieldValues.' . $placeHolderName),
                    init: init,
                }">
                    <div x-ref="editor" class="input px-0 h-[700px]"></div>
                </div>
            </div>
        @endforeach
    @else
        <div class="form-field max-w-full" wire:ignore x-data="{
            value: @entangle('templateFieldValues.html'),
            init: init,
        }">
            <label class="label" for="field_html">
                HTML
            </label>

            <div x-ref="editor" class="input px-0 h-[700px]"></div>
        </div>
    @endif

    <x-mailcoach::replacer-help-texts :model="$model" />
    <x-mailcoach::editor-buttons :preview-html="$fullHtml" :model="$model" />
</div>

