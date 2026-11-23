{{-- Panel layout: sidebar variant. Collapsible below the lg breakpoint. Expects: $config, $data, $style, $texts, $views. --}}
@php
    $panelId = $data['id'] . '-panel';
@endphp

<div
    x-data="filterBar({{ $config->toJson() }})"
    x-on:filter-select-change="setFilter($event.detail.key, $event.detail.value)"
    x-bind:aria-busy="loading"
    role="search"
    class="{{ $style['root'] }}"
>
    <div x-data="{ expanded: false }">
        <div class="flex items-center justify-between gap-2">
            <h2 class="{{ $style['title'] }}">{{ $texts['title'] }}</h2>
            <button
                type="button"
                x-on:click="expanded = ! expanded"
                x-bind:aria-expanded="expanded"
                aria-controls="{{ $panelId }}"
                aria-label="{{ $texts['title'] }}"
                class="inline-flex size-8 items-center justify-center rounded-radius text-on-surface hover:bg-on-surface/10 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary dark:text-on-surface-dark dark:hover:bg-on-surface-dark/10 dark:focus-visible:outline-primary-dark lg:hidden"
            >
                <i class="bi" x-bind:class="expanded ? 'bi-chevron-up' : 'bi-sliders'" aria-hidden="true"></i>
            </button>
        </div>

        <div id="{{ $panelId }}" x-bind:class="expanded ? 'flex' : 'hidden lg:flex'" class="flex-col gap-5 pt-4">
            @include($views['SEARCH'], compact('config', 'data', 'style', 'texts'))
            @include($views['FILTERS'], compact('data', 'style', 'texts', 'views'))
            @include($views['RESET'], compact('style', 'texts'))
        </div>
    </div>
</div>