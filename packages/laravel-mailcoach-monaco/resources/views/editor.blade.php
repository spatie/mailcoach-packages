<div>
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
    <div>
        @if (! $model instanceof \Spatie\Mailcoach\Domain\Campaign\Models\Template)
            <div class="mb-6">
                <x-mailcoach::template-chooser />
            </div>
        @endif

        @if($template?->containsPlaceHolders())
            <div>
                @foreach($template->placeHolderNames() as $placeHolderName)
                    <div class="form-field max-w-full mb-6" wire:key="{{ $placeHolderName }}">
                        <label class="label" for="field_{{ $placeHolderName }}">
                            {{ \Illuminate\Support\Str::of($placeHolderName)->snake(' ')->ucfirst() }}
                        </label>

                        <div wire:ignore x-data="{
                            value: @entangle('templateFieldValues.' . $placeHolderName),
                            init: init,
                        }">
                            <div x-ref="editor" style="position: relative;width:100%;height:700px;border:1px solid #ebf1f7;padding-top: 10px;"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div wire:ignore x-data="{
                value: @entangle('templateFieldValues.html'),
                init: init,
            }">
                <label class="label" for="field_html">
                    HTML
                </label>

                <div x-ref="editor" style="position: relative;width:100%;height:700px;border:1px solid #ebf1f7"></div>
            </div>
        @endif
    </div>

    <x-mailcoach::campaign-replacer-help-texts/>

    <x-mailcoach::editor-buttons :html="$fullHtml" :model="$model" />
</div>

