{{-- Internal partial of jkc.tables.dynamic-table. Not a public component: include the facade instead. --}}
{{-- Rows per page & counter --}}
<div class="flex items-center gap-4 shrink-0">
    <div class="flex items-center gap-2">
        <span class="text-xs text-on-surface/50 dark:text-on-surface-dark/50 whitespace-nowrap">Rows</span>

        @include('jkc-internal.tables.select', [
            'jkcSelectData' => 'jkcTablePerPage',
            'jkcSelectStyle' => 'compact',
        ])
    </div>

    <div
        class="flex items-center gap-2 text-xs font-medium text-on-surface/50 dark:text-on-surface-dark/50 whitespace-nowrap">
        <span x-show="loading" x-cloak
            class="inline-block w-3.5 h-3.5 border-2 border-primary border-t-transparent rounded-full animate-spin"></span>
        <span><span x-text="total" class="text-on-surface dark:text-on-surface-dark font-semibold"></span>results</span>
    </div>
</div>