@php

    use Juksgraphic\BladeComponents\Components\Navigation\Tabs;
    use Juksgraphic\BladeComponents\Components\Navigation\TabsStyles;

   
    $tabsComponent = new Tabs([
        'id'           => $id       ?? null,
        'variant'      => $variant  ?? 'default',
        'color'        => $color    ?? 'primary',
        'tabs'         => $tabs     ?? [],
        'tabClass'     => $tabClass ?? '',
        'contentClass' => $contentClass ?? '',
    ]);

    
    $styles = new TabsStyles($tabsComponent);
@endphp

<!-- Root tabs container with Alpine.js active tab state -->
<div
    id="{{ $tabsComponent->id() }}"
    x-data="{
        ...{{ $tabsComponent->toJson() }},

        changeTab(index) {
            this.activeTab = index;
            this.$dispatch('jkc-tabs-change', {
                id: '{{ $tabsComponent->id() }}',
                index: index
            });
        }
    }"
    class="{{ $styles->root() }}"
>

    <!-- Tab buttons bar -->
    <div
        class="{{ $styles->tabList() }}"
        role="tablist"
    >
        @foreach ($tabsComponent->tabs() as $index => $tab)
            @php
                $isActive         = $index === $tabsComponent->activeIndex();
                $isDisabled       = $tab['disabled'];
                $activeClassStr   = $styles->button(true, $isDisabled);
                $inactiveClassStr = $styles->button(false, $isDisabled);
            @endphp

            <button
                type="button"
                id="{{ $tabsComponent->id() }}-tab-{{ $index }}"
                role="tab"
                :aria-selected="activeTab === {{ $index }} ? 'true' : 'false'"
                @if ($isDisabled)
                    disabled
                    tabindex="-1"
                @else
                    x-on:click="changeTab({{ $index }})"
                @endif
                :class="activeTab === {{ $index }} ? '{{ $activeClassStr }}' : '{{ $inactiveClassStr }}'"
                class="{{ $styles->button($isActive, $isDisabled) }}"
            >
                @if ($tab['icon'] !== null)
                    <span class="{{ $styles->icon() }}">{!! $tab['icon'] !!}</span>
                @endif

                {{ $tab['label'] }}

                @if ($tab['badge'] !== null)
                    <span class="{{ $styles->badge() }}">
                        {{ $tab['badge'] }}
                    </span>
                @endif

                @if ($tabsComponent->variant() === 'animation')
                    <span
                        x-show="activeTab === {{ $index }}"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="scale-x-0 opacity-0"
                        x-transition:enter-end="scale-x-100 opacity-100"
                        class="{{ $styles->animationBar() }}"
                        style="display: none;"
                    ></span>
                @endif

                @if ($tabsComponent->variant() === 'arrow')
                    <span
                        x-show="activeTab === {{ $index }}"
                        class="{{ $styles->arrowIndicator() }}"
                        style="display: none;"
                    >
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </span>
                @endif
            </button>
        @endforeach
    </div>

    <!-- Tab panels container -->
    <div class="{{ $styles->panelsWrapper() }}">
        @foreach ($tabsComponent->tabs() as $index => $tab)
            @php
                $slotVar      = 'slot_' . $index;
                $panelContent = isset($$slotVar) ? $$slotVar : $tab['content'];
            @endphp

            <div
                id="{{ $tabsComponent->id() }}-panel-{{ $index }}"
                x-show="activeTab === {{ $index }}"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-y-1"
                x-transition:enter-end="opacity-100 translate-y-0"
                role="tabpanel"
                :aria-labelledby="'{{ $tabsComponent->id() }}-tab-{{ $index }}'"
                style="{{ $index !== $tabsComponent->activeIndex() ? 'display: none;' : '' }}"
                class="{{ $styles->panel() }}"
            >
                {!! $panelContent !!}
            </div>
        @endforeach
    </div>

</div>