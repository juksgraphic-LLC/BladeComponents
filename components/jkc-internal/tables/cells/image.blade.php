{{-- Cell "image" of jkc.tables.dynamic-table. Alpine scope: `item` (the row) and `column` (this column definition). --}}
<img :src="getCellValue(item, column.key) || '/assets/img/placeholder.png'" alt=""
    class="size-10 rounded-radius object-cover border border-outline dark:border-outline-dark flex-shrink-0" />
