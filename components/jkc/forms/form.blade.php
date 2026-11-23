@php


    use Juksgraphic\BladeComponents\Components\Forms\Form;
    use Juksgraphic\BladeComponents\Components\Forms\FormStyles;

   
    $form = new Form([
        'fields'      => $fields ?? [],
        'initialData' => $initialData ?? [],
        'action'      => $action ?? '',
        'method'      => $method ?? 'POST',
        'submitAttrs' => $submitAttrs ?? '',
        'submitText'  => $submitText ?? null,
        'class'       => $class ?? '',
        'attrs'       => $attrs ?? '',
    ]);

    
    $styles = new FormStyles($form);
@endphp

<!-- Outer form container providing shared Alpine.js state -->
<div x-data="{{ $form->toJson() }}" class="{{ $styles->container() }}" {!! $form->attrs() !!}>

    <!-- Dynamic form element -->
    <form action="{{ $form->action() }}" method="{{ $form->htmlMethod() }}" {!! $form->submitAttrs() !!}
        class="{{ $styles->grid() }}">

        <!-- Hidden method override for RESTful verbs -->
        @if ($form->needsMethodSpoofing())
            <input type="hidden" name="_method" value="{{ $form->method() }}">
        @endif

        <!-- Dynamically rendered field components -->
        @foreach ($form->fields() as $field)
            @include($form->viewFor($field), $form->fieldParams($field))
        @endforeach

        <!-- Async / server response target slot -->
        <div id="form-response" class="{{ $styles->responseBox() }}"></div>

        <!-- Submit action area -->
        <div class="{{ $styles->footer() }}">
            @if (isset($submit))
                {!! $submit !!}
            @else
                <button type="submit" :disabled="loading" class="{{ $styles->submitButton() }}">
                    <span x-show="!loading"
                        x-text="isEdit ? '{{ $form->submitText() ?? 'Save' }}' : '{{ $form->submitText() ?? 'Validate' }}'"></span>
                    <span x-show="loading" x-cloak>Chargement...</span>
                </button>
            @endif
        </div>

    </form>

</div>
