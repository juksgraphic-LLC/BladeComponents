@extends('layout')

@section('content')

    @php
        $triggers = [
            ['button', 'Standard button trigger with customizable colors and sizes.'],
            ['link', 'Simple text link trigger for inline dropdowns.'],
            ['split', 'Split button featuring a primary action link alongside a dropdown toggle.'],
        ];

        $sampleItems = [
            ['text' => 'Action', 'href' => '#'],
            ['text' => 'Another action', 'href' => '#'],
            ['type' => 'divider'],
            ['text' => 'Separated link', 'href' => '#', 'color' => 'danger'],
        ];

        $rows = [
            [
                'Appearance',
                [
                    ['trigger', 'string', 'button', 'Trigger type: <code>button</code>, <code>link</code>, or <code>split</code>.'],
                    ['color', 'string', 'primary', 'Color variant: <code>primary</code>, <code>secondary</code>, <code>success</code>, <code>danger</code>, <code>warning</code>, <code>info</code>, <code>light</code>, <code>dark</code>.'],
                    ['size', 'string|null', 'null', 'Size modifier: <code>lg</code> or <code>sm</code>.'],
                    ['darkMenu', 'bool', 'false', 'Forces the menu container into dark mode independently of global theme.'],
                    ['align', 'string|null', 'null', 'Dropdown alignment: <code>start</code>, <code>end</code>, <code>dropup</code>, etc.'],
                ],
            ],
            [
                'Content',
                [
                    ['items', 'array', '[]', 'Array of items containing <code>text</code>, <code>href</code>, <code>type</code>, <code>icon</code>, <code>color</code>, <code>active</code>, and <code>disabled</code>.'],
                    ['buttonText', 'string', "'Dropdown'", 'Main text displayed on the trigger button.'],
                    ['id', 'string|null', 'null', 'Custom HTML ID. Dispatches a <code>jkc-dropdown-toggle</code> window event on state change.'],
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
                    Dropdown
                </h1>
                <p class="mt-2 max-w-2xl font-paragraph text-on-surface/70 dark:text-on-surface-dark/70">
                    Display toggleable menus for user actions, navigation options, and contextual lists. Supports color variants, custom placements, and event tracking.
                </p>
                <div class="mt-4 flex flex-wrap items-center gap-2">
                    <span class="rounded-full border border-primary/30 bg-primary/10 px-3 py-1 font-code text-xs text-primary dark:border-primary-dark/30 dark:bg-primary-dark/10 dark:text-primary-dark">3 triggers</span>
                    <span class="rounded-full border border-outline bg-surface-alt px-3 py-1 font-code text-xs text-on-surface dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark">Alpine.js requis</span>
                </div>
            </header>
            
            <h2 class="font-title text-2xl font-semibold text-on-surface-strong dark:text-on-surface-dark-strong">
                Usage
            </h2>
            <p class="mt-2 font-paragraph text-on-surface/70 dark:text-on-surface-dark/70">
                The component in action: basic usage, all trigger types, and event inspection.
            </p>

            <div class="mt-6 grid grid-cols-1 gap-6 md:grid-cols-2">

                {{-- Basic Usage --}}
                <div class="md:col-span-2">
                    @component('partials.section', [
                        'title' => 'Basic Usage',
                        'description' => 'Include the dropdown view and pass items as an array.',
                    ])
                        @component('partials.example')
                            @verbatim
                                @include('jkc.dropdowns.dropdown', [
                                    'buttonText' => 'Dropdown button',
                                    'items' => [
                                        ['text' => 'Action', 'href' => '#'],
                                        ['text' => 'Another action', 'href' => '#'],
                                        ['type' => 'divider'],
                                        ['text' => 'Separated link', 'href' => '#']
                                    ]
                                ])
                            @endverbatim
                        @endcomponent
                    @endcomponent
                </div>

                {{-- Triggers — pleine largeur avec sélecteur --}}
                <div class="md:col-span-2">
                    @component('partials.section', [
                        'title' => 'Trigger Types',
                        'description' => 'Different trigger methods available for dropdowns. Select a trigger to preview it.',
                    ])
                        <div x-data="{ trigger: 'button' }">
                            <div class="mb-5 flex flex-wrap gap-2" role="tablist" aria-label="Dropdown Triggers">
                                @foreach ($triggers as $tr)
                                    <button type="button" role="tab" @click="trigger = '{{ $tr[0] }}'"
                                        :aria-selected="trigger === '{{ $tr[0] }}'"
                                        :class="trigger === '{{ $tr[0] }}'
                                            ? 'border-primary bg-primary text-on-primary dark:border-primary-dark dark:bg-primary-dark dark:text-on-primary-dark'
                                            : 'border-outline bg-transparent text-on-surface/70 hover:border-primary hover:text-primary dark:border-outline-dark dark:text-on-surface-dark/70 dark:hover:border-primary-dark dark:hover:text-primary-dark'"
                                        class="rounded-full border px-3.5 py-1.5 font-code text-xs transition">
                                        {{ $tr[0] }}
                                    </button>
                                @endforeach
                            </div>

                            @foreach ($triggers as $tr)
                                <div x-show="trigger === '{{ $tr[0] }}'" x-transition.opacity.duration.150ms>
                                    <p class="mb-4 font-paragraph text-sm italic text-on-surface/60 dark:text-on-surface-dark/60">
                                        {{ $tr[1] }}
                                    </p>
                                    @component('partials.example', ['replace' => ['TRIGGER_NAME' => $tr[0]]])
                                        @verbatim
                                            @include('jkc.dropdowns.dropdown', [
                                                'trigger' => 'TRIGGER_NAME',
                                                'buttonText' => ucfirst('TRIGGER_NAME') . ' Trigger',
                                                'splitAction' => '#',
                                                'items' => [
                                                    ['text' => 'Profile', 'href' => '#'],
                                                    ['text' => 'Settings', 'href' => '#'],
                                                    ['type' => 'divider'],
                                                    ['text' => 'Logout', 'href' => '#', 'color' => 'danger']
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
                        'description' => 'Listens to jkc-dropdown-toggle window events.',
                    ])
                        @component('partials.example')
                            @verbatim
                                <div x-on:jkc-dropdown-toggle.window="console.log('Dropdown toggled:', $event.detail)">
                                    @include('jkc.dropdowns.dropdown', [
                                        'id' => 'demo-dropdown',
                                        'buttonText' => 'Check Console Event',
                                        'color' => 'success',
                                        'items' => [
                                            ['text' => 'Profile', 'href' => '#'],
                                            ['text' => 'Settings', 'href' => '#']
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
                    'description' => 'Full source of the jkc.dropdowns.dropdown component view.',
                ])
                    @include('partials.source', ['view' => 'jkc.dropdowns.dropdown'])
                @endcomponent
            </div>
        </aside>

    </div>
@endsection