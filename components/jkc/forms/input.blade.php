@php
/**
 * Text Input Component — Juksgraphic / Penguin UI
 *
 * @param string $label       Field label. Default: ''.
 * @param string $name        Field HTML name attribute. Default: 'input'.
 * @param string $type        Field input type (text, email, password, etc.). Default: 'text'.
 * @param string $model       Parent Alpine.js x-model object reference. Default: 'formData'.
 * @param bool   $required    Whether field is mandatory. Default: false.
 * @param string $placeholder Input placeholder text. Default: ''.
 * @param string $class       Extra CSS classes. Default: ''.
 */

/** @var string $label */
$label = (string) ($label ?? '');

/** @var string $name */
$name = preg_replace('/[^A-Za-z0-9_\[\]-]/', '', (string) ($name ?? 'input'));

/** @var string $type */
$type = (string) ($type ?? 'text');

/** @var string $model */
$model = (string) ($model ?? 'formData');

/** @var bool $required */
$required = (bool) ($required ?? false);

/** @var string $placeholder */
$placeholder = (string) ($placeholder ?? '');

/** @var string $class */
$class = (string) ($class ?? '');

/** @var string $inputClasses */
$inputClasses = preg_replace('/\s+/', ' ', trim(
    'w-full rounded-radius border border-outline bg-surface-alt px-3.5 py-2 text-sm text-on-surface focus:border-primary focus:outline-none dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:text-on-surface-dark dark:focus:border-primary-dark ' . $class
));
@endphp

<div class="flex flex-col gap-1.5 text-on-surface dark:text-on-surface-dark">
    @if ($label !== '')
        <label for="{{ $name }}" class="w-fit text-sm font-semibold text-on-surface-strong dark:text-on-surface-dark-strong">
            {{ $label }}
            @if ($required)<span class="text-danger dark:text-danger-dark">*</span>@endif
        </label>
    @endif
    <input 
        id="{{ $name }}" 
        name="{{ $name }}" 
        type="{{ $type }}" 
        placeholder="{{ $placeholder }}"
        x-model="{{ $model }}.{{ $name }}"
        class="{{ $inputClasses }}"
        {{ $required ? 'required' : '' }}
    >
</div>