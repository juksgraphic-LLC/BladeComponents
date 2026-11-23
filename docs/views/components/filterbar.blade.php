@extends('layout')

@section('content')

    @php
        $variants = [
            ['inline', 'One row of controls. This is the default variant.'],
            ['compact', 'Tighter row with smaller controls and spacing.'],
            ['stacked', 'Full-width column with a visible label above each filter.'],
            ['sidebar', 'Sticky side panel, collapsible below the lg breakpoint.'],
        ];

        $rows = [
            [
                'Bar',
                [
                    ['endpoint', 'string', 'required', 'Data endpoint URL. Called with <code>page</code>, <code>per_page</code>, <code>search</code>, <code>sort</code>, <code>direction</code> and <code>filters[key]</code>.'],
                    ['id', 'string|null', 'auto', 'Identifier used by events and commands, generated as <code>filterbar-xxxxxxxx</code> when empty. Set it when a pager or a display must follow the bar.'],
                    ['variant', 'string', 'inline', 'One of <code>inline</code>, <code>compact</code>, <code>stacked</code>, <code>sidebar</code>.'],
                    ['perPage', 'int', '10', 'Items requested per page (minimum 1).'],
                    ['autoload', 'bool', 'true', 'Fetch the endpoint when the bar initializes.'],
                    ['search', 'bool|array', 'false', 'Enables the search field. An array accepts <code>placeholder</code> and <code>debounce</code> keys.'],
                    ['sort', 'string|array|null', 'null', 'Initial sort: a column name, or an array with <code>column</code> and <code>direction</code> keys. The bar has no sort UI, use the set-sort command.'],
                    ['texts', 'array', 'French', 'Separate include variable, not part of <code>options</code>. Keys: <code>title</code>, <code>reset</code>, <code>search</code>, <code>empty</code>, <code>clear</code>, <code>all</code>, <code>selected</code>.'],
                ],
            ],
            [
                'Filter definition',
                [
                    ['key', 'string', 'required', 'Query key sent as <code>filters[key]</code>. Letters, digits and <code>_ . -</code> only. Must be unique.'],
                    ['label', 'string', 'key', 'Visible label in stacked and sidebar variants, accessible name elsewhere.'],
                    ['type', 'string', 'select', '<code>select</code> or <code>multiselect</code>. Multiselect sends <code>filters[key][]</code>.'],
                    ['options', 'array', '[]', 'Map of value to label, or a list of arrays with <code>value</code> and <code>label</code> keys.'],
                    ['default', 'string|array|null', 'null', 'Value sent on the first request.'],
                ],
            ],
            [
                'Pager',
                [
                    ['bar', 'string', 'required', 'Id of the bar to follow.'],
                    ['id', 'string|null', 'auto', 'DOM id prefix, generated as <code>pager-xxxxxxxx</code>.'],
                    ['perPageOptions', 'int[]', '[10, 25, 50]', 'Page size choices. Keep the bar <code>perPage</code> in this list. An empty array hides the select.'],
                    ['siblings', 'int', '2', 'Pages kept on each side of the current page.'],
                    ['summary', 'bool', 'true', 'Shows the summary text.'],
                    ['perPage', 'bool', 'true', 'Shows the page size select.'],
                    ['pages', 'bool', 'true', 'Shows the page buttons.'],
                    ['texts', 'array', 'French', 'Keys: <code>summary</code>, <code>per_page</code>, <code>nav</code>, <code>previous</code>, <code>next</code>, <code>page</code>. Placeholders: {from}, {to}, {total}, {page}.'],
                ],
            ],
            [
                'Response contract',
                [
                    ['data', 'array', 'required', 'Items of the current page.'],
                    ['current_page, last_page, per_page, total', 'int', 'required', 'Read from the root of the body, or from a <code>meta</code> object.'],
                    ['from, to', 'int', 'optional', 'Used by the default summary text. When missing, the summary shows 0, so override <code>texts.summary</code>.'],
                ],
            ],
            [
                'Events (emitted on window)',
                [
                    ['filterbar:loading', 'event', 'out', 'Request started. Detail: <code>barId</code>, <code>url</code>.'],
                    ['filterbar:loaded', 'event', 'out', 'Detail: <code>barId</code>, <code>items</code>, <code>meta</code>, <code>raw</code>, <code>url</code>, <code>sort</code>.'],
                    ['filterbar:error', 'event', 'out', 'Detail: <code>barId</code>, <code>status</code>, <code>message</code>, <code>url</code>.'],
                    ['filterbar:change', 'event', 'out', 'Filters, search or sort changed. Detail: <code>barId</code>, <code>filters</code>, <code>search</code>, <code>sort</code>, <code>query</code>.'],
                    ['filterbar:reset', 'event', 'out', 'Filters and search cleared. Detail: <code>barId</code>.'],
                ],
            ],
            [
                'Commands (received on window)',
                [
                    ['filterbar:set-page', 'command', 'in', 'Detail: <code>barId</code>, <code>page</code>.'],
                    ['filterbar:set-per-page', 'command', 'in', 'Detail: <code>barId</code>, <code>perPage</code>. Goes back to page 1.'],
                    ['filterbar:set-sort', 'command', 'in', 'Detail: <code>barId</code>, <code>sort</code>, <code>direction</code>. An empty <code>sort</code> clears the sort.'],
                    ['filterbar:refresh', 'command', 'in', 'Detail: <code>barId</code>. Reloads with the current state.'],
                ],
            ],
        ];
    @endphp

    <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">

        {{-- ── COLONNE GAUCHE : UTILISATIONS (2/3) ──────────────── --}}
        <section id="utilisations" class="scroll-mt-6 lg:col-span-2">

            <header class="mb-10">
                <p class="font-code text-xs uppercase tracking-wide text-primary dark:text-primary-dark">
                    Components
                </p>
                <h1 class="mt-1 font-title text-3xl font-semibold text-on-surface-strong dark:text-on-surface-dark-strong">
                    Filter bar
                </h1>
                <p class="mt-2 max-w-2xl font-paragraph text-on-surface/70 dark:text-on-surface-dark/70">
                    Server-driven listing: a bar for search and filters, a pager, and any markup you like to display the data. They only talk through window events, so the same bar can feed a table, cards or a list.
                </p>
                <div class="mt-4 flex flex-wrap items-center gap-2">
                    <span class="rounded-full border border-primary/30 bg-primary/10 px-3 py-1 font-code text-xs text-primary dark:border-primary-dark/30 dark:bg-primary-dark/10 dark:text-primary-dark">4 variants</span>
                    <span class="rounded-full border border-primary/30 bg-primary/10 px-3 py-1 font-code text-xs text-primary dark:border-primary-dark/30 dark:bg-primary-dark/10 dark:text-primary-dark">5 display styles</span>
                    <span class="rounded-full border border-outline bg-surface-alt px-3 py-1 font-code text-xs text-on-surface dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark">Alpine.js requis</span>
                    <span class="rounded-full border border-outline bg-surface-alt px-3 py-1 font-code text-xs text-on-surface dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark">/tables/users/table</span>
                </div>
            </header>

            <h2 class="font-title text-2xl font-semibold text-on-surface-strong dark:text-on-surface-dark-strong">
                Usage
            </h2>
            <p class="mt-2 font-paragraph text-on-surface/70 dark:text-on-surface-dark/70">
                The bar fetches, the pager navigates, the display listens. Every example below calls the same users endpoint.
            </p>

            <div class="mt-6 grid grid-cols-1 gap-6 md:grid-cols-2">

                {{-- Basic Usage --}}
                <div class="md:col-span-2">
                    @component('partials.section', [
                        'title' => 'Basic Usage',
                        'description' => 'A bar, a minimal list that listens to filterbar:loaded, and a pager. The bar id links the three.',
                    ])
                        @component('partials.example')
                            @verbatim
                                @include('jkc.filters.bar', [
                                    'options' => [
                                        'id' => 'demo-basic',
                                        'endpoint' => '/tables/users/table',
                                        'perPage' => 5,
                                        'search' => ['placeholder' => 'Rechercher un utilisateur…'],
                                        'filters' => [
                                            [
                                                'key' => 'role',
                                                'label' => 'Rôle',
                                                'options' => [
                                                    'admin' => 'Admin',
                                                    'editor' => 'Éditeur',
                                                    'author' => 'Auteur',
                                                    'viewer' => 'Lecteur',
                                                ],
                                            ],
                                            [
                                                'key' => 'status',
                                                'label' => 'Statut',
                                                'options' => [
                                                    'active' => 'Actif',
                                                    'pending' => 'En attente',
                                                    'suspended' => 'Suspendu',
                                                ],
                                            ],
                                            [
                                                'key' => 'city',
                                                'label' => 'Ville',
                                                'type' => 'multiselect',
                                                'options' => [
                                                    'Abidjan' => 'Abidjan',
                                                    'Paris' => 'Paris',
                                                    'Lyon' => 'Lyon',
                                                    'Marseille' => 'Marseille',
                                                    'Montréal' => 'Montréal',
                                                ],
                                            ],
                                        ],
                                    ],
                                ])

                                <ul
                                    x-data="{
                                        rows: [],
                                        init() {
                                            window.addEventListener('filterbar:loaded', (e) => {
                                                if (e.detail.barId === 'demo-basic') this.rows = e.detail.items;
                                            });
                                        },
                                    }"
                                    class="mt-4 divide-y divide-outline rounded-radius border border-outline text-sm text-on-surface dark:divide-outline-dark dark:border-outline-dark dark:text-on-surface-dark"
                                >
                                    <template x-for="row in rows" x-bind:key="row.id">
                                        <li class="flex items-center justify-between gap-4 px-4 py-3">
                                            <span class="font-medium" x-text="row.name"></span>
                                            <span class="truncate opacity-70" x-text="row.email"></span>
                                        </li>
                                    </template>
                                </ul>

                                <div class="mt-4">
                                    @include('jkc.filters.pager', [
                                        'options' => [
                                            'bar' => 'demo-basic',
                                            'perPageOptions' => [5, 10, 25],
                                            'texts' => ['summary' => '{total} utilisateurs'],
                                        ],
                                    ])
                                </div>
                            @endverbatim
                        @endcomponent
                    @endcomponent
                </div>

                {{-- Display styles --}}
                <div class="md:col-span-2">
                    @component('partials.section', [
                        'title' => 'Display styles',
                        'description' => 'One bar and one pager, five ways to render the same items. Column headers of the table send the set-sort command.',
                    ])
                        @component('partials.example')
                            @verbatim
                                <div
                                    x-data="{
                                        barId: 'demo-views',
                                        view: 'table',
                                        views: [['table', 'Table'], ['cards', 'Cards'], ['list', 'List'], ['grid', 'Avatars'], ['json', 'JSON']],
                                        columns: [['name', 'Utilisateur'], ['city', 'Ville'], ['company.name', 'Société'], ['status', 'Statut'], ['created_at', 'Création']],
                                        rows: [],
                                        busy: true,
                                        failure: null,
                                        order: { column: null, direction: 'asc' },
                                        badge: {
                                            active: 'bg-success/10 text-success',
                                            pending: 'bg-warning/10 text-warning',
                                            suspended: 'bg-danger/10 text-danger',
                                        },
                                        init() {
                                            const mine = (event) => event.detail.barId === this.barId;

                                            window.addEventListener('filterbar:loading', (e) => {
                                                if (mine(e)) { this.busy = true; this.failure = null; }
                                            });
                                            window.addEventListener('filterbar:loaded', (e) => {
                                                if (mine(e)) { this.rows = e.detail.items; this.order = e.detail.sort; this.busy = false; }
                                            });
                                            window.addEventListener('filterbar:error', (e) => {
                                                if (mine(e)) { this.failure = e.detail.message; this.busy = false; }
                                            });
                                        },
                                        sortBy(column) {
                                            const next = this.order.column === column && this.order.direction === 'asc' ? 'desc' : 'asc';

                                            window.dispatchEvent(new CustomEvent('filterbar:set-sort', {
                                                detail: { barId: this.barId, sort: column, direction: next },
                                            }));
                                        },
                                        day(value) {
                                            return new Date(value).toLocaleDateString('fr-FR');
                                        },
                                    }"
                                    class="flex flex-col gap-4"
                                >
                                    @include('jkc.filters.bar', [
                                        'options' => [
                                            'id' => 'demo-views',
                                            'endpoint' => '/tables/users/table',
                                            'perPage' => 6,
                                            'variant' => 'compact',
                                            'search' => true,
                                            'filters' => [
                                                [
                                                    'key' => 'status',
                                                    'label' => 'Statut',
                                                    'options' => ['active' => 'Actif', 'pending' => 'En attente', 'suspended' => 'Suspendu'],
                                                ],
                                                [
                                                    'key' => 'role',
                                                    'label' => 'Rôle',
                                                    'type' => 'multiselect',
                                                    'options' => ['admin' => 'Admin', 'editor' => 'Éditeur', 'author' => 'Auteur', 'viewer' => 'Lecteur'],
                                                ],
                                            ],
                                        ],
                                    ])

                                    <div class="flex flex-wrap gap-2" role="tablist" aria-label="Display styles">
                                        <template x-for="tab in views" x-bind:key="tab[0]">
                                            <button
                                                type="button"
                                                role="tab"
                                                x-on:click="view = tab[0]"
                                                x-bind:aria-selected="view === tab[0]"
                                                x-bind:class="view === tab[0]
                                                    ? 'border-primary bg-primary text-on-primary dark:border-primary-dark dark:bg-primary-dark dark:text-on-primary-dark'
                                                    : 'border-outline text-on-surface/70 hover:border-primary hover:text-primary dark:border-outline-dark dark:text-on-surface-dark/70 dark:hover:border-primary-dark dark:hover:text-primary-dark'"
                                                class="rounded-full border px-3.5 py-1.5 font-code text-xs transition"
                                                x-text="tab[1]"
                                            ></button>
                                        </template>
                                    </div>

                                    <div x-bind:class="busy && 'opacity-60'" class="text-sm text-on-surface transition-opacity dark:text-on-surface-dark">
                                        <p x-show="failure" x-text="failure" role="alert" class="mb-3 rounded-radius border border-danger bg-danger/10 p-3 text-danger" style="display: none;"></p>
                                        <p x-show="!busy && !failure && rows.length === 0" class="rounded-radius border border-dashed border-outline p-6 text-center opacity-70 dark:border-outline-dark" style="display: none;">
                                            Aucun utilisateur ne correspond à ces critères.
                                        </p>

                                        {{-- Table --}}
                                        <div x-show="view === 'table' && rows.length > 0" class="overflow-x-auto rounded-radius border border-outline dark:border-outline-dark" style="display: none;">
                                            <table class="w-full text-left">
                                                <thead class="border-b border-outline bg-surface-alt text-xs text-on-surface-strong dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark-strong">
                                                    <tr>
                                                        <template x-for="col in columns" x-bind:key="col[0]">
                                                            <th
                                                                class="p-3 font-semibold"
                                                                x-bind:aria-sort="order.column === col[0] ? (order.direction === 'asc' ? 'ascending' : 'descending') : 'none'"
                                                            >
                                                                <button type="button" x-on:click="sortBy(col[0])" class="inline-flex items-center gap-1.5 hover:text-primary dark:hover:text-primary-dark">
                                                                    <span x-text="col[1]"></span>
                                                                    <i
                                                                        class="bi text-[10px]"
                                                                        x-bind:class="order.column === col[0] ? (order.direction === 'asc' ? 'bi-arrow-up' : 'bi-arrow-down') : 'bi-arrow-down-up opacity-40'"
                                                                        aria-hidden="true"
                                                                    ></i>
                                                                </button>
                                                            </th>
                                                        </template>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-outline dark:divide-outline-dark">
                                                    <template x-for="row in rows" x-bind:key="row.id">
                                                        <tr class="hover:bg-surface-alt/60 dark:hover:bg-surface-dark-alt/60">
                                                            <td class="p-3">
                                                                <div class="flex items-center gap-3">
                                                                    <img x-bind:src="row.avatar" alt="" loading="lazy" class="size-9 rounded-full" />
                                                                    <div class="min-w-0">
                                                                        <p class="truncate font-medium text-on-surface-strong dark:text-on-surface-dark-strong" x-text="row.name"></p>
                                                                        <p class="truncate text-xs opacity-70" x-text="row.email"></p>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td class="p-3" x-text="row.city"></td>
                                                            <td class="p-3" x-text="row.company ? row.company.name : ''"></td>
                                                            <td class="p-3">
                                                                <span class="rounded-full px-2 py-0.5 text-xs font-medium" x-bind:class="badge[row.status]" x-text="row.status"></span>
                                                            </td>
                                                            <td class="whitespace-nowrap p-3" x-text="day(row.created_at)"></td>
                                                        </tr>
                                                    </template>
                                                </tbody>
                                            </table>
                                        </div>

                                        {{-- Cards --}}
                                        <div x-show="view === 'cards'" class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3" style="display: none;">
                                            <template x-for="row in rows" x-bind:key="row.id">
                                                <article class="flex flex-col gap-4 rounded-radius border border-outline bg-surface-alt p-4 dark:border-outline-dark dark:bg-surface-dark-alt">
                                                    <header class="flex items-center gap-3">
                                                        <img x-bind:src="row.avatar" alt="" loading="lazy" class="size-12 rounded-full" />
                                                        <div class="min-w-0">
                                                            <h3 class="truncate font-semibold text-on-surface-strong dark:text-on-surface-dark-strong" x-text="row.name"></h3>
                                                            <p class="truncate text-xs opacity-70" x-text="row.email"></p>
                                                        </div>
                                                    </header>
                                                    <dl class="grid grid-cols-2 gap-2 text-xs">
                                                        <div>
                                                            <dt class="opacity-60">Ville</dt>
                                                            <dd class="font-medium" x-text="row.city"></dd>
                                                        </div>
                                                        <div>
                                                            <dt class="opacity-60">Société</dt>
                                                            <dd class="font-medium" x-text="row.company ? row.company.name : ''"></dd>
                                                        </div>
                                                    </dl>
                                                    <footer class="flex items-center justify-between gap-2 text-xs">
                                                        <span class="rounded-full px-2 py-0.5 font-medium" x-bind:class="badge[row.status]" x-text="row.status"></span>
                                                        <span class="capitalize opacity-70" x-text="row.role"></span>
                                                    </footer>
                                                </article>
                                            </template>
                                        </div>

                                        {{-- List --}}
                                        <ul x-show="view === 'list' && rows.length > 0" class="divide-y divide-outline rounded-radius border border-outline dark:divide-outline-dark dark:border-outline-dark" style="display: none;">
                                            <template x-for="row in rows" x-bind:key="row.id">
                                                <li class="flex items-center gap-3 p-3">
                                                    <img x-bind:src="row.avatar" alt="" loading="lazy" class="size-8 rounded-full" />
                                                    <div class="min-w-0 flex-1">
                                                        <p class="truncate font-medium text-on-surface-strong dark:text-on-surface-dark-strong" x-text="row.name"></p>
                                                        <p class="truncate text-xs opacity-70" x-text="row.website"></p>
                                                    </div>
                                                    <span class="hidden rounded-full px-2 py-0.5 text-xs font-medium sm:inline" x-bind:class="badge[row.status]" x-text="row.status"></span>
                                                    <span class="whitespace-nowrap text-xs opacity-70" x-text="day(row.created_at)"></span>
                                                </li>
                                            </template>
                                        </ul>

                                        {{-- Avatars --}}
                                        <div x-show="view === 'grid'" class="grid grid-cols-2 gap-4 sm:grid-cols-3 md:grid-cols-6" style="display: none;">
                                            <template x-for="row in rows" x-bind:key="row.id">
                                                <div class="flex flex-col items-center gap-2 text-center">
                                                    <img x-bind:src="row.avatar" alt="" loading="lazy" class="size-16 rounded-full" />
                                                    <p class="w-full truncate text-xs font-medium text-on-surface-strong dark:text-on-surface-dark-strong" x-text="row.name"></p>
                                                    <p class="text-xs capitalize opacity-70" x-text="row.role"></p>
                                                </div>
                                            </template>
                                        </div>

                                        {{-- JSON --}}
                                        <pre
                                            x-show="view === 'json'"
                                            x-text="JSON.stringify(rows.map(({ avatar, ...rest }) => rest), null, 2)"
                                            class="max-h-96 overflow-auto rounded-radius border border-outline bg-surface-alt p-4 font-code text-xs dark:border-outline-dark dark:bg-surface-dark-alt"
                                            style="display: none;"
                                        ></pre>
                                    </div>

                                    @include('jkc.filters.pager', [
                                        'options' => [
                                            'bar' => 'demo-views',
                                            'perPageOptions' => [6, 12, 24],
                                            'texts' => ['summary' => '{total} utilisateurs'],
                                        ],
                                    ])
                                </div>
                            @endverbatim
                        @endcomponent
                    @endcomponent
                </div>

                {{-- Variants --}}
                <div class="md:col-span-2">
                    @component('partials.section', [
                        'title' => 'Variants',
                        'description' => 'Four bar styles. The line under each bar shows the last request it sent.',
                    ])
                        <div x-data="{ variant: 'inline' }">
                            <div class="mb-5 flex flex-wrap gap-2" role="tablist" aria-label="Bar variants">
                                @foreach ($variants as $v)
                                    <button type="button" role="tab" x-on:click="variant = '{{ $v[0] }}'"
                                        x-bind:aria-selected="variant === '{{ $v[0] }}'"
                                        x-bind:class="variant === '{{ $v[0] }}'
                                            ? 'border-primary bg-primary text-on-primary dark:border-primary-dark dark:bg-primary-dark dark:text-on-primary-dark'
                                            : 'border-outline bg-transparent text-on-surface/70 hover:border-primary hover:text-primary dark:border-outline-dark dark:text-on-surface-dark/70 dark:hover:border-primary-dark dark:hover:text-primary-dark'"
                                        class="rounded-full border px-3.5 py-1.5 font-code text-xs transition">
                                        {{ $v[0] }}
                                    </button>
                                @endforeach
                            </div>

                            @foreach ($variants as $v)
                                <div x-show="variant === '{{ $v[0] }}'" x-transition.opacity.duration.150ms>
                                    <p class="mb-4 font-paragraph text-sm italic text-on-surface/60 dark:text-on-surface-dark/60">
                                        {{ $v[1] }}
                                    </p>
                                    @component('partials.example', ['replace' => ['TYPE_NAME' => $v[0]]])
                                        @verbatim
                                            @include('jkc.filters.bar', [
                                                'options' => [
                                                    'id' => 'demo-TYPE_NAME',
                                                    'variant' => 'TYPE_NAME',
                                                    'endpoint' => '/tables/users/table',
                                                    'perPage' => 1,
                                                    'search' => true,
                                                    'filters' => [
                                                        [
                                                            'key' => 'role',
                                                            'label' => 'Rôle',
                                                            'options' => ['admin' => 'Admin', 'editor' => 'Éditeur', 'author' => 'Auteur', 'viewer' => 'Lecteur'],
                                                        ],
                                                        [
                                                            'key' => 'status',
                                                            'label' => 'Statut',
                                                            'options' => ['active' => 'Actif', 'pending' => 'En attente', 'suspended' => 'Suspendu'],
                                                        ],
                                                    ],
                                                ],
                                            ])

                                            <p
                                                x-data="{
                                                    url: 'Aucune requête',
                                                    init() {
                                                        window.addEventListener('filterbar:loading', (e) => {
                                                            if (e.detail.barId === 'demo-TYPE_NAME') this.url = decodeURIComponent(e.detail.url);
                                                        });
                                                    },
                                                }"
                                                x-text="url"
                                                class="mt-4 break-all font-code text-xs text-on-surface/70 dark:text-on-surface-dark/70"
                                            ></p>
                                        @endverbatim
                                    @endcomponent
                                </div>
                            @endforeach
                        </div>
                    @endcomponent
                </div>

                {{-- Events & commands --}}
                <div class="md:col-span-2">
                    @component('partials.section', [
                        'title' => 'Événements & commandes',
                        'description' => 'The log lists every event emitted by the bar. The buttons send commands back to it.',
                    ])
                        @component('partials.example')
                            @verbatim
                                <div
                                    x-data="{
                                        barId: 'demo-events',
                                        log: [],
                                        commands: [
                                            ['Refresh', 'refresh', {}],
                                            ['Page 1', 'set-page', { page: 1 }],
                                            ['Page 2', 'set-page', { page: 2 }],
                                            ['5 / page', 'set-per-page', { perPage: 5 }],
                                            ['10 / page', 'set-per-page', { perPage: 10 }],
                                            ['Tri nom A-Z', 'set-sort', { sort: 'name', direction: 'asc' }],
                                            ['Tri nom Z-A', 'set-sort', { sort: 'name', direction: 'desc' }],
                                            ['Effacer le tri', 'set-sort', { sort: '' }],
                                        ],
                                        init() {
                                            ['change', 'loading', 'loaded', 'error', 'reset'].forEach((name) => {
                                                window.addEventListener('filterbar:' + name, (e) => {
                                                    if (e.detail.barId !== this.barId) return;

                                                    this.log = [
                                                        { name, at: new Date().toLocaleTimeString('fr-FR'), info: this.describe(name, e.detail) },
                                                        ...this.log,
                                                    ].slice(0, 10);
                                                });
                                            });
                                        },
                                        describe(name, detail) {
                                            if (name === 'loaded') return detail.items.length + ' items, page ' + detail.meta.current_page + '/' + detail.meta.last_page;
                                            if (name === 'loading') return decodeURIComponent(detail.url);
                                            if (name === 'change') return decodeURIComponent(detail.query);
                                            if (name === 'error') return detail.message;
                                            return '';
                                        },
                                        send(command, detail) {
                                            window.dispatchEvent(new CustomEvent('filterbar:' + command, {
                                                detail: Object.assign({ barId: this.barId }, detail),
                                            }));
                                        },
                                    }"
                                    class="flex flex-col gap-4"
                                >
                                    @include('jkc.filters.bar', [
                                        'options' => [
                                            'id' => 'demo-events',
                                            'endpoint' => '/tables/users/table',
                                            'perPage' => 5,
                                            'search' => true,
                                            'filters' => [
                                                [
                                                    'key' => 'status',
                                                    'label' => 'Statut',
                                                    'options' => ['active' => 'Actif', 'pending' => 'En attente', 'suspended' => 'Suspendu'],
                                                ],
                                            ],
                                        ],
                                    ])

                                    <div class="flex flex-wrap gap-2">
                                        <template x-for="command in commands" x-bind:key="command[0]">
                                            <button
                                                type="button"
                                                x-on:click="send(command[1], command[2])"
                                                x-text="command[0]"
                                                class="rounded-radius border border-outline px-3 py-1.5 text-xs text-on-surface hover:bg-on-surface/10 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary dark:border-outline-dark dark:text-on-surface-dark dark:hover:bg-on-surface-dark/10 dark:focus-visible:outline-primary-dark"
                                            ></button>
                                        </template>
                                    </div>

                                    <ol class="divide-y divide-outline rounded-radius border border-outline font-code text-xs text-on-surface dark:divide-outline-dark dark:border-outline-dark dark:text-on-surface-dark" aria-live="polite">
                                        <template x-for="(entry, index) in log" x-bind:key="entry.at + entry.name + index">
                                            <li class="flex items-baseline gap-3 px-3 py-2">
                                                <span class="shrink-0 opacity-50" x-text="entry.at"></span>
                                                <span class="w-14 shrink-0 font-semibold text-primary dark:text-primary-dark" x-text="entry.name"></span>
                                                <span class="min-w-0 break-all opacity-80" x-text="entry.info"></span>
                                            </li>
                                        </template>
                                        <li x-show="log.length === 0" class="px-3 py-2 opacity-60" style="display: none;">Aucun événement pour le moment.</li>
                                    </ol>
                                </div>
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
                Options of the bar and the pager, the response contract, the window events and commands, followed by the source of both views.
            </p>

            <div x-data="{ query: '' }" class="mt-6">
                <div class="relative mb-4">
                    <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-on-surface/40 dark:text-on-surface-dark/40"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10.5a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z" />
                    </svg>
                    <input type="search" x-model="query" placeholder="Filter the properties…" aria-label="Filter the properties"
                        class="w-full rounded-radius border border-outline bg-surface py-2 pl-9 pr-3 font-paragraph text-sm text-on-surface placeholder:text-on-surface/40 focus:border-primary focus:outline-none dark:border-outline-dark dark:bg-surface-dark dark:text-on-surface-dark dark:placeholder:text-on-surface-dark/40 dark:focus:border-primary-dark">
                </div>

                @foreach ($rows as $group)
                    <div class="mb-6 overflow-hidden rounded-radius border border-outline last:mb-0 dark:border-outline-dark">
                        <h3 class="border-b border-outline bg-surface-alt px-4 py-2.5 font-title text-sm font-semibold uppercase tracking-wide text-on-surface-strong dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark-strong">
                            {{ $group[0] }}
                        </h3>
                        <div>
                            @foreach ($group[1] as $row)
                                <div x-show="'{{ strtolower($row[0]) }} {{ strtolower(strip_tags($row[3])) }}'.includes(query.toLowerCase())"
                                    x-transition.opacity.duration.100ms
                                    class="border-b border-outline px-4 py-3 last:border-b-0 dark:border-outline-dark">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <code class="rounded bg-primary/10 px-2 py-0.5 font-code text-xs font-semibold text-primary dark:bg-primary-dark/10 dark:text-primary-dark">{{ $row[0] }}</code>
                                        <span class="font-code text-xs text-on-surface/50 dark:text-on-surface-dark/50">{{ $row[1] }}</span>
                                        <code class="ml-auto rounded bg-surface-alt px-2 py-0.5 font-code text-xs text-on-surface/60 dark:bg-surface-dark-alt dark:text-on-surface-dark/60">{{ $row[2] }}</code>
                                    </div>
                                    <p class="mt-1.5 font-paragraph text-xs leading-relaxed text-on-surface/70 dark:text-on-surface-dark/70">
                                        {!! $row[3] !!}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Code source des composants --}}
            <div id="source" class="mt-8 scroll-mt-6">
                @component('partials.section', [
                    'title' => 'Code source : bar',
                    'description' => 'Full source of the jkc.filters.bar component view.',
                ])
                    @include('partials.source', ['view' => 'jkc.filters.bar'])
                @endcomponent
            </div>

            <div id="source-pager" class="mt-8 scroll-mt-6">
                @component('partials.section', [
                    'title' => 'Code source : pager',
                    'description' => 'Full source of the jkc.filters.pager component view.',
                ])
                    @include('partials.source', ['view' => 'jkc.filters.pager'])
                @endcomponent
            </div>
        </aside>

    </div>
@endsection