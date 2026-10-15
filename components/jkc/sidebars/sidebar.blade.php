{{--
    Sidebar (blc.sidebar.sidebar)

    Props:
      $items        Array of items (see below).
      $logo         HTML string shown at the top (trusted content only).
      $current      URL path of the page. An item is active when its href equals it.
      $search       bool, show the filter input (default true).
      $searchLabel  placeholder and aria-label of the filter input.
      $navLabel     aria-label of the <nav>.
      $homeHref     link of the logo (default '/').
      $id           prefix for the ids (default 'sidebar').

    Item types:
      ['heading' => 'Title']
      ['label', 'href', 'icon'?, 'active'?, 'badge'?]
      ['label', 'icon'?, 'open'?, 'children' => [['label', 'href', 'active'?, 'badge'?]]]

    'icon' is a Boxicons class string, e.g. 'bx bx-home' (the Boxicons stylesheet must be loaded).
    $logo is raw HTML: pass trusted content only.
    Requires an Alpine ancestor with x-data="{ sidebarIsOpen: false }" and the Alpine collapse plugin.
--}}
@php
    $items = $items ?? [];
    $logo = trim($logo ?? '') !== '' ? $logo : 'Juksgraphic <span class="text-primary dark:text-primary-dark">.</span>';
    $current = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?: '/';
    $search = $search ?? true;
    $searchLabel = $searchLabel ?? 'Search';
    $navLabel = $navLabel ?? 'sidebar navigation';
    $homeHref = $homeHref ?? '/';
    $id = $id ?? 'sidebar';

    $isActive = fn($e) => $e['active'] ?? isset($e['href']) && $e['href'] === $current;

    $topIdle =
        'text-on-surface hover:bg-primary/5 hover:text-on-surface-strong dark:text-on-surface-dark dark:hover:bg-primary-dark/5 dark:hover:text-on-surface-dark-strong';
    $topActive = 'bg-primary/10 text-on-surface-strong dark:bg-primary-dark/10 dark:text-on-surface-dark-strong';
    $topBase =
        'flex items-center rounded-radius gap-2 px-2 py-1.5 text-sm font-medium underline-offset-2 focus-visible:underline focus:outline-hidden';
    $childBase =
        'flex items-center rounded-radius gap-2 px-2 py-1.5 text-sm underline-offset-2 focus:outline-hidden focus-visible:underline';

    // Alpine expressions used by the search filter (q is defined on the <nav>).
    $matchSelf = "q.trim() === '' || \$el.dataset.search.includes(q.toLowerCase().trim())";
    $matchChild = $matchSelf . "  || \$el.closest('[data-group]').dataset.group.includes(q.toLowerCase().trim())";
@endphp

{{-- Dark overlay when the sidebar is open on small screens --}}
<div x-cloak x-show="sidebarIsOpen" class="fixed inset-0 z-20 bg-surface-dark/10 backdrop-blur-xs md:hidden"
    aria-hidden="true" x-on:click="sidebarIsOpen = false" x-transition.opacity></div>

<nav x-cloak x-data="{ q: '' }"
    class="fixed left-0 z-30 flex h-svh w-60 shrink-0 flex-col border-r border-outline bg-surface-alt p-4 transition-transform duration-300 md:w-64 md:translate-x-0 md:relative dark:border-outline-dark dark:bg-surface-dark-alt"
    x-bind:class="sidebarIsOpen ? 'translate-x-0' : '-translate-x-60'" aria-label="{{ $navLabel }}">

    {{-- Logo --}}
    <a href="{{ $homeHref }}"
        class="ml-2 w-fit font-title text-2xl font-bold text-on-surface-strong dark:text-on-surface-dark-strong">
        {!! $logo !!}
    </a>

    {{-- Search --}}
    @if ($search)
        <div class="relative my-4 flex w-full max-w-xs flex-col gap-1 text-on-surface dark:text-on-surface-dark">
            <i class="bx bx-search absolute left-2 top-1/2 -translate-y-1/2 text-xl text-on-surface/50 dark:text-on-surface-dark/50"
                aria-hidden="true"></i>
            <input type="search" x-model="q" name="search" aria-label="{{ $searchLabel }}"
                placeholder="{{ $searchLabel }}"
                class="w-full border border-outline rounded-radius bg-surface px-2 py-1.5 pl-9 text-sm focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary disabled:cursor-not-allowed disabled:opacity-75 dark:border-outline-dark dark:bg-surface-dark/50 dark:focus-visible:outline-primary-dark" />
        </div>
    @endif

    {{-- Links --}}
    <div class="flex flex-1 flex-col gap-2 mt-5 overflow-y-auto pb-6">
        @foreach ($items as $i => $item)
            {{-- Section title --}}
            @if (isset($item['heading']))
                <p
                    class="mt-2 px-2 text-xs font-semibold uppercase tracking-wide text-on-surface/50 dark:text-on-surface-dark/50">
                    {{ $item['heading'] }}</p>

                {{-- Collapsible group --}}
            @elseif (!empty($item['children']))
                @php
                    $gid = $id . '-group-' . $i;
                    $open = !empty($item['open']);
                    $keywords = [mb_strtolower($item['label'])];

                    foreach ($item['children'] as $child) {
                        if ($isActive($child)) {
                            $open = true;
                        }
                        $keywords[] = mb_strtolower($child['label']);
                    }
                @endphp
                <div x-data="{ isExpanded: {{ $open ? 'true' : 'false' }} }" data-group="{{ mb_strtolower($item['label']) }}"
                    data-search="{{ implode('|', $keywords) }}" x-show="{{ $matchSelf }}" class="flex flex-col">
                    <button type="button" x-on:click="isExpanded = ! isExpanded" id="{{ $gid }}-btn"
                        aria-controls="{{ $gid }}" x-bind:aria-expanded="isExpanded ? 'true' : 'false'"
                        class="{{ $topBase }} justify-between"
                        x-bind:class="isExpanded ? '{{ $topActive }}' : '{{ $topIdle }}'">
                        @if (!empty($item['icon']))
                            <i class="{{ $item['icon'] }} shrink-0 text-xl" aria-hidden="true"></i>
                        @endif
                        <span class="mr-auto text-left">{{ $item['label'] }}</span>
                        <i class="bx bx-chevron-down shrink-0 text-xl transition-transform rotate-0"
                            x-bind:class="isExpanded ? 'rotate-180' : 'rotate-0'" aria-hidden="true"></i>
                    </button>

                    <ul x-cloak x-collapse x-show="isExpanded || q.trim() !== ''" id="{{ $gid }}"
                        aria-labelledby="{{ $gid }}-btn">
                        @foreach ($item['children'] as $child)
                            @php $childActive = $isActive($child); @endphp
                            <li data-search="{{ mb_strtolower($child['label']) }}" x-show="{{ $matchChild }}"
                                class="px-1 py-0.5 first:mt-2">
                                <a href="{{ $child['href'] }}"
                                    @if ($childActive) aria-current="page" @endif
                                    class="{{ $childBase }} {{ $childActive ? $topActive : $topIdle }}">
                                    <span>{{ $child['label'] }}</span>
                                    @if (isset($child['badge']))
                                        <span class="ml-auto font-bold">{{ $child['badge'] }}</span>
                                    @endif
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Simple link --}}
            @else
                @php $active = $isActive($item); @endphp
                <a href="{{ $item['href'] }}" data-search="{{ mb_strtolower($item['label']) }}"
                    x-show="{{ $matchSelf }}" @if ($active) aria-current="page" @endif
                    class="{{ $topBase }} {{ $active ? $topActive : $topIdle }}">
                    @if (!empty($item['icon']))
                        <i class="{{ $item['icon'] }} shrink-0 text-xl" aria-hidden="true"></i>
                    @endif
                    <span>{{ $item['label'] }}</span>
                    @if (isset($item['badge']))
                        <span class="ml-auto font-bold">{{ $item['badge'] }}</span>
                    @endif
                </a>
            @endif
        @endforeach
    </div>
</nav>
