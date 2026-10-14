@php
/**
 * Modal component — Juksgraphic / Penguin UI
 *
 * Reusable modal built with Tailwind CSS and Alpine.js.
 * Works with Laravel Blade and BladeOne.
 *
 * External Triggers (Commands):
 * - Open:  window.dispatchEvent(new CustomEvent('jkc-open-modal', { detail: 'modal-id' }))
 * - Close: window.dispatchEvent(new CustomEvent('jkc-close-modal', { detail: 'modal-id' }))
 *
 * Emitted Lifecycle Events (Notifications):
 * - On Open:  jkc-on-open-modal  (dispatched with detail: { id: 'modal-id' })
 * - On Close: jkc-on-close-modal (dispatched with detail: { id: 'modal-id' })
 *
 * @param string      $id         Unique ID of the modal. Default: generated hex.
 * @param string|null $size       Modal size: sm|lg|xl|fullscreen|null. Default: null (md).
 * @param string|null $position   Modal position: top|top-right|bottom|bottom-right|null. Default: null (centered).
 * @param bool        $centered   Vertically centered (ignored if $position is set). Default: false.
 * @param bool        $static     Prevent closing on backdrop click. Default: false.
 * @param bool        $scrollable Scrollable body with fixed header/footer. Default: false.
 * @param string      $animation  Animation style: fade|fade-right|fade-left|fade-up|flip|zoom. Default: 'fade'.
 * @param bool        $showClose  Show close button in header. Default: true.
 * @param string|null $title      Simple title (ignored if header slot is provided). Default: 'Modal Title'.
 */

/** @var string $id */
$id = $id ?? 'modal-' . bin2hex(random_bytes(4));

/** @var string|null $size */
$size = $size ?? null;

/** @var bool $centered */
$centered = (bool) ($centered ?? false);

/** @var bool $static */
$static = (bool) ($static ?? false);

/** @var bool $scrollable */
$scrollable = (bool) ($scrollable ?? false);

/** @var string $animation */
$animation = (string) ($animation ?? 'fade');

/** @var string|null $position */
$position = $position ?? null;

/** @var string $title */
$title = (string) ($title ?? 'Modal Title');

/** @var bool $showClose */
$showClose = (bool) ($showClose ?? true);

/** @var string|null $safeId */
$safeId = preg_replace('/[^A-Za-z0-9_-]/', '', (string) $id);

/** @var array<string, string> $_sizeMap */
$_sizeMap = [
    'sm'         => 'max-w-sm',
    'lg'         => 'max-w-2xl',
    'xl'         => 'max-w-4xl',
    'fullscreen' => 'max-w-full m-4 w-full h-[calc(100vh-2rem)]',
    null         => 'max-w-lg',
];

/** @var array<string, string> $_positionMap */
$_positionMap = [
    'top'          => 'items-start pt-10',
    'top-right'    => 'items-start justify-end pt-10 pr-4 sm:pr-10',
    'bottom'       => 'items-end pb-10',
    'bottom-right' => 'items-end justify-end pb-10 pr-4 sm:pr-10',
    null           => 'items-center justify-center',
];

/** @var string $_animEnter */
$_animEnter = match ($animation) {
    'fade-right' => 'opacity-0 translate-x-12',
    'fade-left'  => 'opacity-0 -translate-x-12',
    'fade-up'    => 'opacity-0 translate-y-12',
    'flip'       => 'opacity-0 scale-95',
    'zoom'       => 'opacity-0 scale-75',
    default      => 'opacity-0 scale-95',
};

/** @var string $_animEnd */
$_animEnd = match ($animation) {
    'flip', 'zoom' => 'opacity-100 scale-100',
    default        => 'opacity-100 translate-x-0 translate-y-0 scale-100',
};

/** @var string $_dialogClasses */
$_dialogClasses = trim(
    'relative w-full '
    . ($_sizeMap[$size] ?? $_sizeMap[null])
    . ' bg-surface dark:bg-surface-dark rounded-radius shadow-2xl border border-outline dark:border-outline-dark overflow-hidden'
    . ($scrollable ? ' flex flex-col max-h-[calc(100vh-2rem)]' : '')
);

/** @var string $_wrapperClass */
$_wrapperClass = 'fixed inset-0 z-50 flex bg-black/50 backdrop-blur-sm transition-opacity duration-300';
if ($position) {
    $_wrapperClass .= ' ' . ($_positionMap[$position] ?? $_positionMap[null]);
} elseif ($centered) {
    $_wrapperClass .= ' items-center justify-center';
} else {
    $_wrapperClass .= ' ' . $_positionMap[null];
}

/** @var bool $_hasHeader */
$_hasHeader = isset($header);

/** @var bool $_hasBody */
$_hasBody = isset($body);

/** @var bool $_hasFooter */
$_hasFooter = isset($footer);
@endphp

{{-- ═══════════════════════════ Template ═══════════════════════════ --}}
<div x-data="{ 
        open: false,
        setOpen(value) {
            if (this.open === value) return;
            this.open = value;
            if (value) {
                this.$dispatch('jkc-on-open-modal', { id: '{{ $safeId }}' });
            } else {
                this.$dispatch('jkc-on-close-modal', { id: '{{ $safeId }}' });
            }
        }
     }"
     x-show="open"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @jkc-open-modal.window="if ($event.detail === '{{ $safeId }}') setOpen(true)"
     @jkc-close-modal.window="if ($event.detail === '{{ $safeId }}') setOpen(false)"
     class="{{ $_wrapperClass }}"
     style="display: none;"
     :style="open ? 'display: flex;' : 'display: none;'"
     @if (!$static) @click.self="setOpen(false)" @endif
     tabindex="-1"
     aria-modal="true"
     role="dialog">

    {{-- Dialog Box --}}
    <div x-show="open"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="{{ $_animEnter }}"
         x-transition:enter-end="{{ $_animEnd }}"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="{{ $_animEnd }}"
         x-transition:leave-end="{{ $_animEnter }}"
         class="{{ $_dialogClasses }}"
         @click.stop>

        {{-- Header --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-outline dark:border-outline-dark {{ $scrollable ? 'flex-shrink-0' : '' }}">
            @if ($_hasHeader)
                {!! $header !!}
            @else
                <h5 class="font-title text-lg font-semibold text-on-surface dark:text-on-surface-dark">
                    {{ $title }}
                </h5>
            @endif

            @if ($showClose)
                <button type="button"
                        @click="setOpen(false)"
                        aria-label="Close modal"
                        class="ml-auto p-1 rounded-lg text-on-surface/50 dark:text-on-surface-dark/50 hover:text-on-surface dark:hover:text-on-surface-dark hover:bg-surface-alt dark:hover:bg-surface-dark-alt transition-colors focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-current">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            @endif
        </div>

        {{-- Body --}}
        <div class="px-6 py-4 {{ $scrollable ? 'overflow-y-auto flex-1' : '' }}">
            @if ($_hasBody)
                {!! $body !!}
            @endif
        </div>

        {{-- Footer --}}
        <div class="flex items-center justify-end gap-2 px-6 py-4 border-t border-outline dark:border-outline-dark {{ $scrollable ? 'flex-shrink-0' : '' }}">
            @if ($_hasFooter)
                {!! $footer !!}
            @else
                <button type="button"
                        x-on:click="setOpen(false)"
                        class="px-4 py-2 text-sm font-medium text-on-surface dark:text-on-surface-dark bg-surface-alt dark:bg-surface-dark-alt hover:bg-surface dark:hover:bg-surface-dark border border-outline dark:border-outline-dark rounded-radius transition-colors">
                    Close
                </button>
                <button type="button"
                        x-on:click="setOpen(false)"
                        class="px-4 py-2 text-sm font-medium text-on-primary bg-primary hover:bg-primary/90 rounded-radius transition-colors">
                    Save changes
                </button>
            @endif
        </div>

    </div>
</div>