@php
    /**
     * Native Alpine Select Component (Single & Multi with Search Pills) — Juksgraphic / Penguin UI
     *
     * @param string $label       Field label text. Default: ''.
     * @param string $name        HTML input name. Default: 'select'.
     * @param bool   $multiple    Enable multiple selection pills. Default: false.
     * @param array  $options     Options list [['id' => '1', 'title' => 'Label']]. Default: [].
     * @param string $model       Alpine.js target model path. Default: 'formData'.
     * @param array  $initialData Initial dataset. Default: [].
     */

    /** @var string $label */
    $label = (string) ($label ?? '');

    /** @var string $name */
    $name = preg_replace('/[^A-Za-z0-9_-]/', '', (string) ($name ?? 'select'));

    /** @var bool $isMultiple */
    $isMultiple = (bool) ($multiple ?? false);

    /** @var array<int, array<string, mixed>> $options */
    $options = is_array($options ?? null) ? $options : [];

    /** @var string $model */
    $model = (string) ($model ?? 'formData');

    /** @var array<string, mixed> $initialData */
    $initialData = is_array($initialData ?? null) ? $initialData : [];

    // Normalisation des options
    $normalizedOptions = array_map(function ($opt) {
        return [
            'id' => (string) ($opt['id'] ?? ($opt['value'] ?? '')),
            'title' => (string) ($opt['title'] ?? ($opt['name'] ?? ($opt['label'] ?? ''))),
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
        $currentVal = is_array($currentVal) ? (string) ($currentVal[0] ?? '') : (string) ($currentVal ?? '');
    }

    /** @var string $jsonOptions */
    $jsonOptions = json_encode(
        $normalizedOptions,
        JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE,
    );

    /** @var string $jsonInitVal */
    $jsonInitVal = json_encode(
        $currentVal,
        JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE,
    );
@endphp

<div class="flex flex-col gap-1.5 text-on-surface dark:text-on-surface-dark">
    @if ($label !== '')
        <label for="{{ $name }}"
            class="w-fit text-sm font-semibold text-on-surface-strong dark:text-on-surface-dark-strong">
            {{ $label }}
        </label>
    @endif

    <div x-data="{
        open: false,
        search: '',
        fieldName: '{{ $name }}',
        modelPath: '{{ $model }}',
        isMulti:    {{ $isMultiple ? 'true' : 'false' }},
        options:    {{ $jsonOptions }},
        init() {
    
            if (this.modelPath === 'formData' &&
                typeof formData !== 'undefined' &&
                formData[this.fieldName] === undefined) {
                formData[this.fieldName] = {{ $jsonInitVal }};
            }
        },

        get modelValue() {
            try {
                return eval(`${this.modelPath}['${this.fieldName}']`);
            } catch (e) {
                return undefined;
            }
        },

        setModelValue(val) {
            try {
                eval(`${this.modelPath}['${this.fieldName}'] = val`);
            } catch (e) {}
        },

        get filteredOptions() {
            if (this.search.trim() === '') return this.options;
            return this.options.filter(o => o.title.toLowerCase().includes(this.search.toLowerCase()));
        },

        isSelected(id) {

            const val = this.modelValue;
            if (this.isMulti) {
                return Array.isArray(val) && val.includes(String(id));
            }
            return String(val) === String(id);
        },

        toggle(id) {
            id = String(id);
            let val = this.modelValue;
            if (this.isMulti) {
                if (!Array.isArray(val)) {
                    val = [];
                }
                if (this.isSelected(id)) {
                    val = val.filter(v => String(v) !== id);
                } else {
                    val.push(id);
                }
                this.setModelValue(val);
            } else {
                this.setModelValue(id);
                this.open = false;
            }
        },
        get selectedTitles() {
            const val = this.modelValue;
            if (this.isMulti) {
                const selected = Array.isArray(val) ? val : [];
                return this.options.filter(o => selected.map(String).includes(String(o.id)));
            }
            const found = this.options.find(o => String(o.id) === String(val));
            return found ? found.title : '';
        }
    }" x-init="init()" x-on:click.outside="open = false" class="relative w-full">
        
        <template x-if="isMulti">
            <template x-for="val in (Array.isArray(modelValue) ? modelValue : [])" :key="val">
                <input type="hidden" name="{{ $name }}[]" :value="val">
            </template>
        </template>
        <template x-if="!isMulti">
            <input type="hidden" name="{{ $name }}" :value="modelValue ?? ''">
        </template>

        {{-- Champ Déclencheur --}}
        <div x-on:click="open = !open"
            class="min-h-[42px] w-full cursor-pointer flex flex-wrap items-center gap-1.5 rounded-radius border border-outline bg-surface-alt px-3 py-2 text-sm text-on-surface focus:border-primary dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:text-on-surface-dark">
            <template x-if="isMulti && selectedTitles.length > 0">
                <div class="flex flex-wrap gap-1.5">
                    <template x-for="item in selectedTitles" :key="item.id">
                        <span
                            class="inline-flex items-center gap-1 rounded bg-primary/10 px-2 py-0.5 text-xs font-medium text-primary dark:bg-primary-dark/20 dark:text-primary-dark">
                            <span x-text="item.title"></span>
                            <button type="button" x-on:click.stop="toggle(item.id)"
                                class="hover:text-danger">&times;</button>
                        </span>
                    </template>
                </div>
            </template>

            <template x-if="!isMulti && selectedTitles !== ''">
                <span x-text="selectedTitles"></span>
            </template>

            <template x-if="(isMulti && selectedTitles.length === 0) || (!isMulti && selectedTitles === '')">
                <span class="text-on-surface/40 dark:text-on-surface-dark/40">Sélectionner...</span>
            </template>

            <i class="bx bx-chevron-down ml-auto shrink-0 text-xl text-on-surface/50 dark:text-on-surface-dark/50"></i>
        </div>

        {{-- Menu Déroulant avec Recherche --}}
        <div x-cloak x-show="open" x-transition.opacity.duration.100ms
            class="absolute z-50 mt-1 max-h-60 w-full overflow-auto rounded-radius border border-outline bg-surface p-2 shadow-lg dark:border-outline-dark dark:bg-surface-dark">
            <input type="search" x-model="search" placeholder="Rechercher..."
                class="mb-2 w-full rounded border border-outline bg-surface-alt px-2.5 py-1.5 text-xs text-on-surface focus:border-primary focus:outline-none dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark">

            <ul class="flex flex-col gap-0.5">
                <template x-for="opt in filteredOptions" :key="opt.id">
                    <li x-on:click="toggle(opt.id)"
                        class="flex cursor-pointer items-center justify-between rounded px-3 py-2 text-sm transition-colors hover:bg-primary/10 dark:hover:bg-primary-dark/20"
                        :class="isSelected(opt.id) ? 'font-semibold text-primary dark:text-primary-dark bg-primary/5' : ''">
                        <span x-text="opt.title"></span>
                        <i x-show="isSelected(opt.id)"
                            class="bx bx-check text-lg text-primary dark:text-primary-dark"></i>
                    </li>
                </template>
                <template x-if="filteredOptions.length === 0">
                    <li class="px-3 py-2 text-xs text-on-surface/50 dark:text-on-surface-dark/50">Aucun résultat trouvé
                    </li>
                </template>
            </ul>
        </div>
    </div>
</div>
