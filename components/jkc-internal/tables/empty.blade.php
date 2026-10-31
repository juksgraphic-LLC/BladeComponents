{{-- Internal partial of jkc.tables.dynamic-table. Not a public component: include the facade instead. --}}
<template x-if="rows.length === 0 && !loading">
    <div class="flex flex-col items-center justify-center p-12 text-on-surface/40 dark:text-on-surface-dark/40">
        @include('jkc-internal.tables.icon', ['jkcIcon' => 'inbox', 'jkcIconClass' => 'size-10 mb-2', 'jkcIconStroke' => '', 'jkcIconAttrs' => ''])
        <p class="italic text-sm">No Results</p>
    </div>
</template>