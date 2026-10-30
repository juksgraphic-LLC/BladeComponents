{{-- Cell "date" of jkc.tables.dynamic-table. Alpine scope: `item` (the row) and `column` (this column definition). --}}
<span class="text-xs text-on-surface/80 dark:text-on-surface-dark/80 whitespace-nowrap"
    x-text="formatDate(getCellValue(item, column.key), column.dateFormat || 'medium')"></span>
