@php
/**
 * Custom Checkbox Component with Hidden Input & Alpine Logic — Juksgraphic / Penguin UI
 */

/** @var string $label */
$label = (string) ($label ?? '');

/** @var string $name */
$name = preg_replace('/[^A-Za-z0-9_-]/', '', (string) ($name ?? 'checkbox'));

/** @var bool $isMultiple */
$isMultiple = (bool) ($multiple ?? false);

/** @var array<int, array<string, mixed>> $options */
$options = is_array($options ?? null) ? $options : [];

/** @var string $model */
$model = (string) ($model ?? 'formData');

/** @var array<string, mixed> $initialData */
$initialData = is_array($initialData ?? null) ? $initialData : [];

$normalizedOptions = array_map(function($opt) {
    return [
        'id' => (string) ($opt['id'] ?? $opt['value'] ?? ''),
        'title' => (string) ($opt['title'] ?? $opt['name'] ?? $opt['label'] ?? '')
    ];
}, $options);

$currentVal = $initialData[$name] ?? null;

if ($isMultiple) {
    if (is_string($currentVal)) {
        $currentVal = array_filter(explode(',', $currentVal));
    } elseif (!is_array($currentVal)) {
        $currentVal = $currentVal !== null ? [$currentVal] : [];
    }
    $currentVal = array_map('strval', (array) $currentVal);
} else {
    $currentVal = filter_var($currentVal ?? false, FILTER_VALIDATE_BOOLEAN);
}

$jsonOptions = json_encode($normalizedOptions, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
$jsonInitVal = json_encode($currentVal, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE);
@endphp

<div class="flex flex-col gap-2 {{ $isMultiple ? 'col-span-full' : '' }} text-on-surface dark:text-on-surface-dark">
    {{-- Mode Single Switch --}}
    @if ($label !== '' && !$isMultiple)
        <div class="flex items-center justify-between gap-4 py-1"
             x-data="{
                fieldName: '{{ $name }}',
                modelPath: '{{ $model }}',
                init() {
                    try {
                        const val = eval(`${this.modelPath}['${this.fieldName}']`);
                        if (val === undefined) {
                            eval(`${this.modelPath}['${this.fieldName}'] = {{ $jsonInitVal }}`);
                        }
                    } catch (e) {}
                },
                get modelValue() {
                    try { return eval(`${this.modelPath}['${this.fieldName}']`); } catch (e) { return false; }
                },
                toggle() {
                    try {
                        const current = eval(`${this.modelPath}['${this.fieldName}']`);
                        eval(`${this.modelPath}['${this.fieldName}'] = !current`);
                    } catch (e) {}
                }
             }"
             x-init="init()"
        >
            <span class="text-sm font-semibold text-on-surface-strong dark:text-on-surface-dark-strong">{{ $label }}</span>
            
            {{-- Input hidden unique pour porter la valeur (0 ou 1) --}}
            <input type="hidden" name="{{ $name }}" :value="modelValue ? '1' : '0'">

            <div @click="toggle()" class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer items-center">
                <span :class="modelValue ? 'border-primary bg-primary dark:border-primary-dark dark:bg-primary-dark' : 'border-outline bg-surface-alt dark:border-outline-dark dark:bg-surface-dark-alt'" class="absolute inset-0 rounded-full border transition-colors duration-200 ease-in-out"></span>
                <span :class="modelValue ? 'translate-x-5' : 'translate-x-0.5'" class="absolute left-0.5 top-0.5 h-5 w-5 rounded-full bg-white shadow-sm transition-transform duration-200 ease-in-out"></span>
            </div>
        </div>
    @elseif ($label !== '' && $isMultiple)
        <label class="w-fit text-sm font-semibold text-on-surface-strong dark:text-on-surface-dark-strong">
            {{ $label }}
        </label>
    @endif

    {{-- Mode Multi-Checkboxes Custom --}}
    @if ($isMultiple)
        <div 
            x-data="{
                fieldName: '{{ $name }}',
                modelPath: '{{ $model }}',
                options: {{ $jsonOptions }},
                init() {
                    try {
                        const val = eval(`${this.modelPath}['${this.fieldName}']`);
                        if (!Array.isArray(val)) {
                            eval(`${this.modelPath}['${this.fieldName}'] = {{ $jsonInitVal }}`);
                        }
                    } catch (e) {}
                },
                get modelValue() {
                    try { return eval(`${this.modelPath}['${this.fieldName}']`) || []; } catch (e) { return []; }
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
            }"
            x-init="init()"
            class="flex flex-wrap gap-4 pt-1"
        >
            {{-- Inputs hidden dynamiques pour chaque valeur du tableau --}}
            <template x-for="val in modelValue" :key="val">
                <input type="hidden" name="{{ $name }}[]" :value="val">
            </template>

            @foreach ($normalizedOptions as $option)
                <div @click="toggle('{{ $option['id'] }}')" class="group inline-flex items-center gap-2.5 cursor-pointer select-none text-sm text-on-surface dark:text-on-surface-dark">
                    
                    {{-- Boîte visuelle custom pilotée par Alpine --}}
                    <div 
                        :class="isChecked('{{ $option['id'] }}') ? 'border-primary bg-primary dark:border-primary-dark dark:bg-primary-dark' : 'border-outline bg-surface-alt dark:border-outline-dark dark:bg-surface-dark-alt'"
                        class="flex h-5 w-5 shrink-0 items-center justify-center rounded border transition-all duration-150 group-hover:border-primary dark:group-hover:border-primary-dark"
                    >
                        <svg :class="isChecked('{{ $option['id'] }}') ? 'opacity-100' : 'opacity-0'" class="h-3.5 w-3.5 text-white transition-opacity duration-150" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>

                    <span class="group-hover:text-on-surface-strong dark:group-hover:text-on-surface-dark-strong transition-colors">{{ $option['title'] }}</span>
                </div>
            @endforeach
        </div>
    @endif
</div>