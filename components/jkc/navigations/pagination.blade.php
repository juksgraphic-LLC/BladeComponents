@php
/**
 * Pagination Component — Juksgraphic / Penguin UI
 *
 * Autonomous pagination component built with Tailwind CSS.
 *
 * @param int    $total       Total number of items. Default: 50.
 * @param int    $current     Current active page number. Default: 1.
 * @param int    $perPage     Number of items per page. Default: 10.
 * @param string $baseUrl     Base URL pattern or route for pagination links. Default: '?page='.
 * @param int    $window      Number of surrounding pages to display. Default: 1.
 * @param string $style       Visual style: default|pill|bordered|minimal. Default: 'default'.
 * @param string $class       Extra CSS classes for the container. Default: ''.
 * @param string $attrs       Extra HTML attributes for the container. Default: ''.
 * @param string $prevLabel   Text for previous button. Default: 'Previous'.
 * @param string $nextLabel   Text for next button. Default: 'Next'.
 */

/** @var int $total */
$total = max(0, (int) ($total ?? 50));

/** @var int $perPage */
$perPage = max(1, (int) ($perPage ?? 10));

/** @var int $last */
$last = (int) ceil($total / $perPage);

/** @var int $current */
$current = max(1, min($last > 0 ? $last : 1, (int) ($current ?? 1)));

/** @var string $baseUrl */
$baseUrl = (string) ($baseUrl ?? '?page=');

/** @var int $window */
$window = max(0, (int) ($window ?? 1));

/** @var string $style */
$style = (string) ($style ?? 'default');

/** @var string $class */
$class = $class ?? '';

/** @var string $attrs */
$attrs = $attrs ?? '';

/** @var string $prevLabel */
$prevLabel = (string) ($prevLabel ?? 'Previous');

/** @var string $nextLabel */
$nextLabel = (string) ($nextLabel ?? 'Next');

$start = max(1, $current - $window);
$end   = min($last, $current + $window);

$hasPages     = $last > 1;
$onFirstPage  = $current <= 1;
$hasMorePages = $current < $last;

$getLink = fn(int $page) => $baseUrl . $page;

// ── Styles UI Mapping ──
$itemBase = match ($style) {
    'pill'     => 'flex size-8 items-center justify-center rounded-full text-sm font-medium transition',
    'bordered' => 'flex size-8 items-center justify-center rounded-radius border border-outline dark:border-outline-dark text-sm font-medium transition',
    'minimal'  => 'flex size-8 items-center justify-center text-sm font-medium transition hover:text-primary dark:hover:text-primary-dark',
    default    => 'flex size-8 items-center justify-center rounded-radius text-sm font-medium transition',
};

$activeClass = match ($style) {
    'minimal' => 'text-primary dark:text-primary-dark font-bold underline underline-offset-4',
    default   => 'bg-primary text-on-primary dark:bg-primary-dark dark:text-on-primary-dark font-bold shadow-sm',
};

$inactiveClass = 'text-on-surface hover:bg-surface-alt dark:text-on-surface-dark dark:hover:bg-surface-dark-alt';
$disabledClass = 'text-on-surface/40 cursor-not-allowed dark:text-on-surface-dark/40 hover:bg-transparent';

$navButtonBase = match ($style) {
    'bordered' => 'flex items-center gap-1 px-3 py-1.5 rounded-radius border border-outline dark:border-outline-dark text-sm font-medium transition',
    'pill'     => 'flex items-center gap-1 px-3 py-1.5 rounded-full text-sm font-medium transition',
    default    => 'flex items-center gap-1 px-3 py-1.5 rounded-radius text-sm font-medium transition',
};
@endphp

@if ($hasPages)
    <nav aria-label="pagination" class="mt-8 flex justify-center {{ $class }}" {!! $attrs !!}>
        <ul class="flex shrink-0 items-center gap-1.5">

            {{-- Previous --}}
            <li>
                @if ($onFirstPage)
                    <span class="{{ $navButtonBase }} {{ $disabledClass }}" aria-disabled="true">
                        <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        {{ $prevLabel }}
                    </span>
                @else
                    <a href="{{ $getLink($current - 1) }}" class="{{ $navButtonBase }} {{ $inactiveClass }}" aria-label="previous page">
                        <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        {{ $prevLabel }}
                    </a>
                @endif
            </li>

            {{-- Première page --}}
            @if ($start > 1)
                <li>
                    <a href="{{ $getLink(1) }}" class="{{ $itemBase }} {{ $inactiveClass }}" aria-label="page 1">1</a>
                </li>
                @if ($start > 2)
                    <li>
                        <span class="flex size-8 items-center justify-center text-on-surface/60 dark:text-on-surface-dark/60" aria-hidden="true">…</span>
                    </li>
                @endif
            @endif

            {{-- Pages du milieu (Window) --}}
            @for ($p = $start; $p <= $end; $p++)
                <li>
                    @if ($p == $current)
                        <span class="{{ $itemBase }} {{ $activeClass }}" aria-current="page" aria-label="page {{ $p }}">{{ $p }}</span>
                    @else
                        <a href="{{ $getLink($p) }}" class="{{ $itemBase }} {{ $inactiveClass }}" aria-label="page {{ $p }}">{{ $p }}</a>
                    @endif
                </li>
            @endfor

            {{-- Dernière page --}}
            @if ($end < $last)
                @if ($end < $last - 1)
                    <li>
                        <span class="flex size-8 items-center justify-center text-on-surface/60 dark:text-on-surface-dark/60" aria-hidden="true">…</span>
                    </li>
                @endif
                <li>
                    <a href="{{ $getLink($last) }}" class="{{ $itemBase }} {{ $inactiveClass }}" aria-label="page {{ $last }}">{{ $last }}</a>
                </li>
            @endif

            {{-- Next --}}
            <li>
                @if ($hasMorePages)
                    <a href="{{ $getLink($current + 1) }}" class="{{ $navButtonBase }} {{ $inactiveClass }}" aria-label="next page">
                        {{ $nextLabel }}
                        <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                @else
                    <span class="{{ $navButtonBase }} {{ $disabledClass }}" aria-disabled="true">
                        {{ $nextLabel }}
                        <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </span>
                @endif
            </li>

        </ul>
    </nav>
@endif