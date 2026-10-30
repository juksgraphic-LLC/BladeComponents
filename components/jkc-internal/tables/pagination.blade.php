{{-- Internal partial of jkc.tables.dynamic-table. Not a public component: include the facade instead. --}}
<div x-show="lastPage > 1" class="flex w-full flex-col items-center gap-3" x-data="jkcTablePagination">

    {{-- Mobile (< md) --}}
    <div class="flex md:hidden w-full items-center justify-between gap-2">
        <button type="button" x-on:click="goToPage(currentPage - 1)" :disabled="currentPage === 1"
            class="flex items-center gap-1 rounded-radius border border-outline dark:border-outline-dark px-3 py-2 text-sm font-medium text-on-surface dark:text-on-surface-dark disabled:opacity-30 disabled:cursor-not-allowed">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                <path fill-rule="evenodd"
                    d="M11.78 5.22a.75.75 0 0 1 0 1.06L8.06 10l3.72 3.72a.75.75 0 1 1-1.06 1.06l-4.25-4.25a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 0 1 1.06 0Z"
                    clip-rule="evenodd" />
            </svg>
            Préc.
        </button>

        <p class="text-xs font-medium text-on-surface/60 dark:text-on-surface-dark/60 whitespace-nowrap">
            Page <span x-text="currentPage" class="text-on-surface dark:text-on-surface-dark font-bold"></span> /
            <span x-text="lastPage"></span>
        </p>

        <button type="button" x-on:click="goToPage(currentPage + 1)" :disabled="currentPage === lastPage"
            class="flex items-center gap-1 rounded-radius border border-outline dark:border-outline-dark px-3 py-2 text-sm font-medium text-on-surface dark:text-on-surface-dark disabled:opacity-30 disabled:cursor-not-allowed">
            Suiv.
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5">
                <path fill-rule="evenodd"
                    d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z"
                    clip-rule="evenodd" />
            </svg>
        </button>
    </div>

    {{-- Desktop (>= md) --}}
    <div class="hidden md:flex w-full flex-row items-center justify-between gap-4">
        <p class="text-xs text-on-surface/50 dark:text-on-surface-dark/50">
            Page <span x-text="currentPage"></span> sur <span x-text="lastPage"></span>
        </p>

        <ul class="flex shrink-0 items-center gap-2 text-sm font-medium">
            <li>
                <button type="button" x-on:click="goToPage(currentPage - 1)" :disabled="currentPage === 1"
                    class="flex items-center rounded-radius p-1 text-on-surface hover:text-primary dark:text-on-surface-dark dark:hover:text-primary-dark disabled:opacity-30 disabled:cursor-not-allowed">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-6">
                        <path fill-rule="evenodd"
                            d="M11.78 5.22a.75.75 0 0 1 0 1.06L8.06 10l3.72 3.72a.75.75 0 1 1-1.06 1.06l-4.25-4.25a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 0 1 1.06 0Z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </li>

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

            <li>
                <button type="button" x-on:click="goToPage(currentPage + 1)" :disabled="currentPage === lastPage"
                    class="flex items-center rounded-radius p-1 text-on-surface hover:text-primary dark:text-on-surface-dark dark:hover:text-primary-dark disabled:opacity-30 disabled:cursor-not-allowed">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-6">
                        <path fill-rule="evenodd"
                            d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </li>

            <li class="ml-2 flex items-center gap-1">
                <input type="number" min="1" :max="lastPage" x-model.number="pageInput"
                    @keydown.enter="jumpToPage(pageInput)"
                    class="w-16 border border-outline dark:border-outline-dark rounded-radius bg-surface dark:bg-surface-dark text-on-surface dark:text-on-surface-dark text-xs px-2 py-1 focus:outline-primary" />
                <button type="button" x-on:click="jumpToPage(pageInput)"
                    class="px-2 py-1 text-xs rounded-radius border border-outline dark:border-outline-dark text-on-surface/70 dark:text-on-surface-dark/70 hover:bg-surface-alt dark:hover:bg-surface-dark-alt transition">
                    Go
                </button>
            </li>
        </ul>
    </div>
</div>
