@extends('layout')

@section('content')

    @php
        $rows = [
            [
                'Configuration',
                [
                    [
                        'variant',
                        'string',
                        'slides-only',
                        'One of <code>slides-only</code>, <code>controls</code>, <code>indicators</code>, <code>captions</code>, <code>crossfade</code>, <code>individual-interval</code>, <code>no-touch</code>, <code>dark</code>, <code>coverflow</code>, <code>cards</code>, <code>creative</code>.',
                    ],
                    [
                        'slides',
                        'array',
                        '[]',
                        'Array of slide elements containing keys like <code>img</code>, <code>alt</code>, <code>caption_title</code>, <code>caption_text</code>, and optional <code>interval</code>.',
                    ],
                    ['autoplay', 'bool', 'false', 'Enables automatic slide transition timing.'],
                    ['autoplayDelay', 'int', '3000', 'Delay in milliseconds between automatic slide transitions.'],
                    ['loop', 'bool', 'true', 'Enables continuous loop mode for infinite sliding.'],
                    [
                        'height',
                        'string',
                        "'h-72'",
                        'Tailwind CSS height class applied to the swiper container (e.g. <code>h-80</code>, <code>h-96</code>).',
                    ],
                    [
                        'effect',
                        'string|null',
                        'null',
                        'Forces a specific Swiper transition effect: <code>slide</code>, <code>fade</code>, <code>coverflow</code>, <code>cards</code>, <code>creative</code>.',
                    ],
                    ['dark', 'bool', 'false', 'Forces dark styling variant for controls and indicators.'],
                    ['wrapClass', 'string', "''", 'Extra custom CSS classes applied to the outer wrapper element.'],
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
                    Media & Sliders
                </p>
                <h1 class="mt-1 font-title text-3xl font-semibold text-on-surface-strong dark:text-on-surface-dark-strong">
                    Carousels
                </h1>
                <p class="mt-2 max-w-2xl font-paragraph text-on-surface/70 dark:text-on-surface-dark/70">
                    Responsive image and content sliders powered by Swiper.js and Alpine.js. Supports navigation controls,
                    pagination indicators, caption overlays, crossfade transitions, and advanced 3D perspective effects.
                </p>
                <div class="mt-4 flex flex-wrap items-center gap-2">
                    <span
                        class="rounded-full border border-primary/30 bg-primary/10 px-3 py-1 font-code text-xs text-primary dark:border-primary-dark/30 dark:bg-primary-dark/10 dark:text-primary-dark">11
                        variants</span>
                    <span
                        class="rounded-full border border-primary/30 bg-primary/10 px-3 py-1 font-code text-xs text-primary dark:border-primary-dark/30 dark:bg-primary-dark/10 dark:text-primary-dark">Swiper.js
                        v11</span>
                    <span
                        class="rounded-full border border-outline bg-surface-alt px-3 py-1 font-code text-xs text-on-surface dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark">Alpine.js
                        required</span>
                </div>
            </header>

            <h2 class="font-title text-2xl font-semibold text-on-surface-strong dark:text-on-surface-dark-strong">
                Usage
            </h2>
            <p class="mt-2 font-paragraph text-on-surface/70 dark:text-on-surface-dark/70">
                Explore all component implementations and layout options.
            </p>

            <div class="mt-6 grid grid-cols-1 gap-6">

                {{-- 1. Slides Only & With Controls --}}
                @component('partials.section', [
                    'title' => 'Slides Only & Controls',
                    'description' => 'Simple slide transition with no controls, and standard side navigation buttons.',
                ])
                    @component('partials.example')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-xs font-semibold uppercase text-on-surface/60 dark:text-on-surface-dark/60 mb-2">
                                    Variant: Slides Only</p>
                                @verbatim
                                    @php
                                        $_slidesBasic = [
                                            [
                                                'img' =>
                                                    'https://images.unsplash.com/photo-1593508512255-86ab42a8e620?q=80&w=900&auto=format&fit=crop',
                                                'alt' => 'Laptop & coffee',
                                            ],
                                            [
                                                'img' =>
                                                    'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?q=80&w=900&auto=format&fit=crop',
                                                'alt' => 'MacBook on desk',
                                            ],
                                            [
                                                'img' =>
                                                    'https://images.unsplash.com/photo-1542744173-8e7e53415bb0?q=80&w=900&auto=format&fit=crop',
                                                'alt' => 'Meeting',
                                            ],
                                        ];
                                    @endphp
                                    @include('jkc.media.carousel', [
                                        'variant' => 'slides-only',
                                        'slides' => $_slidesBasic,
                                        'autoplay' => true,
                                        'loop' => true,
                                        'height' => 'h-56',
                                    ])
                                @endverbatim
                            </div>
                            <div>
                                <p class="text-xs font-semibold uppercase text-on-surface/60 dark:text-on-surface-dark/60 mb-2">
                                    Variant: With Controls</p>
                                @verbatim
                                    @php
                                        $_slidesBasic = [
                                            [
                                                'img' =>
                                                    'https://images.unsplash.com/photo-1593508512255-86ab42a8e620?q=80&w=900&auto=format&fit=crop',
                                                'alt' => 'Laptop & coffee',
                                            ],
                                            [
                                                'img' =>
                                                    'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?q=80&w=900&auto=format&fit=crop',
                                                'alt' => 'MacBook on desk',
                                            ],
                                            [
                                                'img' =>
                                                    'https://images.unsplash.com/photo-1542744173-8e7e53415bb0?q=80&w=900&auto=format&fit=crop',
                                                'alt' => 'Meeting',
                                            ],
                                        ];
                                    @endphp
                                    @include('jkc.media.carousel', [
                                        'variant' => 'controls',
                                        'slides' => $_slidesBasic,
                                        'loop' => true,
                                        'height' => 'h-56',
                                    ])
                                @endverbatim
                            </div>
                        </div>
                    @endcomponent
                @endcomponent

                {{-- 2. Indicators & Captions --}}
                @component('partials.section', [
                    'title' => 'Indicators & Captions',
                    'description' => 'Pagination indicators and text content overlays.',
                ])
                    @component('partials.example')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-xs font-semibold uppercase text-on-surface/60 dark:text-on-surface-dark/60 mb-2">
                                    Variant: With Indicators</p>
                                @verbatim
                                    @php
                                        $_slidesCaption = [
                                            [
                                                'img' =>
                                                    'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?q=80&w=900&auto=format&fit=crop',
                                                'alt' => 'Sunrise beach',
                                                'caption_title' => 'Sunrise above a beach',
                                                'caption_text' => 'Don\'t be afraid to combine font styles.',
                                            ],
                                            [
                                                'img' =>
                                                    'https://images.unsplash.com/photo-1519046904884-53103b34b206?q=80&w=900&auto=format&fit=crop',
                                                'alt' => 'Ocean waves',
                                                'caption_title' => 'Ocean from above',
                                                'caption_text' => 'Experiment with your typography.',
                                            ],
                                        ];
                                    @endphp
                                    @include('jkc.media.carousel', [
                                        'variant' => 'indicators',
                                        'slides' => $_slidesCaption,
                                        'autoplay' => true,
                                        'loop' => true,
                                        'height' => 'h-56',
                                    ])
                                @endverbatim
                            </div>
                            <div>
                                <p class="text-xs font-semibold uppercase text-on-surface/60 dark:text-on-surface-dark/60 mb-2">
                                    Variant: With Captions</p>
                                @verbatim
                                    @php
                                        $_slidesCaption = [
                                            [
                                                'img' =>
                                                    'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?q=80&w=900&auto=format&fit=crop',
                                                'alt' => 'Sunrise beach',
                                                'caption_title' => 'Sunrise above a beach',
                                                'caption_text' => 'Don\'t be afraid to combine font styles.',
                                            ],
                                            [
                                                'img' =>
                                                    'https://images.unsplash.com/photo-1519046904884-53103b34b206?q=80&w=900&auto=format&fit=crop',
                                                'alt' => 'Ocean waves',
                                                'caption_title' => 'Ocean from above',
                                                'caption_text' => 'Experiment with your typography.',
                                            ],
                                        ];
                                    @endphp
                                    @include('jkc.media.carousel', [
                                        'variant' => 'captions',
                                        'slides' => $_slidesCaption,
                                        'loop' => true,
                                        'height' => 'h-56',
                                    ])
                                @endverbatim
                            </div>
                        </div>
                    @endcomponent
                @endcomponent

                {{-- 3. Crossfade Animation & Individual Interval --}}
                @component('partials.section', [
                    'title' => 'Crossfade Animation & Individual Interval',
                    'description' => 'Fade transition effects and customized timing per slide item.',
                ])
                    @component('partials.example')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-xs font-semibold uppercase text-on-surface/60 dark:text-on-surface-dark/60 mb-2">
                                    Variant: Crossfade</p>
                                @verbatim
                                    @php
                                        $_slidesBasic = [
                                            [
                                                'img' =>
                                                    'https://images.unsplash.com/photo-1593508512255-86ab42a8e620?q=80&w=900&auto=format&fit=crop',
                                                'alt' => 'Laptop & coffee',
                                            ],
                                            [
                                                'img' =>
                                                    'https://images.unsplash.com/photo-1496181133206-80ce9b88a853?q=80&w=900&auto=format&fit=crop',
                                                'alt' => 'MacBook on desk',
                                            ],
                                        ];
                                    @endphp
                                    @include('jkc.media.carousel', [
                                        'variant' => 'crossfade',
                                        'slides' => $_slidesBasic,
                                        'autoplay' => true,
                                        'loop' => true,
                                        'height' => 'h-56',
                                    ])
                                @endverbatim
                            </div>
                            <div>
                                <p class="text-xs font-semibold uppercase text-on-surface/60 dark:text-on-surface-dark/60 mb-2">
                                    Variant: Individual Interval</p>
                                @verbatim
                                    @php
                                        $_slidesInterval = [
                                            [
                                                'img' =>
                                                    'https://images.unsplash.com/photo-1539571696357-5a69c17a67c6?q=80&w=900&auto=format&fit=crop',
                                                'alt' => 'Person 1',
                                                'interval' => 2000,
                                            ],
                                            [
                                                'img' =>
                                                    'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?q=80&w=900&auto=format&fit=crop',
                                                'alt' => 'Person 2',
                                                'interval' => 4000,
                                            ],
                                        ];
                                    @endphp
                                    @include('jkc.media.carousel', [
                                        'variant' => 'individual-interval',
                                        'slides' => $_slidesInterval,
                                        'loop' => true,
                                        'height' => 'h-56',
                                    ])
                                @endverbatim
                            </div>
                        </div>
                    @endcomponent
                @endcomponent

                {{-- 4. Disable Touch Swiping & Dark Variant --}}
                @component('partials.section', [
                    'title' => 'Disable Touch Swiping & Dark Variant',
                    'description' => 'Fixed slides without touch drag features, and high-contrast dark theme styling.',
                ])
                    @component('partials.example')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <p class="text-xs font-semibold uppercase text-on-surface/60 dark:text-on-surface-dark/60 mb-2">
                                    Variant: No Touch Swiping</p>
                                @verbatim
                                    @php
                                        $_slidesCaption = [
                                            [
                                                'img' =>
                                                    'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?q=80&w=900&auto=format&fit=crop',
                                                'alt' => 'Sunrise beach',
                                                'caption_title' => 'Sunrise above a beach',
                                            ],
                                            [
                                                'img' =>
                                                    'https://images.unsplash.com/photo-1519046904884-53103b34b206?q=80&w=900&auto=format&fit=crop',
                                                'alt' => 'Ocean waves',
                                                'caption_title' => 'Ocean from above',
                                            ],
                                        ];
                                    @endphp
                                    @include('jkc.media.carousel', [
                                        'variant' => 'no-touch',
                                        'slides' => $_slidesCaption,
                                        'loop' => true,
                                        'height' => 'h-56',
                                    ])
                                @endverbatim
                            </div>
                            <div>
                                <p class="text-xs font-semibold uppercase text-on-surface/60 dark:text-on-surface-dark/60 mb-2">
                                    Variant: Dark Variant</p>
                                @verbatim
                                    @php
                                        $_slidesDark = [
                                            [
                                                'img' =>
                                                    'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?q=80&w=900&auto=format&fit=crop',
                                                'alt' => 'Mountain',
                                                'caption_title' => 'Drawing a sketch',
                                            ],
                                            [
                                                'img' =>
                                                    'https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?q=80&w=900&auto=format&fit=crop',
                                                'alt' => 'Summit',
                                                'caption_title' => 'Summit at dusk',
                                            ],
                                        ];
                                    @endphp
                                    @include('jkc.media.carousel', [
                                        'variant' => 'dark',
                                        'slides' => $_slidesDark,
                                        'loop' => true,
                                        'height' => 'h-56',
                                        'dark' => true,
                                    ])
                                @endverbatim
                            </div>
                        </div>
                    @endcomponent
                @endcomponent

                {{-- 5. 3D Coverflow Effect --}}
                @component('partials.section', [
                    'title' => '3D Coverflow Effect',
                    'description' => 'Interactive 3D coverflow transition for featuring central elements.',
                ])
                    @component('partials.example')
                        @verbatim
                            @php
                                $_slidesCoverflow = [
                                    [
                                        'img' =>
                                            'https://images.unsplash.com/photo-1469474968028-56623f02e42e?q=80&w=600&auto=format&fit=crop',
                                        'alt' => 'Nature 1',
                                    ],
                                    [
                                        'img' =>
                                            'https://images.unsplash.com/photo-1426604966848-d7adac402bff?q=80&w=600&auto=format&fit=crop',
                                        'alt' => 'Nature 2',
                                    ],
                                    [
                                        'img' =>
                                            'https://images.unsplash.com/photo-1447752875215-b2761acb3c5d?q=80&w=600&auto=format&fit=crop',
                                        'alt' => 'Nature 3',
                                    ],
                                    [
                                        'img' =>
                                            'https://images.unsplash.com/photo-1441974231531-c6227db76b6e?q=80&w=600&auto=format&fit=crop',
                                        'alt' => 'Nature 4',
                                    ],
                                ];
                            @endphp
                            @include('jkc.media.carousel', [
                                'variant' => 'coverflow',
                                'slides' => $_slidesCoverflow,
                                'autoplay' => true,
                                'loop' => true,
                                'height' => 'h-64',
                            ])
                        @endverbatim
                    @endcomponent
                @endcomponent

                {{-- 7. Creative Effect --}}
                @component('partials.section', [
                    'title' => 'Creative Effect',
                    'description' => 'Custom spatial perspective transitions.',
                ])
                    @component('partials.example')
                        @verbatim
                            @php
                                $_slidesCreative = [
                                    [
                                        'img' =>
                                            'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?q=80&w=1200&auto=format&fit=crop',
                                        'alt' => 'Mountain at dusk',
                                        'caption_title' => 'Mountain at dusk',
                                        'caption_text' => 'Typography carries the personality of the page.',
                                    ],
                                    [
                                        'img' =>
                                            'https://images.unsplash.com/photo-1519681393784-d120267933ba?q=80&w=1200&auto=format&fit=crop',
                                        'alt' => 'Snowy peaks',
                                        'caption_title' => 'Snowy peaks',
                                        'caption_text' => 'Structure is information.',
                                    ],
                                ];
                            @endphp
                            @include('jkc.media.carousel', [
                                'variant' => 'creative',
                                'slides' => $_slidesCreative,
                                'loop' => true,
                                'height' => 'h-80',
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
                    <input type="search" x-model="query" placeholder="Filter the Properties…"
                        aria-label="Filter the Properties"
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
                    'description' => 'Full source of the jkc.media.carousel component view.',
                ])
                    @include('partials.source', ['view' => 'jkc.media.carousel'])
                @endcomponent
            </div>
        </aside>

    </div>
@endsection
