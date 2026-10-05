@php
    /**
     * Card — Composant de base
     *
     * Slots : $body, $footer, $headerAction
     * Props : $title, $subtitle, $noPadding, $class
     */
    $title     = $title ?? null;
    $subtitle  = $subtitle ?? null;
    $noPadding = $noPadding ?? false;
    $class     = $class ?? '';
    $body      = $body ?? ($slot ?? '');
@endphp

<div
    class="bg-surface dark:bg-surface-dark rounded-radius border border-outline dark:border-outline-dark overflow-hidden {{ $class }}">

    @if ($title || isset($headerAction))
        <div class="flex items-center justify-between gap-4 px-6 py-4 border-b border-outline dark:border-outline-dark">
            <div class="min-w-0">
                @if ($title)
                    <h5 class="font-title text-base font-semibold text-on-surface dark:text-on-surface-dark">
                        {{ $title }}</h5>
                @endif
                @if ($subtitle)
                    <p class="font-paragraph text-sm text-on-surface/60 dark:text-on-surface-dark/60 mt-0.5">
                        {{ $subtitle }}</p>
                @endif
            </div>
            @if (isset($headerAction))
                <div class="shrink-0">{!! $headerAction !!}</div>
            @endif
        </div>
    @endif

    @if (trim((string) $body) !== '')
        <div class="{{ $noPadding ? '' : 'px-6 py-4' }}">{!! $body !!}</div>
    @endif

    @if (isset($footer) && trim((string) $footer) !== '')
        <div class="px-6 py-4 border-t border-outline dark:border-outline-dark">{!! $footer !!}</div>
    @endif
</div>
