@extends('layout')

@php
    $types = [
        'default',
        'flush',
        'horizontal',
        'link',
        'button',
        'custom',
        'numbered',
        'icon',
        'checkbox',
        'radio',
        'badge',
        'contextual',
        'colored',
    ];

    $rows = [
        [
            'Apparence',
            [
                [
                    'type',
                    'string',
                    'default',
                    'One of <code>default</code>, <code>flush</code>, <code>horizontal</code>, <code>link</code>, <code>button</code>, <code>custom</code>, <code>numbered</code>, <code>icon</code>, <code>checkbox</code>, <code>radio</code>, <code>badge</code>, <code>contextual</code>, <code>colored</code>.',
                ],
                [
                    'color',
                    'string',
                    'primary',
                    'Color variant for contextual/colored lists: <code>primary</code>, <code>secondary</code>, <code>success</code>, <code>danger</code>, <code>warning</code>, <code>info</code>, <code>light</code>, <code>dark</code>.',
                ],
                ['class', 'string', "''", 'Extra CSS classes for the container element.'],
                ['attrs', 'string', "''", 'Extra HTML attributes as a trusted string.'],
            ],
        ],
        [
            'Contenu & États',
            [
                [
                    'items',
                    'array',
                    '[]',
                    'Array of items: <code>[{title, content, icon, badge, badgeColor, disabled, active, color, subtitle, avatar, action, href}]</code>.',
                ],
                ['active', 'int|null', 'null', 'Index of the active item in the list.'],
                ['disabled', 'array', '[]', 'Array of disabled item indices.'],
                ['id', 'string|null', 'null', 'HTML id identifier for the container element.'],
            ],
        ],
    ];
@endphp

@section('content')



    {{-- ══════════════════════════════════════════════════════════
         DEUX COLONNES CÔTE À CÔTE
         Gauche : Utilisations (2/3)  |  Droite : Props + Source (1/3)
         Sur petit écran : empilés
    ══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">

        {{-- ── COLONNE GAUCHE : UTILISATIONS (2/3) ──────────────── --}}
        <section id="utilisations" class="scroll-mt-6 lg:col-span-2">

            {{-- ── En-tête ─────────────────────────────────────────────── --}}
            <header class="mb-10">
                <p class="font-code text-xs uppercase tracking-wide text-primary dark:text-primary-dark">
                    Containers &amp; Navigation
                </p>
                <h1 class="mt-1 font-title text-3xl font-semibold text-on-surface-strong dark:text-on-surface-dark-strong">
                    List Group
                </h1>
                <p class="mt-2 max-w-2xl font-paragraph text-on-surface/70 dark:text-on-surface-dark-strong/70">
                    Display a series of items in a comprehensive list format. Supports links, buttons, checkboxes, badges,
                    icons, custom layouts, and contextual color themes.
                </p>
                <div class="mt-4 flex flex-wrap items-center gap-2">
                    <span
                        class="rounded-full border border-primary/30 bg-primary/10 px-3 py-1 font-code text-xs text-primary dark:border-primary-dark/30 dark:bg-primary-dark/10 dark:text-primary-dark">13
                        types</span>
                    <span
                        class="rounded-full border border-outline bg-surface-alt px-3 py-1 font-code text-xs text-on-surface dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark">Tailwind
                        CSS &amp; Alpine.js ready</span>
                </div>
            </header>

            <h2 class="font-title text-2xl font-semibold text-on-surface-strong dark:text-on-surface-dark-strong">
                Usage
            </h2>
            <p class="mt-2 font-paragraph text-on-surface/70 dark:text-on-surface-dark/70">
                The component in action: basic lists, flush layouts, interactive links, badges, and advanced custom designs.
            </p>

            <div class="mt-6 grid grid-cols-1 gap-6 md:grid-cols-2">

                {{-- Basic Usage — pleine largeur --}}
                <div class="md:col-span-2">
                    @component('partials.section', [
                        'title' => 'Basic Usage',
                        'description' => 'Include the view and pass the items array with a default layout.',
                    ])
                        @component('partials.example')
                            @verbatim
                                @include('jkc.lists.list', [
                                    'items' => [
                                        ['title' => 'An active item', 'active' => true],
                                        ['title' => 'A second item here'],
                                        ['title' => 'A third button item'],
                                        ['title' => 'A disabled item', 'disabled' => true],
                                    ],
                                ])
                            @endverbatim
                        @endcomponent
                    @endcomponent
                </div>

                {{-- Flush & Horizontal — pleine largeur --}}
                <div class="md:col-span-2">
                    @component('partials.section', [
                        'title' => 'Flush & Horizontal Layouts',
                        'description' => 'Remove borders and rounded corners with flush, or display items side-by-side using horizontal.',
                    ])
                        <div class="space-y-6">
                            <div>
                                <p class="mb-2 font-code text-xs text-on-surface/60 dark:text-on-surface-dark/60">Flush style:
                                </p>
                                @include('jkc.lists.list', [
                                    'type' => 'flush',
                                    'items' => [
                                        ['title' => 'Profile settings'],
                                        ['title' => 'Account security'],
                                        ['title' => 'Billing & Invoices'],
                                    ],
                                ])
                            </div>
                            <div>
                                <p class="mb-2 font-code text-xs text-on-surface/60 dark:text-on-surface-dark/60">Horizontal
                                    style:</p>
                                @include('jkc.lists.list', [
                                    'type' => 'horizontal',
                                    'items' => [
                                        ['title' => 'Overview'],
                                        ['title' => 'Integration'],
                                        ['title' => 'Pricing'],
                                    ],
                                ])
                            </div>
                        </div>
                    @endcomponent
                </div>

                {{-- Links & Badges — colonne 1 --}}
                @component('partials.section', [
                    'title' => 'Links with Badges',
                    'description' => 'Interactive link items featuring badges and arrow indicators.',
                ])
                    @component('partials.example')
                        @verbatim
                            @include('jkc.lists.list', [
                                'type' => 'link',
                                'items' => [
                                    [
                                        'title' => 'Inbox',
                                        'href' => '#inbox',
                                        'badge' => '14',
                                        'badgeColor' => 'primary',
                                    ],
                                    [
                                        'title' => 'Messages',
                                        'href' => '#messages',
                                        'badge' => '2',
                                        'badgeColor' => 'success',
                                    ],
                                    ['title' => 'Drafts', 'href' => '#drafts'],
                                    ['title' => 'Trash', 'href' => '#trash', 'disabled' => true],
                                ],
                            ])
                        @endverbatim
                    @endcomponent
                @endcomponent

                {{-- Custom Cards — colonne 2 --}}
                @component('partials.section', [
                    'title' => 'Custom Content',
                    'description' => 'Rich layout items containing avatars, subtitles, descriptions, and badges.',
                ])
                    @component('partials.example')
                        @verbatim
                            @include('jkc.lists.list', [
                                'type' => 'custom',
                                'items' => [
                                    [
                                        'title' => 'Jane Doe',
                                        'subtitle' => 'jane.doe@example.com',
                                        'content' =>
                                            'Lead frontend architect working on design system integrations.',
                                        'avatar' =>
                                            'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=150',
                                        'badge' => 'Admin',
                                    ],
                                    [
                                        'title' => 'John Smith',
                                        'subtitle' => 'john.smith@example.com',
                                        'content' =>
                                            'Backend developer specializing in PHP architecture and high-load APIs.',
                                        'avatar' =>
                                            'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150',
                                        'badge' => 'Member',
                                    ],
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
                All configuration properties of the list component, followed by its source code.
            </p>

            <div x-data="{ query: '' }" class="mt-6">
                {{-- Filtre --}}
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
                                    {{-- Ligne 1 : prop + type + défaut --}}
                                    <div class="flex flex-wrap items-center gap-2">
                                        <code
                                            class="rounded bg-primary/10 px-2 py-0.5 font-code text-xs font-semibold text-primary dark:bg-primary-dark/10 dark:text-primary-dark">{{ $row[0] }}</code>
                                        <span
                                            class="font-code text-xs text-on-surface/50 dark:text-on-surface-dark/50">{{ $row[1] }}</span>
                                        <code
                                            class="ml-auto rounded bg-surface-alt px-2 py-0.5 font-code text-xs text-on-surface/60 dark:bg-surface-dark-alt dark:text-on-surface-dark/60">{{ $row[2] }}</code>
                                    </div>
                                    {{-- Ligne 2 : description --}}
                                    <p
                                        class="mt-1.5 font-paragraph text-xs leading-relaxed text-on-surface/70 dark:text-on-surface-dark/70">
                                        {!! $row[3] !!}</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Code source du composant --}}
            <div id="source" class="mt-8 scroll-mt-6">
                @component('partials.section', [
                    'title' => 'Code source',
                    'description' => 'Full source of the jkc.lists.list component view.',
                ])
                    @include('partials.source', ['view' => 'jkc.lists.list'])
                @endcomponent
            </div>
        </aside>

    </div>
@endsection
