{{-- Internal partial of jkc.tables.dynamic-table. Not a public component: include the facade instead. --}}
{{--
    One markup for every screen size. The DOM follows the desktop order (info, previous, pages, next, jump);
    below md the page list and the jump box are hidden and `order-*` puts previous / info / next in a row.
--}}
<div x-show="lastPage > 1" class="flex w-full items-center justify-between gap-2" x-data="jkcTablePagination">

    {{-- Page indicator --}}
    <p
        class="order-2 md:order-none md:mr-auto whitespace-nowrap text-xs font-medium text-on-surface/60 dark:text-on-surface-dark/60">
        Page <span x-text="currentPage" class="font-bold text-on-surface dark:text-on-surface-dark"></span> sur
        <span x-text="lastPage"></span>
    </p>

    {{-- Previous: bordered button with a label on mobile, bare icon from md --}}
    <button type="button" x-on:click="goToPage(currentPage - 1)" :disabled="currentPage === 1"
        class="order-1 md:order-none flex items-center gap-1 rounded-radius border border-outline dark:border-outline-dark px-3 py-2 text-sm font-medium text-on-surface hover:text-primary dark:text-on-surface-dark dark:hover:text-primary-dark disabled:opacity-30 disabled:cursor-not-allowed md:gap-0 md:border-0 md:p-1">
        @include('jkc-internal.tables.icon', ['jkcIcon' => 'chevron-left-mini', 'jkcIconClass' => 'size-5 md:size-6', 'jkcIconStroke' => '', 'jkcIconAttrs' => ''])
        <span class="md:hidden">Préc.</span>
    </button>

    {{-- Page numbers (md and up) --}}
    <ul class="hidden md:flex shrink-0 items-center gap-2 text-sm font-medium">
        <template x-for="(page, index) in pageList()" :key="index">
            <li>
                <template x-if="page !== '...'">
                    <button type="button" x-on:click="goToPage(page)"
                        :class="page === currentPage ?
                            'bg-primary font-bold text-on-primary dark:bg-primary-dark dark:text-on-primary-dark' :
                            'text-on-surface hover:text-primary dark:text-on-surface-dark dark:hover:text-primary-dark'"
                        class="flex size-6 items-center justify-center rounded-radius p-4">
                        <span x-text="page"></span>
                    </button>
                </template>
                <template x-if="page === '...'">
                    <span
                        class="flex size-6 items-center justify-center text-on-surface/50 dark:text-on-surface-dark/50">...</span>
                </template>
            </li>
        </template>
    </ul>

    {{-- Next --}}
    <button type="button" x-on:click="goToPage(currentPage + 1)" :disabled="currentPage === lastPage"
        class="order-3 md:order-none flex items-center gap-1 rounded-radius border border-outline dark:border-outline-dark px-3 py-2 text-sm font-medium text-on-surface hover:text-primary dark:text-on-surface-dark dark:hover:text-primary-dark disabled:opacity-30 disabled:cursor-not-allowed md:gap-0 md:border-0 md:p-1">
        <span class="md:hidden">Suiv.</span>
        @include('jkc-internal.tables.icon', ['jkcIcon' => 'chevron-right-mini', 'jkcIconClass' => 'size-5 md:size-6', 'jkcIconStroke' => '', 'jkcIconAttrs' => ''])
    </button>

    {{-- Jump to a page (md and up) --}}
    <div class="hidden md:flex items-center gap-1 md:ml-2">
        <input type="number" min="1" :max="lastPage" x-model.number="pageInput"
            @keydown.enter="jumpToPage(pageInput)"
            class="w-16 border border-outline dark:border-outline-dark rounded-radius bg-surface dark:bg-surface-dark text-on-surface dark:text-on-surface-dark text-xs px-2 py-1 focus:outline-primary" />
        <button type="button" x-on:click="jumpToPage(pageInput)"
            class="px-2 py-1 text-xs rounded-radius border border-outline dark:border-outline-dark text-on-surface/70 dark:text-on-surface-dark/70 hover:bg-surface-alt dark:hover:bg-surface-dark-alt transition">
            Go
        </button>
    </div>
</div>