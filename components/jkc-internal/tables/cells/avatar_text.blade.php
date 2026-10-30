{{-- Cell "avatar_text" of jkc.tables.dynamic-table. Alpine scope: `item` (the row) and `column` (this column definition). --}}
<div class="flex items-center gap-3">
    <img :src="getCellValue(item, column.avatarKey || 'avatar') ||
        '/assets/img/avatar-placeholder.png'"
        alt="" class="size-8 rounded-full object-cover border border-outline dark:border-outline-dark shrink-0" />
    <div class="flex flex-col">
        <span class="font-medium text-on-surface dark:text-on-surface-dark"
            x-text="getCellValue(item, column.key)"></span>
        <template x-if="column.subtitleKey">
            <span class="text-xs text-on-surface/50 dark:text-on-surface-dark/50"
                x-text="getCellValue(item, column.subtitleKey)"></span>
        </template>
    </div>
</div>
