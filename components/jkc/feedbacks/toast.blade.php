@php
    use Juksgraphic\BladeComponents\Components\Feedbacks\Toast;
    use Juksgraphic\BladeComponents\Components\Feedbacks\ToastStyles;

   
    $toast = new Toast([
        'id'           => $id      ?? null,
        'variant'      => $variant ?? 'info',
        'type'         => $type    ?? 'default',
        'title'        => $title   ?? 'Notification',
        'message'      => $message ?? 'Hello, world! This is a toast message.',
        'time'         => $time    ?? 'Just now',
        'senderName'   => $senderName   ?? null,
        'senderAvatar' => $senderAvatar ?? null,
        'dismissible'  => $dismissible  ?? true,
        'translucent'  => $translucent  ?? false,
        'duration'     => $duration     ?? null,
        'live'         => $live         ?? false,
        'stacked'      => $stacked      ?? false,
    ]);

    $styles = new ToastStyles($toast);
@endphp

<!-- Root toast notification container -->
<div
    id="{{ $toast->id() }}"
    class="{{ $styles->container() }}"
    x-data="{
        cfg:  {{ $toast->toJson() }},
        show: {{ $toast->isLive() ? 'false' : 'true' }},
        timer: null,

        init() {
            if (this.show) {
                this.startTimer();
            }
        },

        startTimer() {
            clearTimeout(this.timer);
            if (this.cfg.duration) {
                this.timer = setTimeout(() => this.dismiss(), this.cfg.duration);
            }
        },

        open() {
            this.show = true;
            this.startTimer();
        },

        dismiss() {
            clearTimeout(this.timer);
            this.show = false;
            this.$dispatch('jkc-on-toast-close', { id: this.cfg.id });
        }
    }"
    x-show="show"
    @if ($toast->isLive())
        @jkc-open-toast.window="if ($event.detail === cfg.id) open()"
        @jkc-close-toast.window="if ($event.detail === cfg.id) show = false"
        style="display: none;"
        :style="show ? 'display: block;' : 'display: none;'"
    @endif
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 -translate-y-2"
    role="alert"
    aria-live="polite"
    aria-atomic="true"
>

    <!-- Layout 1: Bordered toast with semantic icon badge -->
    @if ($toast->isType('bordered'))
        <div class="{{ $styles->inner() }}">

            <!-- Semantic variant icon badge -->
            <div class="{{ $styles->borderedIconBadge() }}" aria-hidden="true">
                {!! $toast->iconSvg() !!}
            </div>

            <!-- Toast title and message body -->
            <div class="flex flex-col gap-1 min-w-0 flex-1">
                @if ($toast->title() !== '')
                    <h3 class="{{ $styles->borderedTitle() }}">
                        {{ $toast->title() }}
                    </h3>
                @endif

                <p class="text-pretty text-sm">{!! $toast->message() !!}</p>
            </div>

            <!-- Optional dismiss button -->
            @if ($toast->isDismissible())
                <button
                    type="button"
                    class="{{ $styles->inlineDismissButton() }}"
                    aria-label="Dismiss notification"
                    x-on:click="dismiss()"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        fill="none"
                        stroke-width="2"
                        class="size-5 shrink-0"
                        aria-hidden="true"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            @endif

        </div>

    <!-- Layout 2: Direct message toast with sender avatar -->
    @elseif ($toast->isType('message'))
        <div class="{{ $styles->inner() }}">

            <!-- Sender avatar image with SVG fallback -->
            @if ($toast->senderAvatar() !== null)
                <img
                    src="{{ $toast->senderAvatar() }}"
                    class="size-10 rounded-full object-cover shrink-0"
                    alt="{{ $toast->senderName() ?? 'Avatar' }}"
                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                >
                <div class="size-10 rounded-full bg-primary/10 flex items-center justify-center shrink-0 hidden">
                    <svg class="size-5 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                        <circle cx="12" cy="7" r="4" />
                    </svg>
                </div>
            @else
                <div class="size-10 rounded-full bg-primary/10 flex items-center justify-center shrink-0">
                    <svg class="size-5 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                        <circle cx="12" cy="7" r="4" />
                    </svg>
                </div>
            @endif

            <!-- Sender name and message body -->
            <div class="flex flex-col gap-1 min-w-0 flex-1">
                @if ($toast->senderName() !== null)
                    <h3 class="text-sm font-semibold text-on-surface-strong dark:text-on-surface-dark-strong">
                        {{ $toast->senderName() }}
                    </h3>
                @endif

                <p class="text-pretty text-sm">{!! $toast->message() !!}</p>
            </div>

            <!-- Optional dismiss button -->
            @if ($toast->isDismissible())
                <button
                    type="button"
                    class="{{ $styles->inlineDismissButton() }}"
                    aria-label="Dismiss notification"
                    x-on:click="dismiss()"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                        stroke="currentColor"
                        fill="none"
                        stroke-width="2"
                        class="size-5 shrink-0"
                        aria-hidden="true"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            @endif

        </div>

    <!-- Layout 3: Default / Translucent stacked header + body toast -->
    @else
        <!-- Top bar with mini avatar, title, timestamp, and close button -->
        <div class="{{ $styles->defaultHeader() }}">

            <!-- Left avatar and title -->
            <div class="flex items-center gap-2 min-w-0">
                @if ($toast->senderAvatar() !== null)
                    <img
                        src="{{ $toast->senderAvatar() }}"
                        class="size-5 rounded-full object-cover shrink-0"
                        alt=""
                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                    >
                    <div class="size-5 rounded-full bg-primary/10 flex items-center justify-center shrink-0 hidden">
                        <svg class="size-3 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                            <circle cx="12" cy="7" r="4" />
                        </svg>
                    </div>
                @else
                    <div class="size-5 rounded-full bg-primary/10 flex items-center justify-center shrink-0">
                        <svg class="size-3 text-primary" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                            <circle cx="12" cy="7" r="4" />
                        </svg>
                    </div>
                @endif

                <strong class="{{ $styles->defaultTitle() }}">
                    {{ $toast->title() }}
                </strong>
            </div>

            <!-- Right timestamp and optional close button -->
            <div class="flex items-center gap-3 shrink-0">
                <small class="{{ $styles->defaultTime() }}">
                    {{ $toast->time() }}
                </small>

                @if ($toast->isDismissible())
                    <button
                        type="button"
                        x-on:click="dismiss()"
                        class="{{ $styles->defaultDismissButton() }}"
                        aria-label="Dismiss notification"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            fill="none"
                            stroke-width="2"
                            class="size-4 shrink-0"
                            aria-hidden="true"
                        >
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                @endif
            </div>

        </div>

        <!-- Bottom message content area -->
        <div class="{{ $styles->defaultBody() }}">
            {!! $toast->message() !!}
        </div>
    @endif

</div>