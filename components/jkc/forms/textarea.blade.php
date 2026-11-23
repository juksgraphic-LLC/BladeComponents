@php

    use Juksgraphic\BladeComponents\Components\Forms\Textarea;
    use Juksgraphic\BladeComponents\Components\Forms\TextareaStyles;

   
    $textarea = new Textarea([
        'label'    => $label ?? '',
        'name'     => $name ?? 'content',
        'model'    => $model ?? 'formData',
        'required' => $required ?? false,
        'useQuill' => $useQuill ?? null,
        'isSimple' => $isSimple ?? false,
        'placeholder' => $placeholder ?? 'Enter your text here...',
        'rows'     => $rows ?? 4,
        'class'    => $class ?? '',
    ]);

    $styles = new TextareaStyles($textarea);
@endphp

<!-- Root textarea field container -->
<div class="{{ $styles->wrapper() }}">

    <!-- Optional field label -->
    @if ($textarea->hasLabel())
        <label for="{{ $textarea->name() }}" class="{{ $styles->label() }}">
            {{ $textarea->label() }}
            @if ($textarea->isRequired())
                <span class="{{ $styles->requiredMarker() }}">*</span>
            @endif
        </label>
    @endif

    <!-- Alpine.js wrapper for plain textarea or Quill editor -->
    <div class="w-full flex flex-col" x-data="{
        useQuill: {{ $textarea->usesQuill() ? 'true' : 'false' }},
        toolbarOptions: {{ $textarea->encodeJson($textarea->toolbar()) }},
    
       
        get fieldValue() {
            return {!! $textarea->modelProperty() !!};
        },
    
        
        set fieldValue(newValue) {
            {!! $textarea->modelProperty() !!} = newValue;
        },
    
        
        get quillEditor() {
            return this.$refs.editor?.__quill;
        },
    
        
        looksLikeHtml(text) {
            return /<[a-z][\s\S]*>/i.test(text);
        },
    
        parseJsonSafely(text) {
            try {
                return JSON.parse(text);
            } catch {
                return null;
            }
        },
    
        convertHtmlToDelta(html) {
            return Quill.version.startsWith('1') ?
                this.quillEditor.clipboard.convert(html) :
                this.quillEditor.clipboard.convert({ html });
        },
    
    
        convertToDelta(rawValue) {

            if (!rawValue) return null;
            if (rawValue.ops) return rawValue;
    
            const parsedDelta = this.parseJsonSafely(rawValue);
            if (parsedDelta?.ops) return parsedDelta;
    
            if (this.looksLikeHtml(rawValue)) return this.convertHtmlToDelta(rawValue);
    
            return { ops: [{ insert: rawValue + '\n' }] };
        },
        
        fillEditor() {
            const initialDelta = this.convertToDelta(this.fieldValue);
            if (initialDelta) this.quillEditor.setContents(initialDelta, 'silent');
        },

        saveToModel() {
            const isEditorFilled = this.quillEditor.getLength() > 1;
            this.fieldValue = isEditorFilled ? { ops: this.quillEditor.getContents().ops } : '';
        },
    
        mountEditor() {
            const editorElement = this.$refs.editor;
            if (editorElement.__quill) return;
    
            editorElement.__quill = new Quill(editorElement, {
                theme: 'snow',
                modules: { toolbar: this.toolbarOptions }
            });
    
            this.fillEditor();
            this.saveToModel();
    
            this.quillEditor.on('text-change', (delta, oldDelta, source) => {
                if (source !== 'silent') this.saveToModel();
            });
        },
    
        init() {
            if (this.useQuill) this.$nextTick(() => this.mountEditor());
        }
    }">

        <!-- Quill rich-text editor mode -->
        @if ($textarea->usesQuill())
            <div class="{{ $styles->quillFrame() }}">
                <div x-ref="editor" class="{{ $styles->quillSurface() }}"></div>
            </div>

            <!-- Hidden input serializing Quill Delta JSON for classic form submission -->
            <input type="hidden" name="{{ $textarea->name() }}" :value="fieldValue ? JSON.stringify(fieldValue) : ''">

            <!-- Standard plain textarea mode -->
        @else
            <textarea id="{{ $textarea->name() }}" name="{{ $textarea->name() }}" rows="{{ $textarea->rows() }}"
                placeholder="{{ $textarea->placeholder() }}" x-model="fieldValue" class="{{ $styles->control() }}"
                {{ $textarea->isRequired() ? 'required' : '' }}></textarea>
        @endif

    </div>

</div>
