@php
    use Juksgraphic\BladeComponents\Components\Feedbacks\Alert;
    use Juksgraphic\BladeComponents\Components\Feedbacks\AlertStyles;

    $alert = new Alert([
        'type'        => $type  ?? 'primary',
        'style'       => $style ?? 'default',
        'dismissible' => $dismissible ?? false,
        'icon'        => $icon  ?? false,
        'title'       => $title ?? null,
        'message'     => $message ?? '',
        'link'        => $link  ?? null,
        'linkText'    => $linkText ?? 'Learn more',
        'live'        => $live   ?? false,
        'id'          => $id    ?? null,
        'class'       => $class ?? '',
        'attrs'       => $attrs ?? '',
        'closeLabel'  => $closeLabel ?? 'Close',
    ]);

    $styles  = new AlertStyles($alert);
    $alertId = $alert->id();
@endphp

<!-- Root alert container -->
<div
    @if ($alertId !== null) id="{{ $alertId }}" @endif
    role="{{ $alert->role() }}"
    @if ($alert->usesAlpine())
        x-data="{
            show: {{ $alert->isLive() ? 'false' : 'true' }},

            dismiss() {
                this.show = false;
                this.$dispatch('jkc-alert-close', {
                    id: {{ $alertId !== null ? "'{$alertId}'" : 'null' }}
                });
            }
        }"
        x-show="show"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 -translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 -translate-y-2"
    @endif
    @if ($alert->isLive())
        x-on:jkc-alert.window="if ({{ $alertId !== null ? "\$event.detail?.id === '{$alertId}'" : 'true' }}) show = true"
        style="display: none"
    @endif
    class="{{ $styles->container() }}"
    {!! $alert->attrs() !!}
>

    <!-- Leading icon: badge style for label-* variants -->
    @if ($alert->hasIcon() && $alert->isLabel())
        <span class="{{ $styles->iconBadge() }}">
            {!! $alert->iconHtml() !!}
        </span>

    <!-- Leading icon: standard aligned icon -->
    @elseif ($alert->hasIcon())
        <span class="{{ $styles->iconSimple() }}">
            {!! $alert->iconHtml() !!}
        </span>
    @endif

    <!-- Alert body: optional title, message content, and optional link -->
    <div class="{{ $styles->content() }}">
        @if ($alert->title() !== null)
            <p class="{{ $styles->title() }}">{{ $alert->title() }}</p>
            <hr class="{{ $styles->titleDivider() }}">
        @endif

        {!! $alert->message() !!}

        @if ($alert->link() !== null)
            <a
                href="{{ $alert->link() }}"
                class="{{ $styles->link() }}"
            >
                {{ $alert->linkText() }}
            </a>
        @endif
    </div>

    <!-- Trailing arrow indicator for label-icon-arrow layout -->
    @if ($alert->isStyle('label-icon-arrow'))
        <svg
            class="{{ $styles->arrow() }}"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
            aria-hidden="true"
        >
            <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M9 5l7 7-7 7"
            />
        </svg>
    @endif

    <!-- Dismiss button when dismissible is enabled -->
    @if ($alert->isDismissible())
        <button
            type="button"
            x-on:click="dismiss()"
            aria-label="{{ htmlspecialchars($alert->closeLabel(), ENT_QUOTES, 'UTF-8') }}"
            class="{{ $styles->dismissButton() }}"
        >
            <svg
                class="size-4"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
                aria-hidden="true"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M6 18L18 6M6 6l12 12"
                />
            </svg>
        </button>
    @endif

</div>