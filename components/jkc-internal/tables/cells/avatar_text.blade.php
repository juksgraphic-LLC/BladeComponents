<div class="flex items-center gap-3">
    <img x-bind:src="{{ $jkcColumn->cell()->avatarExpr() }} ||
        labels.avatarPlaceholder"
        alt="" class="size-8 rounded-full object-cover border border-outline dark:border-outline-dark shrink-0" />
    <div class="flex flex-col">
        <span class="font-medium text-on-surface dark:text-on-surface-dark"
            x-text="{{ $jkcColumn->cell()->valueExpr() }}"></span>
        @if ($jkcColumn->cell()->hasSubtitle())
            <span class="text-xs text-on-surface/50 dark:text-on-surface-dark/50"
                x-text="{{ $jkcColumn->cell()->subtitleExpr() }}"></span>
        @endif
    </div>
</div>