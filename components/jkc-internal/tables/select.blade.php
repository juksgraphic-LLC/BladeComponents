{{--
    Internal partial of jkc.tables.dynamic-table. Not a public component: include the facade instead.

    Generic dropdown (custom, not a native select) shared by the filters and the rows-per-page selector.
    Its behaviour lives in resources/js/jkc-table.js (dropdown()). Parameters, always passed explicitly
    because @include inherits the scope of the page:
      $jkcSelectData   x-data expression of the dropdown.
      $jkcSelectStyle  'filter' or 'compact' (sizes only: the markup is the same).
--}}
@php
    $jkcSelectStyles = [
        'filter' => [
            'root' => 'relative w-full md:w-[200px]',
            'button' =>
                'flex items-center justify-between w-full border border-outline dark:border-outline-dark rounded-radius bg-surface dark:bg-surface-dark px-3 py-2 text-sm text-on-surface dark:text-on-surface-dark text-left focus:outline-primary',
            'label' => 'truncate',
            'icons' => 'ml-2',
            'chevron' => 'size-4 text-on-surface/50 dark:text-on-surface-dark/50 transition-transform duration-200',
            'menu' =>
                'absolute z-30 mt-1 w-full max-h-60 overflow-auto rounded-radius border border-outline dark:border-outline-dark bg-surface dark:bg-surface-dark shadow-lg py-1 text-sm focus:outline-none',
            'option' => 'cursor-pointer select-none px-3 py-2 transition-colors',
        ],
        'compact' => [
            'root' => 'relative w-[80px]',
            'button' =>
                'flex items-center justify-between w-full border border-outline dark:border-outline-dark rounded-radius bg-surface dark:bg-surface-dark px-2.5 py-1.5 text-xs text-on-surface dark:text-on-surface-dark text-left focus:outline-primary',
            'label' => 'truncate font-semibold',
            'icons' => 'ml-1',
            'chevron' => 'size-3.5 text-on-surface/50 dark:text-on-surface-dark/50 transition-transform duration-200',
            'menu' =>
                'absolute right-0 z-30 mt-1 w-full max-h-48 overflow-auto rounded-radius border border-outline dark:border-outline-dark bg-surface dark:bg-surface-dark shadow-lg py-1 text-xs focus:outline-none',
            'option' => 'cursor-pointer select-none px-3 py-1.5 transition-colors text-center',
        ],
    ];

    $jkcSelectUi = $jkcSelectStyles[$jkcSelectStyle ?? 'filter'] ?? $jkcSelectStyles['filter'];

    /** Alpine binding handed to the icon partial: the chevron flips while the menu is open. */
    $jkcChevronAttrs = ':class="isOpen ? \'rotate-180\' : \'\'"';
@endphp

<div class="{{ $jkcSelectUi['root'] }}" x-data="{{ $jkcSelectData }}" @click.outside="close()" @keydown.escape.window="close()">

    <button type="button" x-on:click="toggle()" class="{{ $jkcSelectUi['button'] }}">
        <span class="{{ $jkcSelectUi['label'] }}" x-text="currentLabel()"
            :class="!hasValue() && 'text-on-surface/50 dark:text-on-surface-dark/50'"></span>

        <div class="flex items-center gap-1.5 shrink-0 {{ $jkcSelectUi['icons'] }}">
            <template x-if="canClear()">
                <span @click.stop="clear()"
                    class="text-on-surface/40 hover:text-on-surface dark:text-on-surface-dark/40 dark:hover:text-on-surface-dark transition-colors">
                    @include('jkc-internal.tables.icon', ['jkcIcon' => 'x-mini', 'jkcIconClass' => 'size-3.5', 'jkcIconStroke' => '', 'jkcIconAttrs' => ''])
                </span>
            </template>

            @include('jkc-internal.tables.icon', ['jkcIcon' => 'chevron-down', 'jkcIconClass' => $jkcSelectUi['chevron'], 'jkcIconStroke' => '', 'jkcIconAttrs' => $jkcChevronAttrs])
        </div>
    </button>

    <div x-show="isOpen" x-cloak x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95" class="{{ $jkcSelectUi['menu'] }}">

        
        <div x-show="hasSearch()" class="sticky top-0 bg-surface dark:bg-surface-dark px-2 pb-1">
            <input type="text" x-ref="query" x-model="query" @keydown.enter.prevent="chooseFirst()"
                placeholder="Search..." autocomplete="off"
                class="w-full rounded-radius border border-outline dark:border-outline-dark bg-surface dark:bg-surface-dark px-2 py-1.5 text-sm text-on-surface dark:text-on-surface-dark placeholder:text-on-surface/50 dark:placeholder:text-on-surface-dark/50 focus:outline-primary" />
        </div>

        
        <template x-if="clearable && !isSearching()">
            <div x-on:click="clear()"
                :class="!hasValue() ? 'bg-primary/10 text-primary font-medium' :
                    'text-on-surface dark:text-on-surface-dark hover:bg-surface-alt dark:hover:bg-surface-dark-alt'"
                class="{{ $jkcSelectUi['option'] }}">
                <span x-text="placeholder()"></span>
            </div>
        </template>

        <template x-for="option in shownChoices()" :key="option.value">
            <div x-on:click="choose(option.value)"
                :class="isChosen(option.value) ? 'bg-primary/10 text-primary font-medium' :
                    'text-on-surface dark:text-on-surface-dark hover:bg-surface-alt dark:hover:bg-surface-dark-alt'"
                class="{{ $jkcSelectUi['option'] }}">
                <span x-text="option.label"></span>
            </div>
        </template>

        <div x-show="isSearching() && shownChoices().length === 0"
            class="px-3 py-2 text-on-surface/50 dark:text-on-surface-dark/50">
            No match
        </div>
    </div>
</div>