{{-- Internal partial of jkc.tables.dynamic-table. Not a public component: include the facade instead. --}}
<div class="relative w-full md:w-[200px]" x-data="jkcTableFilter(filter)" @click.outside="isOpen = false"
    @keydown.escape.window="isOpen = false">

    <button type="button" x-on:click="isOpen = !isOpen"
        class="flex items-center justify-between w-full border border-outline dark:border-outline-dark rounded-radius bg-surface dark:bg-surface-dark px-3 py-2 text-sm text-on-surface dark:text-on-surface-dark text-left focus:outline-primary">
        <span class="truncate" x-text="selectedLabel"
            :class="!selectedValue && 'text-on-surface/50 dark:text-on-surface-dark/50'"></span>

        <div class="flex items-center gap-1.5 ml-2 shrink-0">
            <template x-if="selectedValue">
                <span @click.stop="clearSelection()"
                    class="text-on-surface/40 hover:text-on-surface dark:text-on-surface-dark/40 dark:hover:text-on-surface-dark transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-3.5" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path
                            d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" />
                    </svg>
                </span>
            </template>

            <svg xmlns="http://www.w3.org/2000/svg"
                class="size-4 text-on-surface/50 dark:text-on-surface-dark/50 transition-transform duration-200"
                :class="isOpen ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24"
                stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
            </svg>
        </div>
    </button>

    <div x-show="isOpen" x-cloak x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
        class="absolute z-30 mt-1 w-full max-h-60 overflow-auto rounded-radius border border-outline dark:border-outline-dark bg-surface dark:bg-surface-dark shadow-lg py-1 text-sm focus:outline-none">

        <div x-on:click="clearSelection()"
            :class="selectedValue === '' ? 'bg-primary/10 text-primary font-medium' :
                'text-on-surface dark:text-on-surface-dark hover:bg-surface-alt dark:hover:bg-surface-dark-alt'"
            class="cursor-pointer select-none px-3 py-2 transition-colors">
            <span x-text="filter.label"></span>
        </div>

        <template x-for="option in filter.options" :key="option.value">
            <div x-on:click="selectOption(option.value, option.label)"
                :class="selectedValue === option.value ? 'bg-primary/10 text-primary font-medium' :
                    'text-on-surface dark:text-on-surface-dark hover:bg-surface-alt dark:hover:bg-surface-dark-alt'"
                class="cursor-pointer select-none px-3 py-2 transition-colors">
                <span x-text="option.label"></span>
            </div>
        </template>
    </div>
</div>