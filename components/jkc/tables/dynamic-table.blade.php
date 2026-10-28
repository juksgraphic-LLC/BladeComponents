@php
    /**
     * Data Table Component (Alpine.js + fetch) — Juksgraphic / Penguin UI
     *
     * @param string               $baseRoute       Base URL of the resource. Default: ''.
     * @param string               $endpoint        JSON endpoint returning paginated rows. Default: "{baseRoute}/table".
     * @param array<int, array>    $headers         Columns [key, label, type, sortable, variants]. Alias: $columns. Default: [].
     * @param array<int, array>    $filters         Filters [key, label, options]. Options: [value => label]. Default: [].
     * @param array<int, string>   $withAction      Enabled row actions: view, edit, delete. Default: [].
     * @param bool                 $selectable      Enable checkbox selection column for bulk actions. Default: false.
     * @param int                  $perPage         Initial rows per page. Default: 5.
     */

    /** @var string $baseRoute */
    $baseRoute = rtrim((string) ($baseRoute ?? ''), '/');

    /** @var string $endpoint */
    $endpoint = (string) ($endpoint ?? $baseRoute . '/table');

    /** @var array<int, array<string, mixed>> $headers */
    $headers = is_array($headers ?? null) ? $headers : (is_array($columns ?? null) ? $columns : []);

    /** @var array<int, array<string, mixed>> $filters */
    $filters = is_array($filters ?? null) ? $filters : [];

    /** @var array<int, string> $withAction */
    $withAction = is_array($withAction ?? null) ? array_values($withAction) : [];

    /** @var bool $selectable */
    $selectable = (bool) ($selectable ?? false);

    /** @var int $perPage */
    $perPage = max(1, (int) ($perPage ?? 5));

    /** @var array<int, int> $perPageOptions */
    $perPageOptions = array_values(array_unique(array_merge([5, 10, 25, 50, 100], [$perPage])));
    sort($perPageOptions);

    /** Normalizes filter options */
    $filters = array_map(function (array $filter): array {
        $options = [];
        foreach ($filter['options'] ?? [] as $value => $label) {
            $options[] = is_array($label) ? $label : ['value' => (string) $value, 'label' => (string) $label];
        }
        $filter['options'] = $options;
        return $filter;
    }, $filters);

    /** Config JSON */
    $jsonConfig = json_encode(
        compact('endpoint', 'headers', 'filters', 'withAction', 'selectable', 'perPage', 'perPageOptions'),
        JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE,
    );
@endphp

<div data-config="{{ $jsonConfig }}" class="flex flex-col gap-4 min-w-0 w-full" x-data="{
    endpoint: '',
    headers: [],
    filters: [],
    withAction: [],
    selectable: false,
    perPage: 5,
    perPageOptions: [],

    rows: [],
    total: 0,
    currentPage: 1,
    lastPage: 1,
    search: '',
    activeFilters: {},
    sortKey: '',
    sortDir: 'asc',
    loading: false,
    errorMessage: '',
    lastRequestId: 0,

    init() {
        this.applyConfig()
        this.fetchData()
    },

    applyConfig() {
        Object.assign(this, JSON.parse(this.$el.dataset.config))
    },

    requestHeaders() {
        const csrfToken = document.querySelector('meta[name=csrf-token]')?.content
        return {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            ...(csrfToken && { 'X-CSRF-TOKEN': csrfToken })
        }
    },

    buildParams() {
        const params = new URLSearchParams({ page: this.currentPage, per_page: this.perPage })
        if (this.search.trim()) params.append('search', this.search.trim())
        if (this.sortKey) {
            params.append('sort', this.sortKey)
            params.append('direction', this.sortDir)
        }
        Object.entries(this.activeFilters).forEach(([key, value]) => {
            if (value) params.append('filters[' + key + ']', value)
        })
        return params.toString()
    },

    applyResponse(json) {
        this.rows = json.data ?? []
        this.total = json.total ?? 0
        this.currentPage = json.current_page ?? 1
        this.lastPage = json.last_page ?? 1
    },

    async fetchData() {
        const requestId = ++this.lastRequestId
        this.loading = true
        this.errorMessage = ''

        try {
            const response = await fetch(this.endpoint + '?' + this.buildParams(), { headers: this.requestHeaders() })
            if (!response.ok) throw new Error('HTTP ' + response.status)
            const json = await response.json()

            if (requestId === this.lastRequestId) this.applyResponse(json)

        } catch (error) {

            if (requestId === this.lastRequestId) this.errorMessage = 'Failed to load the data'

        } finally {
            if (requestId === this.lastRequestId) this.loading = false
        }
    },

    resetAndFetch() {
        this.currentPage = 1
        this.fetchData()
    },

    setFilter(key, value) {
        this.activeFilters[key] = value
        this.resetAndFetch()
    },

    sortBy(key) {
        this.sortDir = this.sortKey === key && this.sortDir === 'asc' ? 'desc' : 'asc'
        this.sortKey = key
        this.resetAndFetch()
    },

    goToPage(page) {
        const target = parseInt(page)
        if (isNaN(target) || target < 1 || target > this.lastPage || target === this.currentPage) return
        this.currentPage = target
        this.fetchData()
    }
}">

    {{-- TOOLBAR SECTION --}}
    <div class="flex flex-col md:flex-row items-center justify-between gap-4">

        <div class="flex flex-wrap items-center gap-4 flex-1" x-data="{}">

            {{-- Search Bar --}}
            <div class="relative w-full md:w-sm">
                <span
                    class="absolute inset-y-0 left-0 flex items-center pl-3 text-on-surface/50 dark:text-on-surface-dark/50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </span>
                <input type="text" x-model="search" @input.debounce.300ms="resetAndFetch()" placeholder="Search..."
                    class="w-full border placeholder:text-on-surface/50 dark:text-on-surface-dark dark:placeholder:text-on-surface-dark/50 border-outline rounded-radius bg-surface px-3 py-2 pl-10 text-sm focus:outline-primary dark:border-outline-dark dark:bg-surface-dark" />
            </div>

            {{-- Filter Select Dropdowns --}}
            <template x-for="filter in filters" :key="filter.key">
                <div class="relative w-full md:w-[200px]" x-data="{
                    isOpen: false,
                    selectedValue: '',
                    selectedLabel: filter.label,
                
                    selectOption(value, label) {
                        this.selectedValue = value
                        this.selectedLabel = value === '' ? filter.label : label
                        this.isOpen = false
                        setFilter(filter.key, value)
                    },
                    clearSelection() {
                        this.selectOption('', filter.label)
                    }
                }" @click.outside="isOpen = false"
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
            </template>
        </div>

        {{-- Rows per page & counter --}}
        <div class="flex items-center gap-4 shrink-0" x-data="{}">
            <div class="flex items-center gap-2">
                <span class="text-xs text-on-surface/50 dark:text-on-surface-dark/50 whitespace-nowrap">Rows</span>

                <div class="relative w-[80px]" x-data="{
                    isOpen: false,
                    selectOption(value) {
                        perPage = value
                        this.isOpen = false
                        resetAndFetch()
                    }
                }" @click.outside="isOpen = false"
                    @keydown.escape.window="isOpen = false">

                    <button type="button" @click="isOpen = !isOpen"
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
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
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
    </div>

    {{-- ERROR BANNER --}}
    <p x-show="errorMessage" x-cloak x-text="errorMessage" role="alert"
        class="rounded-radius border border-danger/30 bg-danger/10 px-4 py-2 text-sm text-danger"></p>

    {{-- 2. SECTION TABLE : Gestion des lignes (rows), sélection multiple (bulk actions) et formatage des cellules --}}
    <div class="w-full min-w-0 rounded-radius border border-outline dark:border-outline-dark bg-surface dark:bg-surface-dark overflow-hidden"
        x-data="{
            selectedIds: [],
        
            safeUrl(url) {
                const text = String(url ?? '').trim()
                return /^(https?:\/\/|\/|#)/i.test(text) ? text : '#'
            },
        
            getCellValue(item, path) {
                if (!path) return ''
                return path.split('.').reduce((current, key) => (current != null ? current[key] : ''), item) ?? ''
            },
        
            cellType(column) {
                const validTypes = ['text', 'badge', 'progress', 'image', 'avatar_text', 'link', 'boolean', 'currency', 'date']
                return validTypes.includes(column.type) ? column.type : 'text'
            },
        
            getBadgeClasses(value, columnConfig) {
                const variant = columnConfig?.variants?.[value] || columnConfig?.defaultVariant || 'neutral'
                const variants = {
                    success: 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border-emerald-500/20',
                    warning: 'bg-amber-500/10 text-amber-600 dark:text-amber-400 border-amber-500/20',
                    danger: 'bg-rose-500/10 text-rose-600 dark:text-rose-400 border-rose-500/20',
                    primary: 'bg-primary/10 text-primary border-primary/20',
                    info: 'bg-sky-500/10 text-sky-600 dark:text-sky-400 border-sky-500/20',
                    neutral: 'bg-surface-alt text-on-surface/70 dark:bg-surface-dark-alt dark:text-on-surface-dark/70 border-outline dark:border-outline-dark'
                }
                return variants[variant] || variants.neutral
            },
        
            getProgressClasses(columnConfig) {
                const variant = columnConfig?.variant || 'primary'
                const variants = {
                    success: 'bg-emerald-500',
                    warning: 'bg-amber-500',
                    danger: 'bg-rose-500',
                    primary: 'bg-primary',
                    info: 'bg-sky-500'
                }
                return variants[variant] || variants.primary
            },
        
            formatCurrency(value, columnConfig) {
                const num = parseFloat(value)
                if (isNaN(num)) return { text: value, classes: 'text-on-surface dark:text-on-surface-dark font-semibold' }
        
                const currency = columnConfig?.currency || 'USD'
                const locale = columnConfig?.locale || 'en-US'
                const formatted = new Intl.NumberFormat(locale, { style: 'currency', currency }).format(num)
        
                let variant = columnConfig?.variant || 'neutral'
        
                if (columnConfig?.rules && Array.isArray(columnConfig.rules)) {
                    for (const rule of columnConfig.rules) {
                        let matches = true
                        if (rule.max !== undefined && num > rule.max) matches = false
                        if (rule.min !== undefined && num < rule.min) matches = false
                        if (rule.lt !== undefined && num >= rule.lt) matches = false
                        if (rule.gt !== undefined && num <= rule.gt) matches = false
        
                        if (matches) {
                            variant = rule.variant
                            break
                        }
                    }
                }
        
                const variants = {
                    success: 'text-emerald-600 dark:text-emerald-400 font-semibold',
                    warning: 'text-amber-600 dark:text-amber-400 font-semibold',
                    danger: 'text-rose-600 dark:text-rose-400 font-semibold',
                    primary: 'text-primary font-semibold',
                    info: 'text-sky-600 dark:text-sky-400 font-semibold',
                    neutral: 'text-on-surface dark:text-on-surface-dark font-semibold'
                }
        
                return {
                    text: formatted,
                    classes: variants[variant] || variants.neutral
                }
            },
        
            formatDate(value, format = 'medium') {
                if (!value) return '-'
                const date = new Date(value)
                if (isNaN(date.getTime())) return value
                return new Intl.DateTimeFormat('fr-FR', { dateStyle: format }).format(date)
            },
        
            isAllSelected() {
                return rows.length > 0 && this.selectedIds.length === rows.length
            },
        
            toggleSelectAll() {
                if (this.isAllSelected()) {
                    this.selectedIds = []
                } else {
                    this.selectedIds = rows.map(row => row.id).filter(Boolean)
                }
                this.dispatchSelectionChange()
            },
        
            toggleSelectRow(id) {
                if (this.selectedIds.includes(id)) {
                    this.selectedIds = this.selectedIds.filter(item => item !== id)
                } else {
                    this.selectedIds.push(id)
                }
                this.dispatchSelectionChange()
            },
        
            getSelectedItems() {
                return rows.filter(row => this.selectedIds.includes(row.id))
            },
        
            dispatchSelectionChange() {
                this.$dispatch('on-jkc-table-selection-change', {
                    selectedIds: this.selectedIds,
                    selectedItems: this.getSelectedItems()
                })
            },
        
            hasAction(name) { return withAction.includes(name) },
            triggerView(item) { if (this.hasAction('view')) this.$dispatch('on-jkc-table-view', { item }) },
            triggerEdit(item) { if (this.hasAction('edit')) this.$dispatch('on-jkc-table-edit', { item }) },
            triggerDelete(item) { if (this.hasAction('delete')) this.$dispatch('on-jkc-table-delete', { item }) }
        }">
        <div class="overflow-x-auto" style="-webkit-overflow-scrolling: touch;">
            <table class="w-full text-left border-collapse">

                <thead
                    class="text-xs text-on-surface/50 dark:text-on-surface-dark/50 uppercase border-b border-outline dark:border-outline-dark">
                    <tr>
                        <template x-if="selectable">
                            <th scope="col" class="px-4 py-3 w-10 text-center">
                                <div x-on:click="toggleSelectAll()"
                                    :class="isAllSelected() ?
                                        'border-primary bg-primary dark:border-primary-dark dark:bg-primary-dark' :
                                        'border-outline bg-surface-alt dark:border-outline-dark dark:bg-surface-dark-alt'"
                                    class="flex h-5 w-5 mx-auto shrink-0 items-center justify-center rounded border transition-all duration-150 cursor-pointer group hover:border-primary dark:hover:border-primary-dark">
                                    <svg :class="isAllSelected() ? 'opacity-100' : 'opacity-0'"
                                        class="h-3.5 w-3.5 text-white transition-opacity duration-150" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                </div>
                            </th>
                        </template>

                        {{-- DYNAMIC HEADERS --}}
                        <template x-for="column in headers" :key="column.key">
                            <th scope="col" class="px-6 py-3 font-semibold tracking-wider whitespace-nowrap">
                                <template x-if="column.sortable">
                                    <button type="button" x-on:click="sortBy(column.key)"
                                        class="flex items-center gap-1.5 hover:text-primary transition-colors focus:outline-none uppercase tracking-wider">
                                        <span x-text="column.label"></span>
                                        <span class="flex flex-col -space-y-1">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-2.5"
                                                :class="sortKey === column.key && sortDir === 'asc' ? 'text-primary' :
                                                    'opacity-30'"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M14.707 12.707a1 1 0 01-1.414 0L10 9.414l-3.293 3.293a1 1 0 01-1.414-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 010 1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-2.5"
                                                :class="sortKey === column.key && sortDir === 'desc' ? 'text-primary' :
                                                    'opacity-30'"
                                                viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </span>
                                    </button>
                                </template>
                                <template x-if="!column.sortable">
                                    <span x-text="column.label"></span>
                                </template>
                            </th>
                        </template>

                        {{-- ACTIONS COLUMN --}}
                        <template x-if="withAction.length > 0">
                            <th scope="col"
                                class="px-6 py-3 font-semibold tracking-wider text-right whitespace-nowrap">Actions
                            </th>
                        </template>
                    </tr>
                </thead>

                <tbody class="divide-y divide-outline dark:divide-outline-dark">
                    <template x-for="(item, index) in rows" :key="item.id ?? index">
                        <tr x-on:click="triggerView(item)" @keydown.enter="triggerView(item)"
                            :tabindex="hasAction('view') ? 0 : null"
                            :class="[
                                hasAction('view') ? 'cursor-pointer' : '',
                                selectedIds.includes(item.id) ? 'bg-primary/10 dark:bg-primary/20' :
                                'hover:bg-primary/5 dark:hover:bg-primary/10'
                            ]"
                            class="relative transition-colors group whitespace-nowrap">

                            {{-- ROW CHECKBOX --}}
                            <template x-if="selectable">
                                <td class="px-4 py-3 text-center relative z-10" @click.stop>
                                    <div x-on:click="toggleSelectRow(item.id)"
                                        :class="selectedIds.includes(item.id) ?
                                            'border-primary bg-primary dark:border-primary-dark dark:bg-primary-dark' :
                                            'border-outline bg-surface-alt dark:border-outline-dark dark:bg-surface-dark-alt'"
                                        class="flex h-5 w-5 mx-auto shrink-0 items-center justify-center rounded border transition-all duration-150 cursor-pointer group hover:border-primary dark:hover:border-primary-dark">
                                        <svg :class="selectedIds.includes(item.id) ? 'opacity-100' : 'opacity-0'"
                                            class="h-3.5 w-3.5 text-white transition-opacity duration-150"
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                            stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </div>
                                </td>
                            </template>

                            {{-- 10 DATA CELL TYPES --}}
                            <template x-for="column in headers" :key="column.key">
                                <td class="px-6 py-3 text-sm text-on-surface dark:text-on-surface-dark">

                                    <template x-if="cellType(column) === 'text'">
                                        <span
                                            class="text-on-surface dark:text-on-surface-dark group-hover:text-primary transition-colors"
                                            x-text="getCellValue(item, column.key)"></span>
                                    </template>

                                    <template x-if="cellType(column) === 'badge'">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border capitalize"
                                            :class="getBadgeClasses(getCellValue(item, column.key), column)"
                                            x-text="getCellValue(item, column.key) || 'N/A'"></span>
                                    </template>

                                    <template x-if="cellType(column) === 'progress'">
                                        <div class="flex items-center gap-3 w-36">
                                            <div
                                                class="w-full bg-surface-alt dark:bg-surface-dark-alt rounded-full h-2 overflow-hidden border border-outline dark:border-outline-dark">
                                                <div class="h-full transition-all duration-300 rounded-full"
                                                    :class="getProgressClasses(column)"
                                                    :style="`width: ${Math.min(100, Math.max(0, getCellValue(item, column.key) || 0))}%`">
                                                </div>
                                            </div>
                                            <span class="text-xs text-on-surface/70 dark:text-on-surface-dark/70"
                                                x-text="`${getCellValue(item, column.key) || 0}%`"></span>
                                        </div>
                                    </template>

                                    <template x-if="cellType(column) === 'image'">
                                        <img :src="getCellValue(item, column.key) || '/assets/img/placeholder.png'"
                                            alt=""
                                            class="size-10 rounded-radius object-cover border border-outline dark:border-outline-dark flex-shrink-0" />
                                    </template>

                                    <template x-if="cellType(column) === 'avatar_text'">
                                        <div class="flex items-center gap-3">
                                            <img :src="getCellValue(item, column.avatarKey || 'avatar') ||
                                                '/assets/img/avatar-placeholder.png'"
                                                alt=""
                                                class="size-8 rounded-full object-cover border border-outline dark:border-outline-dark shrink-0" />
                                            <div class="flex flex-col">
                                                <span class="font-medium text-on-surface dark:text-on-surface-dark"
                                                    x-text="getCellValue(item, column.key)"></span>
                                                <template x-if="column.subtitleKey">
                                                    <span
                                                        class="text-xs text-on-surface/50 dark:text-on-surface-dark/50"
                                                        x-text="getCellValue(item, column.subtitleKey)"></span>
                                                </template>
                                            </div>
                                        </div>
                                    </template>

                                    <template x-if="cellType(column) === 'link'">
                                        <a class="hover:underline text-primary font-medium inline-flex items-center gap-1 relative z-10"
                                            @click.stop :href="safeUrl(getCellValue(item, column.key))"
                                            target="_blank" rel="noopener">
                                            <span x-text="getCellValue(item, column.key) || 'N/A'"></span>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="size-3" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                            </svg>
                                        </a>
                                    </template>

                                    <template x-if="cellType(column) === 'boolean'">
                                        <span class="inline-flex items-center justify-center">
                                            <template x-if="Boolean(getCellValue(item, column.key))">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="size-5 text-emerald-500" viewBox="0 0 20 20"
                                                    fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </template>
                                            <template x-if="!Boolean(getCellValue(item, column.key))">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="size-5 text-rose-500/60" viewBox="0 0 20 20"
                                                    fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </template>
                                        </span>
                                    </template>

                                    {{-- TYPE 8: CURRENCY (with color variants) --}}
                                    <template x-if="cellType(column) === 'currency'">
                                        <span
                                            :class="formatCurrency(getCellValue(item, column.key), column).classes"
                                            x-text="formatCurrency(getCellValue(item, column.key), column).text"></span>
                                    </template>
                                    <template x-if="cellType(column) === 'date'">
                                        <span
                                            class="text-xs text-on-surface/80 dark:text-on-surface-dark/80 whitespace-nowrap"
                                            x-text="formatDate(getCellValue(item, column.key), column.dateFormat || 'medium')"></span>
                                    </template>
                                </td>
                            </template>

                            {{-- Standard Row Actions --}}
                            <template x-if="withAction.length > 0">
                                <td class="px-6 py-3 text-right relative z-10" @click.stop>
                                    <div class="flex justify-end gap-2">
                                        <template x-if="hasAction('view')">
                                            <button type="button" x-on:click="triggerView(item)"
                                                class="inline-flex items-center justify-center bg-transparent rounded-radius border border-outline dark:border-outline-dark p-1.5 text-on-surface/60 dark:text-on-surface-dark/60 transition hover:opacity-75"
                                                aria-label="View">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </button>
                                        </template>

                                        <template x-if="hasAction('edit')">
                                            <button type="button" x-on:click="triggerEdit(item)"
                                                class="inline-flex items-center justify-center bg-transparent rounded-radius border border-outline dark:border-outline-dark p-1.5 text-on-surface/60 dark:text-on-surface-dark/60 transition hover:opacity-75"
                                                aria-label="Edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>
                                        </template>

                                        <template x-if="hasAction('delete')">
                                            <button type="button" x-on:click="triggerDelete(item)"
                                                class="inline-flex items-center justify-center bg-transparent rounded-radius border border-outline dark:border-outline-dark p-1.5 text-on-surface/60 dark:text-on-surface-dark/60 transition hover:opacity-75"
                                                aria-label="Delete">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="size-4" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </template>
                                    </div>
                                </td>
                            </template>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>

        {{-- Empty state --}}
        <template x-if="rows.length === 0 && !loading">
            <div
                class="flex flex-col items-center justify-center p-12 text-on-surface/40 dark:text-on-surface-dark/40">
                <svg xmlns="http://www.w3.org/2000/svg" class="size-10 mb-2" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                </svg>
                <p class="italic text-sm">Aucun résultat</p>
            </div>
        </template>
    </div>

    {{-- 3. SECTION PAGINATION : Gestion locale de l'input de saut de page et des listes numérotées --}}
    <div x-show="lastPage > 1" class="flex w-full flex-col items-center gap-3" x-data="{
        pageInput: 1,
    
        init() {
            this.pageInput = currentPage
            this.$watch('currentPage', (val) => { this.pageInput = val })
        },
    
        pageList() {
            if (lastPage <= 1) return [1]
            const start = Math.max(2, currentPage - 1)
            const end = Math.min(lastPage - 1, currentPage + 1)
            const middle = Array.from({ length: Math.max(0, end - start + 1) }, (_, index) => start + index)
            return [1, ...(start > 2 ? ['...'] : []), ...middle, ...(end < lastPage - 1 ? ['...'] : []), lastPage]
        },
    
        jumpToPage(target) {
            const pageNum = parseInt(target)
    
            if (isNaN(pageNum) || pageNum < 1 || pageNum > lastPage) {
                this.pageInput = currentPage
                return
            }
            goToPage(pageNum)
        }
    }">

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
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                            class="size-6">
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
                    <button type="button" x-on:click="goToPage(currentPage + 1)"
                        :disabled="currentPage === lastPage"
                        class="flex items-center rounded-radius p-1 text-on-surface hover:text-primary dark:text-on-surface-dark dark:hover:text-primary-dark disabled:opacity-30 disabled:cursor-not-allowed">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                            class="size-6">
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

</div>
