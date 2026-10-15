@php

    $components = [
        [
            'title' => 'Alerts',
            'description' => 'Short contextual messages with 8 types and 9 styles.',
            'url' => '/components/alert',
            'icon' => 'bx bx-bell',
        ],
        [
            'title' => 'Accordion',
            'description' => 'Collapsible content panels with multiple variants and custom icons.',
            'url' => '/components/accordion',
            'icon' => 'bx bx-list-plus',
        ],
        [
            'title' => 'Dropdowns',
            'description' => 'Toggleable menu overlays for actions, links, and contextual options.',
            'url' => '/components/dropdown',
            'icon' => 'bx bx-menu',
        ],
        [
            'title' => 'Modals',
            'description' => 'Dialog windows with various sizes, positions, and animations.',
            'url' => '/components/modal',
            'icon' => 'bx bx-windows',
        ],
        [
            'title' => 'Cards',
            'description' => 'Flexible containers with headers, footers, and slot support.',
            'url' => '/components/card',
            'icon' => 'bx bx-id-card',
        ],
        [
            'title' => 'Tabs', 
            'description' => 'Organize content into multiple panels with various design variants.', 
            'url' => '/components/tab', 
            'icon' => 'bx bx-tab'
        ],
        [
            'title' => 'Offcanvas',
            'description' => 'Sliding side panels supporting 4 different directions.',
            'url' => '/components/offcanvas',
            'icon' => 'bx bx-layout',
        ],
        [
            'title' => 'Toasts & Notifications',
            'label' => 'Toasts',
            'description' => 'Push notifications and brief messages with a built-in stack manager.',
            'url' => '/components/toast',
            'icon' => 'bx bx-message-square-detail',
        ],
        [
            'title' => 'Carousel',
            'description' => 'Image and content sliders powered by Alpine.js.',
            'url' => '/components/carousel',
            'icon' => 'bx bx-slideshow',
        ],
    ];

    $items = array_merge(
        [['label' => 'Home', 'href' => '/', 'icon' => 'bx bx-home'], ['heading' => 'Composants']],
        array_map(
            fn($c) => [
                'label' => $c['label'] ?? $c['title'],
                'href' => $c['url'],
                'icon' => $c['icon'],
            ],
            $components,
        ),
    );
@endphp
<!DOCTYPE html>
<html lang="en" x-data="{ dark: document.documentElement.classList.contains('dark') }" x-on:jkc-theme-dark.window="dark = true"
    x-on:jkc-theme-light.window="dark = false" x-bind:class="{ 'dark': dark }"
    x-bind:style="{ colorScheme: dark ? 'dark' : 'light' }">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Juksgraphic Components</title>

    <script>
        try {
            if (localStorage.getItem('dark') === '1') {
                document.documentElement.classList.add('dark');
                document.documentElement.style.colorScheme = 'dark';
            }
        } catch (e) {}
    </script>

    <script type="module" src="/build/app.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@2.1.4/css/boxicons.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Google+Sans:ital,opsz,wght@0,17..18,400..700;1,17..18,400..700&display=swap">
    <link rel="stylesheet" href="/build/app.css">
</head>

<body hx-boost:inherited="true"
    class="bg-surface dark:bg-surface-dark scroll-smooth text-on-surface dark:text-on-surface-dark font-paragraph">

    <div x-cloak x-data="{ sidebarIsOpen: false }" class="relative flex w-full flex-col md:flex-row">

        <a class="sr-only" href="#main-content" hx-boost="false">Aller au contenu principal</a>

        @include('jkc.sidebars.sidebar', [
            'items' => $items,
            'searchLabel' => 'Rechercher',
            'search' => false,
            'navLabel' => 'Navigation',
        ])

        <div id="page-scroll" class="h-svh w-full min-w-0 overflow-y-auto bg-surface dark:bg-surface-dark">

            <header
                class="sticky top-0 z-10 flex items-center gap-4 border-b border-outline bg-surface-alt px-4 py-4 dark:border-outline-dark dark:bg-surface-dark-alt">

                {{-- Sidebar toggle (mobile) --}}
                <button type="button" class="md:hidden" x-on:click="sidebarIsOpen = true">
                    <i class="bx bx-menu text-2xl text-primary dark:text-primary-dark"></i>
                    <span class="sr-only">Afficher le sidebar</span>
                </button>

                @include('jkc.switches.theme', [
                    'storageKey' => 'dark',
                    'label' => 'Activer le mode sombre',
                    'class' => 'shrink-0',
                ])

                {{-- Recherche : un seul bouton, icône seule en mobile, champ complet dès md --}}
                <button type="button" aria-label="Rechercher un composant"
                    x-on:click="$dispatch('jkc-open-modal', 'site-search-modal')"
                    class="ml-auto flex h-9 w-9 items-center justify-center gap-4 rounded-full border border-outline bg-surface text-sm text-on-surface/70 transition-colors hover:border-primary/30 dark:border-outline-dark dark:bg-surface-dark dark:text-on-surface-dark/70 md:mx-auto md:ml-0 md:w-full md:max-w-xl md:flex-1 md:justify-between md:px-3">
                    <span class="flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" aria-hidden="true">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.3-4.3"></path>
                        </svg>
                        <span class="hidden text-on-surface/50 dark:text-on-surface-dark/50 md:inline">Search a
                            component...</span>
                    </span>
                    <span class="hidden items-center gap-1 md:flex">
                        @foreach (['Ctrl', 'K'] as $key)
                            <kbd
                                class="inline-flex h-5 items-center rounded border border-outline bg-surface-alt px-1.5 text-[10px] font-medium text-on-surface/60 dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark/60">{{ $key }}</kbd>
                        @endforeach
                    </span>
                </button>
            </header>

            <main id="main-content" class="p-4">
                @yield('content')
            </main>
        </div>
    </div>

    @include('partials.search-modal', ['components' => $components])
</body>

</html>
