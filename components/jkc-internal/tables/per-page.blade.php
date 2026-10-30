{{-- Internal partial of jkc.tables.dynamic-table. Not a public component: include the facade instead. --}}
{{-- Rows per page & counter --}}
<div class="flex items-center gap-4 shrink-0">
    <div class="flex items-center gap-2">
        <span class="text-xs text-on-surface/50 dark:text-on-surface-dark/50 whitespace-nowrap">Rows</span>

        <div class="relative w-[80px]" x-data="jkcTablePerPage" @click.outside="isOpen = false"
            @keydown.escape.window="isOpen = false">

            <button type="button" x-on:click="isOpen = !isOpen"
                class="flex items-center justify-between w-full border border-outline dark:border-outline-dark rounded-radius bg-surface dark:bg-surface-dark px-2.5 py-1.5 text-xs text-on-surface dark:text-on-surface-dark text-left focus:outline-primary">
                <span class="truncate font-semibold" x-text="perPage"></span>

                <svg xmlns="http://www.w3.org/2000/svg"
                    class="size-3.5 text-on-surface/50 dark:text-on-surface-dark/50 transition-transform duration-200 ml-1"
                    :class="isOpen ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                    stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <div x-show="isOpen" x-cloak x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="absolute right-0 z-30 mt-1 w-full max-h-48 overflow-auto rounded-radius border border-outline dark:border-outline-dark bg-surface dark:bg-surface-dark shadow-lg py-1 text-xs focus:outline-none">

                <template x-for="option in perPageOptions" :key="option">
                    <div x-on:click="selectOption(option)"
                        :class="perPage === option ? 'bg-primary/10 text-primary font-medium' :
                            'text-on-surface dark:text-on-surface-dark hover:bg-surface-alt dark:hover:bg-surface-dark-alt'"
                        class="cursor-pointer select-none px-3 py-1.5 transition-colors text-center">
                        <span x-text="option"></span>
                    </div>
                </template>
            </div>
        </div>
    </div>

    <div
        class="flex items-center gap-2 text-xs font-medium text-on-surface/50 dark:text-on-surface-dark/50 whitespace-nowrap">
        <span x-show="loading" x-cloak
            class="inline-block w-3.5 h-3.5 border-2 border-primary border-t-transparent rounded-full animate-spin"></span>
        <span><span x-text="total" class="text-on-surface dark:text-on-surface-dark font-semibold"></span>
            results</span>
    </div>
</div>
