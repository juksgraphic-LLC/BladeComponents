{{-- Row layout: inline, compact and stacked variants. Expects: $config, $data, $style, $texts, $views. --}}
<div
    x-data="filterBar({{ $config->toJson() }})"
    x-on:filter-select-change="setFilter($event.detail.key, $event.detail.value)"
    x-bind:aria-busy="loading"
    role="search"
    class="{{ $style['root'] }}"
>
    @include($views['SEARCH'],  compact('config', 'data', 'style', 'texts'))
    @include($views['FILTERS'], compact('data', 'style', 'texts', 'views'))
    @include($views['RESET'],   compact('style', 'texts'))
</div>