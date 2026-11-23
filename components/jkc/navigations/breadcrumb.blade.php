@php
 
    use Juksgraphic\BladeComponents\Components\Navigation\BreadCrumb;
    use Juksgraphic\BladeComponents\Components\Navigation\BreadCrumbStyles;

    $breadcrumb = new BreadCrumb([
        'items' => $items ?? [],
        'style' => $style ?? 'chevron',
        'class' => $class ?? '',
        'attrs' => $attrs ?? '',
    ]);

    $styles = new BreadCrumbStyles($breadcrumb);
@endphp

@if (!$breadcrumb->isEmpty())
    <!-- BreadCrumb navigation landmark -->
    <nav class="{{ $styles->container() }}" aria-label="breadcrumb" {!! $breadcrumb->attrs() !!}>
        <ol class="{{ $styles->list() }}">

            @foreach ($breadcrumb->items() as $item)
                @if (!$item['is_last'])
                    <!-- Ancestor breadcrumb step with link and separator -->
                    <li class="{{ $styles->stepItem() }}">
                        <a href="{{ $item['url'] }}" class="{{ $styles->link() }}">
                            @if ($item['icon'] !== null)
                                <span class="{{ $styles->icon() }}">{!! $item['icon'] !!}</span>
                            @endif
                            <span>{{ $item['label'] }}</span>
                        </a>

                        @if ($breadcrumb->style() === 'slash')
                            <span class="{{ $styles->slashSeparator() }}" aria-hidden="true">/</span>
                        @elseif ($breadcrumb->style() === 'bullet')
                            <span class="{{ $styles->bulletSeparator() }}" aria-hidden="true">&bull;</span>
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                aria-hidden="true" stroke-width="2" stroke="currentColor"
                                class="{{ $styles->chevronSeparator() }}">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                            </svg>
                        @endif
                    </li>
                @else
                    <!-- Current active page step -->
                    <li class="{{ $styles->currentItem() }}" aria-current="page">
                        @if ($item['icon'] !== null)
                            <span class="{{ $styles->icon() }}">{!! $item['icon'] !!}</span>
                        @endif
                        <span>{{ $item['label'] }}</span>
                    </li>
                @endif
            @endforeach

        </ol>
    </nav>
@endif
