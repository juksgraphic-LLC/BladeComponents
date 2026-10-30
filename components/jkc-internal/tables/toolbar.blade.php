{{-- Internal partial of jkc.tables.dynamic-table. Not a public component: include the facade instead. --}}
<div class="flex flex-col md:flex-row items-center justify-between gap-4">

    <div class="flex flex-wrap items-center gap-4 flex-1">

        {{-- Search Bar --}}
        <div class="relative w-full md:w-sm">
            <span
                class="absolute inset-y-0 left-0 flex items-center pl-3 text-on-surface/50 dark:text-on-surface-dark/50">
                @include('jkc-internal.tables.icon', ['jkcIcon' => 'search', 'jkcIconClass' => 'size-4', 'jkcIconStroke' => '', 'jkcIconAttrs' => ''])
            </span>
            <input type="text" x-model="search" @input.debounce.300ms="resetAndFetch()" placeholder="Search..."
                class="w-full border placeholder:text-on-surface/50 dark:text-on-surface-dark dark:placeholder:text-on-surface-dark/50 border-outline rounded-radius bg-surface px-3 py-2 pl-10 text-sm focus:outline-primary dark:border-outline-dark dark:bg-surface-dark" />
        </div>

        {{-- Filter Select Dropdowns --}}
        <template x-for="filter in filters" :key="filter.key">
            @include('jkc-internal.tables.filter')
        </template>
    </div>

    @include('jkc-internal.tables.per-page')
</div>