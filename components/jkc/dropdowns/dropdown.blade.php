@php
    /**
     * Dropdown component — Juksgraphic / Penguin UI
     *
     * Reusable dropdown built with Tailwind CSS and Alpine.js.
     * Works with Laravel Blade and BladeOne.
     *
     * @param string      $trigger     Trigger type: button|link|split. Default: 'button'.
     * @param string      $color       Color variant: primary|secondary|success|danger|warning|info|light|dark. Default: 'primary'.
     * @param string|null $size        Size variant: lg|sm|null. Default: null.
     * @param bool        $darkMenu    Force dark menu appearance. Default: false.
     * @param string|null $align       Dropdown alignment: start|end|start-lg|end-lg|dropstart|dropend|dropup|null. Default: null.
     * @param bool|string $autoClose   Auto close behavior: true|'outside'|'inside'|false. Default: true.
     * @param array       $items       Array of items. Default: [].
     * @param string|null $header      Header title. Default: null.
     * @param string|null $text        Static text content. Default: null.
     * @param string|null $splitAction Action URL for split button. Default: null.
     * @param string      $buttonText  Text of the button. Default: 'Dropdown'.
     * @param string|null $buttonIcon  Button SVG icon. Default: chevron SVG.
     * @param string      $menuClass   Extra CSS classes for the menu. Default: ''.
     * @param string|null $id          HTML id / Event target identifier. Default: null.
     */

    /** @var string $trigger */
    $trigger = (string) ($trigger ?? 'button');

    /** @var string $color */
    $color = (string) ($color ?? 'primary');

    /** @var string|null $size */
    $size = $size ? (string) $size : null;

    /** @var bool $darkMenu */
    $darkMenu = (bool) ($darkMenu ?? false);

    /** @var string|null $align */
    $align = $align ? (string) $align : null;

    /** @var bool|string $autoClose */
    $autoClose = $autoClose ?? true;

    /** @var array<int, array> $items */
    $items = $items ?? [];

    /** @var string|null $header */
    $header = $header ?? null;

    /** @var string|null $text */
    $text = $text ?? null;

    /** @var string|null $splitAction */
    $splitAction = $splitAction ?? null;

    /** @var string $buttonText */
    $buttonText = (string) ($buttonText ?? 'Dropdown');

    /** @var string $buttonIcon */
    $buttonIcon =
        $buttonIcon ??
        '<svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>';

    /** @var string $menuClass */
    $menuClass = (string) ($menuClass ?? '');

    /** @var string|null $safeId */
    $safeId = isset($id)
        ? preg_replace('/[^A-Za-z0-9_-]/', '', (string) $id)
        : 'jkc-dropdown-' . bin2hex(random_bytes(4));

    /** @var array<string, string> $btnThemes */
    $btnThemes = [
        'primary' =>
            'bg-primary text-on-primary hover:bg-primary/90 focus:ring-primary/30 dark:bg-primary-dark dark:text-on-primary-dark',
        'secondary' =>
            'bg-secondary text-on-secondary hover:bg-secondary/90 focus:ring-secondary/30 dark:bg-secondary-dark dark:text-on-secondary-dark',
        'success' => 'bg-success text-on-success hover:bg-success/90 focus:ring-success/30',
        'danger' => 'bg-danger text-on-danger hover:bg-danger/90 focus:ring-danger/30',
        'warning' => 'bg-warning text-on-warning hover:bg-warning/90 focus:ring-warning/30',
        'info' => 'bg-info text-on-info hover:bg-info/90 focus:ring-info/30',
        'light' =>
            'bg-surface-alt text-on-surface border border-outline hover:bg-surface focus:ring-outline/30 dark:bg-surface-dark-alt dark:text-on-surface-dark dark:border-outline-dark dark:hover:bg-surface-dark',
        'dark' =>
            'bg-surface-dark-alt text-on-surface-dark border border-outline-dark hover:bg-surface-dark focus:ring-outline-dark/30 dark:bg-surface-alt dark:text-on-surface dark:border-outline dark:hover:bg-surface',
    ];

    /** @var array<string, string> $sizeClasses */
    $sizeClasses = [
        'lg' => 'px-5 py-3 text-base',
        'sm' => 'px-3 py-1.5 text-xs',
        null => 'px-4 py-2 text-sm',
    ];

    /** @var array<string, string> $alignClasses */
    $alignClasses = [
        'start' => 'left-0',
        'end' => 'right-0',
        'start-lg' => 'right-0 lg:left-0 lg:right-auto',
        'end-lg' => 'left-0 lg:right-0 lg:left-auto',
        'dropstart' => 'right-full top-0 mr-2',
        'dropend' => 'left-full top-0 ml-2',
        'dropup' => 'bottom-full mb-2 left-0',
        null => 'left-0',
    ];

    $closeOutside = in_array($autoClose, [true, 'outside'], true);
    $closeInside  = in_array($autoClose, [true, 'inside'], true);

    $btnClass = trim(
        ($btnThemes[$color] ?? $btnThemes['primary']) .
            ' ' .
            ($sizeClasses[$size] ?? $sizeClasses['']) .
            ' inline-flex items-center justify-center font-medium rounded-radius transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-surface-dark',
    );

    $menuBaseClass =
        'absolute z-50 min-w-[12rem] mt-2 rounded-radius border shadow-lg overflow-hidden ' .
        ($alignClasses[$align] ?? $alignClasses['']) .
        ' ' .
        $menuClass;

    if ($darkMenu) {
        $menuBaseClass .= ' bg-surface-dark border-outline-dark text-on-surface-dark';
    } else {
        $menuBaseClass .=
            ' bg-surface dark:bg-surface-dark border-outline dark:border-outline-dark text-on-surface dark:text-on-surface-dark';
    }
@endphp

<div id="{{ $safeId }}" x-data="{
    open: false,
    toggle() {
        this.open = !this.open;
        this.$dispatch('jkc-dropdown-toggle', { id: '{{ $safeId }}', isOpen: this.open });
    }
}"
    @if ($closeOutside) @click.outside="open = false" @endif
    class="relative inline-flex {{ $trigger === 'split' ? 'group' : '' }}">
    {{-- Single Button / Link --}}
    @if ($trigger === 'button' || $trigger === 'link')
        <button x-on:click="toggle()" type="button" class="{{ $btnClass }}" aria-expanded="false"
            :aria-expanded="open.toString()">
            {{ $buttonText }}
            {!! $buttonIcon !!}
        </button>
    @endif

    {{-- Split Button --}}
    @if ($trigger === 'split')
        <a href="{{ $splitAction ?? '#' }}"
            class="{{ $btnClass }} rounded-r-none border-r border-white/20 dark:border-black/20">
            {{ $buttonText }}
        </a>
        <button x-on:click="toggle()" type="button" class="{{ $btnClass }} rounded-l-none px-3" aria-expanded="false"
            :aria-expanded="open.toString()">
            <span class="sr-only">Toggle dropdown</span>
            {!! $buttonIcon !!}
        </button>
    @endif

    {{-- Menu --}}
    <div x-show="open" x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-1" @if ($closeInside) @click="open = false" @endif
        class="{{ $menuBaseClass }}" style="display: none; " :style="open ? 'display: block;' : 'display: none;'">
        @if ($header)
            <div
                class="px-4 py-2 text-xs font-semibold font-title uppercase tracking-wider border-b {{ $darkMenu ? 'text-on-surface-dark/60 border-outline-dark' : 'text-on-surface/60 dark:text-on-surface-dark/60 border-outline dark:border-outline-dark' }}">
                {{ $header }}
            </div>
        @endif

        @if ($text)
            <div
                class="px-4 py-3 text-sm {{ $darkMenu ? 'text-on-surface-dark/80' : 'text-on-surface/80 dark:text-on-surface-dark/80' }}">
                {{ $text }}
            </div>
        @endif

        <div class="py-1 z-5">
            @foreach ($items as $item)
                @php
                    $itemType = $item['type'] ?? 'link';
                    $itemColor = $item['color'] ?? null;
                    $isActive = $item['active'] ?? false;
                    $isDisabled = $item['disabled'] ?? false;
                    $itemBase = 'block w-full px-4 py-2 text-sm font-paragraph transition-colors duration-150';

                    if ($itemType === 'divider') {
                        echo '<div class="my-1 border-t ' .
                            ($darkMenu ? 'border-outline-dark' : 'border-outline dark:border-outline-dark') .
                            '"></div>';
                        continue;
                    }

                    if ($itemType === 'header') {
                        echo '<div class="px-4 py-2 text-xs font-semibold font-title uppercase tracking-wider ' .
                            ($darkMenu
                                ? 'text-on-surface-dark/60'
                                : 'text-on-surface/60 dark:text-on-surface-dark/60') .
                            '">' .
                            ($item['text'] ?? '') .
                            '</div>';
                        continue;
                    }

                    if ($itemType === 'text') {
                        echo '<span class="' .
                            $itemBase .
                            ' ' .
                            ($darkMenu
                                ? 'text-on-surface-dark/80'
                                : 'text-on-surface/80 dark:text-on-surface-dark/80') .
                            '">' .
                            ($item['text'] ?? '') .
                            '</span>';
                        continue;
                    }

                    if ($itemType === 'form') {
                        echo '<div class="px-4 py-2">' . ($item['content'] ?? '') . '</div>';
                        continue;
                    }

                    $itemColorClass = '';
                    if ($itemColor) {
                        $colorMap = [
                            'primary' => 'text-primary hover:bg-primary/10',
                            'secondary' => 'text-secondary hover:bg-secondary/10',
                            'success' => 'text-success hover:bg-success/10',
                            'danger' => 'text-danger hover:bg-danger/10',
                            'warning' => 'text-warning hover:bg-warning/10',
                            'info' => 'text-info hover:bg-info/10',
                        ];
                        $itemColorClass = $colorMap[$itemColor] ?? '';
                    } else {
                        $itemColorClass = $darkMenu
                            ? 'text-on-surface-dark hover:bg-white/5 hover:text-on-surface-dark-strong'
                            : 'text-on-surface dark:text-on-surface-dark hover:bg-surface-alt dark:hover:bg-white/5 hover:text-on-surface-strong dark:hover:text-on-surface-dark-strong';
                    }

                    $activeClass = $isActive
                        ? ($darkMenu
                            ? 'bg-white/10 text-on-surface-dark-strong'
                            : 'bg-surface-alt dark:bg-white/10 text-on-surface-strong dark:text-on-surface-dark-strong')
                        : '';

                    $disabledClass = $isDisabled ? 'opacity-50 pointer-events-none' : '';
                @endphp

                <a href="{{ $item['href'] ?? '#' }}"
                    class="{{ trim($itemBase . ' ' . $itemColorClass . ' ' . $activeClass . ' ' . $disabledClass) }}"
                    @if ($isDisabled) tabindex="-1" aria-disabled="true" @endif>
                    @if (isset($item['icon']))
                        <span class="inline-flex items-center gap-2">
                            {!! $item['icon'] !!}
                            {{ $item['text'] ?? '' }}
                        </span>
                    @else
                        {{ $item['text'] ?? '' }}
                    @endif
                </a>
            @endforeach
        </div>
    </div>
</div>
