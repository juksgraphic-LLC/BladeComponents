{{-- Cell "badge" of jkc.tables.dynamic-table. Alpine scope: `item` (the row). --}}
<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border capitalize"
    x-data="{ badgeMap: {{ $jkcColumn->cell()->badgeMapJson() }}, val: {{ $jkcColumn->cell()->valueExpr() }} }"
    :class="badgeMap.map[val] || badgeMap.default"
   x-text="val || labels.na"></span>