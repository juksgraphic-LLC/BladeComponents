@php
 
    use Juksgraphic\BladeComponents\Components\Forms\Input;
    use Juksgraphic\BladeComponents\Components\Forms\InputStyles;

    $input = new Input([
        'label'       => $label ?? '',
        'name'        => $name ?? 'input',
        'type'        => $type ?? 'text',
        'model'       => $model ?? 'formData',
        'required'    => $required ?? false,
        'placeholder' => $placeholder ?? '',
        'class'       => $class ?? '',
    ]);

    $styles = new InputStyles($input);
@endphp

<!-- Field wrapper -->
<div class="{{ $styles->wrapper() }}">

    <!-- Optional field label -->
    @if ($input->hasLabel())
        <label for="{{ $input->name() }}" class="{{ $styles->label() }}">
            {{ $input->label() }}
            @if ($input->isRequired())
                <span class="{{ $styles->requiredMarker() }}">*</span>
            @endif
        </label>
    @endif

    <!-- Two-way bound Alpine input element -->
    <input
        id="{{ $input->name() }}"
        name="{{ $input->name() }}"
        type="{{ $input->type() }}"
        placeholder="{{ $input->placeholder() }}"
        x-model="{{ $input->modelBinding() }}"
        class="{{ $styles->control() }}"
        {{ $input->isRequired() ? 'required' : '' }}>

</div>
