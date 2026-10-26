@php
    /**
     * Toast component — Juksgraphic / Penguin UI
     *
     * Reusable toast notification built with Tailwind CSS and Alpine.js.
     * Works with Laravel Blade and BladeOne.
     *
     * External Triggers (Commands):
     * - Open:  window.dispatchEvent(new CustomEvent('jkc-open-toast', { detail: 'toast-id' }))
     * - Close: window.dispatchEvent(new CustomEvent('jkc-close-toast', { detail: 'toast-id' }))
     *
     * Emitted Lifecycle Events (Notifications):
     * - On Close: jkc-on-toast-close (dispatched with detail: { id: 'toast-id' })
     *
     * @param string      $variant      Variant theme: default|info|success|warning|danger|message. Default: 'info'.
     * @param string      $type         Layout type: default|translucent|bordered|message. Default: 'default'.
     * @param string      $title        Toast title. Default: 'Notification'.
     * @param string      $message      Toast message (raw HTML supported). Default: 'Hello, world!'.
     * @param string      $time         Timestamp text. Default: 'Just now'.
     * @param string|null $senderName   Sender name for message toasts. Default: null.
     * @param string|null $senderAvatar Sender avatar URL. Default: null.
     * @param bool        $dismissible  Show close button. Default: true.
     * @param bool        $translucent  Translucent glassmorphism background. Default: false.
     * @param int|null    $duration     Auto-dismiss duration in milliseconds. Default: null.
     * @param string|null $id           Unique ID. Default: generated hex.
     * @param bool        $live         Hidden until a "jkc-open-toast" event is received. Default: false.
     * @param bool        $stacked      Add spacing for container stacking. Default: false.
     */

    /** @var string $variant */
    $variant = (string) ($variant ?? 'info');

    /** @var string $type */
    $type  = (string) ($type ?? 'default');

    /** @var string $title */
    $title = (string) ($title ?? 'Notification');

    /** @var string $message */
    $message = (string) ($message ?? 'Hello, world! This is a toast message.');

    /** @var string $time */
    $time    = (string) ($time ?? 'Just now');

    /** @var string|null $senderName */
    $senderName = $senderName ?? null;

    /** @var string|null $senderAvatar */
    $senderAvatar = $senderAvatar ?? null;

    /** @var bool $dismissible */
    $dismissible  = (bool) ($dismissible ?? true);

    /** @var bool $translucent */
    $translucent  = (bool) ($translucent ?? false);

    /** @var int|null $duration */
    $duration = (int) ($duration ?? null);

    /** @var string $customId */
    $customId = $id ?? 'toast-' . bin2hex(random_bytes(4));

    /** @var string|null $safeId */
    $safeId = preg_replace('/[^A-Za-z0-9_-]/', '', (string) $customId);

    /** @var bool $live */
    $live = (bool) ($live ?? false);

    /** @var bool $stacked */
    $stacked = (bool) ($stacked ?? false);

    /** @var array<string, array<string, string>> $themes */
    $themes = [
        'info' => [
            'border' => 'border-info',
            'text' => 'text-info',
            'bgSoft' => 'bg-info/10',
            'bgBadge' => 'bg-info/15',
            'iconSvg' =>
                '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5" aria-hidden="true"><path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-7-4a1 1 0 1 1-2 0 1 1 0 0 1 2 0ZM9 9a.75.75 0 0 0 0 1.5h.253a.25.25 0 0 1 .244.304l-.459 2.066A1.75 1.75 0 0 0 10.747 15H11a.75.75 0 0 0 0-1.5h-.253a.25.25 0 0 1-.244-.304l.459-2.066A1.75 1.75 0 0 0 9.253 9H9Z" clip-rule="evenodd" /></svg>',
        ],
        'success' => [
            'border' => 'border-success',
            'text' => 'text-success',
            'bgSoft' => 'bg-success/10',
            'bgBadge' => 'bg-success/15',
            'iconSvg' =>
                '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd" /></svg>',
        ],
        'warning' => [
            'border' => 'border-warning',
            'text' => 'text-warning',
            'bgSoft' => 'bg-warning/10',
            'bgBadge' => 'bg-warning/15',
            'iconSvg' =>
                '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5" aria-hidden="true"><path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495ZM10 5a.75.75 0 0 1 .75.75v3.5a.75.75 0 0 1-1.5 0v-3.5A.75.75 0 0 1 10 5Zm0 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd" /></svg>',
        ],
        'danger' => [
            'border' => 'border-danger',
            'text' => 'text-danger',
            'bgSoft' => 'bg-danger/10',
            'bgBadge' => 'bg-danger/15',
            'iconSvg' =>
                '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16ZM8.28 7.22a.75.75 0 0 0-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 1 0 1.06 1.06L10 11.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L11.06 10l1.72-1.72a.75.75 0 0 0-1.06-1.06L10 8.94 8.28 7.22Z" clip-rule="evenodd" /></svg>',
        ],
        'message' => [
            'border' => 'border-outline',
            'text' => 'text-on-surface',
            'bgSoft' => 'bg-surface-alt',
            'bgBadge' => 'bg-primary/15',
            'iconSvg' => '',
        ],
        'default' => [
            'border' => 'border-outline',
            'text' => 'text-on-surface',
            'bgSoft' => 'bg-surface-alt',
            'bgBadge' => 'bg-primary/15',
            'iconSvg' =>
                '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5" aria-hidden="true"><path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-7-4a1 1 0 1 1-2 0 1 1 0 0 1 2 0ZM9 9a.75.75 0 0 0 0 1.5h.253a.25.25 0 0 1 .244.304l-.459 2.066A1.75 1.75 0 0 0 10.747 15H11a.75.75 0 0 0 0-1.5h-.253a.25.25 0 0 1-.244-.304l.459-2.066A1.75 1.75 0 0 0 9.253 9H9Z" clip-rule="evenodd" /></svg>',
        ],
    ];

    /** @var array<string, string> $theme */
    $theme = $themes[$variant] ?? $themes['info'];

    /** @var string $translucentClass */
    $translucentClass = $translucent ? 'bg-white/80 dark:bg-surface-dark/80 backdrop-blur-sm' : '';

    /** @var string $rootClasses */
    /** @var string $innerClasses */
    switch ($type) {
        case 'bordered':
            $rootClasses = "pointer-events-auto relative rounded-radius border {$theme['border']} bg-surface text-on-surface dark:bg-surface-dark dark:text-on-surface-dark";
            $innerClasses = "flex w-full items-center gap-2.5 {$theme['bgSoft']} rounded-radius p-4 transition-all duration-300";
            break;
        case 'translucent':
            $rootClasses =
                'pointer-events-auto relative rounded-radius border border-outline bg-white/80 text-on-surface backdrop-blur-sm dark:border-outline-dark dark:bg-surface-dark/80 dark:text-on-surface-dark';
            $innerClasses = 'flex w-full items-center gap-2.5 rounded-radius p-4 transition-all duration-300';
            break;
        case 'message':
            $rootClasses =
                'pointer-events-auto relative rounded-radius border border-outline bg-surface text-on-surface dark:border-outline-dark dark:bg-surface-dark dark:text-on-surface-dark';
            $innerClasses = 'flex w-full items-start gap-3 rounded-radius p-4 transition-all duration-300';
            break;
        case 'default':
        default:
            $rootClasses =
                'pointer-events-auto relative rounded-radius border border-outline bg-surface text-on-surface dark:border-outline-dark dark:bg-surface-dark dark:text-on-surface-dark';
            $innerClasses = 'flex w-full flex-col rounded-radius transition-all duration-300';
            break;
    }
@endphp

{{-- ═══════════════════════════ Template ═══════════════════════════ --}}
<div id="{{ $safeId }}" class="{{ $rootClasses }} {{ $stacked ? 'mb-3' : '' }} {{ $translucentClass }}"
    x-data="{
        show: {{ $live ? 'false' : 'true' }},
        init() {
            @if ($duration) if (this.show) {
                 setTimeout(() => this.dismiss(), {{ $duration }});
             } @endif
        },
        dismiss() {
            this.show = false;
            this.$dispatch('jkc-on-toast-close', { id: '{{ $safeId }}' });
        }
    }" x-show="show"
    @if ($live) @jkc-open-toast.window="if ($event.detail === '{{ $safeId }}') { show = true; @if ($duration) setTimeout(() => dismiss(), {{ $duration }}); @endif
    }" @jkc-close-toast.window="if ($event.detail === '{{ $safeId }}') show = false" style="display: none;"
    :style="show ? 'display: block;' : 'display: none;'" @endif
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 -translate-y-2"
    role="alert"
    aria-live="polite"
    aria-atomic="true">

    @if ($type === 'bordered')
        {{-- Bordered with Icon Toast --}}
        <div class="{{ $innerClasses }}">
            <div class="rounded-full {{ $theme['bgBadge'] }} p-0.5 {{ $theme['text'] }}" aria-hidden="true">
                {!! $theme['iconSvg'] !!}
            </div>
            <div class="flex flex-col gap-1 min-w-0 flex-1">
                @if ($title)
                    <h3 class="text-sm font-semibold {{ $theme['text'] }}">{{ $title }}</h3>
                @endif
                <p class="text-pretty text-sm">{!! $message !!}</p>
            </div>
            @if ($dismissible)
                <button type="button"
                    class="ml-auto text-on-surface dark:text-on-surface-dark hover:text-danger transition-colors focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-current"
                    aria-label="Dismiss notification" @click="dismiss()">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor" fill="none"
                        stroke-width="2" class="size-5 shrink-0" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            @endif
        </div>
    @elseif ($type === 'message')
        {{-- Message Toast (with avatar) --}}
        <div class="{{ $innerClasses }}">
            @if ($senderAvatar)
                <img src="{{ $senderAvatar }}" class="size-10 rounded-full object-cover shrink-0"
                    alt="{{ $senderName ?? 'Avatar' }}"
                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                <div class="size-10 rounded-full bg-primary/10 flex items-center justify-center shrink-0 hidden">
                    <svg class="size-5 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                        <circle cx="12" cy="7" r="4" />
                    </svg>
                </div>
            @else
                <div class="size-10 rounded-full bg-primary/10 flex items-center justify-center shrink-0">
                    <svg class="size-5 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                        <circle cx="12" cy="7" r="4" />
                    </svg>
                </div>
            @endif
            <div class="flex flex-col gap-1 min-w-0 flex-1">
                @if ($senderName)
                    <h3 class="text-sm font-semibold text-on-surface-strong dark:text-on-surface-dark-strong">
                        {{ $senderName }}
                    </h3>
                @endif
                <p class="text-pretty text-sm">{!! $message !!}</p>
            </div>
            @if ($dismissible)
                <button type="button"
                    class="ml-auto text-on-surface dark:text-on-surface-dark hover:text-danger transition-colors focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-current"
                    aria-label="Dismiss notification" @click="dismiss()">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor" fill="none"
                        stroke-width="2" class="size-5 shrink-0" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            @endif
        </div>
    @else
        {{-- Default / Translucent Toast --}}
        <div class="flex items-center justify-between px-4 py-3 border-b border-outline dark:border-outline-dark">
            <div class="flex items-center gap-2 min-w-0">
                @if ($senderAvatar)
                    <img src="{{ $senderAvatar }}" class="size-5 rounded-full object-cover shrink-0" alt=""
                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="size-5 rounded-full bg-primary/10 flex items-center justify-center shrink-0 hidden">
                        <svg class="size-3 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                            <circle cx="12" cy="7" r="4" />
                        </svg>
                    </div>
                @else
                    <div class="size-5 rounded-full bg-primary/10 flex items-center justify-center shrink-0">
                        <svg class="size-3 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                            <circle cx="12" cy="7" r="4" />
                        </svg>
                    </div>
                @endif
                <strong
                    class="text-sm font-semibold text-on-surface-strong dark:text-on-surface-dark-strong truncate">{{ $title }}</strong>
            </div>
            <div class="flex items-center gap-3 shrink-0">
                <small class="text-xs text-on-surface/60 dark:text-on-surface-dark/60">{{ $time }}</small>
                @if ($dismissible)
                    <button type="button" @click="dismiss()"
                        class="text-on-surface/50 dark:text-on-surface-dark/50 hover:text-danger transition-colors focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-current"
                        aria-label="Dismiss notification">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor"
                            fill="none" stroke-width="2" class="size-4 shrink-0" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                @endif
            </div>
        </div>
        <div class="px-4 py-3 text-sm text-on-surface dark:text-on-surface-dark">
            {!! $message !!}
        </div>
    @endif
</div>
