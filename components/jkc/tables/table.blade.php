@php
    $baseRoute = rtrim($baseRoute ?? '', '/');
@endphp

<div x-data="{
    endpoint:  '{{ $baseRoute }}/table',
    baseRoute: '{{ $baseRoute }}',
    headers:    {{ \Illuminate\Support\Js::from($headers ?? ($columns ?? [])) }},
    filters:    {{ \Illuminate\Support\Js::from($filters ?? []) }},
    withAction: {{ \Illuminate\Support\Js::from($withAction ?? []) }},

    rows: [],
    total: 0,
    currentPage: 1,
    lastPage: 1,
    pageInput: 1,

    search: '',
    activeFilters: {},
    perPage: {{ $perPage ?? 10 }},
    perPageOptions: [10, 25, 50, 100],
    sortKey: '',
    sortDir: 'asc',
    loading: false,

    init() {
        this.fetchData();
    },

    buildParams() {
        const params = new URLSearchParams();
        params.append('page', this.currentPage);
        params.append('per_page', this.perPage);
        if (this.search.trim()) params.append('search', this.search.trim());
        if (this.sortKey) params.append('sort', this.sortKey);
        if (this.sortKey) params.append('direction', this.sortDir);
        Object.entries(this.activeFilters).forEach(([key, val]) => {
            if (val) params.append('filters[' + key + ']', val);
        });
        return params.toString();
    },

    async fetchData() {
        this.loading = true;
        const res = await fetch(this.endpoint + '?' + this.buildParams());
        const json = await res.json();
        this.rows = json.data;
        this.total = json.total;
        this.currentPage = json.current_page;
        this.lastPage = json.last_page;
        this.pageInput = this.currentPage;
        this.loading = false;
        this.$nextTick(() => htmx.process(this.$root));
    },

    sortBy(key) {
        if (this.sortKey === key) {
            this.sortDir = this.sortDir === 'asc' ? 'desc' : 'asc';
        } else {
            this.sortKey = key;
            this.sortDir = 'asc';
        }
        this.currentPage = 1;
        this.fetchData();
    },

    changePerPage(val) {
        this.perPage = parseInt(val);
        this.currentPage = 1;
        this.fetchData();
    },

    goToPage(page) {
        page = parseInt(page);
        if (isNaN(page) || page < 1 || page > this.lastPage || page === this.currentPage) {
            this.pageInput = this.currentPage;
            return;
        }
        this.currentPage = page;
        this.fetchData();
    },


    pageList() {
        const total = this.lastPage;
        const current = this.currentPage;
        const delta = 1;
        const range = [];

        if (total <= 1) return [1];

        const start = Math.max(2, current - delta);
        const end = Math.min(total - 1, current + delta);

        range.push(1);

        if (start > 2) {
            range.push('...');
        }

        for (let i = start; i <= end; i++) {
            range.push(i);
        }

        if (end < total - 1) {
            range.push('...');
        }

        range.push(total);

        return range;
    },

    val(obj, path) {
        if (!path) return '';
        return path.split('.').reduce((o, k) => (o != null ? o[k] : ''), obj) ?? '';
    },

    initSlimSelect(el, key) {
        new SlimSelect({
            select: el,
            settings: {
                showSearch: true,
                placeholderText: el.getAttribute('placeholder') || 'Select...',
                allowDeselect: true,
            },
            events: {
                beforeChange: (newVal) => {
                    this.activeFilters[key] = newVal[0]?.value ?? '';
                    this.currentPage = 1;
                    this.fetchData();
                    return true;
                }
            }
        });
    },

    deleteModalOpen: false,
    itemToDelete: null,

    deleteItem(id) {
        this.itemToDelete = id;
        this.deleteModalOpen = true;
    },

    async confirmDelete() {
        if (!this.itemToDelete) return;
        await fetch(this.baseRoute + '/' + this.itemToDelete, { method: 'DELETE' });
        this.deleteModalOpen = false;
        this.itemToDelete = null;
        this.fetchData();
    }
}" x-init="init()" class="flex flex-col gap-4">

    {{-- FILTERS BAR --}}
    <div class="flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="flex flex-wrap items-center gap-4 flex-1">

            {{-- Search --}}
            <div class="relative w-full md:w-sm">
                <span
                    class="absolute inset-y-0 left-0 flex items-center pl-3 text-on-surface/50 dark:text-on-surface-dark/50">
                    <i class="bi bi-search"></i>
                </span>
                <input type="text" x-model="search" @input.debounce.300ms="currentPage = 1; fetchData()"
                    placeholder="Search..."
                    class="w-full border placeholder:text-on-surface/50 dark:text-on-surface-dark dark:placeholder:text-on-surface-dark/50 border-outline rounded-radius bg-surface px-3 py-2 pl-10 text-sm focus:outline-primary dark:border-outline-dark dark:bg-surface-dark" />
            </div>

            {{-- SlimSelect Filters --}}
            <template x-for="filter in filters" :key="filter.key">
                <div class="w-full md:w-[200px] slim-select-custom">
                    <select :placeholder="filter.label" x-init="initSlimSelect($el, filter.key)">
                        <option data-placeholder="true"></option>
                        <template x-for="(label, value) in filter.options" :key="value">
                            <option :value="value" x-text="label"></option>
                        </template>
                    </select>
                </div>
            </template>
        </div>

        {{-- Per page + Results counter + loader --}}
        <div class="flex items-center gap-4">
            <div class="flex items-center gap-2">
                <label class="text-xs text-on-surface/50 dark:text-on-surface-dark/50 whitespace-nowrap">Rows</label>
                <select x-on:change="changePerPage($event.target.value)"
                    class="border border-outline dark:border-outline-dark rounded-radius bg-surface dark:bg-surface-dark text-on-surface dark:text-on-surface-dark text-xs px-2 py-1.5 focus:outline-primary">
                    <template x-for="opt in perPageOptions" :key="opt">
                        <option :value="opt" :selected="opt === perPage" x-text="opt"></option>
                    </template>
                </select>
            </div>
            <div class="text-xs font-medium text-on-surface/50 dark:text-on-surface-dark/50 flex items-center gap-2">
                <span x-show="loading"
                    class="inline-block w-3 h-3 border-2 border-primary border-t-transparent rounded-full animate-spin"></span>
                <span x-text="total"></span> results
            </div>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="overflow-hidden w-full overflow-x-auto rounded-radius border border-outline dark:border-outline-dark bg-surface dark:bg-surface-dark"
        :class="loading ? 'opacity-50 pointer-events-none' : ''">
        <table class="w-full text-nowrap text-left text-sm">

            {{-- THEAD --}}
            <thead
                class="border-b border-outline bg-surface-alt text-on-surface-strong dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark-strong">
                <tr>
                    <template x-for="col in headers" :key="col.key">
                        <th scope="col" class="p-4">

                            {{-- Sortable --}}
                            <template x-if="col.sortable">
                                <button x-on:click="sortBy(col.key)"
                                    class="flex items-center gap-1.5 hover:text-primary transition-colors focus:outline-none">
                                    <span x-text="col.label"></span>
                                    <span class="flex flex-col -space-y-1">
                                        <i class="bi bi-caret-up-fill text-[8px]"
                                            :class="sortKey === col.key && sortDir === 'asc' ? 'text-primary' :
                                                'opacity-30'"></i>
                                        <i class="bi bi-caret-down-fill text-[8px]"
                                            :class="sortKey === col.key && sortDir === 'desc' ? 'text-primary' :
                                                'opacity-30'"></i>
                                    </span>
                                </button>
                            </template>

                            {{-- Not sortable --}}
                            <template x-if="!col.sortable">
                                <span x-text="col.label"></span>
                            </template>

                        </th>
                    </template>
                    <template x-if="withAction.length > 0">
                        <th scope="col" class="p-4 text-right">Actions</th>
                    </template>
                </tr>
            </thead>

            {{-- TBODY --}}
            <tbody class="divide-y divide-outline dark:divide-outline-dark">

                {{-- SKELETON --}}
                <template x-if="loading">
                    <template x-for="i in perPage" :key="i">
                        <tr class="animate-pulse">
                            <template x-for="col in headers" :key="col.key">
                                <td class="p-4">
                                    <div class="h-4 bg-on-surface/10 dark:bg-on-surface-dark/10 rounded-radius w-3/4">
                                    </div>
                                </td>
                            </template>
                            <template x-if="withAction.length > 0">
                                <td class="p-4">
                                    <div class="flex justify-end gap-2">
                                        <div class="h-7 w-7 bg-on-surface/10 dark:bg-on-surface-dark/10 rounded-radius">
                                        </div>
                                        <div class="h-7 w-7 bg-on-surface/10 dark:bg-on-surface-dark/10 rounded-radius">
                                        </div>
                                        <div class="h-7 w-7 bg-on-surface/10 dark:bg-on-surface-dark/10 rounded-radius">
                                        </div>
                                    </div>
                                </td>
                            </template>
                        </tr>
                    </template>
                </template>

                {{-- ROWS --}}
                <template x-if="!loading">
                    <template x-for="(item, index) in rows" :key="item.id ?? index">
                        <tr class="relative hover:bg-primary/5 dark:hover:bg-primary/10 transition-colors group">

                            <template x-for="(col, colIndex) in headers" :key="col.key">
                                <td class="p-4">

                                    <template x-if="colIndex === 0 && withAction.includes('view')">
                                        <a :href="`${baseRoute}/${item.id}`" class="absolute inset-0 z-0"
                                            aria-label="View row"></a>
                                    </template>
                                    <template x-if="col.type === 'link'">
                                        <a class="hover:underline text-blue-500 relative z-10"
                                            :href="val(item, col.key) || ''" x-text="val(item, col.key) || 'N/A'"></a>
                                    </template>
                                    <template x-if="col.type === 'badge'">
                                        <span
                                            class="py-1 px-3 bg-secondary/10 text-secondary rounded-full text-xs font-bold capitalize"
                                            x-text="val(item, col.key) || 'N/A'"></span>
                                    </template>
                                    <template x-if="col.type === 'image'">
                                        <img :src="val(item, col.key) || '/assets/img/placeholder.png'"
                                            onerror="this.src='/assets/shared/img/no-image.png'"
                                            class="size-10 rounded-full" />
                                    </template>
                                    <template x-if="col.type === 'text'">
                                        <span
                                            class="text-on-surface-strong dark:text-on-surface-dark-strong group-hover:text-primary transition-colors"
                                            x-text="val(item, col.key)"></span>
                                    </template>
                                </td>
                            </template>

                            {{-- ACTIONS --}}
                            <template x-if="withAction.length > 0">
                                <td class="p-4 text-right relative z-10" @click.stop>
                                    <div class="flex justify-end gap-2">
                                        <template x-if="withAction.includes('view')">
                                            <a :href="`${baseRoute}/${item.id}`"
                                                class="bg-transparent rounded-radius border border-outline dark:border-outline-dark px-2 py-1 text-on-surface/60 dark:text-on-surface-dark/60 transition hover:opacity-75"
                                                aria-label="View">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                        </template>
                                        <template x-if="withAction.includes('edit')">
                                            <a :href="`${baseRoute}/${item.id}/edit`"
                                                class="bg-transparent rounded-radius border border-outline dark:border-outline-dark px-2 py-1 text-on-surface/60 dark:text-on-surface-dark/60 transition hover:opacity-75"
                                                aria-label="Edit">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                        </template>
                                        <template x-if="withAction.includes('delete')">
                                            <button type="button" x-on:click="deleteItem(item.id)"
                                                class="bg-transparent rounded-radius border border-outline dark:border-outline-dark px-2 py-1 text-on-surface/60 dark:text-on-surface-dark/60 transition hover:opacity-75"
                                                aria-label="Delete">
                                                <i class="bi bi-trash3"></i>
                                            </button>
                                        </template>
                                    </div>
                                </td>
                            </template>

                        </tr>
                    </template>
                </template>

            </tbody>
        </table>

        {{-- EMPTY STATE --}}
        <template x-if="rows.length === 0 && !loading">
            <div
                class="flex flex-col items-center justify-center p-12 text-on-surface/40 dark:text-on-surface-dark/40">
                <i class="bi bi-inbox text-4xl mb-2"></i>
                <p class="italic text-sm">No results found</p>
            </div>
        </template>
    </div>

    {{-- PAGINATION --}}
    <div x-show="lastPage > 1" class="flex flex-col md:flex-row items-center justify-between gap-4">
        <p class="text-xs text-on-surface/50 dark:text-on-surface-dark/50">
            Page <span x-text="currentPage"></span> sur <span x-text="lastPage"></span>
        </p>

        <ul class="flex shrink-0 items-center gap-2 text-sm font-medium">
            <li>
                <button type="button" x-on:click="goToPage(currentPage - 1)" :disabled="currentPage === 1"
                    class="flex items-center rounded-radius p-1 text-on-surface hover:text-primary dark:text-on-surface-dark dark:hover:text-primary-dark disabled:opacity-30 disabled:cursor-not-allowed"
                    aria-label="previous page">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                        aria-hidden="true" class="size-6">
                        <path fill-rule="evenodd"
                            d="M11.78 5.22a.75.75 0 0 1 0 1.06L8.06 10l3.72 3.72a.75.75 0 1 1-1.06 1.06l-4.25-4.25a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 0 1 1.06 0Z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </li>

            <template x-for="(page, idx) in pageList()" :key="idx">
                <li>
                    <template x-if="page !== '...'">
                        <button type="button" x-on:click="goToPage(page)"
                            :aria-current="page === currentPage ? 'page' : null" :aria-label="`page ${page}`"
                            :class="page === currentPage ?
                                'bg-primary font-bold text-on-primary dark:bg-primary-dark dark:text-on-primary-dark' :
                                'text-on-surface hover:text-primary dark:text-on-surface-dark dark:hover:text-primary-dark'"
                            class="flex size-6 items-center justify-center rounded-radius p-4">
                            <span x-text="page"></span>
                        </button>
                    </template>
                    <template x-if="page === '...'">
                        <span
                            class="flex size-6 items-center justify-center text-on-surface/50 dark:text-on-surface-dark/50"
                            aria-hidden="true">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="size-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM12.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0ZM18.75 12a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                            </svg>
                        </span>
                    </template>
                </li>
            </template>

            <li>
                <button type="button" x-on:click="goToPage(currentPage + 1)" :disabled="currentPage === lastPage"
                    class="flex items-center rounded-radius p-1 text-on-surface hover:text-primary dark:text-on-surface-dark dark:hover:text-primary-dark disabled:opacity-30 disabled:cursor-not-allowed"
                    aria-label="next page">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                        aria-hidden="true" class="size-6">
                        <path fill-rule="evenodd"
                            d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z"
                            clip-rule="evenodd" />
                    </svg>
                </button>
            </li>

            {{-- Aller à une page précise --}}
            <li class="ml-2 flex items-center gap-1">
                <input type="number" min="1" :max="lastPage" x-model.number="pageInput"
                    @keydown.enter="goToPage(pageInput)"
                    class="w-16 border border-outline dark:border-outline-dark rounded-radius bg-surface dark:bg-surface-dark text-on-surface dark:text-on-surface-dark text-xs px-2 py-1 focus:outline-primary"
                    aria-label="Aller à la page" />
                <button type="button" x-on:click="goToPage(pageInput)"
                    class="px-2 py-1 text-xs rounded-radius border border-outline dark:border-outline-dark text-on-surface/70 dark:text-on-surface-dark/70 hover:bg-surface-alt dark:hover:bg-surface-dark-alt transition">
                    Go
                </button>
            </li>
        </ul>
    </div>

    {{-- DELETE MODAL --}}
    <div x-show="deleteModalOpen" x-cloak class="fixed inset-0 z-25 overflow-y-auto">
        <div x-show="deleteModalOpen" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="fixed inset-0 bg-black/50 backdrop-blur-sm">
        </div>
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div x-show="deleteModalOpen" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                @click.away="deleteModalOpen = false"
                class="relative bg-surface dark:bg-surface-dark w-full max-w-md rounded-radius shadow-2xl border border-outline dark:border-outline-dark p-6">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 rounded-full bg-danger/10 flex items-center justify-center text-danger">
                        <i class="bi bi-exclamation-triangle text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-on-surface dark:text-on-surface-dark">Confirm deletion
                    </h3>
                </div>
                <p class="text-on-surface/70 dark:text-on-surface-dark/70 mb-6">
                    Are you sure you want to delete this item?<br>
                    <span class="text-xs font-medium text-danger">This action cannot be undone.</span>
                </p>
                <div class="flex justify-between gap-3">
                    <button x-on:click="deleteModalOpen = false"
                        class="px-4 py-2 rounded-radius border border-outline dark:border-outline-dark text-on-surface dark:text-on-surface-dark font-medium hover:bg-surface-alt dark:hover:bg-surface-dark-alt transition-colors">
                        Cancel
                    </button>
                    <button x-on:click="confirmDelete()"
                        class="px-4 py-2 rounded-radius bg-danger text-white font-bold hover:opacity-90 flex items-center gap-2">
                        <i class="bi bi-trash"></i> Delete
                    </button>
                </div>
            </div>
        </div>
    </div>

</div>
