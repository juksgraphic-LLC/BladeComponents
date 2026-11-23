@php
    use Juksgraphic\BladeComponents\Components\Forms\Radio;
    use Juksgraphic\BladeComponents\Components\Forms\RadioStyles;
        
    $radio = new Radio([
        'label'       => $label ?? '',
        'name'        => $name ?? 'radio',
        'options'     => $options ?? [],
        'model'       => $model ?? 'formData',
        'required'    => $required ?? false,
        'initialData' => $initialData ?? [],
        'class'       => $class ?? '',
    ]);

    
    $styles = new RadioStyles($radio);
@endphp

<!-- Root radio group container -->
<div class="{{ $styles->wrapper() }}">

    <!-- Optional group label -->
    @if ($radio->hasLabel())
        <label class="{{ $styles->label() }}">
            {{ $radio->label() }}
            @if ($radio->isRequired())
                <span class="{{ $styles->requiredMarker() }}">*</span>
            @endif
        </label>
    @endif

    <!-- Interactive Alpine.js radio options controller -->
    <div
        x-data="{
            fieldName: '{{ $radio->name() }}',
            modelPath: '{{ $radio->model() }}',
            options: {{ $radio->encodeJson($radio->options()) }},

            // Initialize selected value on parent model if undefined
            init() {
                try {
                    const currentVal = eval(`${this.modelPath}['${this.fieldName}']`);
                    if (currentVal === undefined) {
                        eval(`${this.modelPath}['${this.fieldName}'] = {{ $radio->encodeJson($radio->initialValue()) }}`);
                    }
                } catch (e) {}
            },

            // Read currently selected option ID
            getValue() {
                try {
                    return eval(`${this.modelPath}['${this.fieldName}']`) || '';
                } catch (e) {
                    return '';
                }
            },

            // Update selected option ID on parent model
            select(id) {
                try {
                    eval(`${this.modelPath}['${this.fieldName}'] = id`);
                } catch (e) {}
            },

            // Check whether a given option ID is active
            isSelected(id) {
                return this.getValue() === id;
            }
        }"
        x-init="init()"
        class="{{ $styles->optionsContainer() }}"
    >

        <!-- Hidden input carrying active radio value for form submission -->
        <input
            type="hidden"
            name="{{ $radio->name() }}"
            :value="getValue()"
        >

        <!-- Custom radio option items -->
        @foreach ($radio->options() as $option)
            <div
                x-on:click="select('{{ $option['id'] }}')"
                class="{{ $styles->optionItem() }}"
            >
                <div
                    :class="{!! $styles->circleBinding($option['id']) !!}"
                    class="{{ $styles->circle() }}"
                >
                    <span
                        :class="{!! $styles->dotBinding($option['id']) !!}"
                        class="{{ $styles->dot() }}"
                    ></span>
                </div>

                <span class="{{ $styles->optionTitle() }}">{{ $option['title'] }}</span>
            </div>
        @endforeach

    </div>

</div>