@extends('layout')

@section('content')

    @php
        $types = [
            ['default', 'Standard collapsible panels with borders and background.'],
            ['flush', 'Borderless and background-less panels for edge-to-edge layouts.'],
            ['icon', 'Panels featuring an icon with a rotation transition on open.'],
            ['plus', 'Panels featuring a plus icon that rotates into a cross.'],
            ['arrow', 'Panels featuring an arrow chevron that rotates on open.'],
            ['left-icon', 'Panels with a static accent icon placed on the left of the title.'],
            ['custom', 'Panels featuring custom colored borders and dynamic active states.'],
        ];

        $sampleItems = [
            [
                'title' => 'What is Juksgraphic UI?',
                'content' => 'Juksgraphic UI is a modern component library built with Tailwind CSS and Alpine.js, designed for Laravel Blade applications.',
                'expanded' => true,
                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
            ],
            [
                'title' => 'How do I install components?',
                'content' => 'You can easily include components via Blade directives such as <code>include(\'jkc.components.accordion\')</code>.',
                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>'
            ],
            [
                'title' => 'Is Alpine.js required?',
                'content' => 'Yes, Alpine.js handles reactivity, transitions, and state management for interactive components.',
                'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>'
            ],
        ];

        $rows = [
            [
                'Appearance',
                [
                    [
                        'type',
                        'string',
                        'default',
                        'One of <code>default</code>, <code>flush</code>, <code>icon</code>, <code>plus</code>, <code>arrow</code>, <code>left-icon</code>, <code>custom</code>, <code>nested</code>.',
                    ],
                    [
                        'color',
                        'string',
                        'primary',
                        'Color variant: <code>primary</code>, <code>secondary</code>, <code>success</code>, <code>danger</code>, <code>warning</code>, <code>info</code>, <code>light</code>, <code>dark</code>.',
                    ],
                    [
                        'multiple',
                        'bool',
                        'false',
                        'Allows multiple accordion panels to remain open simultaneously.',
                    ],
                    [
                        'flush',
                        'bool',
                        'false',
                        'Removes external borders and background container styles.',
                    ],
                ],
            ],
            [
                'Content',
                [
                    [
                        'items',
                        'array',
                        '[]',
                        'Array of items containing <code>title</code>, <code>content</code>, <code>icon</code>, <code>disabled</code>, <code>expanded</code>, <code>color</code>, and <code>nested</code>.',
                    ],
                    [
                        'id',
                        'string|null',
                        'null',
                        'Custom HTML ID. Dispatches a <code>jkc-accordion-toggle</code> window event on interaction.',
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
                    Components
                </p>
                <h1 class="mt-1 font-title text-3xl font-semibold text-on-surface-strong dark:text-on-surface-dark-strong">
                    Accordion
                </h1>
                <p class="mt-2 max-w-2xl font-paragraph text-on-surface/70 dark:text-on-surface-dark/70">
                    Display collapsible content panels for presenting information in a limited space. Supports multiple styles, custom icons, and Alpine.js transitions.
                </p>
                <div class="mt-4 flex flex-wrap items-center gap-2">
                    <span class="rounded-full border border-primary/30 bg-primary/10 px-3 py-1 font-code text-xs text-primary dark:border-primary-dark/30 dark:bg-primary-dark/10 dark:text-primary-dark">7 types</span>
                    <span class="rounded-full border border-outline bg-surface-alt px-3 py-1 font-code text-xs text-on-surface dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark">Alpine.js requis</span>
                </div>
            </header>
            
            <h2 class="font-title text-2xl font-semibold text-on-surface-strong dark:text-on-surface-dark-strong">
                Usage
            </h2>
            <p class="mt-2 font-paragraph text-on-surface/70 dark:text-on-surface-dark/70">
                The component in action: basic usage, all presentation types, and event tracking.
            </p>

            <div class="mt-6 grid grid-cols-1 gap-6 md:grid-cols-2">

                {{-- Basic Usage --}}
                <div class="md:col-span-2">
                    @component('partials.section', [
                        'title' => 'Basic Usage',
                        'description' => 'Include the view and pass the items array.',
                    ])
                        @component('partials.example')
                            @verbatim
                                @include('jkc.accordions.accordion', [
                                    'items' => [
                                        [
                                            'title' => 'Section 1',
                                            'content' => 'Content for the first section.',
                                            'expanded' => true
                                        ],
                                        [
                                            'title' => 'Section 2',
                                            'content' => 'Content for the second section.'
                                        ]
                                    ]
                                ])
                            @endverbatim
                        @endcomponent
                    @endcomponent
                </div>

                {{-- Types — pleine largeur avec sélecteur (Similaire aux alertes) --}}
                <div class="md:col-span-2">
                    @component('partials.section', [
                        'title' => 'Types',
                        'description' => 'Seven presentation types available for accordions. Select a type to preview it.',
                    ])
                        <div x-data="{ type: 'default' }">
                            <div class="mb-5 flex flex-wrap gap-2" role="tablist" aria-label="Accordion Types">
                                @foreach ($types as $t)
                                    <button type="button" role="tab" @click="type = '{{ $t[0] }}'"
                                        :aria-selected="type === '{{ $t[0] }}'"
                                        :class="type === '{{ $t[0] }}'
                                            ? 'border-primary bg-primary text-on-primary dark:border-primary-dark dark:bg-primary-dark dark:text-on-primary-dark'
                                            : 'border-outline bg-transparent text-on-surface/70 hover:border-primary hover:text-primary dark:border-outline-dark dark:text-on-surface-dark/70 dark:hover:border-primary-dark dark:hover:text-primary-dark'"
                                        class="rounded-full border px-3.5 py-1.5 font-code text-xs transition">
                                        {{ $t[0] }}
                                    </button>
                                @endforeach
                            </div>

                            @foreach ($types as $t)
                                <div x-show="type === '{{ $t[0] }}'" x-transition.opacity.duration.150ms>
                                    <p class="mb-4 font-paragraph text-sm italic text-on-surface/60 dark:text-on-surface-dark/60">
                                        {{ $t[1] }}
                                    </p>
                                    @component('partials.example', ['replace' => ['TYPE_NAME' => $t[0]]])
                                        @verbatim
                                            @include('jkc.accordions.accordion', [
                                                'type' => 'TYPE_NAME',
                                                'items' => [
                                                    [
                                                        'title' => 'First panel item',
                                                        'content' => 'This is the expanded content for the TYPE_NAME accordion variant.',
                                                        'expanded' => true,
                                                        'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
                                                    ],
                                                    [
                                                        'title' => 'Second panel item',
                                                        'content' => 'Another collapsible section containing descriptive details.',
                                                        'icon' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>'
                                                    ]
                                                ]
                                            ])
                                        @endverbatim
                                    @endcomponent
                                </div>
                            @endforeach
                        </div>
                    @endcomponent
                </div>

                {{-- Événement & Console Log --}}
                <div class="md:col-span-2">
                    @component('partials.section', [
                        'title' => 'Événement & Intéraction',
                        'description' => 'Listens to jkc-accordion-toggle window events.',
                    ])
                        @component('partials.example')
                            @verbatim
                                <div x-on:jkc-accordion-toggle.window="console.log('Accordion toggled:', $event.detail)">
                                    @include('jkc.accordions.accordion', [
                                        'id' => 'demo-accordion',
                                        'items' => [
                                            [
                                                'title' => 'Click to check console event',
                                                'content' => 'This accordion fires a jkc-accordion-toggle event on change.'
                                            ]
                                        ]
                                    ])
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
                All props of the component, followed by the full source code.
            </p>

            <div x-data="{ query: '' }" class="mt-6">
                <div class="relative mb-4">
                    <svg class="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-on-surface/40 dark:text-on-surface-dark/40"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10.5a6.5 6.5 0 11-13 0 6.5 6.5 0 0113 0z" />
                    </svg>
                    <input type="search" x-model="query" placeholder="Filter theProperties…" aria-label="Filter theProperties"
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

            {{-- Code source du composant --}}
            <div id="source" class="mt-8 scroll-mt-6">
                @component('partials.section', [
                    'title' => 'Code source',
                    'description' => 'Full source of the jkc.accordions.accordion component view.',
                ])
                    @include('partials.source', ['view' => 'jkc.accordions.accordion'])
                @endcomponent
            </div>
        </aside>

    </div>
@endsection