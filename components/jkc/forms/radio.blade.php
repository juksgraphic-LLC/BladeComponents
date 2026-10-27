@php
    /**
     * Custom Radio Group Component with Hidden Input & Alpine Logic — Juksgraphic / Penguin UI
     */

    /** @var string $label */
    $label = (string) ($label ?? '');

    /** @var string $name */
    $name = preg_replace('/[^A-Za-z0-9_-]/', '', (string) ($name ?? 'radio'));

    /** @var array<int, array<string, mixed>> $options */
    $options = is_array($options ?? null) ? $options : [];

    /** @var string $model */
    $model = (string) ($model ?? 'formData');

    /** @var bool $required */
    $required = (bool) ($required ?? false);

    /** @var array<string, mixed> $initialData */
    $initialData = is_array($initialData ?? null) ? $initialData : [];

    $normalizedOptions = array_map(function ($opt) {
        return [
            'id' => (string) ($opt['id'] ?? ($opt['value'] ?? '')),
            'title' => (string) ($opt['title'] ?? ($opt['name'] ?? ($opt['label'] ?? ''))),
        ];
    }, $options);

    $currentVal = (string) ($initialData[$name] ?? ($normalizedOptions[0]['id'] ?? ''));

    $jsonOptions = json_encode(
        $normalizedOptions,
        JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE,
    );

    $jsonInitVal = json_encode(
        $currentVal,
        JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE,
    );
@endphp

<div class="flex flex-col gap-2 col-span-full text-on-surface dark:text-on-surface-dark">
    @if ($label !== '')
        <label class="w-fit text-sm font-semibold text-on-surface-strong dark:text-on-surface-dark-strong">
            {{ $label }}
            @if ($required)
                <span class="text-danger dark:text-danger-dark">*</span>
            @endif
        </label>
    @endif

    <div x-data="{
        fieldName: '{{ $name }}',
        modelPath: '{{ $model }}',
        options: {{ $jsonOptions }},
        init() {
            try {
                const currentVal = eval(`${this.modelPath}['${this.fieldName}']`);
                if (currentVal === undefined) {
                    eval(`${this.modelPath}['${this.fieldName}'] = {{ $jsonInitVal }}`);
                }
            } catch (e) {}
        },
        getValue() {
            try {
                return eval(`${this.modelPath}['${this.fieldName}']`) || '';
            } catch (e) {
                return '';
            }
        },
        select(id) {
            try {
                eval(`${this.modelPath}['${this.fieldName}'] = id`);
            } catch (e) {}
        },
        isSelected(id) {
            return this.getValue() === id;
        }
    }" x-init="init()" class="flex flex-wrap gap-4 pt-1">

        {{-- Input hidden unique qui porte la valeur active pour la soumission --}}
        <input type="hidden" name="{{ $name }}" :value="getValue()">

        @foreach ($normalizedOptions as $option)
            <div @click="select('{{ $option['id'] }}')"
                class="group inline-flex items-center gap-2.5 cursor-pointer select-none text-sm text-on-surface dark:text-on-surface-dark">

                {{-- Cercle radio custom piloté par Alpine --}}
                <div :class="isSelected('{{ $option['id'] }}') ? 'border-primary dark:border-primary-dark' :
                    'border-outline dark:border-outline-dark'"
                    class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full border bg-surface-alt transition-all duration-150 group-hover:border-primary dark:bg-surface-dark-alt dark:group-hover:border-primary-dark">
                    <span :class="isSelected('{{ $option['id'] }}') ? 'scale-100 opacity-100' : 'scale-0 opacity-0'"
                        class="h-2 w-2 rounded-full bg-primary transition-all duration-150 dark:bg-primary-dark"></span>
                </div>

                <span
                    class="group-hover:text-on-surface-strong dark:group-hover:text-on-surface-dark-strong transition-colors">{{ $option['title'] }}</span>
            </div>
        @endforeach
    </div>
</div>
