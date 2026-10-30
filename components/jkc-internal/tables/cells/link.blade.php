<a class="hover:underline text-primary font-medium inline-flex items-center gap-1 relative z-10" @click.stop
    :href="{{ $jkcColumn->cell()->valueExpr() }}" target="_blank" rel="noopener">
    <span x-text="{{ $jkcColumn->cell()->valueExpr() }} || labels.na"></span>
    @include('jkc-internal.tables.icon', [
        'jkcIcon' => 'external-link',
        'jkcIconClass' => 'size-3',
        'jkcIconStroke' => '',
        'jkcIconAttrs' => '',
    ])
</a>
