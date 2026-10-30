{{-- Cell "badge" of jkc.tables.dynamic-table. Alpine scope: `item` (the row) and `column` (this column definition). --}}
<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border capitalize"
    :class="getBadgeClasses(getCellValue(item, column.key), column)"
    x-text="getCellValue(item, column.key) || 'N/A'"></span>
