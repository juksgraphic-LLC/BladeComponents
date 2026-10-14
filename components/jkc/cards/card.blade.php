@php
/**
 * Card component — Juksgraphic / Penguin UI
 *
 * Reusable container card built with Tailwind CSS.
 * Works with Laravel Blade and BladeOne.
 *
 * Slots:
 * - $body       → Main body content (or default $slot)
 * - $footer     → Footer area content
 * - $headerAction → Actions rendered on the right side of the header
 *
 * @param string|null $title     Card title (escaped). Default: null.
 * @param string|null $subtitle  Card subtitle (escaped). Default: null.
 * @param bool        $noPadding Remove padding from the body. Default: false.
 * @param string      $class     Extra CSS classes. Default: ''.
 */

/** @var string|null $title */
$title = $title ?? null;

/** @var string|null $subtitle */
$subtitle = $subtitle ?? null;

/** @var bool $noPadding */
$noPadding = (bool) ($noPadding ?? false);

/** @var string $class */
$class = (string) ($class ?? '');

/** @var string $body */
$body = $body ?? ($slot ?? '');
@endphp

{{-- ═══════════════════════════ Template ═══════════════════════════ --}}
<div class="bg-surface dark:bg-surface-dark rounded-radius border border-outline dark:border-outline-dark overflow-hidden {{ $class }}">

    {{-- Header --}}
    @if ($title || isset($headerAction))
        <div class="flex items-center justify-between gap-4 px-6 py-4 border-b border-outline dark:border-outline-dark">
            <div class="min-w-0">
                @if ($title)
                    <h5 class="font-title text-base font-semibold text-on-surface dark:text-on-surface-dark">
                        {{ $title }}
                    </h5>
                @endif
                @if ($subtitle)
                    <p class="font-paragraph text-sm text-on-surface/60 dark:text-on-surface-dark/60 mt-0.5">
                        {{ $subtitle }}
                    </p>
                @endif
            </div>
            @if (isset($headerAction))
                <div class="shrink-0">{!! $headerAction !!}</div>
            @endif
        </div>
    @endif

    {{-- Body --}}
    @if (trim((string) $body) !== '')
        <div class="{{ $noPadding ? '' : 'px-6 py-4' }}">{!! $body !!}</div>
    @endif

    {{-- Footer --}}
    @if (isset($footer) && trim((string) $footer) !== '')
        <div class="px-6 py-4 border-t border-outline dark:border-outline-dark">{!! $footer !!}</div>
    @endif
</div>