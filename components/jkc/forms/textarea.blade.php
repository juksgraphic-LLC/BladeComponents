@php
    /**
     * Textarea Component (Standard or Quill JS) — Juksgraphic / Penguin UI
     *
     * Quill mode: the model value is ALWAYS a Delta ({ ops: [...] }).
     * Accepted input (initialData): Delta (object), Delta (JSON string), HTML or plain text.
     *
     * @param string    $label       Field label text. Default: ''.
     * @param string    $name        HTML field name. Default: 'content'.
     * @param string    $model       Alpine.js target object path. Default: 'formData'.
     * @param bool      $required    Mandatory field flag. Default: false.
     * @param bool|null $useQuill    Enable Quill editor. Null = deduced from $isSimple. Default: null.
     * @param bool      $isSimple    Legacy flag for standard textarea mode. Default: false.
     * @param string    $placeholder Textarea placeholder. Default: 'Enter your text here...'.
     * @param int       $rows        Textarea rows height (standard mode). Default: 4.
     * @param string    $class       Extra CSS classes. Default: ''.
     */

    /** @var string $label */
    $label = (string) ($label ?? '');

    /** @var string $name */
    $name = preg_replace('/[^A-Za-z0-9_-]/', '', (string) ($name ?? 'content'));

    /** @var string $model */
    $model = preg_replace('/[^A-Za-z0-9_.]/', '', (string) ($model ?? 'formData'));

    /** @var bool $required */
    $required = (bool) ($required ?? false);

    /** @var bool $useQuill */
    $useQuill = ($useQuill ?? null) === null ? !((bool) ($isSimple ?? false)) : (bool) $useQuill;

    /** @var string $placeholder */
    $placeholder = (string) ($placeholder ?? 'Enter your text here...');

    /** @var int $rows */
    $rows = (int) ($rows ?? 4);

    /** @var string $class */
    $class = (string) ($class ?? '');

    /** @var array<int, array<int, mixed>> $toolbar */
    $toolbar = [
        [['header' => [1, 2, 3, 4, 5, 6, false]]],
        [['font' => []]],
        ['link', 'image', 'video', 'formula'],
        ['bold', 'italic', 'underline', 'strike'],
        ['blockquote', 'code-block'],
        [['list' => 'ordered'], ['list' => 'bullet'], ['list' => 'check']],
        [['indent' => '-1'], ['indent' => '+1']],
        [['align' => []]],
        ['clean'],
    ];

    /** @var string $jsonToolbar */
    $jsonToolbar = json_encode(
        $toolbar,
        JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE
    );

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

    <div class="w-full flex flex-col" x-data="{
        useQuill: {{ $useQuill ? 'true' : 'false' }},
        toolbarOptions: {{ $jsonToolbar }},

        /** Reads the field value from the Alpine model. */
        get fieldValue() { return {{ $model }}['{{ $name }}'] },

        /** Writes the field value into the Alpine model.*/
        set fieldValue(newValue) { {{ $model }}['{{ $name }}'] = newValue },

        /** Returns the Quill instance stored on the editor element (outside Alpine's proxy). */
        get quillEditor() { return this.$refs.editor?.__quill },

        /** Tells whether a string contains HTML tags. */
        looksLikeHtml(text) { return /<[a-z][\s\S]*>/i.test(text) },

        /** Parses a JSON string and returns null when it is invalid. */
        parseJsonSafely(text) {
            try { return JSON.parse(text) } catch { return null }
        },

        /** Converts an HTML string into a Delta (Quill 1.x and 2.x signatures). */
        convertHtmlToDelta(html) {
            return Quill.version.startsWith('1')
                ? this.quillEditor.clipboard.convert(html)
                : this.quillEditor.clipboard.convert({ html })
        },

        /** Converts any supported input (Delta, JSON, HTML, text) into a Delta. */
        convertToDelta(rawValue) {
            if (!rawValue) return null
            if (rawValue.ops) return rawValue

            const parsedDelta = this.parseJsonSafely(rawValue)
            if (parsedDelta?.ops) return parsedDelta

            if (this.looksLikeHtml(rawValue)) return this.convertHtmlToDelta(rawValue)

            return { ops: [{ insert: rawValue + '\n' }] }
        },

        /** Fills the editor with the initial model value, without triggering events. */
        fillEditor() {
            const initialDelta = this.convertToDelta(this.fieldValue)
            if (initialDelta) this.quillEditor.setContents(initialDelta, 'silent')
        },

        /** Saves the editor content into the model as a Delta (empty string if empty). */
        saveToModel() {
            const isEditorFilled = this.quillEditor.getLength() > 1
            this.fieldValue = isEditorFilled ? { ops: this.quillEditor.getContents().ops } : ''
        },

        /** Creates the Quill editor, fills it and listens to user changes. */
        mountEditor() {
            const editorElement = this.$refs.editor
            if (editorElement.__quill) return

            editorElement.__quill = new Quill(editorElement, {
                theme: 'snow',
                modules: { toolbar: this.toolbarOptions }
            })

            this.fillEditor()
            this.saveToModel()
            this.quillEditor.on('text-change', (delta, oldDelta, source) => {
                if (source !== 'silent') this.saveToModel()
            })
        },

        /** Alpine lifecycle hook: mounts the editor once the DOM is ready. */
        init() {
            if (this.useQuill) this.$nextTick(() => this.mountEditor())
        }
    }">

        @if ($useQuill)
            <div class="overflow-hidden rounded-radius border border-outline dark:border-outline-dark">
                <div x-ref="editor"
                    class="max-h-[160px] bg-surface-alt dark:bg-surface-dark-alt/50 font-paragraph text-sm"></div>
            </div>
            {{-- Hidden input: Delta serialized as JSON for a classic submit --}}
            <input type="hidden" name="{{ $name }}" :value="fieldValue ? JSON.stringify(fieldValue) : ''">
        @else
            <textarea id="{{ $name }}" name="{{ $name }}" rows="{{ $rows }}"
                placeholder="{{ $placeholder }}" x-model="fieldValue" class="{{ $textareaClasses }}"
                {{ $required ? 'required' : '' }}></textarea>
        @endif
    </div>
</div>