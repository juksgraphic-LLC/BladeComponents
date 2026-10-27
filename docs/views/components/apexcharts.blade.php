@extends('layout')

@section('content')

    @php
        $rows = [
            [
                'Propriétés & Configuration',
                [
                    ['title', 'string', "''", 'Title displayed at the top of the chart card container.'],
                    [
                        'chartId',
                        'string',
                        "'chart-unique-id'",
                        'Unique DOM element identifier required for ApexCharts rendering.',
                    ],
                    [
                        'options',
                        'array',
                        '[]',
                        'Configuration array passed directly to ApexCharts (includes <code>chart</code>, <code>series</code>, <code>xaxis</code>, <code>colors</code>, etc.).',
                    ],
                ],
            ],
        ];
    @endphp

    <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">

        {{-- ── COLONNE GAUCHE : UTILISATIONS (2/3) ──────────────── --}}
        <section id="utilisations" class="scroll-mt-6 lg:col-span-2">

            <header class="mb-10">
                <p class="font-code text-xs uppercase tracking-wide text-primary dark:text-primary-dark">
                    Data Visualization &amp; Charts
                </p>
                <h1 class="mt-1 font-title text-3xl font-semibold text-on-surface-strong dark:text-on-surface-dark-strong">
                    ApexCharts
                </h1>
                <p class="mt-2 max-w-2xl font-paragraph text-on-surface/70 dark:text-on-surface-dark/70">
                    Interactive, responsive charts powered by ApexCharts. Easily render line, bar, area, pie, donut, polar
                    area, radar, scatter, heatmap, and candlestick visualizations.
                </p>
                <div class="mt-4 flex flex-wrap items-center gap-2">
                    <span
                        class="rounded-full border border-primary/30 bg-primary/10 px-3 py-1 font-code text-xs text-primary dark:border-primary-dark/30 dark:bg-primary-dark/10 dark:text-primary-dark">10+
                        Chart Types</span>
                    <span
                        class="rounded-full border border-outline bg-surface-alt px-3 py-1 font-code text-xs text-on-surface dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark">Responsive
                        &amp; Dark Mode Ready</span>
                </div>
            </header>

            {{-- ── Installation ──────────────────────────────────── --}}
            <div class="mb-8">
                <h2 class="font-title text-xl font-semibold text-on-surface-strong dark:text-on-surface-dark-strong mb-3">
                    Installation
                </h2>
                <p class="font-paragraph text-sm text-on-surface/70 dark:text-on-surface-dark/70 mb-3">
                    Ce composant nécessite la bibliothèque <code>apexcharts</code>. Installez-la via npm dans votre projet :
                </p>
                <div class="rounded-radius border border-outline bg-surface-alt p-4 dark:border-outline-dark dark:bg-surface-dark-alt">
                    <code class="font-code text-sm text-primary dark:text-primary-dark">npm install apexcharts</code>
                </div>
            </div>

            <h2 class="font-title text-2xl font-semibold text-on-surface-strong dark:text-on-surface-dark-strong">
                Usage & Examples
            </h2>
            <p class="mt-2 font-paragraph text-on-surface/70 dark:text-on-surface-dark/70">
                Explorez ci-dessous tous les types de graphiques pris en charge par le composant.
            </p>

            <div class="mt-6 grid grid-cols-1 gap-6">

                {{-- Line Chart Example --}}
                @component('partials.section', [
                    'title' => 'Line Chart',
                    'description' => 'Display trends over time with multiple series support.',
                ])
                    @component('partials.example')
                        @verbatim
                            @include('jkc.charts.apex', [
                                'title' => 'Sales Analytics',
                                'chartId' => 'chart-line-demo',
                                'options' => [
                                    'chart' => ['type' => 'line', 'height' => 300],
                                    'series' => [
                                        ['name' => 'Series A', 'data' => [30, 40, 35, 50, 49, 60, 70]],
                                        ['name' => 'Series B', 'data' => [23, 32, 45, 36, 56, 45, 62]],
                                    ],
                                    'xaxis' => ['categories' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul']],
                                ],
                            ])
                        @endverbatim
                    @endcomponent
                @endcomponent

                {{-- Bar Chart Example --}}
                @component('partials.section', [
                    'title' => 'Bar Chart',
                    'description' => 'Compare categorical data using vertical columns.',
                ])
                    @component('partials.example')
                        @verbatim
                            @include('jkc.charts.apex', [
                                'title' => 'Monthly Revenue',
                                'chartId' => 'chart-bar-demo',
                                'options' => [
                                    'chart' => ['type' => 'bar', 'height' => 300],
                                    'series' => [['name' => 'Revenue', 'data' => [44, 55, 57, 56, 61, 58]]],
                                    'xaxis' => ['categories' => ['Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul']],
                                ],
                            ])
                        @endverbatim
                    @endcomponent
                @endcomponent

                {{-- Area Chart Example --}}
                @component('partials.section', [
                    'title' => 'Area Chart',
                    'description' => 'Visualize volume changes over time with gradient fills.',
                ])
                    @component('partials.example')
                        @verbatim
                            @include('jkc.charts.apex', [
                                'title' => 'Revenue vs Expenses',
                                'chartId' => 'chart-area-demo',
                                'options' => [
                                    'chart' => ['type' => 'area', 'height' => 300],
                                    'series' => [
                                        ['name' => 'Revenue', 'data' => [31, 40, 28, 51, 42, 109, 100]],
                                        ['name' => 'Expenses', 'data' => [11, 32, 45, 32, 34, 52, 41]],
                                    ],
                                    'xaxis' => ['categories' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul']],
                                ],
                            ])
                        @endverbatim
                    @endcomponent
                @endcomponent

                {{-- Column Chart Grouped Example --}}
                @component('partials.section', [
                    'title' => 'Column Chart (Grouped)',
                    'description' => 'Group multiple data series side-by-side using vertical columns.',
                ])
                    @component('partials.example')
                        @verbatim
                            @include('jkc.charts.apex', [
                                'title' => 'Product Performance',
                                'chartId' => 'chart-column-demo',
                                'options' => [
                                    'chart' => ['type' => 'bar', 'height' => 300],
                                    'series' => [
                                        ['name' => 'Product A', 'data' => [44, 55, 57, 56, 61, 58, 63]],
                                        ['name' => 'Product B', 'data' => [76, 85, 101, 98, 87, 105, 91]],
                                    ],
                                    'xaxis' => ['categories' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul']],
                                ],
                            ])
                        @endverbatim
                    @endcomponent
                @endcomponent

                {{-- Pie Chart Example --}}
                @component('partials.section', [
                    'title' => 'Pie Chart',
                    'description' => 'Display parts of a whole in a circular statistical graphic.',
                ])
                    @component('partials.example')
                        @verbatim
                            @include('jkc.charts.apex', [
                                'title' => 'Device Distribution',
                                'chartId' => 'chart-pie-demo',
                                'options' => [
                                    'chart' => ['type' => 'pie', 'height' => 320],
                                    'series' => [44, 55, 41, 17],
                                    'labels' => ['Desktops', 'Tablets', 'Mobile', 'Other'],
                                ],
                            ])
                        @endverbatim
                    @endcomponent
                @endcomponent

                {{-- Donut Chart Example --}}
                @component('partials.section', [
                    'title' => 'Donut Chart',
                    'description' => 'Display proportional data breakdown with a central total label.',
                ])
                    @component('partials.example')
                        @verbatim
                            @include('jkc.charts.apex', [
                                'title' => 'Traffic Sources',
                                'chartId' => 'chart-donut-demo',
                                'options' => [
                                    'chart' => ['type' => 'donut', 'height' => 320],
                                    'series' => [44, 55, 41, 17],
                                    'labels' => ['Desktops', 'Tablets', 'Mobile', 'Other'],
                                ],
                            ])
                        @endverbatim
                    @endcomponent
                @endcomponent

                {{-- Polar Area Chart Example --}}
                @component('partials.section', [
                    'title' => 'Polar Area Chart',
                    'description' => 'Display cyclic data values using polar coordinate sectors.',
                ])
                    @component('partials.example')
                        @verbatim
                            @include('jkc.charts.apex', [
                                'title' => 'Polar Distribution',
                                'chartId' => 'chart-polar-demo',
                                'options' => [
                                    'chart' => ['type' => 'polarArea', 'height' => 340],
                                    'series' => [42, 47, 52, 58, 65],
                                    'labels' => ['Series 1', 'Series 2', 'Series 3', 'Series 4', 'Series 5'],
                                ],
                            ])
                        @endverbatim
                    @endcomponent
                @endcomponent

                {{-- Radar Chart Example --}}
                @component('partials.section', [
                    'title' => 'Radar Chart',
                    'description' => 'Compare multiple quantitative variables across axes.',
                ])
                    @component('partials.example')
                        @verbatim
                            @include('jkc.charts.apex', [
                                'title' => 'Skills Matrix',
                                'chartId' => 'chart-radar-demo',
                                'options' => [
                                    'chart' => ['type' => 'radar', 'height' => 340],
                                    'series' => [
                                        ['name' => 'User A', 'data' => [80, 50, 30, 40, 100, 20]],
                                        ['name' => 'User B', 'data' => [20, 30, 40, 80, 20, 80]],
                                    ],
                                    'xaxis' => ['categories' => ['Eating', 'Drinking', 'Sleeping', 'Designing', 'Coding', 'Cycling']],
                                ],
                            ])
                        @endverbatim
                    @endcomponent
                @endcomponent

                {{-- Scatter Chart Example --}}
                @component('partials.section', [
                    'title' => 'Scatter Chart',
                    'description' => 'Display values for typically two variables as a set of points.',
                ])
                    @component('partials.example')
                        @verbatim
                            @include('jkc.charts.apex', [
                                'title' => 'Correlation Analysis',
                                'chartId' => 'chart-scatter-demo',
                                'options' => [
                                    'chart' => ['type' => 'scatter', 'height' => 300],
                                    'series' => [
                                        ['name' => 'Group A', 'data' => [[16.4, 5.4], [21.7, 2], [25.4, 3], [19, 2]]],
                                        ['name' => 'Group B', 'data' => [[36.4, 13.4], [1.7, 11], [5.4, 8], [9, 17]]],
                                    ],
                                ],
                            ])
                        @endverbatim
                    @endcomponent
                @endcomponent

                {{-- Heatmap Chart Example --}}
                @component('partials.section', [
                    'title' => 'Heatmap Chart',
                    'description' => 'Represent data intensity through color variations across matrices.',
                ])
                    @component('partials.example')
                        @verbatim
                            @include('jkc.charts.apex', [
                                'title' => 'Activity Heatmap',
                                'chartId' => 'chart-heatmap-demo',
                                'options' => [
                                    'chart' => ['type' => 'heatmap', 'height' => 300],
                                    'series' => [
                                        ['name' => 'Mon', 'data' => [['x' => 'Jan', 'y' => 65], ['x' => 'Feb', 'y' => 32], ['x' => 'Mar', 'y' => 87]]],
                                        ['name' => 'Tue', 'data' => [['x' => 'Jan', 'y' => 28], ['x' => 'Feb', 'y' => 73], ['x' => 'Mar', 'y' => 45]]],
                                    ],
                                ],
                            ])
                        @endverbatim
                    @endcomponent
                @endcomponent

                {{-- Candlestick Chart Example --}}
                @component('partials.section', [
                    'title' => 'Candlestick Chart',
                    'description' => 'Display financial price movements (Open, High, Low, Close).',
                ])
                    @component('partials.example')
                        @verbatim
                            @include('jkc.charts.apex', [
                                'title' => 'Stock Price Movement',
                                'chartId' => 'chart-candlestick-demo',
                                'options' => [
                                    'chart' => ['type' => 'candlestick', 'height' => 300],
                                    'series' => [
                                        [
                                            'name' => 'Price',
                                            'data' => [
                                                ['x' => '2024-01-02', 'y' => [150.2, 155.4, 148.5, 153.1]],
                                                ['x' => '2024-01-03', 'y' => [153.1, 158.2, 151.3, 156.8]],
                                                ['x' => '2024-01-04', 'y' => [156.8, 157.5, 152.1, 153.4]],
                                            ],
                                        ],
                                    ],
                                ],
                            ])
                        @endverbatim
                    @endcomponent
                @endcomponent

                {{-- Mixed Chart Example --}}
                @component('partials.section', [
                    'title' => 'Mixed Chart (Line + Column)',
                    'description' => 'Combine multiple chart types (e.g., bar and line) on a single view.',
                ])
                    @component('partials.example')
                        @verbatim
                            @include('jkc.charts.apex', [
                                'title' => 'Revenue & Cash Flow',
                                'chartId' => 'chart-mixed-demo',
                                'options' => [
                                    'chart' => ['type' => 'line', 'height' => 300],
                                    'series' => [
                                        ['name' => 'Revenue', 'type' => 'column', 'data' => [23, 11, 22, 27, 13, 22]],
                                        ['name' => 'Cash Flow', 'type' => 'line', 'data' => [30, 25, 36, 30, 45, 35]],
                                    ],
                                    'xaxis' => ['categories' => ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun']],
                                ],
                            ])
                        @endverbatim
                    @endcomponent
                @endcomponent

            </div>
        </section>

        {{-- ── COLONNE DROITE : PROPS + SOURCE (1/3) ────────────── --}}
        <aside id="proprietes" class="scroll-mt-6 lg:col-span-1">
            <h2 class="font-title text-2xl font-semibold text-on-surface-strong dark:text-on-surface-dark-strong">
                Properties
            </h2>
            <p class="mt-2 font-paragraph text-on-surface/70 dark:text-on-surface-dark/70">
                All configuration properties of the ApexCharts wrapper component.
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
                    'description' => 'Full source of the jkc.charts.apex component view.',
                ])
                    @include('partials.source', ['view' => 'jkc.charts.apex'])
                @endcomponent
            </div>
        </aside>

    </div>
@endsection