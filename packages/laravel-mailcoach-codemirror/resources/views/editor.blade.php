<div class="form-grid" wire:ignore>
    <script>
        window.debounce = function(func, timeout = 300) {
            let timer;
            return (...args) => {
                clearTimeout(timer);
                timer = setTimeout(() => { func.apply(this, args); }, timeout);
            };
        }
    </script>
    <div>
        @if ($model->hasTemplates())
            <x-mailcoach::template-chooser :clearable="false" wire:key="template-chooser" />
        @endif
    </div>

    @foreach($template?->fields() ?? [['name' => 'html', 'type' => 'editor']] as $field)
        <x-mailcoach::editor-fields :name="$field['name']" :type="$field['type']" :label="$field['name']">
            <x-slot name="editor">
                <div
                    x-data="{
                    html: @entangle('templateFieldValues.' . $field['name'] . '.html').live,
                }" x-init="
                    setupCodeMirror($refs.editor, html, window.debounce((viewUpdate) => {
                        html = viewUpdate.state.doc.toString();
                    }))
                ">
                    <div x-ref="editor" class="input bg-white px-0 overflow-scroll h-[700px]"></div>
                </div>
            </x-slot>
        </x-mailcoach::editor-fields>
    @endforeach

    <x-mailcoach::replacer-help-texts :model="$model" />
    <x-mailcoach::editor-buttons :preview-html="$this->previewHtml" :model="$model" />
</div>
