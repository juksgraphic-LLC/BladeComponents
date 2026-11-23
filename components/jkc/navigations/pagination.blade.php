@php
    use Juksgraphic\BladeComponents\Components\Navigation\Pagination;
    use Juksgraphic\BladeComponents\Components\Navigation\PaginationStyles;


    $pagination = new Pagination([
        'total'     => $total   ?? 50,
        'current'   => $current ?? 1,
        'perPage'   => $perPage ?? 10,
        'baseUrl'   => $baseUrl ?? '?page=',
        'window'    => $window  ?? 1,
        'style'     => $style   ?? 'default',
        'class'     => $class   ?? '',
        'attrs'     => $attrs   ?? '',
        'prevLabel' => $prevLabel ?? 'Previous',
        'nextLabel' => $nextLabel ?? 'Next',
    ]);

    $styles = new PaginationStyles($pagination);
@endphp

@if ($pagination->hasPages())
    <!-- Pagination navigation landmark -->
    <nav aria-label="pagination" class="{{ $styles->nav() }}" {!! $pagination->attrs() !!}>
        <ul class="{{ $styles->list() }}">

            <!-- Previous page control -->
            <li>
                @if ($pagination->onFirstPage())
                    <span class="{{ $styles->navButton(true) }}" aria-disabled="true">
                        <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        {{ $pagination->prevLabel() }}
                    </span>
                @else
                    <a href="{{ $pagination->linkFor($pagination->current() - 1) }}"
                        class="{{ $styles->navButton(false) }}" aria-label="previous page">
                        <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        {{ $pagination->prevLabel() }}
                    </a>
                @endif
            </li>

            <!-- First page shortcut and leading ellipsis -->
            @if ($pagination->start() > 1)
                <li>
                    <a href="{{ $pagination->linkFor(1) }}" class="{{ $styles->inactivePage() }}"
                        aria-label="page 1">1</a>
                </li>

                @if ($pagination->showLeadingEllipsis())
                    <li>
                        <span class="{{ $styles->ellipsis() }}" aria-hidden="true">&hellip;</span>
                    </li>
                @endif
            @endif

            <!-- Surrounding page window -->
            @for ($page = $pagination->start(); $page <= $pagination->end(); $page++)
                <li>
                    @if ($page === $pagination->current())
                        <span class="{{ $styles->activePage() }}" aria-current="page"
                            aria-label="page {{ $page }}">{{ $page }}</span>
                    @else
                        <a href="{{ $pagination->linkFor($page) }}" class="{{ $styles->inactivePage() }}"
                            aria-label="page {{ $page }}">{{ $page }}</a>
                    @endif
                </li>
            @endfor

            <!-- Trailing ellipsis and last page shortcut -->
            @if ($pagination->end() < $pagination->last())
                @if ($pagination->showTrailingEllipsis())
                    <li>
                        <span class="{{ $styles->ellipsis() }}" aria-hidden="true">&hellip;</span>
                    </li>
                @endif

                <li>
                    <a href="{{ $pagination->linkFor($pagination->last()) }}" class="{{ $styles->inactivePage() }}"
                        aria-label="page {{ $pagination->last() }}">{{ $pagination->last() }}</a>
                </li>
            @endif

            <!-- Next page control -->
            <li>
                @if ($pagination->hasMorePages())
                    <a href="{{ $pagination->linkFor($pagination->current() + 1) }}"
                        class="{{ $styles->navButton(false) }}" aria-label="next page">
                        {{ $pagination->nextLabel() }}
                        <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </a>
                @else
                    <span class="{{ $styles->navButton(true) }}" aria-disabled="true">
                        {{ $pagination->nextLabel() }}
                        <svg class="size-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </span>
                @endif
            </li>

        </ul>
    </nav>
@endif
