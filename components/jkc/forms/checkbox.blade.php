@php
  
    use Juksgraphic\BladeComponents\Components\Forms\CheckBox;
    use Juksgraphic\BladeComponents\Components\Forms\CheckBoxStyles;

    
    $checkbox = new CheckBox([
        'label'       => $label ?? '',
        'name'        => $name ?? 'checkbox',
        'multiple'    => $multiple ?? false,
        'options'     => $options ?? [],
        'model'       => $model ?? 'formData',
        'initialData' => $initialData ?? [],
        'class'       => $class ?? '',
    ]);

    
    $styles = new CheckBoxStyles($checkbox);
@endphp

<!-- Root checkbox field container -->
<div class="{{ $styles->wrapper() }}">

    <!-- Mode 1: Single toggle switch with label -->
    @if ($checkbox->hasLabel() && !$checkbox->isMultiple())
        <div class="{{ $styles->switchRow() }}"
            x-data="{
            fieldName: '{{ $checkbox->name() }}',
            modelPath: '{{ $checkbox->model() }}',
        
            
            init() {
                try {
                    const val = eval(`${this.modelPath}['${this.fieldName}']`);
                    if (val === undefined) {
                        eval(`${this.modelPath}['${this.fieldName}'] = {{ $checkbox->encodeJson($checkbox->initialValue()) }}`);
                    }
                } catch (e) {}
            },
        
           
            get modelValue() {
                try {
                    return eval(`${this.modelPath}['${this.fieldName}']`);
                } catch (e) {
                    return false;
                }
            },
        
           
            toggle() {
                try {
                    const current = eval(`${this.modelPath}['${this.fieldName}']`);
                    eval(`${this.modelPath}['${this.fieldName}'] = !current`);
                } catch (e) {}
            }
        }" x-init="init()">
            <span class="{{ $styles->switchLabel() }}">{{ $checkbox->label() }}</span>

            <!-- Hidden input carrying '1' or '0' for form submission -->
            <input type="hidden" name="{{ $checkbox->name() }}" :value="modelValue ? '1' : '0'">

            <!-- Interactive switch control -->
            <div x-on:click="toggle()" class="{{ $styles->switchButton() }}">
                <span :class="{!! $styles->switchTrackBinding() !!}" class="{{ $styles->switchTrack() }}"></span>
                <span :class="{!! $styles->switchThumbBinding() !!}" class="{{ $styles->switchThumb() }}"></span>
            </div>
        </div>

        <!-- Group label for multi-checkbox mode -->
    @elseif ($checkbox->hasLabel() && $checkbox->isMultiple())
        <label class="{{ $styles->groupLabel() }}">
            {{ $checkbox->label() }}
        </label>
    @endif

    <!-- Mode 2: Multi-checkboxes group -->
    @if ($checkbox->isMultiple())
        <div x-data="{
            fieldName: '{{ $checkbox->name() }}',
            modelPath: '{{ $checkbox->model() }}',
            options: {{ $checkbox->encodeJson($checkbox->options()) }},
        
           
            init() {
                try {
                    const val = eval(`${this.modelPath}['${this.fieldName}']`);
                    if (!Array.isArray(val)) {
                        eval(`${this.modelPath}['${this.fieldName}'] = {{ $checkbox->encodeJson($checkbox->initialValue()) }}`);
                    }
                } catch (e) {}
            },
        
            
            get modelValue() {
                try {
                    return eval(`${this.modelPath}['${this.fieldName}']`) || [];
                } catch (e) {
                    return [];
                }
            },
        
           
            isChecked(id) {
                return this.modelValue.includes(id);
            },
        
            
            toggle(id) {
                try {
                    let arr = [...this.modelValue];
                    if (arr.includes(id)) {
                        arr = arr.filter(item => item !== id);
                    } else {
                        arr.push(id);
                    }
                    eval(`${this.modelPath}['${this.fieldName}'] = arr`);
                } catch (e) {}
            }
        }" x-init="init()" class="{{ $styles->optionsContainer() }}">

            <!-- Dynamic hidden inputs for array submission -->
            <template x-for="val in modelValue" :key="val">
                <input type="hidden" name="{{ $checkbox->name() }}[]" :value="val">
            </template>

            <!-- Custom checkbox option items -->
            @foreach ($checkbox->options() as $option)
                <div x-on:click="toggle('{{ $option['id'] }}')" class="{{ $styles->optionItem() }}">
                    <div :class="{!! $styles->optionBoxBinding($option['id']) !!}" class="{{ $styles->optionBox() }}">
                        <svg :class="{!! $styles->optionCheckBinding($option['id']) !!}" class="{{ $styles->optionCheckIcon() }}" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>

                    <span class="{{ $styles->optionTitle() }}">{{ $option['title'] }}</span>
                </div>
            @endforeach

        </div>
    @endif

</div>
