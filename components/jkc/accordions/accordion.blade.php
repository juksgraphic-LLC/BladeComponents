@php
  
    use Juksgraphic\BladeComponents\Components\Accordions\Accordion;
    use Juksgraphic\BladeComponents\Components\Accordions\AccordionStyles;

    $accordion = new Accordion([
        'id'       => $id    ?? null,
        'type'     => $flush ?? false ? 'flush' : $type ?? 'default',
        'color'    => $color ?? 'primary',
        'multiple' => $multiple ?? false,
        'items'    => $items ?? [],
    ]);
    $styles = new AccordionStyles($accordion);

@endphp

<div id="{{ $accordion->id() }}" x-data="{
    cfg: {{ $accordion->toJson() }},
    active: [],
    nested: [],

    init() {
        this.active = this.cfg.multiple ? [...this.cfg.expanded] :
            this.cfg.expanded.slice(0, 1);
    },

    isOpen(index) {
        return this.active.includes(index);
    },

    toggle(index) {

        if (this.cfg.disabled.includes(index)) return;

        if (this.isOpen(index)) {
            this.active = this.active.filter(i => i !== index);
        } else {
            this.active = this.cfg.multiple ? [...this.active, index] : [index];
        }
        this.$dispatch('jkc-accordion-toggle', {
            id: this.cfg.id,
            index: index,
            isOpen: this.isOpen(index)
        });
    },

    isNestedOpen(key) {
        return this.nested.includes(key);
    },

    toggleNested(key) {
        this.nested = this.isNestedOpen(key) ?
        this.nested.filter(k => k !== key) :
        [...this.nested, key];
    }
}" class="{{ $styles->container() }}">
    @foreach ($accordion->items() as $index => $item)
        @php
            $isDisabled = $item['disabled'];
            $isExpanded = $accordion->isExpanded($index);
            $itemIcon = $item['icon'];
            $hasNested = $item['nested'] !== [];

            $_slotVar    = 'slot_' . $index;
            $itemContent = isset($$_slotVar) ? $$_slotVar : $item['content'];

            $headerId = $accordion->headerId($index);
            $panelId  = $accordion->panelId($index);
        @endphp

        <div class="{{ $styles->item($index) }}">
            {{-- Header --}}
            <button type="button" id="{{ $headerId }}" d
                @if (!$isDisabled) x-on:click="toggle({{ $index }})" @endif
                class="{{ $styles->header($index) }}"
                @if ($accordion->is('custom')) :class="isOpen({{ $index }}) ? '{{ $styles->headerOpen($index) }}' : ''" @endif
                aria-controls="{{ $panelId }}" aria-expanded="{{ $isExpanded ? 'true' : 'false' }}"
                :aria-expanded="isOpen({{ $index }})"
                @if ($isDisabled) disabled aria-disabled="true" @endif>
                <span class="flex items-center gap-3">
                    {{-- Left icon --}}
                    @if ($accordion->is('left-icon') && $itemIcon)
                        <span class="{{ $styles->icon($index) }}">
                            {!! $itemIcon !!}
                        </span>
                    @endif

                    {{-- Icon with rotation --}}
                    @if ($accordion->is('icon') && $itemIcon)
                        <span class="{{ $styles->icon($index) }}"
                            :class="isOpen({{ $index }}) ? 'rotate-180' : ''">
                            {!! $itemIcon !!}
                        </span>
                    @endif

                    {{-- Title --}}
                    <span class="{{ $styles->title($index) }}">{{ $item['title'] }}</span>
                </span>

                {{-- Toggle icon --}}
                <span class="{{ $styles->toggle($index) }}">
                    <span class="block transition-transform duration-200"
                        :class="isOpen({{ $index }}) ? '{{ $styles->toggleRotate() }}' : ''">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                            aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="{{ $styles->togglePath() }}" />
                        </svg>
                    </span>
                </span>
            </button>

            {{-- Content --}}
            <div id="{{ $panelId }}" role="region" aria-labelledby="{{ $headerId }}"
                x-show="isOpen({{ $index }})" x-collapse
                @if (!$isExpanded) style="display: none;" @endif>
                <div class="{{ $styles->content() }}">
                    <div class="pt-1">
                        {!! $itemContent !!}
                    </div>

                    {{-- Nested accordion --}}
                    @if ($hasNested)
                        <div class="mt-3 space-y-2">
                            @foreach ($item['nested'] as $nestedIndex => $nestedItem)
                                @php
                                    $nestedKey = $accordion->nestedKey($index, $nestedIndex);
                                    $nestedPanelId = $accordion->id() . '-nested-' . $nestedKey;
                                @endphp
                                <div
                                    class="rounded-radius border border-outline dark:border-outline-dark overflow-hidden">
                                    <button type="button" x-on:click="toggleNested('{{ $nestedKey }}')"
                                        aria-controls="{{ $nestedPanelId }}" aria-expanded="false"
                                        :aria-expanded="isNestedOpen('{{ $nestedKey }}')"
                                        class="w-full flex items-center justify-between px-4 py-3 text-left text-sm font-medium text-on-surface dark:text-on-surface-dark hover:bg-surface-alt dark:hover:bg-surface-dark-alt transition-colors cursor-pointer">
                                        <span class="flex items-center gap-2">
                                            @if (!empty($nestedItem['icon']))
                                                <span
                                                    class="text-on-surface/50 dark:text-on-surface-dark/50">{!! $nestedItem['icon'] !!}</span>
                                            @endif
                                            {{ $nestedItem['title'] ?? 'Nested Item' }}
                                        </span>
                                        <svg class="w-4 h-4 text-on-surface/50 dark:text-on-surface-dark/50 transition-transform duration-200"
                                            :class="isNestedOpen('{{ $nestedKey }}') ? 'rotate-180' : ''"
                                            fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                    <div id="{{ $nestedPanelId }}" x-show="isNestedOpen('{{ $nestedKey }}')"
                                        x-collapse style="display: none;">
                                        <div
                                            class="px-4 pb-3 text-sm text-on-surface/70 dark:text-on-surface-dark/70 font-paragraph">
                                            {!! $nestedItem['content'] ?? '' !!}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endforeach
</div>
