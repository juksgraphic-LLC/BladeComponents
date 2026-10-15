@extends('layout')

@section('content')


    @php
        $sizes = [
            ['modal-sm', 'sm', 'Small'],
            ['modal-lg', 'lg', 'Large'],
            ['modal-xl', 'xl', 'Extra Large'],
            ['modal-fs', 'fullscreen', 'Fullscreen'],
        ];

        $rows = [
            [
                'Apparence',
                [
                    [
                        'size',
                        'string',
                        "'md'",
                        'One of <code>sm</code>, <code>md</code> (default), <code>lg</code>, <code>xl</code>, <code>fullscreen</code>.',
                    ],
                    [
                        'position',
                        'string',
                        "'center'",
                        'One of <code>center</code>, <code>top</code>, <code>bottom</code>, <code>bottom-right</code>, …',
                    ],
                    [
                        'animation',
                        'string',
                        "'zoom'",
                        'One of <code>zoom</code>, <code>fade-up</code>, <code>flip</code>.',
                    ],
                    ['class', 'string', "''", 'Extra CSS classes applied to the modal panel.'],
                    ['attrs', 'string', "''", 'Extra HTML attributes as a trusted string.'],
                ],
            ],
            [
                'Contenu',
                [
                    ['title', 'string|null', 'null', 'Default modal title rendered in the built-in header. Escaped.'],
                    [
                        'header',
                        'slot',
                        '—',
                        "Named slot <code> slot(\'header\') </code> replaces the default header entirely.",
                    ],
                    ['body', 'slot', '—', "Named slot <code> slot(\'body\')</code> for the main content."],
                    [
                        'footer',
                        'slot',
                        '—',
                        "Named slot <code> slot(\'footer\')</code> replaces the default footer buttons.",
                    ],
                ],
            ],
            [
                'Comportement',
                [
                    [
                        'id',
                        'string',
                        'required',
                        'Unique identifier. Used as the payload of the <code>jkc-open-modal</code> / <code>jkc-close-modal</code> events.',
                    ],
                    [
                        'static',
                        'bool',
                        'false',
                        '<code>true</code> keeps the modal open when clicking the backdrop or pressing Escape.',
                    ],
                    ['closeLabel', 'string', "'Close'", 'Accessible label of the close button.'],
                ],
            ],
            [
                'Événements',
                [
                    [
                        'jkc-open-modal',
                        'window event',
                        '—',
                        "Dispatch with <code>detail = modal-id</code> to open the modal.",
                    ],
                    [
                        'jkc-close-modal',
                        'window event',
                        '—',
                        "Dispatch with <code>detail = modal-id</code> to close the modal.",
                    ],
                    [
                        'jkc-on-open-modal',
                        'window event',
                        '—',
                        'Fired by the component when opened. Detail: <code>{ id }</code>.',
                    ],
                    [
                        'jkc-on-close-modal',
                        'window event',
                        '—',
                        'Fired by the component when closed. Detail: <code>{ id }</code>.',
                    ],
                ],
            ],
        ];
    @endphp

    {{-- Conteneur global des événements de démo --}}
    <div x-on:jkc-on-open-modal.window="console.log('Modal ouverte :', $event.detail.id)"
        x-on:jkc-on-close-modal.window="console.log('Modal fermée :', $event.detail.id)">

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-3">



            <section id="utilisations" class="scroll-mt-6 lg:col-span-2">

                {{-- ── En-tête ─────────────────────────────────────────────── --}}
                <header class="mb-10">
                    <p class="font-code text-xs uppercase tracking-wide text-primary dark:text-primary-dark">
                        Overlays
                    </p>
                    <h1
                        class="mt-1 font-title text-3xl font-semibold text-on-surface-strong dark:text-on-surface-dark-strong">
                        Modal
                    </h1>
                    <p class="mt-2 max-w-2xl font-paragraph text-on-surface/70 dark:text-on-surface-dark/70">
                        Display dialogs, lightboxes, or user interaction windows. Supports custom sizes, positions,
                        animations,
                        scrollable content, and named slots.
                    </p>
                    <div class="mt-4 flex flex-wrap items-center gap-2">
                        <span
                            class="rounded-full border border-primary/30 bg-primary/10 px-3 py-1 font-code text-xs text-primary dark:border-primary-dark/30 dark:bg-primary-dark/10 dark:text-primary-dark">4
                            tailles</span>
                        <span
                            class="rounded-full border border-primary/30 bg-primary/10 px-3 py-1 font-code text-xs text-primary dark:border-primary-dark/30 dark:bg-primary-dark/10 dark:text-primary-dark">3
                            positions</span>
                        <span
                            class="rounded-full border border-primary/30 bg-primary/10 px-3 py-1 font-code text-xs text-primary dark:border-primary-dark/30 dark:bg-primary-dark/10 dark:text-primary-dark">3
                            animations</span>
                        <span
                            class="rounded-full border border-outline bg-surface-alt px-3 py-1 font-code text-xs text-on-surface dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark">Alpine.js
                            requis</span>
                    </div>
                </header>

                <h2 class="font-title text-2xl font-semibold text-on-surface-strong dark:text-on-surface-dark-strong">
                    Usage
                </h2>
                <p class="mt-2 font-paragraph text-on-surface/70 dark:text-on-surface-dark/70">
                    The component in action: standard usage, sizes, positions &amp; animations, and custom slots.
                </p>

                <div class="mt-6 grid grid-cols-1 gap-6 md:grid-cols-2">

                    {{-- 1. Standard Modal — colonne 1 --}}
                    @component('partials.section', [
                        'title' => 'Standard Modal',
                        'description' => 'A basic modal with default title, body, and footer buttons.',
                    ])
                        @component('partials.example')
                            @verbatim
                                <button type="button"
                                    onclick="window.dispatchEvent(new CustomEvent('jkc-open-modal', { detail: 'modal-standard' }))"
                                    class="rounded-radius bg-primary px-4 py-2 text-sm font-medium text-on-primary transition hover:bg-primary/90 dark:bg-primary-dark dark:text-on-primary-dark dark:hover:bg-primary-dark/90">
                                    Open Standard Modal
                                </button>

                                @component('jkc.modals.modal', ['id' => 'modal-standard', 'title' => 'Welcome Modal'])
                                    @slot('body')
                                        <p class="text-on-surface/80 dark:text-on-surface-dark/80">This is the body content of the standard
                                            modal window.</p>
                                    @endslot
                                @endcomponent
                            @endverbatim
                        @endcomponent
                    @endcomponent

                    {{-- 2. Sizes — colonne 2 --}}
                    @component('partials.section', [
                        'title' => 'Modal Sizes',
                        'description' => 'Available sizes: <code>sm</code>, <code>lg</code>, <code>xl</code>, and <code>fullscreen</code>.',
                    ])
                        @component('partials.example')
                            @verbatim
                                <div class="flex flex-wrap gap-3">
                                    <button type="button"
                                        onclick="window.dispatchEvent(new CustomEvent('jkc-open-modal', { detail: 'modal-sm' }))"
                                        class="rounded-radius border border-outline bg-surface-alt px-3 py-1.5 text-xs font-medium text-on-surface transition hover:border-primary dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark dark:hover:border-primary-dark">Small</button>
                                    <button type="button"
                                        onclick="window.dispatchEvent(new CustomEvent('jkc-open-modal', { detail: 'modal-lg' }))"
                                        class="rounded-radius border border-outline bg-surface-alt px-3 py-1.5 text-xs font-medium text-on-surface transition hover:border-primary dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark dark:hover:border-primary-dark">Large</button>
                                    <button type="button"
                                        onclick="window.dispatchEvent(new CustomEvent('jkc-open-modal', { detail: 'modal-xl' }))"
                                        class="rounded-radius border border-outline bg-surface-alt px-3 py-1.5 text-xs font-medium text-on-surface transition hover:border-primary dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark dark:hover:border-primary-dark">Extra
                                        Large</button>
                                    <button type="button"
                                        onclick="window.dispatchEvent(new CustomEvent('jkc-open-modal', { detail: 'modal-fs' }))"
                                        class="rounded-radius border border-outline bg-surface-alt px-3 py-1.5 text-xs font-medium text-on-surface transition hover:border-primary dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark dark:hover:border-primary-dark">Fullscreen</button>
                                </div>

                                @component('jkc.modals.modal', ['id' => 'modal-sm', 'size' => 'sm', 'title' => 'Small Modal'])
                                    @slot('body')
                                        <p>Small modal body content.</p>
                                    @endslot
                                @endcomponent

                                @component('jkc.modals.modal', ['id' => 'modal-lg', 'size' => 'lg', 'title' => 'Large Modal'])
                                    @slot('body')
                                        <p>Large modal body content.</p>
                                    @endslot
                                @endcomponent

                                @component('jkc.modals.modal', ['id' => 'modal-xl', 'size' => 'xl', 'title' => 'Extra Large Modal'])
                                    @slot('body')
                                        <p>Extra large modal body content.</p>
                                    @endslot
                                @endcomponent

                                @component('jkc.modals.modal', ['id' => 'modal-fs', 'size' => 'fullscreen', 'title' => 'Fullscreen Modal'])
                                    @slot('body')
                                        <p>Fullscreen modal body content.</p>
                                    @endslot
                                @endcomponent
                            @endverbatim
                        @endcomponent
                    @endcomponent

                    {{-- 3. Positions & Animations — colonne 1 --}}
                    @component('partials.section', [
                        'title' => 'Positions & Animations',
                        'description' =>
                            'Position modals at the top, bottom, or corner, and combine them with smooth animations (zoom, fade-up, flip).',
                    ])
                        @component('partials.example')
                            @verbatim
                                <div class="flex flex-wrap gap-3">
                                    <button type="button"
                                        onclick="window.dispatchEvent(new CustomEvent('jkc-open-modal', { detail: 'modal-top' }))"
                                        class="rounded-radius border border-outline bg-surface-alt px-3 py-1.5 text-xs font-medium text-on-surface transition hover:border-primary dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark dark:hover:border-primary-dark">Top
                                        Position (Zoom)</button>
                                    <button type="button"
                                        onclick="window.dispatchEvent(new CustomEvent('jkc-open-modal', { detail: 'modal-bottom-right' }))"
                                        class="rounded-radius border border-outline bg-surface-alt px-3 py-1.5 text-xs font-medium text-on-surface transition hover:border-primary dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark dark:hover:border-primary-dark">Bottom
                                        Right (Fade Up)</button>
                                </div>

                                @component('jkc.modals.modal', [
                                    'id' => 'modal-top',
                                    'position' => 'top',
                                    'animation' => 'zoom',
                                    'title' => 'Top Position Modal',
                                ])
                                    @slot('body')
                                        <p>This modal appears at the top with a zoom effect.</p>
                                    @endslot
                                @endcomponent

                                @component('jkc.modals.modal', [
                                    'id' => 'modal-bottom-right',
                                    'position' => 'bottom-right',
                                    'animation' => 'fade-up',
                                    'title' => 'Notification Style',
                                ])
                                    @slot('body')
                                        <p>Appears at the bottom right corner.</p>
                                    @endslot
                                @endcomponent
                            @endverbatim
                        @endcomponent
                    @endcomponent

                    {{-- 4. Custom Slots — colonne 2 --}}
                    @component('partials.section', [
                        'title' => 'Custom Slots',
                        'description' => 'Use named slots to completely customize header, body, and footer content.',
                    ])
                        @component('partials.example')
                            @verbatim
                                <button type="button"
                                    onclick="window.dispatchEvent(new CustomEvent('jkc-open-modal', { detail: 'modal-custom' }))"
                                    class="rounded-radius bg-primary px-4 py-2 text-sm font-medium text-on-primary transition hover:bg-primary/90 dark:bg-primary-dark dark:text-on-primary-dark dark:hover:bg-primary-dark/90">
                                    Open Custom Modal
                                </button>

                                @component('jkc.modals.modal', ['id' => 'modal-custom', 'static' => true])
                                    @slot('header')
                                        <div class="flex items-center gap-2">
                                            <span class="size-3 rounded-full bg-success"></span>
                                            <h3 class="font-title text-lg font-bold text-on-surface-strong dark:text-on-surface-dark-strong">
                                                Custom Header Slot</h3>
                                        </div>
                                    @endslot

                                    @slot('body')
                                        <form class="space-y-4">
                                            <div>
                                                <label
                                                    class="mb-1 block text-xs font-semibold uppercase text-on-surface/70 dark:text-on-surface-dark/70">Email
                                                    address</label>
                                                <input type="email"
                                                    class="w-full rounded-radius border border-outline bg-surface p-2 font-paragraph text-sm text-on-surface placeholder:text-on-surface/40 focus:border-primary focus:outline-none dark:border-outline-dark dark:bg-surface-dark dark:text-on-surface-dark dark:placeholder:text-on-surface-dark/40 dark:focus:border-primary-dark"
                                                    placeholder="name@example.com">
                                            </div>
                                        </form>
                                    @endslot

                                    @slot('footer')
                                        <button type="button"
                                            onclick="window.dispatchEvent(new CustomEvent('jkc-close-modal', { detail: 'modal-custom' }))"
                                            class="rounded-radius border border-outline bg-surface-alt px-4 py-2 text-sm font-medium text-on-surface transition hover:border-primary dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark dark:hover:border-primary-dark">Cancel</button>
                                        <button type="button"
                                            onclick="window.dispatchEvent(new CustomEvent('jkc-close-modal', { detail: 'modal-custom' }))"
                                            class="rounded-radius bg-primary px-4 py-2 text-sm font-medium text-on-primary transition hover:bg-primary/90 dark:bg-primary-dark dark:text-on-primary-dark dark:hover:bg-primary-dark/90">Submit</button>
                                    @endslot
                                @endcomponent
                            @endverbatim
                        @endcomponent
                    @endcomponent

                </div>
            </section>

            {{-- ── COLONNE DROITE : PROPS + SOURCE (1/3) ────────── --}}
            <aside id="proprietes" class="scroll-mt-6 lg:col-span-1">
                <h2 class="font-title text-2xl font-semibold text-on-surface-strong dark:text-on-surface-dark-strong">
                    Properties
                </h2>
                <p class="mt-2 font-paragraph text-on-surface/70 dark:text-on-surface-dark/70">
                    All props, slots and events of the component, followed by the full source code.
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
                        'description' => 'Full source of the jkc.modals.modal component view.',
                    ])
                        @include('partials.source', ['view' => 'jkc.modals.modal'])
                    @endcomponent
                </div>
            </aside>

        </div>
    </div>
@endsection
