@php
    /**
     * Textarea Component (Standard or Quill JS) — Juksgraphic / Penguin UI
     *
     * @param string      $label       Field label text. Default: ''.
     * @param string      $name        HTML field name. Default: 'content'.
     * @param string      $model       Alpine.js target object path. Default: 'formData'.
     * @param bool        $required    Mandatory field flag. Default: false.
     * @param bool        $useQuill    Enable rich text Quill editor instead of plain textarea. Default: false.
     * @param bool        $isSimple    Alias or legacy flag for standard textarea mode. Default: false.
     * @param string      $placeholder Textarea placeholder. Default: ''.
     * @param int         $rows        Textarea rows height (for standard mode). Default: 4.
     * @param string      $class       Extra CSS classes. Default: ''.
     */

    /** @var string $label */
    $label = (string) ($label ?? '');

    /** @var string $name */
    $name = preg_replace('/[^A-Za-z0-9_-]/', '', (string) ($name ?? 'content'));

    /** @var string $model */
    $model = (string) ($model ?? 'formData');

    /** @var bool $required */
    $required = (bool) ($required ?? false);

    // Permet de supporter soit $useQuill, soit $isSimple
    $useQuill = isset($useQuill) ? (bool) $useQuill : !((bool) ($isSimple ?? false));

    /** @var string $placeholder */
    $placeholder = (string) ($placeholder ?? 'Enter your text here...');

    /** @var int $rows */
    $rows = (int) ($rows ?? 4);

    /** @var string $class */
    $class = (string) ($class ?? '');

    /** @var string $textareaClasses */
    $textareaClasses = preg_replace(
        '/\s+/',
        ' ',
        trim(
            'min-h-[120px] w-full placeholder:text-on-surface dark:placeholder:text-on-surface-dark rounded-radius border border-outline bg-surface-alt p-3 text-sm outline-none transition-all focus:border-primary focus:ring-2 focus:ring-primary/20 dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:text-on-surface-dark ' .
                $class,
        ),
    );
@endphp

<div class="flex flex-col gap-1.5 col-span-full text-on-surface dark:text-on-surface-dark">
    @if ($label !== '')
        <label for="{{ $name }}"
            class="w-fit pl-0.5 text-sm font-semibold text-on-surface-strong dark:text-on-surface-dark-strong">
            {{ $label }}
            @if ($required)
                <span class="text-danger dark:text-danger-dark">*</span>
            @endif
        </label>
    @endif

    <div x-data="{
        fieldName: '{{ $name }}',
        modelPath: '{{ $model }}',
        useQuill: {{ $useQuill ? 'true' : 'false' }},
        quillInstance: null,
        init() {
            if (this.useQuill) {
                this.$nextTick(() => {
                    this.launch();
                });
            }
        },
        launch() {
        
            if (typeof Quill === 'undefined' || !this.$refs.editor) return;
            if (this.quillInstance || this.$refs.editor.__quill) return;

            this.quillInstance = new Quill(this.$refs.editor, {
                theme: 'snow',
                modules: {
                    toolbar: {
                        container: [
                            [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
                            [{ 'font': [] }],
                            ['link', 'image', 'video', 'formula'],
                            ['bold', 'italic', 'underline', 'strike'],
                            ['blockquote', 'code-block'],
                            [{ 'list': 'ordered' }, { 'list': 'bullet' }, { 'list': 'check' }],
                            [{ 'indent': '-1' }, { 'indent': '+1' }],
                            [{ 'align': [] }],
                            ['clean']
                        ]
                    }
                }
            });

            this.$refs.editor.__quill = this.quillInstance;

            const initialVal = this.getValue();
            if (initialVal && this.quillInstance.root.innerHTML !== initialVal) {
                this.quillInstance.root.innerHTML = initialVal;
            }

            this.quillInstance.on('text-change', () => {
                const html = this.quillInstance.root.innerHTML;
                if (this.getValue() !== html) {
                    this.setValue(html);
                }
            });
        },
        getValue() {
            try {
                return eval(`${this.modelPath}['${this.fieldName}']`) || '';
            } catch (e) {
                return '';
            }
        },
        setValue(val) {
            try {
                eval(`${this.modelPath}['${this.fieldName}'] = val`);
            } catch (e) {}
        }
    }" x-init="init()" class="w-full flex flex-col">

        {{-- Mode Standard Textarea --}}
        <template x-if="!useQuill">
            <textarea id="{{ $name }}" name="{{ $name }}" rows="{{ $rows }}" placeholder="{{ $placeholder }}"
                x-model="{{ $model }}.{{ $name }}" class="{{ $textareaClasses }}"
                {{ $required ? 'required' : '' }}></textarea>
        </template>

        {{-- Mode Éditeur Riche (Quill JS) --}}
        <template x-if="useQuill">
            <div class="w-full flex flex-col">
                <div class="overflow-hidden rounded-radius border border-outline dark:border-outline-dark">
                    <div x-ref="editor"
                        class="max-h-[160px] bg-surface-alt dark:bg-surface-dark-alt/50 font-paragraph text-sm"></div>
                </div>
                {{-- Input caché pour soumettre la donnée de Quill --}}
                <input type="hidden" name="{{ $name }}" :value="getValue()">
            </div>
        </template>
    </div>
</div>