@php
    /**
     * Offcanvas component — Juksgraphic / Penguin UI
     *
     * Reusable offcanvas panel built with Tailwind CSS and Alpine.js.
     * Works with Laravel Blade and BladeOne.
     *
     * External Triggers (Commands):
     * - Open:  window.dispatchEvent(new CustomEvent('jkc-open-offcanvas', { detail: 'offcanvas-id' }))
     * - Close: window.dispatchEvent(new CustomEvent('jkc-close-offcanvas', { detail: 'offcanvas-id' }))
     *
     * Emitted Lifecycle Events (Notifications):
     * - On Open:  jkc-on-open-offcanvas  (dispatched with detail: { id: 'offcanvas-id' })
     * - On Close: jkc-on-close-offcanvas (dispatched with detail: { id: 'offcanvas-id' })
     *
     * @param string      $id         Unique ID of the offcanvas. Default: generated hex.
     * @param string      $placement  Placement side: start|end|top|bottom. Default: 'start'.
     * @param string|null $size       Panel size: sm|lg|xl|fullscreen|null. Default: null.
     * @param bool        $backdrop   Show backdrop overlay. Default: true.
     * @param bool        $scroll     Allow body scrolling while open. Default: false.
     * @param string      $title      Panel title. Default: 'Offcanvas'.
     * @param bool        $showClose  Show close button in header. Default: true.
     */

    /** @var string $id */
    $id = $id ?? 'offcanvas-' . bin2hex(random_bytes(4));

    /** @var string $placement */
    $placement = (string) ($placement ?? 'start');

    /** @var string|null $size */
    $size = $size ?? null;

    /** @var bool $backdrop */
    $backdrop = (bool) ($backdrop ?? true);

    /** @var bool $scroll */
    $scroll = (bool) ($scroll ?? false);

    /** @var string $title */
    $title = (string) ($title ?? 'Offcanvas');

    /** @var bool $showClose */
    $showClose = (bool) ($showClose ?? true);

    /** @var string|null $safeId */
    $safeId = preg_replace('/[^A-Za-z0-9_-]/', '', (string) $id);

    /** @var array<string, string> $sizeClasses */
    $sizeClasses = [
        'sm' => 'w-80',
        'lg' => 'w-[32rem]',
        'xl' => 'w-[40rem]',
        'fullscreen' => 'w-full',
        null => 'w-96',
    ];

    /** @var array<string, string> $placementClasses */
    $placementClasses = [
        'start'  => 'top-0 left-0 h-full ' . ($sizeClasses[$size] ?? $sizeClasses[null]),
        'end'    => 'top-0 right-0 h-full '  . ($sizeClasses[$size] ?? $sizeClasses[null]),
        'top'    => 'top-0 left-0 right-0 w-full h-auto max-h-[50vh]',
        'bottom' => 'bottom-0 left-0 right-0 w-full h-auto max-h-[50vh]',
    ];

    /** @var string $animEnter */
    $animEnter = match ($placement) {
        'start' => '-translate-x-full',
        'end' => 'translate-x-full',
        'top' => '-translate-y-full',
        'bottom' => 'translate-y-full',
        default => '-translate-x-full',
    };

    /** @var string $animEnd */
    $animEnd = 'translate-x-0 translate-y-0';
@endphp

{{-- ═══════════════════════════ Template ═══════════════════════════ --}}
<div x-data="{
    open: false,
    setOpen(value) {
        if (this.open === value) return;
        this.open = value;
        if (value) {
            this.$dispatch('jkc-on-open-offcanvas', { id: '{{ $safeId }}' });
        } else {
            this.$dispatch('jkc-on-close-offcanvas', { id: '{{ $safeId }}' });
        }
    }
}" x-show="open" x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    @jkc-open-offcanvas.window="if ($event.detail === '{{ $safeId }}') setOpen(true)"
    @jkc-close-offcanvas.window="if ($event.detail === '{{ $safeId }}') setOpen(false)"
    class="fixed inset-0 z-50 {{ $scroll ? '' : 'overflow-hidden' }}" style="display: none;"
    :style="open ? 'display: block;' : 'display: none;'">

    {{-- Backdrop --}}
    @if ($backdrop)
        <div x-show="open" x-transition:enter="transition-opacity ease-out duration-300"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition-opacity ease-in duration-200" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" x-on:click="setOpen(false)"
            class="fixed inset-0 bg-black/50 backdrop-blur-sm">
        </div>
    @endif

    {{-- Panel --}}
    <div x-show="open" x-transition:enter="transform transition ease-out duration-300"
        x-transition:enter-start="{{ $animEnter }}" x-transition:enter-end="{{ $animEnd }}"
        x-transition:leave="transform transition ease-in duration-200" x-transition:leave-start="{{ $animEnd }}"
        x-transition:leave-end="{{ $animEnter }}"
        class="fixed {{ $placementClasses[$placement] ?? $placementClasses['start'] }} bg-surface dark:bg-surface-dark shadow-2xl border border-outline dark:border-outline-dark overflow-y-auto"
        @click.stop>

        {{-- Header --}}
        <div class="flex items-center justify-between px-5 py-4 border-b border-outline dark:border-outline-dark">
            <h5 class="font-title text-lg font-semibold text-on-surface dark:text-on-surface-dark">
                {{ $title }}
            </h5>
            @if ($showClose)
                <button type="button" x-on:click="setOpen(false)" aria-label="Close offcanvas"
                    class="p-1.5 rounded-lg text-on-surface/50 dark:text-on-surface-dark/50 hover:text-on-surface dark:hover:text-on-surface-dark hover:bg-surface-alt dark:hover:bg-surface-dark-alt transition-colors focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-current">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            @endif
        </div>

        {{-- Body --}}
        @if (isset($body) && trim($body) !== '')
            <div class="px-5 py-4">
                {!! $body !!}
            </div>
        @endif

        {{-- Footer --}}
        @if (isset($footer) && trim($footer) !== '')
            <div class="px-5 py-4 border-t border-outline dark:border-outline-dark">
                {!! $footer !!}
            </div>
        @endif

    </div>
</div>
