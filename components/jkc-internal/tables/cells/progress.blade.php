{{-- Cell "progress" of jkc.tables.dynamic-table. Alpine scope: `item` (the row) and `column` (this column definition). --}}
<div class="flex items-center gap-3 w-36">
    <div
        class="w-full bg-surface-alt dark:bg-surface-dark-alt rounded-full h-2 overflow-hidden border border-outline dark:border-outline-dark">
        <div class="h-full transition-all duration-300 rounded-full" :class="getProgressClasses(column)"
            :style="`width: ${Math.min(100, Math.max(0, getCellValue(item, column.key) || 0))}%`">
        </div>
    </div>
    <span class="text-xs text-on-surface/70 dark:text-on-surface-dark/70"
        x-text="`${getCellValue(item, column.key) || 0}%`"></span>
</div>
