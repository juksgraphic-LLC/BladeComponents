@php
/**
 * Form Component — Juksgraphic / Penguin UI
 *
 * Dynamic form container with slot submission and Alpine.js context sharing.
 * Works with Laravel Blade and BladeOne.
 *
 * @param array<int, array<string, mixed>> $fields      Field configuration array. Default: [].
 * @param array<string, mixed>             $initialData Initial key-value array for form fields. Default: [].
 * @param string                           $action      Form action URL target. Default: ''.
 * @param string                           $method      HTTP verb (GET, POST, PUT, DELETE). Default: 'POST'.
 * @param string                           $submitAttrs Extra attributes for the <form> tag. Default: ''.
 * @param string|null                      $submitText  Default submit button text if slot is omitted. Default: null.
 * @param string                           $class       Extra CSS classes for container. Default: ''.
 * @param string                           $attrs       Extra HTML attributes for container. Default: ''.
 */

/** @var array<int, array<string, mixed>> $fields */
$fields = is_array($fields ?? null) ? $fields : [];

/** @var array<string, mixed> $initialData */
$initialData = is_array($initialData ?? null) ? $initialData : [];

/** @var string $action */
$action = (string) ($action ?? '');

/** @var string $method */
$method = strtoupper((string) ($method ?? 'POST'));

/** @var string $submitAttrs */
$submitAttrs = (string) ($submitAttrs ?? '');

/** @var string|null $submitText */
$submitText = isset($submitText) ? (string) $submitText : null;

/** @var string $class */
$class = (string) ($class ?? '');

/** @var string $attrs */
$attrs = (string) ($attrs ?? '');

/** @var bool $isEdit */
$isEdit = !empty($initialData['id']);

/** @var string $containerClasses */
$containerClasses = preg_replace('/\s+/', ' ', trim(
    'w-full bg-surface p-6 rounded-radius border border-outline dark:bg-surface-dark-alt dark:border-outline-dark ' . $class
));

/** @var string $jsonInitialData */
$jsonInitialData = json_encode(
    $initialData,
    JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE
);
@endphp

{{-- ═══════════════════════════ Template ═══════════════════════════ --}}
<div 
    x-data="{
        formData: {{ $jsonInitialData }},
        isEdit:  {{ $isEdit ? 'true' : 'false' }},
        loading: false
    }"
    class="{{ $containerClasses }}"
    {!! $attrs !!}
>
    <form 
        action="{{ $action }}"
        method="{{ in_array($method, ['GET', 'POST']) ? $method : 'POST' }}"
        {!! $submitAttrs !!}
        class="grid grid-cols-1 md:grid-cols-2 gap-6"
    >
        @if (!in_array($method, ['GET', 'POST']))
            <input type="hidden" name="_method" value="{{ $method }}">
        @endif

        {{-- Dynamic Fields --}}
        @foreach ($fields as $field)
            @php
                $type = $field['type'] ?? 'text';
                $view = match ($type) {
                    'select'   => 'jkc.forms.select',
                    'textarea' => 'jkc.forms.textarea',
                    'file'     => 'jkc.forms.file',
                    'checkbox' => 'jkc.forms.checkbox',
                    'radio'    => 'jkc.forms.radio',
                    default    => 'jkc.forms.input',
                };
            @endphp

            @include($view, [
                'label'       => $field['label'] ?? '',
                'name'        => $field['name'] ?? 'field',
                'type'        => $type,
                'options'     => $field['options'] ?? [],
                'model'       => 'formData',
                'multiple'    => $field['multiple'] ?? false,
                'required'    => $field['required'] ?? false,
                'useQuill'    => $field['useQuill'] ?? false,
                'isSimple'    => $field['isSimple'] ?? false,
                'accept'      => $field['accept'] ?? '',
                'recommended' => $field['recommended'] ?? '',
                'placeholder' => $field['placeholder'] ?? '',
                'rows'        => $field['rows'] ?? 4,
                'class'       => $field['class'] ?? '',
                'initialData' => $initialData,
            ])
        @endforeach

        <div id="form-response" class="col-span-full"></div>

        {{-- Submission Slot Area --}}
        <div class="col-span-full flex justify-end pt-6 border-t border-outline dark:border-outline-dark">
            @if (isset($submit))
                {!! $submit !!}
            @else
                <button 
                    type="submit" 
                    :disabled="loading"
                    class="bg-primary hover:bg-primary/90 text-on-primary font-medium px-6 py-2.5 rounded-radius text-sm transition-colors disabled:opacity-50 cursor-pointer dark:bg-primary-dark dark:text-on-primary-dark"
                >
                    <span x-show="!loading" x-text="isEdit ? '{{ $submitText ?? 'Save' }}' : '{{ $submitText ?? 'Validate' }}'"></span>
                    <span x-show="loading" x-cloak>Chargement...</span>
                </button>
            @endif
        </div>
    </form>
</div>