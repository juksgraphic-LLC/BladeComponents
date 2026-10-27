@php
    /**
     * List Group Component — Juksgraphic / Penguin UI
     *
     * Reusable list group built with Tailwind CSS and Alpine.js.
     * Works with Laravel Blade and BladeOne.
     *
     * @param string      $type       List layout/variant: default|flush|horizontal|link|button|custom|numbered|icon|checkbox|radio|badge|contextual|colored. Default: 'default'.
     * @param array       $items      Array of items: [{title, content, icon, badge, badgeColor, disabled, active, color, subtitle, avatar, action, href}]. Default: [].
     * @param string      $color      Color variant for contextual/colored lists: primary|secondary|success|danger|warning|info|light|dark. Default: 'primary'.
     * @param int|null    $active     Index of the active item. Default: null.
     * @param array       $disabled   Array of disabled item indices. Default: [].
     * @param string|null $id         HTML id identifier. Default: null.
     * @param string      $class      Extra CSS classes for the container. Default: ''.
     * @param string      $attrs      Extra HTML attributes for the container. Default: ''.
     */

    /** @var string $type */
    $type = (string) ($type ?? 'default');

    /** @var array<int, array<string, mixed>> $items */
    $items = $items ?? [];

    /** @var string $color */
    $color = (string) ($color ?? 'primary');

    /** @var int|null $active */
    $active = isset($active) ? (int) $active : null;

    /** @var array<int> $disabled */
    $disabled = $disabled ?? [];

    /** @var string|null $id */
    $safeId = isset($id)
        ? preg_replace('/[^A-Za-z0-9_-]/', '', (string) $id)
        : 'list-group-' . bin2hex(random_bytes(4));

    /** @var string $class */
    $class = $class ?? '';

    /** @var string $attrs */
    $attrs = $attrs ?? '';

    /** @var array<string, array<string, string>> $themes */
    $themes = [
        'primary' => [
            'activeBg' => 'bg-primary/5 dark:bg-primary/10',
            'activeText' => 'text-primary dark:text-primary-dark',
            'activeBorder' => 'border-primary/20 dark:border-primary/30',
            'badgeBg' => 'bg-primary/10 text-primary dark:bg-primary-dark/15 dark:text-primary-dark',
            'iconActive' => 'text-primary dark:text-primary-dark',
        ],
        'secondary' => [
            'activeBg' => 'bg-secondary/5 dark:bg-secondary/10',
            'activeText' => 'text-secondary dark:text-secondary-dark',
            'activeBorder' => 'border-secondary/20 dark:border-secondary/30',
            'badgeBg' => 'bg-secondary/10 text-secondary dark:bg-secondary-dark/15 dark:text-secondary-dark',
            'iconActive' => 'text-secondary dark:text-secondary-dark',
        ],
        'success' => [
            'activeBg' => 'bg-success/5 dark:bg-success/10',
            'activeText' => 'text-success',
            'activeBorder' => 'border-success/20 dark:border-success/30',
            'badgeBg' => 'bg-success/10 text-success',
            'iconActive' => 'text-success',
        ],
        'danger' => [
            'activeBg' => 'bg-danger/5 dark:bg-danger/10',
            'activeText' => 'text-danger',
            'activeBorder' => 'border-danger/20 dark:border-danger/30',
            'badgeBg' => 'bg-danger/10 text-danger',
            'iconActive' => 'text-danger',
        ],
        'warning' => [
            'activeBg' => 'bg-warning/5 dark:bg-warning/10',
            'activeText' => 'text-warning',
            'activeBorder' => 'border-warning/20 dark:border-warning/30',
            'badgeBg' => 'bg-warning/10 text-warning',
            'iconActive' => 'text-warning',
        ],
        'info' => [
            'activeBg' => 'bg-info/5 dark:bg-info/10',
            'activeText' => 'text-info',
            'activeBorder' => 'border-info/20 dark:border-info/30',
            'badgeBg' => 'bg-info/10 text-info',
            'iconActive' => 'text-info',
        ],
        'light' => [
            'activeBg' => 'bg-surface-alt dark:bg-surface-dark-alt',
            'activeText' => 'text-on-surface-strong dark:text-on-surface-dark-strong',
            'activeBorder' => 'border-outline dark:border-outline-dark',
            'badgeBg' => 'bg-surface-alt text-on-surface dark:bg-surface-dark-alt dark:text-on-surface-dark',
            'iconActive' => 'text-on-surface-strong dark:text-on-surface-dark-strong',
        ],
        'dark' => [
            'activeBg' => 'bg-surface-dark dark:bg-surface',
            'activeText' => 'text-on-surface-dark-strong dark:text-on-surface-strong',
            'activeBorder' => 'border-outline-dark-strong dark:border-outline-strong',
            'badgeBg' => 'bg-surface text-on-surface-strong dark:bg-surface-dark dark:text-on-surface-dark-strong',
            'iconActive' => 'text-on-surface-dark-strong dark:text-on-surface-strong',
        ],
    ];

    /** @var array<string, string> $theme */
    $theme = $themes[$color] ?? $themes['primary'];

    /** @var string $containerVariantClasses */
    $containerVariantClasses = match ($type) {
        'flush' => '',
        'horizontal' => 'flex flex-row flex-wrap gap-0',
        'custom' => 'space-y-3',
        default
            => 'rounded-radius border border-outline dark:border-outline-dark overflow-hidden bg-surface dark:bg-surface-dark',
    };

    /** @var string $containerClasses */
    $containerClasses = preg_replace('/\s+/', ' ', trim($containerVariantClasses . ' ' . $class));

    /** @var string $itemBase */
    $itemBase = 'relative flex items-center gap-3 px-5 py-3 text-sm font-medium transition-all duration-200';

    /** @var string $itemVariantClasses */
    $itemVariantClasses = match ($type) {
        'flush'
            => "$itemBase border-b border-outline dark:border-outline-dark last:border-0 text-on-surface dark:text-on-surface-dark hover:bg-surface-alt dark:hover:bg-surface-dark-alt",
        'horizontal'
            => "$itemBase border border-outline dark:border-outline-dark text-on-surface dark:text-on-surface-dark hover:bg-surface-alt dark:hover:bg-surface-dark-alt px-4 py-2",
        'custom'
            => 'flex items-start gap-4 p-4 rounded-radius border border-outline dark:border-outline-dark bg-surface dark:bg-surface-dark hover:bg-surface-alt dark:hover:bg-surface-dark-alt transition-all duration-200',
        'contextual', 'colored' => "$itemBase border-b border-outline dark:border-outline-dark last:border-0",
        default
            => "$itemBase border-b border-outline dark:border-outline-dark last:border-0 text-on-surface dark:text-on-surface-dark hover:bg-surface-alt dark:hover:bg-surface-dark-alt",
    };

    /** @var string $disabledClasses */
    $disabledClasses = 'opacity-50 cursor-not-allowed pointer-events-none';
@endphp

{{-- ═══════════════════════════ Template ═══════════════════════════ --}}
<div id="{{ $safeId }}" class="{{ $containerClasses }}" {!! $attrs !!}>
    @foreach ($items as $index => $item)
        @php
            $isActive   = $active === $index || ($item['active'] ?? false);
            $isDisabled = in_array($index, $disabled, true) || ($item['disabled'] ?? false);

            $itemColor  = $item['color'] ?? null;
            $itemTheme  = $itemColor ? $themes[$itemColor] ?? $theme : $theme;

            $itemIcon          = $item['icon'] ?? null;
            $itemBadge         = $item['badge'] ?? null;
            $itemBadgeColor    = $item['badgeColor'] ?? null;
            $currentBadgeTheme = $itemBadgeColor ? $themes[$itemBadgeColor] ?? $itemTheme : $itemTheme;

            $itemSubtitle = $item['subtitle'] ?? null;
            $itemAvatar   = $item['avatar'] ?? null;
            $itemAction   = $item['action'] ?? null;
            $itemContent  = $item['content'] ?? null;
            $itemTitle    = $item['title'] ?? 'Item ' . ($index + 1);

            $safeHref = null;

            if (
                isset($item['href']) &&
                is_string($item['href']) &&
                trim($item['href']) !== '' &&
                !preg_match('/^\s*(javascript|data|vbscript):/i', $item['href'])
            ) {
                $safeHref = trim($item['href']);
            }

            $itemNumber = $index + 1;

            $finalItemClass = $itemVariantClasses;

            if ($isActive) {
                if ($type === 'contextual' || $type === 'colored') {
                    $finalItemClass .=
                        ' ' .
                        $itemTheme['activeBg'] .
                        ' ' .
                        $itemTheme['activeText'] .
                        ' ' .
                        $itemTheme['activeBorder'];
                } elseif ($type === 'custom') {
                    $finalItemClass .= ' bg-primary/5 border-primary/20 dark:bg-primary/10 dark:border-primary/35';
                } else {
                    $finalItemClass .=
                        ' ' .
                        $itemTheme['activeBg'] .
                        ' ' .
                        $itemTheme['activeText'] .
                        ' ' .
                        $itemTheme['activeBorder'];
                }
            }

            if ($isDisabled) {
                $finalItemClass .= ' ' . $disabledClasses;
            }
        @endphp

        @if ($type === 'link')
            <a href="{{ $isDisabled || !$safeHref ? '#' : $safeHref }}" class="{{ $finalItemClass }}">
            @elseif ($type === 'button')
                <button type="button" @if (!$isDisabled && $itemAction) onclick="{{ $itemAction }}" @endif
                    class="{{ $finalItemClass }} w-full text-left">
                @elseif ($type === 'checkbox')
                    <label class="{{ $finalItemClass }} cursor-pointer">
                        <input type="checkbox"
                            class="size-4 rounded border-outline dark:border-outline-dark text-primary focus:ring-primary/20"
                            {{ $isActive ? 'checked' : '' }} {{ $isDisabled ? 'disabled' : '' }}>
                    @elseif ($type === 'radio')
                        <label class="{{ $finalItemClass }} cursor-pointer">
                            <input type="radio" name="{{ $safeId }}_radio"
                                class="size-4 border-outline dark:border-outline-dark text-primary focus:ring-primary/20"
                                {{ $isActive ? 'checked' : '' }} {{ $isDisabled ? 'disabled' : '' }}>
                        @else
                            <div class="{{ $finalItemClass }}">
        @endif

        {{-- Numbered indicator --}}
        @if ($type === 'numbered')
            <span
                class="flex size-6 shrink-0 items-center justify-center rounded-full bg-surface-alt dark:bg-surface-dark-alt font-semibold text-xs text-on-surface/60 dark:text-on-surface-dark/60">
                {{ $itemNumber }}
            </span>
        @endif

        {{-- Avatar (custom layout) --}}
        @if ($itemAvatar && $type === 'custom')
            <img src="{{ $itemAvatar }}" alt="" class="size-10 shrink-0 rounded-full object-cover">
        @endif

        {{-- Icon --}}
        @if ($itemIcon && $type !== 'custom')
            <span
                class="shrink-0 {{ $isActive ? $itemTheme['iconActive'] : 'text-on-surface/50 dark:text-on-surface-dark/50' }}">
                {!! $itemIcon !!}
            </span>
        @endif

        {{-- Content --}}
        <div class="min-w-0 flex-1">
            @if ($type === 'custom')
                <div class="mb-1 flex items-center justify-between">
                    <h4 class="font-title font-semibold text-sm text-on-surface dark:text-on-surface-dark">
                        {{ $itemTitle }}
                    </h4>
                    @if ($itemBadge)
                        <span class="rounded-full px-2 py-0.5 font-medium text-xs {{ $currentBadgeTheme['badgeBg'] }}">
                            {{ $itemBadge }}
                        </span>
                    @endif
                </div>
                @if ($itemSubtitle)
                    <p class="mb-1 font-paragraph text-xs text-on-surface/60 dark:text-on-surface-dark/60">
                        {{ $itemSubtitle }}</p>
                @endif
                @if ($itemContent)
                    <p class="font-paragraph text-sm text-on-surface/80 dark:text-on-surface-dark/80">
                        {!! $itemContent !!}</p>
                @endif
            @else
                <span
                    class="truncate {{ $isActive && ($type === 'contextual' || $type === 'colored') ? $itemTheme['activeText'] : '' }}">
                    {{ $itemTitle }}
                </span>
            @endif
        </div>

        {{-- Badge (standard layouts) --}}
        @if ($itemBadge && $type !== 'custom')
            <span class="shrink-0 rounded-full px-2 py-0.5 font-medium text-xs {{ $currentBadgeTheme['badgeBg'] }}">
                {{ $itemBadge }}
            </span>
        @endif

        {{-- Action / Arrow (link/button layouts) --}}
        @if (($type === 'link' || $type === 'button') && !$itemBadge)
            <svg class="size-4 shrink-0 {{ $isActive ? $itemTheme['iconActive'] : 'text-on-surface/40 dark:text-on-surface-dark/40' }}"
                fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        @endif

        @if ($type === 'link')
            </a>
        @elseif ($type === 'button')
            </button>
        @elseif ($type === 'checkbox' || $type === 'radio')
            </label>
        @else
</div>
@endif
@endforeach
</div>
