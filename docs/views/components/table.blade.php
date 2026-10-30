@extends('layout')

@php
    /**
     * Demo page of the dynamic table component.
     *
     * Variable names below intentionally differ from the component props (headers, filters, urls, perPage…),
     * because @include inherits the scope of the page.
     *
     * @var array<int, array{0: string, 1: array<int, array{0: string, 1: string, 2: string, 3: string}>}> $propertyGroups
     *      Groups of [name, type, default, description] rows. Descriptions may contain trusted HTML.
     * @var \Closure $buildSearchText Builds the lowercase text matched by the property filter, as a safe JS string.
     */
    $propertyGroups = [
        [
            'Data',
            [
                [
                    'baseRoute',
                    'string',
                    "''",
                    'Base URL of the resource. The trailing slash is removed. Used to derive <code>endpoint</code>.',
                ],
                ['endpoint', 'string', "'{baseRoute}/table'", 'JSON endpoint returning the paginated rows.'],
                [
                    'perPage',
                    'int',
                    '5',
                    'Initial rows per page. Added to the selector (5, 10, 25, 50, 100) when missing.',
                ],
            ],
        ],
        [
            'Columns',
            [
                [
                    'headers',
                    'array',
                    '[]',
                    'Columns as <code>key</code>, <code>label</code>, <code>type</code>, <code>sortable</code>, <code>variants</code>. The key accepts a dotted path such as <code>company.name</code>. Alias: <code>columns</code>.',
                ],
                [
                    'headers[].type',
                    'string',
                    "'text'",
                    'One of <code>text</code>, <code>link</code>, <code>badge</code>, <code>progress</code>, <code>image</code>, <code>avatar_text</code>, <code>boolean</code>, <code>currency</code>, <code>date</code>. Any other value falls back to <code>text</code>.',
                ],
                [
                    'filters',
                    'array',
                    '[]',
                    'Filters as <code>key</code>, <code>label</code>, <code>options</code>. Options is a <code>[value =&gt; label]</code> map, sent as <code>filters[key]</code>. Uses a custom Alpine.js dropdown select.',
                ],
            ],
        ],
        [
            'Actions & Selection',
            [
                [
                    'withAction',
                    'array',
                    '[]',
                    'Enabled row actions: <code>view</code>, <code>edit</code>, <code>delete</code>. Adds an Actions column. <code>view</code> also makes the whole row clickable.',
                ],
                [
                    'selectable',
                    'bool',
                    'false',
                    'Enables checkbox selection column on each row and a select-all master checkbox in the header.',
                ],
            ],
        ],
        [
            'Events',
            [
                [
                    'jkc-table-view',
                    'CustomEvent',
                    '—',
                    'Fired when clicking the view link, button or row, before the navigation. <code>detail</code> holds <code>tableId</code> and <code>item</code>. Cancelable: <code>preventDefault()</code> skips the default behavior.',
                ],
                [
                    'jkc-table-edit',
                    'CustomEvent',
                    '—',
                    'Fired when clicking the edit link or button, before the navigation. <code>detail</code> holds <code>tableId</code> and <code>item</code>. Cancelable: <code>preventDefault()</code> skips the default behavior.',
                ],
                [
                    'jkc-table-delete',
                    'CustomEvent',
                    '—',
                    'Fired when clicking the delete button, before the confirmation modal. <code>detail</code> holds <code>tableId</code> and <code>item</code>. Cancelable: <code>preventDefault()</code> skips the modal and the request.',
                ],
                [
                    'jkc-table-selection-change',
                    'CustomEvent',
                    '—',
                    'Fired when row selections change, and when the selection is cleared because the rows changed. <code>detail</code> holds <code>tableId</code>, <code>selectedIds</code> and <code>selectedItems</code>.',
                ],
                [
                    'jkc-table-refresh',
                    'CustomEvent (command)',
                    '—',
                    'Dispatch it on <code>window</code> to reload a table, keeping its page, search, sort and filters. <code>detail</code> is a <code>tableId</code>; without it every table reloads.',
                ],
            ],
        ],
        [
            'Server Contract',
            [
                [
                    'GET endpoint',
                    'query',
                    '—',
                    'Query string: <code>page</code>, <code>per_page</code>, <code>search</code>, <code>sort</code>, <code>direction</code>, <code>filters[key]</code>.',
                ],
                [
                    'JSON response',
                    'object',
                    '—',
                    'Must contain <code>data</code>, <code>total</code>, <code>current_page</code> and <code>last_page</code>.',
                ],
            ],
        ],
    ];

    $buildSearchText = fn(array $property): string => json_encode(
        strtolower($property[0] . ' ' . strip_tags($property[3])),
        JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP,
    );
@endphp


@section('content')

    <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">

        {{-- ── LEFT COLUMN: USAGES & COMBINATIONS (2/3) ──────────────── --}}
        <section id="utilisations" class="min-w-0 scroll-mt-6 lg:col-span-2">

            <header class="mb-10">
                <p class="font-code text-xs uppercase tracking-wide text-primary dark:text-primary-dark">
                    Data display & Variants
                </p>
                <h1 class="mt-1 font-title text-3xl font-semibold text-on-surface-strong dark:text-on-surface-dark-strong">
                    Dynamic Table Comprehensive Showcase
                </h1>
                <p class="mt-2 max-w-2xl font-paragraph text-on-surface/70 dark:text-on-surface-dark/70">
                    A comprehensive exploration of all possible dynamic table component combinations:
                    server-side management, cell types (multi-variant badges, progress bars, currencies, avatars),
                    multiple selection, and contextual actions.
                </p>
                <div class="mt-4 flex flex-wrap items-center gap-2">
                    <span
                        class="rounded-full border border-primary/30 bg-primary/10 px-3 py-1 font-code text-xs text-primary dark:border-primary-dark/30 dark:bg-primary-dark/10 dark:text-primary-dark">server-side</span>
                    <span
                        class="rounded-full border border-primary/30 bg-primary/10 px-3 py-1 font-code text-xs text-primary dark:border-primary-dark/30 dark:bg-primary-dark/10 dark:text-primary-dark">9
                        cell types</span>
                    <span
                        class="rounded-full border border-outline bg-surface-alt px-3 py-1 font-code text-xs text-on-surface dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark">Alpine.js
                        (nested SRP)</span>
                </div>
            </header>

            <h2 class="font-title text-2xl font-semibold text-on-surface-strong dark:text-on-surface-dark-strong">
                Combinations & Variants
            </h2>
            <p class="mt-2 font-paragraph text-on-surface/70 dark:text-on-surface-dark/70">
                Find below integration examples for each advanced use case.
            </p>

            <div class="mt-6 grid grid-cols-1 gap-6 md:grid-cols-2">

                {{-- 1. Basic Usage --}}
                <div class="min-w-0 md:col-span-2">
                    @component('partials.section', [
                        'title' => '1. Basic Usage',
                        'description' => 'Simple inclusion with a base route and basic text columns.',
                    ])
                        @component('partials.example')
                            @verbatim
                                @include('jkc.tables.table', [
                                    'baseRoute' => '/tables/users',
                                    'endpoint' => '/tables/users/table',
                                    'headers' => [
                                        ['key' => 'name', 'label' => 'Name', 'type' => 'text', 'sortable' => true],
                                        [
                                            'key' => 'email',
                                            'label' => 'Email',
                                            'type' => 'text',
                                            'sortable' => true,
                                        ],
                                        [
                                            'key' => 'city',
                                            'label' => 'City',
                                            'type' => 'text',
                                            'sortable' => false,
                                        ],
                                    ],
                                ])
                            @endverbatim
                        @endcomponent
                    @endcomponent
                </div>

                {{-- 2. Badge Variants --}}
                <div class="min-w-0 md:col-span-2">
                    @component('partials.section', [
                        'title' => '2. Badge Variants (Success, Warning, Danger, Primary, Info, Neutral)',
                        'description' =>
                            'Demonstration of different colors and badge states dynamically managed via column configurations.',
                    ])
                        @component('partials.example')
                            @verbatim
                                @include('jkc.tables.table', [
                                    'baseRoute' => '/tables/users',
                                    'endpoint' => '/tables/users/table',
                                    
                                    'headers' => [
                                        ['key' => 'name', 'label' => 'User', 'type' => 'text', 'sortable' => true],
                                        [
                                            'key' => 'role',
                                            'label' => 'Badge Variants',
                                            'type' => 'badge',
                                            'sortable' => true,
                                            'variants' => [
                                                'admin' => 'primary',
                                                'editor' => 'success',
                                                'viewer' => 'info',
                                                'author' => 'warning',
                                                'banned' => 'danger',
                                            ],
                                            'defaultVariant' => 'neutral',
                                        ],
                                    ],
                                ])
                            @endverbatim
                        @endcomponent
                    @endcomponent
                </div>

                {{-- 3. Progress Bars & Currencies --}}
                <div class="min-w-0 md:col-span-2">
                    @component('partials.section', [
                        'title' => '3. Progress Bars & Currencies (Progress & Currency Variants)',
                        'description' =>
                            'Display of colored progress bars (success, warning, danger, primary, info) and formatted currency amounts with indicators.',
                    ])
                        @component('partials.example')
                            @verbatim
                                @include('jkc.tables.table', [
                                    'baseRoute' => '/tables/orders',
                                    'endpoint' => '/tables/orders/table',
                                    'headers' => [
                                        [
                                            'key' => 'reference',
                                            'label' => 'Ref.',
                                            'type' => 'text',
                                            'sortable' => true,
                                        ],
                                        [
                                            'key' => 'task_progress',
                                            'label' => 'Progress',
                                            'type' => 'progress',
                                            'variant' => 'success',
                                        ],
                                        [
                                            'key' => 'total',
                                            'label' => 'Amount',
                                            'type' => 'currency',
                                            'currency' => 'USD',
                                            'locale' => 'en-US',
                                            'variant' => 'success',
                                        ],
                                    ],
                                ])
                            @endverbatim
                        @endcomponent
                    @endcomponent
                </div>

                {{-- 4. Advanced Types --}}
                <div class="min-w-0 md:col-span-2">
                    @component('partials.section', [
                        'title' => '4. Advanced Types (Avatars, Links, Booleans, Dates)',
                        'description' =>
                            'Combination of rich cells: avatar with subtitle, secure external link, boolean status indicator (active/inactive), and date formatting.',
                    ])
                        @component('partials.example')
                            @verbatim
                                @include('jkc.tables.table', [
                                    'baseRoute' => '/tables/users',
                                    'endpoint' => '/tables/users/table',
                                    'headers' => [
                                        [
                                            'key' => 'name',
                                            'label' => 'Profile',
                                            'type' => 'avatar_text',
                                            'subtitleKey' => 'email',
                                            'sortable' => true,
                                        ],
                                        [
                                            'key' => 'website',
                                            'label' => 'Website',
                                            'type' => 'link',
                                            'sortable' => false,
                                        ],
                                        [
                                            'key' => 'is_verified',
                                            'label' => 'Verified',
                                            'type' => 'boolean',
                                            'sortable' => true,
                                        ],
                                        [
                                            'key' => 'created_at',
                                            'label' => 'Joined',
                                            'type' => 'date',
                                            'dateFormat' => 'medium',
                                            'sortable' => true,
                                        ],
                                    ],
                                ])
                            @endverbatim
                        @endcomponent
                    @endcomponent
                </div>

                {{-- 5. Multiple Selection & Row Actions --}}
                <div class="min-w-0 md:col-span-2">
                    @component('partials.section', [
                        'title' => '5. Multiple Selection (Selectable) & Row Actions',
                        'description' => 'Enabling left-side checkboxes for bulk actions and view / edit / delete buttons on each row.',
                    ])
                        @component('partials.example')
                            @verbatim
                                <div x-data="{ count: 0 }"
                                    x-on:jkc-table-view.window.prevent x-on:jkc-table-edit.window.prevent
                                    x-on:jkc-table-selection-change.window="count = $event.detail.selectedIds.length">
                                    <p class="mb-2 font-code text-xs text-primary" role="status" x-text="count + ' item(s) selected'">
                                    </p>

                                    @include('jkc.tables.table', [
                                        'baseRoute' => '/tables/users/',
                                        'endpoint' => '/tables/users/table',
                                        'selectable' => true,
                                        'withAction' => ['view', 'edit', 'delete'],
                                        'headers' => [
                                            [
                                                'key' => 'name',
                                                'label' => 'Name',
                                                'type' => 'text',
                                                'sortable' => true,
                                            ],
                                            [
                                                'key' => 'role',
                                                'label' => 'Role',
                                                'type' => 'badge',
                                                'sortable' => true,
                                            ],
                                        ],
                                    ])
                                </div>
                            @endverbatim
                        @endcomponent
                    @endcomponent
                </div>

                {{-- 6. Ultimate Combined Table --}}
                <div class="min-w-0 md:col-span-2">
                    @component('partials.section', [
                        'title' => '6. Ultimate Combined Table (All Features)',
                        'description' =>
                            'The ultimate component showcase: text search, sorting, custom dropdown filters, advanced pagination, and a complete set of cell types.',
                    ])
                        @component('partials.example')
                            @verbatim
                                <div x-data x-on:jkc-table-view.window.prevent x-on:jkc-table-edit.window.prevent
                                    x-on:jkc-table-selection-change.window="console.log($event.detail)">
                                    @include('jkc.tables.table', [
                                        'baseRoute' => '/tables/orders',
                                        'endpoint' => '/tables/orders/table',
                                        'perPage' => 10,
                                        'selectable' => true,
                                        'withAction' => ['view', 'edit', 'delete'],
                                        'headers' => [
                                            [
                                                'key' => 'reference',
                                                'label' => 'Ref.',
                                                'type' => 'text',
                                                'sortable' => true,
                                            ],
                                            [
                                                'key' => 'customer',
                                                'label' => 'Customer',
                                                'type' => 'text',
                                                'sortable' => true,
                                            ],
                                            [
                                                'key' => 'status',
                                                'label' => 'Status',
                                                'type' => 'badge',
                                                'sortable' => true,
                                                'variants' => [
                                                    'paid' => 'success',
                                                    'shipped' => 'info',
                                                    'delivered' => 'warning',
                                                    'cancelled' => 'danger',
                                                ],
                                            ],
                                            [
                                                'key' => 'total',
                                                'label' => 'Total',
                                                'type' => 'currency',
                                                'currency' => 'EUR',
                                                'locale' => 'fr-FR',
                                                'sortable' => true,
                                                'rules' => [
                                                    ['max' => 0, 'variant' => 'danger'], 
                                                    ['min' => 500, 'variant' => 'success'], 
                                                    ['min' => 100, 'variant' => 'warning'], 
                                                ],
                                                'variant' => 'neutral',
                                            ],
                                            [
                                                'key' => 'task_progress',
                                                'label' => 'Progress',
                                                'type' => 'progress',
                                                'variant' => 'primary',
                                            ],
                                        ],
                                        'filters' => [
                                            [
                                                'key' => 'status',
                                                'label' => 'Order Status',
                                                'options' => [
                                                    'paid' => 'Paid',
                                                    'shipped' => 'Shipped',
                                                    'delivered' => 'Delivered',
                                                    'cancelled' => 'Cancelled',
                                                ],
                                            ],
                                        ],
                                    ])
                                </div>

                            @endverbatim
                        @endcomponent
                    @endcomponent
                </div>

                {{-- Empty & Error States --}}
                <div class="min-w-0 md:col-span-2">
                    <div>
                        @component('partials.section', [
                            'title' => 'Empty State',
                            'description' => 'No data returned by the endpoint.',
                        ])
                            @component('partials.example')
                                @verbatim
                                    @include('jkc.tables.table', [
                                        'baseRoute' => '/tables/empty',
                                        'endpoint' => '/tables/empty/table',
                                        'headers' => [
                                            [
                                                'key' => 'name',
                                                'label' => 'Name',
                                                'type' => 'text',
                                                'sortable' => false,
                                            ],
                                        ],
                                    ])
                                @endverbatim
                            @endcomponent
                        @endcomponent
                    </div>


                </div>

                <div class="min-w-0 md:col-span-2">
                    @component('partials.section', [
                        'title' => 'Error State',
                        'description' => 'Simulating a 500 server error.',
                    ])
                        @component('partials.example')
                            @verbatim
                                @include('jkc.tables.table', [
                                    'baseRoute' => '/tables/failing',
                                    'endpoint' => '/tables/failing/table',
                                    'headers' => [
                                        [
                                            'key' => 'name',
                                            'label' => 'Name',
                                            'type' => 'text',
                                            'sortable' => false,
                                        ],
                                    ],
                                ])
                            @endverbatim
                        @endcomponent
                    @endcomponent
                </div>

            </div>
        </section>

        {{-- ── RIGHT COLUMN: PROPS + SOURCE (1/3) ────────────── --}}
        <aside id="proprietes" class="min-w-0 scroll-mt-6 lg:col-span-1">
            <h2 class="font-title text-2xl font-semibold text-on-surface-strong dark:text-on-surface-dark-strong">
                Properties & Reference
            </h2>
            <p class="mt-2 font-paragraph text-on-surface/70 dark:text-on-surface-dark/70">
                Exhaustive list of properties, cell types, badge variants, and component events.
            </p>

            <div x-data="{ query: '' }" class="mt-6">
                <div class="relative mb-4">
                    <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-on-surface/40 dark:text-on-surface-dark/40"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                        stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 21l-4.35-4.35M17 10.5a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z" />
                    </svg>
                    <input type="search" x-model="query" placeholder="Filter the properties…"
                        aria-label="Filter the properties"
                        class="w-full rounded-radius border border-outline bg-surface py-2 pl-9 pr-3 font-paragraph text-sm text-on-surface placeholder:text-on-surface/40 focus:border-primary focus:outline-none dark:border-outline-dark dark:bg-surface-dark dark:text-on-surface-dark dark:placeholder:text-on-surface-dark/40 dark:focus:border-primary-dark">
                </div>

                @foreach ($propertyGroups as $propertyGroup)
                    <div
                        class="mb-6 overflow-hidden rounded-radius border border-outline last:mb-0 dark:border-outline-dark">
                        <h3
                            class="border-b border-outline bg-surface-alt px-4 py-2.5 font-title text-sm font-semibold uppercase tracking-wide text-on-surface-strong dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark-strong">
                            {{ $propertyGroup[0] }}
                        </h3>
                        <div>
                            @foreach ($propertyGroup[1] as $property)
                                <div x-show="{{ $buildSearchText($property) }}.includes(query.toLowerCase())"
                                    x-transition.opacity.duration.100ms
                                    class="border-b border-outline px-4 py-3 last:border-b-0 dark:border-outline-dark">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <code
                                            class="rounded bg-primary/10 px-2 py-0.5 font-code text-xs font-semibold text-primary dark:bg-primary-dark/10 dark:text-primary-dark">{{ $property[0] }}</code>
                                        <span
                                            class="font-code text-xs text-on-surface/50 dark:text-on-surface-dark/50">{{ $property[1] }}</span>
                                        <code
                                            class="ml-auto rounded bg-surface-alt px-2 py-0.5 font-code text-xs text-on-surface/60 dark:bg-surface-dark-alt dark:text-on-surface-dark/60">{{ $property[2] }}</code>
                                    </div>
                                    <p
                                        class="mt-1.5 font-paragraph text-xs leading-relaxed text-on-surface/70 dark:text-on-surface-dark/70">
                                        {!! $property[3] !!}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Component source code --}}
            <div id="source" class="mt-8 scroll-mt-6">
                @component('partials.section', [
                    'title' => 'Source Code',
                    'description' => 'Full source of the jkc.tables.table component view.',
                ])
                    @include('partials.source', ['view' => 'jkc.tables.table'])
                @endcomponent
            </div>
        </aside>

    </div>
@endsection