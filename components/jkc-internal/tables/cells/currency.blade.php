{{-- Cell "currency" of jkc.tables.dynamic-table. Alpine scope: `item` (the row) and `column` (this column definition). --}}
<span :class="formatCurrency(getCellValue(item, column.key), column).classes"
    x-text="formatCurrency(getCellValue(item, column.key), column).text"></span>