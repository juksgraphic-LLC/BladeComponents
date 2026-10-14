{{--
    Affiche le code source d'un composant, repliable.
    Usage : @include('partials.source', ['view' => 'jkc.alerts.alert'])
    Le fichier est cherché dans BladeComponents::path().
--}}
@php
    $view = $view ?? '';
    $file = null;

    if (preg_match('/^[a-z0-9_-]+(\.[a-z0-9_-]+)*$/i', $view)) {
        $file = \Juksgraphic\BladeComponents\BladeComponents::path() . '/' . str_replace('.', '/', $view) . '.blade.php';
    }
    
    $code = $file && is_file($file) ? file_get_contents($file) : '{{-- Component not found: {$view} --}}';
@endphp
<div x-data="{ open: false }" class="mt-5 overflow-hidden rounded-radius border border-outline dark:border-outline-dark">
    <button type="button" @click="open = !open" :aria-expanded="open"
        class="flex w-full items-center justify-between bg-surface px-4 py-3 text-left text-sm font-medium text-on-surface transition hover:bg-surface-alt focus-visible:outline-2 focus-visible:-outline-offset-2 focus-visible:outline-primary dark:bg-surface-dark dark:text-on-surface-dark dark:hover:bg-surface-dark-alt dark:focus-visible:outline-primary-dark">
        <span>View component source <span
                class="ml-1 font-code text-xs text-on-surface/60 dark:text-on-surface-dark/60">{{ $view }}</span></span>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
            class="size-4 transition-transform" :class="open ? 'rotate-180' : ''" aria-hidden="true">
            <path d="m6 9 6 6 6-6" />
        </svg>
    </button>
    <div x-show="open" x-collapse x-cloak>
        @include('partials.code', [
            'code' => $code,
            'label' => str_replace('.', '/', $view) . '.blade.php',
        ])
    </div>
</div>
