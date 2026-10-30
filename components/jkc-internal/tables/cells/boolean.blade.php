<span class="inline-flex items-center justify-center"
    x-data="{ val: Boolean({{ $jkcColumn->cell()->valueExpr() }})}">
    <template x-if="val">
        @include('jkc-internal.tables.icon', [
            'jkcIcon' => 'check-circle-mini',
            'jkcIconClass' => 'size-5 text-emerald-500',
            'jkcIconStroke' => '',
            'jkcIconAttrs' => '',
        ])
    </template>
    <template x-if="!val">
        @include('jkc-internal.tables.icon', [
            'jkcIcon' => 'x-circle-mini',
            'jkcIconClass' => 'size-5 text-rose-500/60',
            'jkcIconStroke' => '',
            'jkcIconAttrs' => '',
        ])
    </template>
</span>
