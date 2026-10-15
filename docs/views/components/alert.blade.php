@extends('layout')

@section('content')



    @php
        $types = ['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'light', 'dark'];

        $styles = [
            ['default', 'Soft background with a subtle border. This is the default style.'],
            ['borderless', 'Same soft background, without any border.'],
            ['outline', 'Surface background with a colored border.'],
            ['left-border', 'Soft background with a thick accent border on the left.'],
            ['top-border', 'Soft background with a thick accent border on top.'],
            ['solid', 'Solid color background with high contrast text.'],
            ['label-icon', 'Icon inside a rounded square badge. The icon is always shown.'],
            ['label-icon-arrow', 'Same as label-icon, with an arrow on the right.'],
            ['rounded-label', 'Icon inside a round badge. The icon is always shown.'],
        ];

        $rows = [
            [
                'Apparence',
                [
                    [
                        'type',
                        'string',
                        'primary',
                        'One of <code>primary</code>, <code>secondary</code>, <code>success</code>, <code>danger</code>, <code>warning</code>, <code>info</code>, <code>light</code>, <code>dark</code>. <code>light</code> and <code>dark</code> are component variants, not the site dark mode.',
                    ],
                    [
                        'style',
                        'string',
                        'default',
                        'One of <code>default</code>, <code>borderless</code>, <code>outline</code>, <code>left-border</code>, <code>top-border</code>, <code>solid</code>, <code>label-icon</code>, <code>label-icon-arrow</code>, <code>rounded-label</code>.',
                    ],
                    [
                        'icon',
                        'bool|string',
                        'false',
                        '<code>true</code> shows the icon of the type. A string starting with <code>&lt;</code> is used as a custom SVG, so pass trusted markup only. Always on for <code>label-*</code> styles.',
                    ],
                    [
                        'class',
                        'string',
                        "''",
                        'Extra CSS classes, for example margins like <code>mb-4</code>. The component has no outer margin.',
                    ],
                    [
                        'attrs',
                        'string',
                        "''",
                        'Extra HTML attributes as a trusted string, for example <code>data-test="x"</code>.',
                    ],
                ],
            ],
            [
                'Contenu',
                [
                    [
                        'message',
                        'string',
                        "''",
                        'Message content. Rendered as <strong>raw HTML</strong>: never pass unescaped user input.',
                    ],
                    ['title', 'string|null', 'null', 'Bold title shown above the message. Escaped.'],
                    ['link', 'string|null', 'null', 'URL of the link shown after the message.'],
                    ['linkText', 'string', "'Learn more'", 'Text of the link.'],
                ],
            ],
            [
                'Comportement',
                [
                    [
                        'dismissible',
                        'bool',
                        'false',
                        'Adds a close button and fires a <code>jkc-alert-close</code> event. Requires Alpine.js.',
                    ],
                    [
                        'live',
                        'bool',
                        'false',
                        'The alert is hidden until a <code>jkc-alert</code> event is received. Requires Alpine.js.',
                    ],
                    [
                        'id',
                        'string|null',
                        'null',
                        'HTML id. With <code>live</code>, only events with the same <code>detail.id</code> show the alert.',
                    ],
                    ['closeLabel', 'string', "'Close'", 'Accessible label of the close button.'],
                ],
            ],
        ];
    @endphp

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
                    Feedbacks
                </p>
                <h1 class="mt-1 font-title text-3xl font-semibold text-on-surface-strong dark:text-on-surface-dark-strong">
                    Alert
                </h1>
                <p class="mt-2 max-w-2xl font-paragraph text-on-surface/70 dark:text-on-surface-dark/70">
                    Display a short, contextual message to the user. Eight color types, nine styles, optional icon,
                    title, link, dismiss button, and a live mode that appears on demand.
                </p>
                <div class="mt-4 flex flex-wrap items-center gap-2">
                    <span
                        class="rounded-full border border-primary/30 bg-primary/10 px-3 py-1 font-code text-xs text-primary dark:border-primary-dark/30 dark:bg-primary-dark/10 dark:text-primary-dark">8
                        types</span>
                    <span
                        class="rounded-full border border-primary/30 bg-primary/10 px-3 py-1 font-code text-xs text-primary dark:border-primary-dark/30 dark:bg-primary-dark/10 dark:text-primary-dark">9
                        styles</span>
                    <span
                        class="rounded-full border border-outline bg-surface-alt px-3 py-1 font-code text-xs text-on-surface dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark">Alpine.js
                        requis : dismissible &amp; live</span>
                </div>
            </header>

            
            <h2 class="font-title text-2xl font-semibold text-on-surface-strong dark:text-on-surface-dark-strong">
                Usage
            </h2>
            <p class="mt-2 font-paragraph text-on-surface/70 dark:text-on-surface-dark/70">
                The component in action: basic usage, the eight types, the nine styles, and the two Alpine.js interactions.
            </p>

            <div class="mt-6 grid grid-cols-1 gap-6 md:grid-cols-2">

                {{-- Basic Usage — pleine largeur --}}
                <div class="md:col-span-2">
                    @component('partials.section', [
                        'title' => 'Basic Usage',
                        'description' => 'Include the view and pass the props as an array.',
                    ])
                        @component('partials.example')
                            @verbatim
                                @include('jkc.feedbacks.alert', [
                                    'type' => 'success',
                                    'title' => 'Profile updated',
                                    'message' => 'Your changes have been saved.',
                                    'icon' => true,
                                    'dismissible' => true,
                                ])
                            @endverbatim
                        @endcomponent
                    @endcomponent
                </div>

                {{-- Types — pleine largeur (8 alertes) --}}
                <div class="md:col-span-2">
                    @component('partials.section', [
                        'title' => 'Types',
                        'description' => 'The eight color types with the default style.',
                    ])
                        <div class="grid gap-3 md:grid-cols-2">
                            @foreach ($types as $type)
                                @include('jkc.feedbacks.alert', [
                                    'type' => $type,
                                    'icon' => true,
                                    'message' => '<strong>' . ucfirst($type) . '</strong> alert message.',
                                ])
                            @endforeach
                        </div>
                    @endcomponent
                </div>

                {{-- Styles — pleine largeur avec sélecteur --}}
                <div class="md:col-span-2">
                    @component('partials.section', [
                        'title' => 'Styles',
                        'description' => 'Nine presentation styles, applicable to every type. Select a style to preview it.',
                    ])
                        <div x-data="{ style: 'default' }">
                            <div class="mb-5 flex flex-wrap gap-2" role="tablist" aria-label="Styles">
                                @foreach ($styles as $s)
                                    <button type="button" role="tab" @click="style = '{{ $s[0] }}'"
                                        :aria-selected="style === '{{ $s[0] }}'"
                                        :class="style === '{{ $s[0] }}'
                                            ?
                                            'border-primary bg-primary text-on-primary dark:border-primary-dark dark:bg-primary-dark dark:text-on-primary-dark' :
                                            'border-outline bg-transparent text-on-surface/70 hover:border-primary hover:text-primary dark:border-outline-dark dark:text-on-surface-dark/70 dark:hover:border-primary-dark dark:hover:text-primary-dark'"
                                        class="rounded-full border px-3.5 py-1.5 font-code text-xs transition">
                                        {{ $s[0] }}
                                    </button>
                                @endforeach
                            </div>

                            @foreach ($styles as $s)
                                <div x-show="style === '{{ $s[0] }}'" x-transition.opacity.duration.150ms>
                                    <p
                                        class="mb-4 font-paragraph text-sm italic text-on-surface/60 dark:text-on-surface-dark/60">
                                        {{ $s[1] }}
                                    </p>
                                    @component('partials.example', ['replace' => ['STYLE_NAME' => $s[0]]])
                                        @verbatim
                                            <div class="space-y-3">
                                                @foreach (['primary', 'secondary', 'success', 'danger', 'warning', 'info', 'light', 'dark'] as $type)
                                                    @include('jkc.feedbacks.alert', [
                                                        'type' => $type,
                                                        'style' => 'STYLE_NAME',
                                                        'icon' => true,
                                                        'message' => ucfirst($type) . ' alert message.',
                                                    ])
                                                @endforeach
                                            </div>
                                        @endverbatim
                                    @endcomponent
                                </div>
                            @endforeach
                        </div>
                    @endcomponent
                </div>

                {{-- Dismissible — colonne 1 --}}
                @component('partials.section', [
                    'title' => 'Dismissible',
                    'description' => 'Adds a close button. Fires a jkc-alert-close event on window when closed.',
                ])
                    @component('partials.example')
                        @verbatim
                            <div class="space-y-3" x-on:jkc-alert-close.window="console.log('Alert closed:', $event.detail)">
                                @include('jkc.feedbacks.alert', [
                                    'id' => 'alert-success-demo',
                                    'type' => 'success',
                                    'icon' => true,
                                    'dismissible' => true,
                                    'message' => 'Dismiss me with the button. Check console for event details.',
                                ])
                                @include('jkc.feedbacks.alert', [
                                    'type' => 'danger',
                                    'style' => 'solid',
                                    'icon' => true,
                                    'dismissible' => true,
                                    'message' => 'A solid dismissible alert.',
                                ])
                            </div>
                        @endverbatim
                    @endcomponent
                @endcomponent

                {{-- Live — colonne 2 --}}
                @component('partials.section', [
                    'title' => 'Live',
                    'description' => 'Hidden until a jkc-alert event is dispatched with its id.',
                ])
                    @component('partials.example')
                        @verbatim
                            <div class="space-y-3">
                                <button type="button"
                                    onclick="window.dispatchEvent(new CustomEvent('jkc-alert', { detail: { id: 'saved-alert' } }))"
                                    class="rounded-radius border border-primary bg-primary px-3 py-1.5 text-xs font-medium tracking-wide text-on-primary transition hover:opacity-75 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary dark:border-primary-dark dark:bg-primary-dark dark:text-on-primary-dark dark:focus-visible:outline-primary-dark">
                                    Show alert
                                </button>

                                @include('jkc.feedbacks.alert', [
                                    'id' => 'saved-alert',
                                    'live' => true,
                                    'dismissible' => true,
                                    'type' => 'success',
                                    'icon' => true,
                                    'message' => 'This alert was triggered by an event.',
                                ])
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
                    'description' => 'Full source of the jkc.feedbacks.alert component view.',
                ])
                    @include('partials.source', ['view' => 'jkc.feedbacks.alert'])
                @endcomponent
            </div>
        </aside>

    </div>
@endsection
