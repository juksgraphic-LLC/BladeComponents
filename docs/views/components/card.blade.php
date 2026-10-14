@extends('layout')

@section('content')

    @php
        $rows = [
            [
                'Configuration',
                [
                    [
                        'title',
                        'string|null',
                        'null',
                        'Card title displayed in the header area. Escaped for security.',
                    ],
                    [
                        'subtitle',
                        'string|null',
                        'null',
                        'Card subtitle displayed underneath the main title. Escaped.',
                    ],
                    [
                        'noPadding',
                        'bool',
                        'false',
                        'Removes inner padding from the body container (ideal for edge-to-edge images or tables).',
                    ],
                    [
                        'class',
                        'string',
                        "''",
                        'Extra custom CSS classes applied to the outer container element.',
                    ],
                ],
            ],
            [
                'Slots',
                [
                    [
                        'body',
                        'string',
                        "''",
                        'Main body content area (can also use default $slot). Renders raw HTML.',
                    ],
                    [
                        'footer',
                        'string|null',
                        'null',
                        'Optional footer section at the bottom of the card.',
                    ],
                    [
                        'headerAction',
                        'string|null',
                        'null',
                        'Actions or badges rendered on the right side of the header area.',
                    ],
                ],
            ],
        ];
    @endphp

    {{-- ══════════════════════════════════════════════════════════
         DEUX COLONNES CÔTE À CÔTE
         Gauche : Utilisations (2/3)  |  Droite : Props + Source (1/3)
    ══════════════════════════════════════════════════════════ --}}
    <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">

        {{-- ── COLONNE GAUCHE : UTILISATIONS (2/3) ──────────────── --}}
        <section id="utilisations" class="scroll-mt-6 lg:col-span-2">

            {{-- ── En-tête ─────────────────────────────────────────────── --}}
            <header class="mb-10">
                <p class="font-code text-xs uppercase tracking-wide text-primary dark:text-primary-dark">
                    Containers
                </p>
                <h1 class="mt-1 font-title text-3xl font-semibold text-on-surface-strong dark:text-on-surface-dark-strong">
                    Card
                </h1>
                <p class="mt-2 max-w-2xl font-paragraph text-on-surface/70 dark:text-on-surface-dark/70">
                    Flexible and extensible content containers with support for headers, footers, custom action slots, and various layout styles
                </p>
                <div class="mt-4 flex flex-wrap items-center gap-2">
                    <span class="rounded-full border border-primary/30 bg-primary/10 px-3 py-1 font-code text-xs text-primary dark:border-primary-dark/30 dark:bg-primary-dark/10 dark:text-primary-dark">Headers & Footers</span>
                    <span class="rounded-full border border-primary/30 bg-primary/10 px-3 py-1 font-code text-xs text-primary dark:border-primary-dark/30 dark:bg-primary-dark/10 dark:text-primary-dark">Custom Slots</span>
                    <span class="rounded-full border border-outline bg-surface-alt px-3 py-1 font-code text-xs text-on-surface dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark">Tailwind CSS</span>
                </div>
            </header>

            <h2 class="font-title text-2xl font-semibold text-on-surface-strong dark:text-on-surface-dark-strong">
                Utilisations
            </h2>
            <p class="mt-2 font-paragraph text-on-surface/70 dark:text-on-surface-dark/70">
                Explore component implementations: standard summary cards, blog post cards with cover images, profile containers, and metrics analytics layouts.
            </p>

            <div class="mt-6 grid grid-cols-1 gap-6">

                {{-- 1. Standard Cards --}}
                @component('partials.section', [
                    'title' => 'Standard Cards',
                    'description' => 'A set of basic cards featuring titles, subtitles, header actions, and footers[cite: 5].',
                ])
                    @component('partials.example')
                        @verbatim
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                @component('jkc.cards.card', ['title' => 'Overview', 'subtitle' => 'Daily metrics summary'])
                                    @slot('headerAction')
                                        <span class="text-xs text-primary font-medium">Active</span>
                                    @endslot
                                    @slot('body')
                                        <p class="text-sm text-on-surface/80 dark:text-on-surface-dark/80">
                                            Review your daily performance reports and system updates.
                                        </p>
                                    @endslot
                                    @slot('footer')
                                        <span class="text-xs text-on-surface/60">Updated just now</span>
                                    @endslot
                                @endcomponent

                                @component('jkc.cards.card', ['title' => 'Projects', 'subtitle' => 'Active codebases'])
                                    @slot('headerAction')
                                        <span class="text-xs text-success font-medium">3 running</span>
                                    @endslot
                                    @slot('body')
                                        <p class="text-sm text-on-surface/80 dark:text-on-surface-dark/80">
                                            Manage your full-stack web applications and API architectures under JUKS Graphic.
                                        </p>
                                    @endslot
                                    @slot('footer')
                                        <span class="text-xs text-on-surface/60">Synced to GitHub</span>
                                    @endslot
                                @endcomponent

                                @component('jkc.cards.card', ['title' => 'Security', 'subtitle' => 'Access logs'])
                                    @slot('headerAction')
                                        <span class="text-xs text-warning font-medium">Alert</span>
                                    @endslot
                                    @slot('body')
                                        <p class="text-sm text-on-surface/80 dark:text-on-surface-dark/80">
                                            Two new sign-ins detected from trusted development devices on macOS.
                                        </p>
                                    @endslot
                                    @slot('footer')
                                        <span class="text-xs text-on-surface/60">View history</span>
                                    @endslot
                                @endcomponent
                            </div>
                        @endverbatim
                    @endcomponent
                @endcomponent

                {{-- 2. Blog Post Cards --}}
                @component('partials.section', [
                    'title' => 'Blog Post Cards',
                    'description' => 'Ideal for articles or news items featuring cover images, category badges, and author details[cite: 5].',
                ])
                    @component('partials.example')
                        @verbatim
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                @component('jkc.cards.card', ['noPadding' => true])
                                    @slot('body')
                                        <img src="https://images.unsplash.com/photo-1618005182384-a83a8bd57fbe?q=80&w=800&auto=format&fit=crop" alt="Blog cover" class="w-full h-40 object-cover">
                                        <div class="p-5 space-y-2">
                                            <span class="inline-block px-2 py-0.5 text-xs font-semibold bg-primary/10 text-primary rounded-full">Engineering</span>
                                            <h3 class="font-title text-base font-bold text-on-surface dark:text-on-surface-dark">Building Modern Full-Stack Architectures</h3>
                                            <p class="text-xs text-on-surface/70 dark:text-on-surface-dark/70 line-clamp-2">
                                                Explore how to leverage modern design tokens and utility classes to build sleek web apps fast.
                                            </p>
                                        </div>
                                    @endslot
                                    @slot('footer')
                                        <div class="flex items-center justify-between text-xs text-on-surface/60">
                                            <div class="flex items-center gap-2">
                                                <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=200&auto=format&fit=crop" alt="Author" class="size-6 rounded-full object-cover">
                                                <span>Frantzia F.</span>
                                            </div>
                                            <span>Oct 12, 2026</span>
                                        </div>
                                    @endslot
                                @endcomponent

                                @component('jkc.cards.card', ['noPadding' => true])
                                    @slot('body')
                                        <img src="https://images.unsplash.com/photo-1555066931-4365d14bab8c?q=80&w=800&auto=format&fit=crop" alt="Blog cover" class="w-full h-40 object-cover">
                                        <div class="p-5 space-y-2">
                                            <span class="inline-block px-2 py-0.5 text-xs font-semibold bg-success/10 text-success rounded-full">UI/UX</span>
                                            <h3 class="font-title text-base font-bold text-on-surface dark:text-on-surface-dark">Minimalist Design Systems with Penguin UI</h3>
                                            <p class="text-xs text-on-surface/70 dark:text-on-surface-dark/70 line-clamp-2">
                                                Crafting consistent user interfaces using semantic CSS tokens and clean editorial layouts.
                                            </p>
                                        </div>
                                    @endslot
                                    @slot('footer')
                                        <div class="flex items-center justify-between text-xs text-on-surface/60">
                                            <div class="flex items-center gap-2">
                                                <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=200&auto=format&fit=crop" alt="Author" class="size-6 rounded-full object-cover">
                                                <span>Kingsley G.</span>
                                            </div>
                                            <span>Oct 10, 2026</span>
                                        </div>
                                    @endslot
                                @endcomponent

                                @component('jkc.cards.card', ['noPadding' => true])
                                    @slot('body')
                                        <img src="https://images.unsplash.com/photo-1526374965328-7f61d4dc18c5?q=80&w=800&auto=format&fit=crop" alt="Blog cover" class="w-full h-40 object-cover">
                                        <div class="p-5 space-y-2">
                                            <span class="inline-block px-2 py-0.5 text-xs font-semibold bg-warning/10 text-warning rounded-full">PHP & Packages</span>
                                            <h3 class="font-title text-base font-bold text-on-surface dark:text-on-surface-dark">Publishing Open Source Blade Packages</h3>
                                            <p class="text-xs text-on-surface/70 dark:text-on-surface-dark/70 line-clamp-2">
                                                A deep dive into testing with Pest and publishing packages like bladetranslator to Packagist.
                                            </p>
                                        </div>
                                    @endslot
                                    @slot('footer')
                                        <div class="flex items-center justify-between text-xs text-on-surface/60">
                                            <div class="flex items-center gap-2">
                                                <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?q=80&w=200&auto=format&fit=crop" alt="Author" class="size-6 rounded-full object-cover">
                                                <span>Darbysson</span>
                                            </div>
                                            <span>Oct 05, 2026</span>
                                        </div>
                                    @endslot
                                @endcomponent
                            </div>
                        @endverbatim
                    @endcomponent
                @endcomponent

                {{-- 3. Profile Cards --}}
                @component('partials.section', [
                    'title' => 'Profile Cards',
                    'description' => 'Showcase user info, high-resolution avatar photos, roles, and connection buttons[cite: 5].',
                ])
                    @component('partials.example')
                        @verbatim
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                @component('jkc.cards.card')
                                    @slot('body')
                                        <div class="text-center py-2 space-y-3">
                                            <img src="https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=400&auto=format&fit=crop" alt="Profile avatar" class="size-20 mx-auto rounded-full object-cover border-2 border-primary">
                                            <div>
                                                <h4 class="font-title font-semibold text-base text-on-surface dark:text-on-surface-dark">Frantzia Fevry</h4>
                                                <p class="text-xs text-primary font-code mt-0.5">Cia Academy Manager</p>
                                            </div>
                                            <p class="text-xs text-on-surface/70 dark:text-on-surface-dark/70 px-2">
                                                Managing digital learning platforms and online educational systems.
                                            </p>
                                        </div>
                                    @endslot
                                    @slot('footer')
                                        <div class="flex gap-2">
                                            <button type="button" class="w-full py-1.5 text-xs font-medium text-on-primary bg-primary hover:bg-primary/90 rounded-radius transition">Connect</button>
                                        </div>
                                    @endslot
                                @endcomponent

                                @component('jkc.cards.card')
                                    @slot('body')
                                        <div class="text-center py-2 space-y-3">
                                            <img src="https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=400&auto=format&fit=crop" alt="Profile avatar" class="size-20 mx-auto rounded-full object-cover border-2 border-primary">
                                            <div>
                                                <h4 class="font-title font-semibold text-base text-on-surface dark:text-on-surface-dark">Kingsley Guillaume</h4>
                                                <p class="text-xs text-primary font-code mt-0.5">Lead Software Developer</p>
                                            </div>
                                            <p class="text-xs text-on-surface/70 dark:text-on-surface-dark/70 px-2">
                                                Founder of JUKS Graphic, specializing in full-stack web and mobile apps.
                                            </p>
                                        </div>
                                    @endslot
                                    @slot('footer')
                                        <div class="flex gap-2">
                                            <button type="button" class="w-full py-1.5 text-xs font-medium text-on-primary bg-primary hover:bg-primary/90 rounded-radius transition">Connect</button>
                                        </div>
                                    @endslot
                                @endcomponent

                                @component('jkc.cards.card')
                                    @slot('body')
                                        <div class="text-center py-2 space-y-3">
                                            <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?q=80&w=400&auto=format&fit=crop" alt="Profile avatar" class="size-20 mx-auto rounded-full object-cover border-2 border-primary">
                                            <div>
                                                <h4 class="font-title font-semibold text-base text-on-surface dark:text-on-surface-dark">Darbysson</h4>
                                                <p class="text-xs text-primary font-code mt-0.5">Project Collaborator</p>
                                            </div>
                                            <p class="text-xs text-on-surface/70 dark:text-on-surface-dark/70 px-2">
                                                Partner on software contracts and development for Lambi Connect.
                                            </p>
                                        </div>
                                    @endslot
                                    @slot('footer')
                                        <div class="flex gap-2">
                                            <button type="button" class="w-full py-1.5 text-xs font-medium text-on-primary bg-primary hover:bg-primary/90 rounded-radius transition">Connect</button>
                                        </div>
                                    @endslot
                                @endcomponent
                            </div>
                        @endverbatim
                    @endcomponent
                @endcomponent

                {{-- 4. Statistic / Metric Cards --}}
                @component('partials.section', [
                    'title' => 'Statistic / Metric Cards',
                    'description' => 'Display key performance indicators or analytics numbers across three columns[cite: 5].',
                ])
                    @component('partials.example')
                        @verbatim
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                @component('jkc.cards.card')
                                    @slot('body')
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-xs font-medium text-on-surface/60 uppercase tracking-wider">Total Revenue</p>
                                                <h4 class="text-2xl font-bold font-title mt-1 text-on-surface dark:text-on-surface-dark">$45,231.89</h4>
                                            </div>
                                            <div class="p-3 bg-success/10 text-success rounded-lg">
                                                <i class="bx bx-dollar text-xl"></i>
                                            </div>
                                        </div>
                                        <div class="mt-4 flex items-center text-xs text-success font-medium">
                                            <i class="bx bx-up-arrow-alt text-sm mr-1"></i> +20.1% from last month
                                        </div>
                                    @endslot
                                @endcomponent

                                @component('jkc.cards.card')
                                    @slot('body')
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-xs font-medium text-on-surface/60 uppercase tracking-wider">Active Subscriptions</p>
                                                <h4 class="text-2xl font-bold font-title mt-1 text-on-surface dark:text-on-surface-dark">+2,350</h4>
                                            </div>
                                            <div class="p-3 bg-primary/10 text-primary rounded-lg">
                                                <i class="bx bx-user text-xl"></i>
                                            </div>
                                        </div>
                                        <div class="mt-4 flex items-center text-xs text-primary font-medium">
                                            <i class="bx bx-up-arrow-alt text-sm mr-1"></i> +180 this week
                                        </div>
                                    @endslot
                                @endcomponent

                                @component('jkc.cards.card')
                                    @slot('body')
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-xs font-medium text-on-surface/60 uppercase tracking-wider">System Load</p>
                                                <h4 class="text-2xl font-bold font-title mt-1 text-on-surface dark:text-on-surface-dark">12.4%</h4>
                                            </div>
                                            <div class="p-3 bg-info/10 text-info rounded-lg">
                                                <i class="bx bx-server text-xl"></i>
                                            </div>
                                        </div>
                                        <div class="mt-4 flex items-center text-xs text-info font-medium">
                                            <i class="bx bx-check-shield text-sm mr-1"></i> Optimal performance
                                        </div>
                                    @endslot
                                @endcomponent
                            </div>
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
                All props of the component, followed by the full source code.
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
                    <input type="search" x-model="query" placeholder="Filter theProperties…"
                        aria-label="Filter theProperties"
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
                    'description' => 'Full source of the jkc.cards.card component view.',
                ])
                    @include('partials.source', ['view' => 'jkc.cards.card'])
                @endcomponent
            </div>
        </aside>

    </div>
@endsection