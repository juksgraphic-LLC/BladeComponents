{{-- Cell "boolean" of jkc.tables.dynamic-table. Alpine scope: `item` (the row) and `column` (this column definition). --}}
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