@php
/**
 * Accordion component — Juksgraphic / Penguin UI
 *
 * Reusable accordion built with Tailwind CSS and Alpine.js.
 * Works with Laravel Blade and BladeOne.
 *
 * @param string      $type      Layout variant: default|flush|always-open|icon|plus|arrow|left-icon|custom|nested. Default: 'default'.
 * @param array       $items     Array of items [{title, content, icon, disabled, expanded, color, nested}]. Default: [].
 * @param string      $color     Color variant: primary|secondary|success|danger|warning|info|light|dark. Default: 'primary'.
 * @param bool        $multiple  Allow multiple panels open simultaneously. Default: false.
 * @param bool        $flush     Remove borders and background. Default: false.
 * @param string|null $id        HTML id / Event target identifier. Default: null.
 */

/** @var string $type */
$type = (string) ($type ?? 'default');

/** @var array<int, array{title?: string, content?: string, icon?: string, disabled?: bool, expanded?: bool, color?: string, nested?: array}> $items */
$items = $items ?? [];

/** @var string $color */
$color = (string) ($color ?? 'primary');

/** @var bool $multiple */
$multiple = (bool) ($multiple ?? false);

/** @var bool $flush */
$flush = (bool) ($flush ?? false);

/** @var string|null $safeId */
$safeId = isset($id) ? preg_replace('/[^A-Za-z0-9_-]/', '', (string) $id) : 'jkc-accordion-' . bin2hex(random_bytes(4));

/** @var array<string, string> $colorMap */
$colorMap = [
    'primary'   => 'text-primary border-primary/20 bg-primary/5 dark:text-primary-dark dark:border-primary-dark/20 dark:bg-primary-dark/5',
    'secondary' => 'text-secondary border-secondary/20 bg-secondary/5 dark:text-secondary-dark dark:border-secondary-dark/20 dark:bg-secondary-dark/5',
    'success'   => 'text-success border-success/20 bg-success/5',
    'danger'    => 'text-danger border-danger/20 bg-danger/5',
    'warning'   => 'text-warning border-warning/20 bg-warning/5',
    'info'      => 'text-info border-info/20 bg-info/5',
    'light'     => 'text-on-surface border-outline bg-surface-alt dark:text-on-surface-dark dark:border-outline-dark dark:bg-surface-dark-alt',
    'dark'      => 'text-on-surface-strong border-outline-strong bg-surface dark:text-on-surface-dark-strong dark:border-outline-dark-strong dark:bg-surface-dark',
];

/** @var array<string, string> $textColor */
$textColor = [
    'primary'   => 'text-primary dark:text-primary-dark',
    'secondary' => 'text-secondary dark:text-secondary-dark',
    'success'   => 'text-success',
    'danger'    => 'text-danger',
    'warning'   => 'text-warning',
    'info'      => 'text-info',
    'light'     => 'text-on-surface dark:text-on-surface-dark',
    'dark'      => 'text-on-surface-strong dark:text-on-surface-dark-strong',
];

$activeColor = $colorMap[$color] ?? $colorMap['primary'];
$titleColor = $textColor[$color] ?? $textColor['primary'];

$containerClasses = match ($type) {
    'flush'  => '',
    'nested' => 'space-y-2',
    default  => 'rounded-radius border border-outline dark:border-outline-dark overflow-hidden bg-surface dark:bg-surface-dark',
};

$itemClasses = match ($type) {
    'flush'  => 'border-b border-outline dark:border-outline-dark last:border-0',
    'nested' => 'rounded-radius border border-outline dark:border-outline-dark overflow-hidden bg-surface-alt dark:bg-surface-dark-alt',
    default  => 'border-b border-outline dark:border-outline-dark last:border-0',
};

$headerBase = 'w-full flex items-center justify-between px-5 py-4 text-left font-title font-medium text-sm transition-all duration-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-primary/20';

$headerClasses = match ($type) {
    'flush', 'icon', 'left-icon', 'custom', 'plus', 'arrow' => $headerBase . ' text-on-surface dark:text-on-surface-dark hover:bg-surface-alt dark:hover:bg-surface-dark-alt',
     default => $headerBase . ' text-on-surface dark:text-on-surface-dark hover:bg-surface-alt dark:hover:bg-surface-dark-alt',
    'nested' => $headerBase . ' text-on-surface dark:text-on-surface-dark hover:bg-surface dark:hover:bg-surface-dark',
};

$contentBase = 'overflow-hidden transition-all duration-300 ease-in-out';
$contentClasses = 'px-5 pb-4 text-sm text-on-surface/80 dark:text-on-surface-dark/80 font-paragraph bg-surface dark:bg-surface-dark';
@endphp

<div 
    id="{{ $safeId }}"
    x-data="{
        active: {{ $multiple ? '[]' : 'null' }},
        init() {
            @foreach ($items as $i => $item)
                @if ($item['expanded'] ?? false)
                    @if ($multiple)
                        this.active.push({{ $i }});
                    @else
                        this.active = {{ $i }};
                    @endif
                @endif
            @endforeach
        },
        isOpen(index) {
            return {{ $multiple ? 'this.active.includes(index)' : 'this.active === index' }};
        },
        toggle(index) {
            if ({{ $multiple ? 'true' : 'false' }}) {
                if (this.active.includes(index)) {
                    this.active = this.active.filter(i => i !== index);
                } else {
                    this.active.push(index);
                }
            } else {
                this.active = this.active === index ? null : index;
            }
            this.$dispatch('jkc-accordion-toggle', { id: '{{ $safeId }}', index: index, isOpen: this.isOpen(index) });
        }
    }"
    class="{{ $containerClasses }}"
>
    @foreach ($items as $index => $item)
        @php
            $itemColor  = $item['color'] ?? null;
            $isDisabled = $item['disabled'] ?? false;
            $itemIcon   = $item['icon'] ?? null;
            $itemTitle  = $item['title'] ?? 'Item ' . ($index + 1);
            $hasNested  = isset($item['nested']) && is_array($item['nested']);

            $_slotVar    = 'slot_' . $index;
            $itemContent = isset($$_slotVar) ? $$_slotVar : ($item['content'] ?? '');

            $currentActiveColor = $itemColor ? $colorMap[$itemColor] ?? $activeColor : $activeColor;
            $currentTitleColor  = $itemColor ? $textColor[$itemColor] ?? $titleColor : $titleColor;

            $headerTypeClass = '';

            if ($type === 'custom') {
                $headerTypeClass = 'rounded-radius border ' . $currentActiveColor;
            }
            if ($isDisabled) {
                $headerTypeClass .= ' opacity-50 cursor-not-allowed pointer-events-none';
            }
        @endphp

        <div class="{{ $itemClasses }} {{ trim($headerTypeClass) }}">
            {{-- Header --}}
            <button 
                type="button" 
                @if (!$isDisabled) x-on:click="toggle({{ $index }})" @endif
                class="{{ $headerClasses }} {{ $isDisabled ? '' : 'cursor-pointer' }}"
                :class="isOpen({{ $index }}) ? '{{ $type === 'custom' ? $currentActiveColor : '' }}' : ''"
                aria-expanded="false" 
                :aria-expanded="isOpen({{ $index }}).toString()"
            >
                <span class="flex items-center gap-3">
                    {{-- Left Icon --}}
                    @if ($type === 'left-icon' && $itemIcon)
                        <span class="flex-shrink-0 {{ $currentTitleColor }}">
                            {!! $itemIcon !!}
                        </span>
                    @endif

                    {{-- Icon with rotation --}}
                    @if ($type === 'icon' && $itemIcon)
                        <span class="flex-shrink-0 transition-transform duration-200 {{ $currentTitleColor }}"
                            :class="isOpen({{ $index }}) ? 'rotate-180' : ''">
                            {!! $itemIcon !!}
                        </span>
                    @endif

                    {{-- Title --}}
                    <span class="{{ $type === 'custom' && ($itemColor || $color !== 'primary') ? $currentTitleColor : '' }}">
                        {{ $itemTitle }}
                    </span>
                </span>

                {{-- Toggle Icons --}}
                <span class="flex-shrink-0 {{ $type === 'custom' ? $currentTitleColor : 'text-on-surface/50 dark:text-on-surface-dark/50' }}">
                    @if ($type === 'plus')
                        <span class="block transition-transform duration-200" :class="isOpen({{ $index }}) ? 'rotate-45' : ''">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                        </span>
                    @elseif($type === 'arrow')
                        <span class="block transition-transform duration-200" :class="isOpen({{ $index }}) ? 'rotate-90' : ''">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </span>
                    @else
                        <span class="block transition-transform duration-200" :class="isOpen({{ $index }}) ? 'rotate-180' : ''">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </span>
                    @endif
                </span>
            </button>

            {{-- Content --}}
            <div 
                x-show="isOpen({{ $index }})" 
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 max-h-0" 
                x-transition:enter-end="opacity-100 max-h-[500px]"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 max-h-[500px]" 
                x-transition:leave-end="opacity-0 max-h-0"
                class="{{ $contentBase }} {{ $type === 'flush' ? 'px-5 pb-4 text-sm text-on-surface/80 dark:text-on-surface-dark/80 font-paragraph' : $contentClasses }}"
                style="display: none;"
            >
                <div class="pt-1">
                    {!! $itemContent !!}
                </div>

                {{-- Nested Accordion --}}
                @if ($hasNested)
                    <div class="mt-3 space-y-2">
                        @foreach ($item['nested'] as $nestedItem)
                            <div class="rounded-radius border border-outline dark:border-outline-dark overflow-hidden">
                                <button type="button"
                                    x-on:click="$event.stopPropagation(); $el.nextElementSibling.classList.toggle('hidden')"
                                    class="w-full flex items-center justify-between px-4 py-3 text-left text-sm font-medium text-on-surface dark:text-on-surface-dark hover:bg-surface-alt dark:hover:bg-surface-dark-alt transition-colors">
                                    <span class="flex items-center gap-2">
                                        @if (isset($nestedItem['icon']))
                                            <span class="text-on-surface/50 dark:text-on-surface-dark/50">{!! $nestedItem['icon'] !!}</span>
                                        @endif
                                        {{ $nestedItem['title'] ?? 'Nested Item' }}
                                    </span>
                                    <svg class="w-4 h-4 text-on-surface/50 dark:text-on-surface-dark/50" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                <div class="hidden px-4 pb-3 text-sm text-on-surface/70 dark:text-on-surface-dark/70 font-paragraph">
                                    {!! $nestedItem['content'] ?? '' !!}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    @endforeach
</div>