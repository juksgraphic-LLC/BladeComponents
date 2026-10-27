@extends('layout')

@section('content')

    @php
        $styles = [
            ['chevron', 'Classic style using right arrow chevron icons as separators.'],
            ['slash', 'Minimal style using slash (/) characters as separators.'],
            ['bullet', 'Clean style using bullet points (•) as separators.'],
            ['background', 'Enclosed inside a subtle background panel for clean section headers.'],
        ];

        $rows = [
            [
                'Propriétés & Configuration',
                [
                    [
                        'items',
                        'array',
                        '[]',
                        'Array of items: <code>[{label: string, url?: string, icon?: string}]</code>.',
                    ],
                    [
                        'style',
                        'string',
                        "'chevron'",
                        'Visual separator style variant: <code>chevron</code>, <code>slash</code>, <code>bullet</code>, <code>background</code>.',
                    ],
                    ['class', 'string', "''", 'Extra CSS classes for the container element.'],
                    ['attrs', 'string', "''", 'Extra HTML attributes as a trusted string.'],
                ],
            ],
        ];
    @endphp

    <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">

        {{-- ── COLONNE GAUCHE : UTILISATIONS (2/3) ──────────────── --}}
        <section id="utilisations" class="scroll-mt-6 lg:col-span-2">

            <header class="mb-10">
                <p class="font-code text-xs uppercase tracking-wide text-primary dark:text-primary-dark">
                    Navigations
                </p>
                <h1 class="mt-1 font-title text-3xl font-semibold text-on-surface-strong dark:text-on-surface-dark-strong">
                    Breadcrumb
                </h1>
                <p class="mt-2 max-w-2xl font-paragraph text-on-surface/70 dark:text-on-surface-dark/70">
                    Indicate the current page location within a navigational hierarchy with multiple separator styles and
                    optional icons.
                </p>
                <div class="mt-4 flex flex-wrap items-center gap-2">
                    <span
                        class="rounded-full border border-primary/30 bg-primary/10 px-3 py-1 font-code text-xs text-primary dark:border-primary-dark/30 dark:bg-primary-dark/10 dark:text-primary-dark">4
                        UI Styles</span>
                    <span
                        class="rounded-full border border-outline bg-surface-alt px-3 py-1 font-code text-xs text-on-surface dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark">Icons
                        Support</span>
                </div>
            </header>

            <h2 class="font-title text-2xl font-semibold text-on-surface-strong dark:text-on-surface-dark-strong">
                Usage & Styles
            </h2>
            <p class="mt-2 font-paragraph text-on-surface/70 dark:text-on-surface-dark/70">
                Explore the different presentation styles available for the breadcrumb component.
            </p>

            <div class="mt-6 grid grid-cols-1 gap-6 md:grid-cols-2">

                {{-- Basic Usage --}}
                <div class="md:col-span-2">
                    @component('partials.section', [
                        'title' => 'Basic Usage',
                        'description' => 'Default chevron breadcrumb navigation.',
                    ])
                        @component('partials.example')
                            @verbatim
                                @include('jkc.navigations.breadcrumb', [
                                    'items' => [
                                        ['label' => 'Home', 'url' => '#'],
                                        ['label' => 'Components', 'url' => '#'],
                                        ['label' => 'Navigations', 'url' => '#'],
                                        ['label' => 'Breadcrumb'],
                                    ],
                                ])
                            @endverbatim
                        @endcomponent
                    @endcomponent
                </div>

                {{-- Styles Selector Interactif --}}
                <div class="md:col-span-2">
                    @component('partials.section', [
                        'title' => 'UI Styles',
                        'description' => 'Switch between separator style variants to preview their appearance.',
                    ])
                        <div x-data="{ breadcrumbStyle: 'chevron' }">
                            <div class="mb-6 flex flex-wrap gap-2" role="tablist" aria-label="Breadcrumb Styles">
                                @foreach ($styles as $st)
                                    <button type="button" role="tab" @click="breadcrumbStyle = '{{ $st[0] }}'"
                                        :aria-selected="breadcrumbStyle === '{{ $st[0] }}'"
                                        :class="breadcrumbStyle === '{{ $st[0] }}'
                                            ?
                                            'border-primary bg-primary text-on-primary dark:border-primary-dark dark:bg-primary-dark dark:text-on-primary-dark' :
                                            'border-outline bg-transparent text-on-surface/70 hover:border-primary hover:text-primary dark:border-outline-dark dark:text-on-surface-dark/70 dark:hover:border-primary-dark dark:hover:text-primary-dark'"
                                        class="rounded-full border px-3.5 py-1.5 font-code text-xs transition">
                                        {{ $st[0] }}
                                    </button>
                                @endforeach
                            </div>

                            @foreach ($styles as $st)
                                <div x-show="breadcrumbStyle === '{{ $st[0] }}'" x-transition.opacity.duration.150ms>
                                    <p
                                        class="mb-4 font-paragraph text-sm italic text-on-surface/60 dark:text-on-surface-dark/60">
                                        {{ $st[1] }}
                                    </p>
                                    @component('partials.example', ['replace' => ['STYLE_NAME' => $st[0]]])
                                        @verbatim
                                            @include('jkc.navigations.breadcrumb', [
                                                'style' => 'STYLE_NAME',
                                                'items' => [
                                                    ['label' => 'Dashboard', 'url' => '#'],
                                                    ['label' => 'Settings', 'url' => '#'],
                                                    ['label' => 'Profile'],
                                                ],
                                            ])
                                        @endverbatim
                                    @endcomponent
                                </div>
                            @endforeach
                        </div>
                    @endcomponent
                </div>

                {{-- Breadcrumb with Icons --}}
                <div class="md:col-span-2">
                    @component('partials.section', [
                        'title' => 'With Icons',
                        'description' => 'Pass custom SVGs into items for icon support.',
                    ])
                        @component('partials.example')
                            @verbatim
                                @include('jkc.navigations.breadcrumb', [
                                    'style' => 'background',
                                    'items' => [
                                        [
                                            'label' => 'Home',
                                            'url' => '#',
                                            'icon' =>
                                                '<svg class="size-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>',
                                        ],
                                        ['label' => 'Projects', 'url' => '#'],
                                        ['label' => 'Blade Components'],
                                    ],
                                ])
                            @endverbatim
                        @endcomponent
                    @endcomponent
                </div>

            </div>
        </section>

        {{-- ── COLONNE DROITE : PROPS + SOURCE (1/3) ────────────── --}}
        <aside id="proprietes" class="scroll-mt-6 lg:col-span-1">
            <h2 class="font-title text-2xl font-semibold text-on-surface-strong dark:text-on-surface-dark-strong">
                Properties
            </h2>
            <p class="mt-2 font-paragraph text-on-surface/70 dark:text-on-surface-dark/70">
                All configuration properties of the breadcrumb component.
            </p>

            <div x-data="{ query: '' }" class="mt-6">
                <div class="relative mb-4">
                    <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-on-surface/40 dark:text-on-surface-dark/40"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-4.35-4.35M17 10.5a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z" />
                    </svg>
                    <input type="search" x-model="query" placeholder="Filter properties…" aria-label="Filter properties"
                        class="w-full rounded-radius border border-outline bg-surface py-2 pl-9 pr-3 font-paragraph text-sm text-on-surface placeholder:text-on-surface/40 focus:border-primary focus:outline-none dark:border-outline-dark dark:bg-surface-dark dark:text-on-surface-dark dark:placeholder:text-on-surface-dark/40 dark:focus:border-primary-dark">
                </div>

                @foreach ($rows as $group)
                    <div
                        class="mb-6 overflow-hidden rounded-radius border border-outline last:mb-0 dark:border-outline-dark">
                        <h3
                            class="border-b border-outline bg-surface-alt px-4 py-2.5 font-title text-sm font-semibold uppercase tracking-wide text-on-surface-strong dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark-strong">
                            {{ $group[0] }}
                        </h3>
                        <div>
                            @foreach ($group[1] as $row)
                                <div x-show="'{{ strtolower($row[0]) }} {{ strtolower(strip_tags($row[3])) }}'.includes(query.toLowerCase())"
                                    x-transition.opacity.duration.100ms
                                    class="border-b border-outline px-4 py-3 last:border-b-0 dark:border-outline-dark">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <code
                                            class="rounded bg-primary/10 px-2 py-0.5 font-code text-xs font-semibold text-primary dark:bg-primary-dark/10 dark:text-primary-dark">{{ $row[0] }}</code>
                                        <span
                                            class="font-code text-xs text-on-surface/50 dark:text-on-surface-dark/50">{{ $row[1] }}</span>
                                        <code
                                            class="ml-auto rounded bg-surface-alt px-2 py-0.5 font-code text-xs text-on-surface/60 dark:bg-surface-dark-alt dark:text-on-surface-dark/60">{{ $row[2] }}</code>
                                    </div>
                                    <p
                                        class="mt-1.5 font-paragraph text-xs leading-relaxed text-on-surface/70 dark:text-on-surface-dark/70">
                                        {!! $row[3] !!}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <div id="source" class="mt-8 scroll-mt-6">
                @component('partials.section', [
                    'title' => 'Code source',
                    'description' => 'Full source of the jkc.navigations.breadcrumb component view.',
                ])
                    @include('partials.source', ['view' => 'jkc.navigations.breadcrumb'])
                @endcomponent
            </div>
        </aside>

    </div>
@endsection
