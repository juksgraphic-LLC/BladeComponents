@extends('layout')

@section('content')

    @php
        $styles = [
            ['default', 'Standard clean look with square rounded corners and subtle active states.'],
            ['pill', 'Fully rounded circular pill buttons for modern interfaces.'],
            ['bordered', 'Distinct outer borders around every page button for structured grids.'],
            ['minimal', 'Borderless minimal text style with clean active underline accents.'],
        ];

        $rows = [
            [
                'Propriétés & Configuration',
                [
                    ['total', 'int', '50', 'Total number of items to paginate.'],
                    ['current', 'int', '1', 'The current active page number (highlighted automatically).'],
                    ['perPage', 'int', '10', 'Number of items displayed per page.'],
                    [
                        'style',
                        'string',
                        "'default'",
                        'Visual style variant: <code>default</code>, <code>pill</code>, <code>bordered</code>, <code>minimal</code>.',
                    ],
                    ['baseUrl', 'string', "'?page='", 'Base URL pattern or prefix used for constructing page links.'],
                    ['window', 'int', '1', 'Number of surrounding page numbers to display around the current page.'],
                    ['prevLabel', 'string', "'Previous'", 'Accessible text label for the previous button.'],
                    ['nextLabel', 'string', "'Next'", 'Accessible text label for the next button.'],
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
                    Pagination
                </h1>
                <p class="mt-2 max-w-2xl font-paragraph text-on-surface/70 dark:text-on-surface-dark/70">
                    Autonomous pagination component supporting active page highlighting and multiple UI style variants.
                </p>
                <div class="mt-4 flex flex-wrap items-center gap-2">
                    <span
                        class="rounded-full border border-primary/30 bg-primary/10 px-3 py-1 font-code text-xs text-primary dark:border-primary-dark/30 dark:bg-primary-dark/10 dark:text-primary-dark">4
                        UI Styles</span>
                    <span
                        class="rounded-full border border-outline bg-surface-alt px-3 py-1 font-code text-xs text-on-surface dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark">Active
                        Highlighting</span>
                </div>
            </header>

            <h2 class="font-title text-2xl font-semibold text-on-surface-strong dark:text-on-surface-dark-strong">
                Usage & Styles
            </h2>
            <p class="mt-2 font-paragraph text-on-surface/70 dark:text-on-surface-dark/70">
                Explore the different presentation styles available for the pagination component.
            </p>

            <div class="mt-6 grid grid-cols-1 gap-6 md:grid-cols-2">

                {{-- Basic Usage --}}
                <div class="md:col-span-2">
                    @component('partials.section', [
                        'title' => 'Basic Usage',
                        'description' => 'Default pagination style highlighting page 3 out of 10.',
                    ])
                        @component('partials.example')
                            @verbatim
                                @include('jkc.navigations.pagination', [
                                    'total' => 100,
                                    'current' => 3,
                                    'perPage' => 10,
                                    'style' => 'default',
                                ])
                            @endverbatim
                        @endcomponent
                    @endcomponent
                </div>

                {{-- Styles Selector Interactif --}}
                <div class="md:col-span-2">
                    @component('partials.section', [
                        'title' => 'UI Styles',
                        'description' => 'Switch between style variants to preview their appearance.',
                    ])
                        <div x-data="{ paginationStyle: 'default' }">
                            <div class="mb-6 flex flex-wrap gap-2" role="tablist" aria-label="Pagination Styles">
                                @foreach ($styles as $st)
                                    <button type="button" role="tab" @click="paginationStyle = '{{ $st[0] }}'"
                                        :aria-selected="paginationStyle === '{{ $st[0] }}'"
                                        :class="paginationStyle === '{{ $st[0] }}'
                                            ?
                                            'border-primary bg-primary text-on-primary dark:border-primary-dark dark:bg-primary-dark dark:text-on-primary-dark' :
                                            'border-outline bg-transparent text-on-surface/70 hover:border-primary hover:text-primary dark:border-outline-dark dark:text-on-surface-dark/70 dark:hover:border-primary-dark dark:hover:text-primary-dark'"
                                        class="rounded-full border px-3.5 py-1.5 font-code text-xs transition">
                                        {{ $st[0] }}
                                    </button>
                                @endforeach
                            </div>

                            @foreach ($styles as $st)
                                <div x-show="paginationStyle === '{{ $st[0] }}'" x-transition.opacity.duration.150ms>
                                    <p
                                        class="mb-4 font-paragraph text-sm italic text-on-surface/60 dark:text-on-surface-dark/60">
                                        {{ $st[1] }}
                                    </p>
                                    @component('partials.example', ['replace' => ['STYLE_NAME' => $st[0]]])
                                        @verbatim
                                            @include('jkc.navigations.pagination', [
                                                'total' => 80,
                                                'current' => 4,
                                                'perPage' => 10,
                                                'style' => 'STYLE_NAME',
                                            ])
                                        @endverbatim
                                    @endcomponent
                                </div>
                            @endforeach
                        </div>
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
                All configuration properties of the pagination component.
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
                    'description' => 'Full source of the jkc.navigations.pagination component view.',
                ])
                    @include('partials.source', ['view' => 'jkc.navigations.pagination'])
                @endcomponent
            </div>
        </aside>

    </div>
@endsection
