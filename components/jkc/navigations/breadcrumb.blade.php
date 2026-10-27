@php
    /**
     * Breadcrumb Component — Juksgraphic / Penguin UI
     *
     * Navigation breadcrumb component built with Tailwind CSS.
     * Compatible with Laravel Blade and BladeOne.
     *
     * @param array  $items  Array of items: [{label: string, url?: string, icon?: string}]. Default: [].
     * @param string $style  Visual style variant: chevron|slash|bullet|background. Default: 'chevron'.
     * @param string $class  Extra CSS classes for the container. Default: ''.
     * @param string $attrs  Extra HTML attributes for the container. Default: ''.
     */

    /** @var array<int, array<string, mixed>> $items */
    $items = $items ?? [];

    /** @var string $style */
    $style = (string) ($style ?? 'chevron');

    /** @var string $class */
    $class = $class ?? '';

    /** @var string $attrs */
    $attrs = $attrs ?? '';

    $totalItems = count($items);

    $containerClasses = match ($style) {
        'background' => 'rounded-radius bg-surface-alt dark:bg-surface-dark-alt px-4 py-2.5 text-xs font-medium text-on-surface dark:text-on-surface-dark',
        default => 'text-xs font-medium text-on-surface dark:text-on-surface-dark',
    };
@endphp

@if ($totalItems > 0)
    <nav class="{{ $containerClasses }} {{ $class }}" aria-label="breadcrumb" {!! $attrs !!}>
        <ol class="flex flex-wrap items-center gap-1.5">
            @foreach ($items as $index => $item)
                @php
                    $isLast = $index === $totalItems - 1;
                    $label = $item['label'] ?? '';
                    $icon = $item['icon'] ?? null;

                    $safeUrl = null;
                    if (
                        isset($item['url']) &&
                        is_string($item['url']) &&
                        trim($item['url']) !== '' &&
                        !preg_match('/^\s*(javascript|data|vbscript):/i', $item['url'])
                    ) {
                        $safeUrl = trim($item['url']);
                    }
                @endphp

                @if (!$isLast)
                    {{-- Étapes précédentes avec lien --}}
                    <li class="flex items-center gap-1.5">
                        <a href="{{ $safeUrl ?? '#' }}"
                            class="inline-flex items-center gap-1.5 hover:text-primary dark:hover:text-primary-dark transition-colors">
                            @if ($icon)
                                <span class="shrink-0">{!! $icon !!}</span>
                            @endif
                            <span>{{ $label }}</span>
                        </a>

                        {{-- Séparateurs selon le style --}}
                        @if ($style === 'slash')
                            <span class="opacity-40 select-none font-normal" aria-hidden="true">/</span>
                        @elseif ($style === 'bullet')
                            <span class="opacity-40 select-none font-bold text-xs" aria-hidden="true">•</span>
                        @else
                            {{-- Chevron par défaut (et pour background) --}}
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                aria-hidden="true" stroke-width="2" stroke="currentColor"
                                class="size-3.5 opacity-50 shrink-0">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                            </svg>
                        @endif
                    </li>
                @else
                    {{-- Dernière étape (Page actuelle) --}}
                    <li class="inline-flex items-center gap-1.5 font-bold text-on-surface-strong dark:text-on-surface-dark-strong"
                        aria-current="page">
                        @if ($icon)
                            <span class="shrink-0">{!! $icon !!}</span>
                        @endif
                        <span>{{ $label }}</span>
                    </li>
                @endif
            @endforeach
        </ol>
    </nav>
@endif
