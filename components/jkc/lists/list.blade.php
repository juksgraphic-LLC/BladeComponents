@php
    use Juksgraphic\BladeComponents\Components\Lists\ListGroup;
    use Juksgraphic\BladeComponents\Components\Lists\ListGroupStyles;

   
    $list = new ListGroup([
        'id'       => $id ?? null,
        'type'     => $type ?? 'default',
        'color'    => $color ?? 'primary',
        'active'   => $active ?? null,
        'disabled' => $disabled ?? [],
        'items'    => $items ?? [],
        'class'    => $class ?? '',
        'attrs'    => $attrs ?? '',
    ]);

   
    $styles = new ListGroupStyles($list);
@endphp

<!-- Root list group container -->
<div id="{{ $list->id() }}" class="{{ $styles->container() }}" {!! $list->attrs() !!}>

    @foreach ($list->items() as $index => $item)
        <!-- Opening element tag based on list interaction mode -->
        @if ($list->isType('link'))
            <a href="{{ $item['href'] }}" class="{{ $styles->item($index) }}">
            @elseif ($list->isType('button'))
                <button type="button" @if (!$item['disabled'] && $item['action'] !== null) onclick="{{ $item['action'] }}" @endif
                    class="{{ $styles->item($index) }}">
                @elseif ($list->isType('checkbox'))
                    <label class="{{ $styles->item($index) }}">
                        <input type="checkbox" class="{{ $styles->checkboxInput() }}"
                            {{ $item['active'] ? 'checked' : '' }} {{ $item['disabled'] ? 'disabled' : '' }}>
                    @elseif ($list->isType('radio'))
                        <label class="{{ $styles->item($index) }}">
                            <input type="radio" name="{{ $list->id() }}_radio" class="{{ $styles->radioInput() }}"
                                {{ $item['active'] ? 'checked' : '' }} {{ $item['disabled'] ? 'disabled' : '' }}>
                        @else
                            <div class="{{ $styles->item($index) }}">
        @endif

        <!-- Leading numbered index indicator -->
        @if ($list->isType('numbered'))
            <span class="{{ $styles->numberBadge() }}">
                {{ $item['number'] }}
            </span>
        @endif

        <!-- Leading avatar image for custom cards -->
        @if ($item['avatar'] !== null && $list->isType('custom'))
            <img src="{{ $item['avatar'] }}" alt="" class="{{ $styles->avatar() }}">
        @endif

        <!-- Leading icon for non-custom list variants -->
        @if ($item['icon'] !== null && !$list->isType('custom'))
            <span class="{{ $styles->itemIcon($index) }}">
                {!! $item['icon'] !!}
            </span>
        @endif

        <!-- Item body: rich custom card vs standard single-line label -->
        <div class="min-w-0 flex-1">
            @if ($list->isType('custom'))
                <div class="mb-1 flex items-center justify-between">
                    <h4 class="{{ $styles->customTitle() }}">
                        {{ $item['title'] }}
                    </h4>

                    @if ($item['badge'] !== null)
                        <span class="{{ $styles->badge($index) }}">
                            {{ $item['badge'] }}
                        </span>
                    @endif
                </div>

                @if ($item['subtitle'] !== null)
                    <p class="{{ $styles->customSubtitle() }}">
                        {{ $item['subtitle'] }}
                    </p>
                @endif

                @if ($item['content'] !== null)
                    <p class="{{ $styles->customContent() }}">
                        {!! $item['content'] !!}
                    </p>
                @endif
            @else
                <span class="{{ $styles->itemTitle($index) }}">
                    {{ $item['title'] }}
                </span>
            @endif
        </div>

        <!-- Trailing badge pill for standard list variants -->
        @if ($item['badge'] !== null && !$list->isType('custom'))
            <span class="{{ $styles->badge($index) }}">
                {{ $item['badge'] }}
            </span>
        @endif

        <!-- Trailing chevron indicator for link and button items -->
        @if ($list->isType('link', 'button') && $item['badge'] === null)
            <svg class="{{ $styles->chevron($index) }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
        @endif

        <!-- Closing element tag matching opening mode -->
        @if ($list->isType('link'))
            </a>
        @elseif ($list->isType('button'))
            </button>
        @elseif ($list->isType('checkbox', 'radio'))
            </label>
        @else
</div>
@endif
@endforeach

</div>
