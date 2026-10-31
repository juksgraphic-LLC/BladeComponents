{{-- Cell "text" of jkc.tables.dynamic-table. Alpine scope: `item` (the row). --}}
<span 
class="text-on-surface dark:text-on-surface-dark group-hover:text-primary transition-colors"
x-text="{{ $jkcColumn->cell()->valueExpr() }}">
</span>