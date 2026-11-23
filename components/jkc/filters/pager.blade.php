@use(Juksgraphic\BladeComponents\Components\Filters\Pagers\Pager)
@use(Juksgraphic\BladeComponents\Components\Filters\Pagers\PagerStyles)
@use(Juksgraphic\BladeComponents\BladeComponents)

@if (BladeComponents::shouldInline('jkc-filters'))
    <script>
        {!! BladeComponents::inline('jkc-filters') !!}
    </script>
@endif


@php
    $config = new Pager($options);
    $data   = $config->toArray();
    $style  = PagerStyles::ITEMS;
    $texts  = $data['texts'];
    $uid    = $data['id'];
@endphp

@if ($config->isVisible())
    <div x-data="filterPager({{ $config->toJson() }})" x-show="hasMeta" class="{{ $style['root'] }}" style="display: none;">
        @if ($config->hasSummary() || $config->hasPerPage())
            <div class="{{ $style['start'] }}">
                @if ($config->hasSummary())
                    <p class="{{ $style['summary'] }}" x-text="summary" aria-live="polite"></p>
                @endif

                @if ($config->hasPerPage())
                    <div class="{{ $style['perPage'] }}">
                        <label for="{{ $uid }}-per-page"
                            class="{{ $style['label'] }}">{{ $texts['per_page'] }}</label>
                        <select id="{{ $uid }}-per-page" x-bind:value="perPage"
                            x-bind:disabled="loading" x-on:change="changePerPage($event.target.value)"
                            class="{{ $style['select'] }}">
                            @foreach ($data['perPageOptions'] as $size)
                                <option value="{{ $size }}">{{ $size }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
            </div>
        @endif

        @if ($config->hasPages())
            <nav x-show="hasPages" aria-label="{{ $texts['nav'] }}" class="{{ $style['nav'] }}"
                style="display: none;">
                <ul class="{{ $style['list'] }}">
                    <li>
                        <button type="button" x-on:click="prev()" x-bind:disabled="!canPrev"
                            class="{{ $style['step'] }}">
                            <i class="bi bi-chevron-left" aria-hidden="true"></i> {{ $texts['previous'] }}
                        </button>
                    </li>

                    <template x-for="(entry, index) in pageItems" x-bind:key="index">
                        <li>
                            <span x-show="entry.gap" class="{{ $style['gap'] }}" style="display: none;">…</span>
                            <button type="button" x-show="!entry.gap" x-on:click="goTo(entry.page)"
                                x-bind:disabled="loading || isCurrent(entry.page)"
                                x-bind:aria-current="isCurrent(entry.page) ? 'page' : null"
                                x-bind:aria-label="entry.gap ? null : pageLabel(entry.page)"
                                x-bind:class="isCurrent(entry.page) ? '{{ $style['pageActive'] }}' : '{{ $style['pageIdle'] }}'"
                                class="{{ $style['page'] }}" x-text="entry.page"></button>
                        </li>
                    </template>

                    <li>
                        <button type="button" x-on:click="next()" x-bind:disabled="!canNext"
                            class="{{ $style['step'] }}">
                            {{ $texts['next'] }} <i class="bi bi-chevron-right" aria-hidden="true"></i>
                        </button>
                    </li>
                </ul>
            </nav>
        @endif
    </div>
@endif
