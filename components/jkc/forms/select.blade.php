@php

    use Juksgraphic\BladeComponents\Components\Forms\Select;
    use Juksgraphic\BladeComponents\Components\Forms\SelectStyles;

    
    $select = new Select([
        'label'       => $label ?? '',
        'name'        => $name ?? 'select',
        'multiple'    => $multiple ?? false,
        'options'     => $options ?? [],
        'model'       => $model ?? 'formData',
        'initialData' => $initialData ?? [],
        'class'       => $class ?? '',
    ]);

    $styles = new SelectStyles($select);
@endphp

<!-- Field wrapper -->
<div class="{{ $styles->wrapper() }}">

    <!-- Optional field label -->
    @if ($select->hasLabel())
        <label for="{{ $select->name() }}" class="{{ $styles->label() }}">
            {{ $select->label() }}
        </label>
    @endif

    <!-- Searchable dropdown controller -->
    <div x-data="{
        open: false,
        search: '',
        fieldName: '{{ $select->name() }}',
        modelPath: '{{ $select->model() }}',
        isMulti: {{ $select->isMultiple() ? 'true' : 'false' }},
        options: {{ $select->encodeJson($select->options()) }},
    
        // Initialize model property when missing
        init() {
            if (
                this.modelPath === 'formData' &&
                typeof formData !== 'undefined' &&
                formData[this.fieldName] === undefined
            ) {
                formData[this.fieldName] = {{ $select->encodeJson($select->initialValue()) }};
            }
        },
    
        // Read current value from target model
        get modelValue() {
            try {
                return eval(`${this.modelPath}['${this.fieldName}']`);
            } catch (e) {
                return undefined;
            }
        },
    
        // Write updated value to target model
        setModelValue(val) {
            try {
                eval(`${this.modelPath}['${this.fieldName}'] = val`);
            } catch (e) {}
        },
    
        // Filter options matching current search query
        get filteredOptions() {
            if (this.search.trim() === '') return this.options;
            return this.options.filter(o =>
                o.title.toLowerCase().includes(this.search.toLowerCase())
            );
        },
    
        // Determine whether an option ID is currently selected
        isSelected(id) {
            const val = this.modelValue;
            if (this.isMulti) {
                return Array.isArray(val) && val.includes(String(id));
            }
            return String(val) === String(id);
        },
    
        // Toggle selection in multi mode or select and close in single mode
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
    
        // Resolve selected option objects (multi) or single selected label
        get selectedTitles() {
            const val = this.modelValue;
            if (this.isMulti) {
                const selected = Array.isArray(val) ? val : [];
                return this.options.filter(o => selected.map(String).includes(String(o.id)));
            }
            const found = this.options.find(o => String(o.id) === String(val));
            return found ? found.title : '';
        }
    }" x-init="init()" x-on:click.outside="open = false"
        class="{{ $styles->dropdownWrapper() }}">

        <!-- Hidden inputs carrying submitted selection values -->
        <template x-if="isMulti">
            <template x-for="val in (Array.isArray(modelValue) ? modelValue : [])" :key="val">
                <input type="hidden" name="{{ $select->name() }}[]" :value="val">
            </template>
        </template>

        <template x-if="!isMulti">
            <input type="hidden" name="{{ $select->name() }}" :value="modelValue ?? ''">
        </template>

        <!-- Select trigger box displaying pills or single selected value -->
        <div x-on:click="open = !open" class="{{ $styles->trigger() }}">
            <template x-if="isMulti && selectedTitles.length > 0">
                <div class="flex flex-wrap gap-1.5">
                    <template x-for="item in selectedTitles" :key="item.id">
                        <span class="{{ $styles->pill() }}">
                            <span x-text="item.title"></span>
                            <button type="button" x-on:click.stop="toggle(item.id)"
                                class="{{ $styles->pillRemoveButton() }}">&times;</button>
                        </span>
                    </template>
                </div>
            </template>

            <template x-if="!isMulti && selectedTitles !== ''">
                <span x-text="selectedTitles"></span>
            </template>

            <template x-if="(isMulti && selectedTitles.length === 0) || (!isMulti && selectedTitles === '')">
                <span class="{{ $styles->placeholder() }}">Sélectionner...</span>
            </template>

            <i class="{{ $styles->chevron() }}"></i>
        </div>

        <!-- Dropdown menu with live search filter -->
        <div x-cloak x-show="open" x-transition.opacity.duration.100ms class="{{ $styles->menu() }}">
            <input type="search" x-model="search" placeholder="Rechercher..." class="{{ $styles->searchInput() }}">

            <ul class="flex flex-col gap-0.5">
                <template x-for="opt in filteredOptions" :key="opt.id">
                    <li x-on:click="toggle(opt.id)" class="{{ $styles->optionItem() }}"
                        :class="{!! $styles->optionActiveBinding() !!}">
                        <span x-text="opt.title"></span>
                        <i x-show="isSelected(opt.id)" class="{{ $styles->checkIcon() }}"></i>
                    </li>
                </template>

                <template x-if="filteredOptions.length === 0">
                    <li class="{{ $styles->emptyState() }}">Aucun résultat trouvé</li>
                </template>
            </ul>
        </div>

    </div>

</div>
