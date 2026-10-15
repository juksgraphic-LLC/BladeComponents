@extends('layout')

@section('content')
    <div class="space-y-10">
        {{-- En-tête de la page --}}
        <div class="border-b border-outline dark:border-outline-dark pb-8">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-radius bg-surface-alt dark:bg-surface-dark-alt border border-outline dark:border-outline-dark text-xs font-semibold mb-3">
                <i class='bx bx-code-alt text-primary'></i> Cross-Framework Component Library
            </div>
            <h1 class="font-title text-3xl md:text-4xl font-bold text-on-surface-strong dark:text-on-surface-dark-strong tracking-tight">
                Juksgraphic Components
            </h1>
            <p class="text-base text-on-surface/80 dark:text-on-surface-dark/80 mt-2 font-paragraph">
                A professional UI component library engineered with Tailwind CSS, Alpine.js, and Penguin UI principles. Works seamlessly with Laravel Blade and standalone PHP via BladeOne.
            </p>
        </div>

        {{-- Layout 2 Colonnes --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            {{-- Colonne de Gauche : Vue d'ensemble, Installation & Stack --}}
            <div class="lg:col-span-5 space-y-6">
                
                {{-- Block Installation --}}
                <div class="p-5 rounded-radius border border-outline dark:border-outline-dark bg-surface dark:bg-surface-dark">
                    <h3 class="font-title text-base font-semibold mb-3 text-on-surface-strong dark:text-on-surface-dark-strong flex items-center gap-2">
                        <i class='bx bx-download text-primary'></i> Quick Installation
                    </h3>
                    <p class="text-xs text-on-surface/70 dark:text-on-surface-dark/70 mb-3 font-paragraph">
                        Install the package via Composer to integrate the component library into your project.
                    </p>
                    <pre class="p-3 text-xs rounded-radius bg-surface-alt dark:bg-surface-dark-alt border border-outline dark:border-outline-dark overflow-x-auto font-mono"><code>composer require juksgraphic/bladecomponents</code></pre>
                </div>

                {{-- Block Environment & Tech Stack --}}
                <div class="p-5 rounded-radius border border-outline dark:border-outline-dark bg-surface dark:bg-surface-dark">
                    <h3 class="font-title text-base font-semibold mb-3 text-on-surface-strong dark:text-on-surface-dark-strong flex items-center gap-2">
                        <i class='bx bx-cog text-primary'></i> Compatibility Requirements
                    </h3>
                    <ul class="space-y-2 text-xs font-paragraph text-on-surface/80 dark:text-on-surface-dark/80">
                        <li class="flex items-center justify-between p-2 rounded-radius bg-surface-alt dark:bg-surface-dark-alt border border-outline dark:border-outline-dark">
                            <span class="flex items-center gap-2"><i class='bx bx-check-circle text-success'></i> PHP Runtime</span>
                            <span class="font-mono font-medium">8.2+</span>
                        </li>
                        <li class="flex items-center justify-between p-2 rounded-radius bg-surface-alt dark:bg-surface-dark-alt border border-outline dark:border-outline-dark">
                            <span class="flex items-center gap-2"><i class='bx bx-check-circle text-success'></i> Blade Engine</span>
                            <span class="font-mono font-medium">Laravel / BladeOne v5</span>
                        </li>
                        <li class="flex items-center justify-between p-2 rounded-radius bg-surface-alt dark:bg-surface-dark-alt border border-outline dark:border-outline-dark">
                            <span class="flex items-center gap-2"><i class='bx bx-check-circle text-success'></i> Testing Suite</span>
                            <span class="font-mono font-medium">Pest v4</span>
                        </li>
                    </ul>
                </div>

                {{-- Block Repository Link --}}
                <div class="p-5 rounded-radius border border-outline dark:border-outline-dark bg-surface dark:bg-surface-dark">
                    <h3 class="font-title text-base font-semibold mb-2 text-on-surface-strong dark:text-on-surface-dark-strong flex items-center gap-2">
                        <i class='bx bxl-github text-primary'></i> Open Source
                    </h3>
                    <p class="text-xs text-on-surface/70 dark:text-on-surface-dark/70 mb-4 font-paragraph">
                        Explore the codebase, contribute, or report issues on GitHub.
                    </p>
                    <a href="https://github.com/juksgraphic/bladecomponents" target="_blank" class="w-full py-2.5 px-4 text-xs font-medium text-on-primary bg-primary hover:bg-primary/90 rounded-radius transition-colors inline-flex items-center justify-center gap-2">
                        <i class='bx bxl-github text-sm'></i> Visit GitHub Repository
                    </a>
                </div>

            </div>

            {{-- Colonne de Droite : Architecture, Caractéristiques & Composants Live --}}
            <div class="lg:col-span-7 space-y-6">
                
                {{-- Section Features Grid --}}
                <div class="space-y-4">
                    <div class="border-b border-outline dark:border-outline-dark pb-2">
                        <h2 class="font-title text-lg font-semibold text-on-surface-strong dark:text-on-surface-dark-strong flex items-center gap-2">
                            <i class='bx bx-layer text-primary'></i> Key Features
                        </h2>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="p-4 rounded-radius border border-outline dark:border-outline-dark bg-surface dark:bg-surface-dark">
                            <div class="size-8 rounded-lg bg-primary/10 text-primary flex items-center justify-center mb-3 text-lg">
                                <i class='bx bx-server'></i>
                            </div>
                            <h4 class="font-title text-sm font-semibold mb-1">Dual Renderer</h4>
                            <p class="text-xs text-on-surface/70 dark:text-on-surface-dark/70 font-paragraph">
                                Engineered to function in Laravel frameworks and lightweight PHP frameworks using <code>BladeOne</code>.
                            </p>
                        </div>

                        <div class="p-4 rounded-radius border border-outline dark:border-outline-dark bg-surface dark:bg-surface-dark">
                            <div class="size-8 rounded-lg bg-success/10 text-success flex items-center justify-center mb-3 text-lg">
                                <i class='bx bx-wind'></i>
                            </div>
                            <h4 class="font-title text-sm font-semibold mb-1">Tailwind & Alpine</h4>
                            <p class="text-xs text-on-surface/70 dark:text-on-surface-dark/70 font-paragraph">
                                Clean, utility-first styling with interactive state handling dispatched seamlessly via Alpine events.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Section Showcase Preview --}}
                <div class="space-y-4 pt-2">
                    <div class="border-b border-outline dark:border-outline-dark pb-2">
                        <h2 class="font-title text-lg font-semibold text-on-surface-strong dark:text-on-surface-dark-strong flex items-center gap-2">
                            <i class='bx bx-show text-primary'></i> Live Showcase
                        </h2>
                    </div>

                    {{-- Alert Component --}}
                    @include('jkc.feedbacks.alert', [
                        'type' => 'success',
                        'style' => 'left-border',
                        'title' => 'System Status',
                        'message' => 'All library components, test suites, and configurations are verified and operational.'
                    ])

                    {{-- Card Component --}}
                    @component('jkc.cards.card', [
                        'title' => 'Component Path Resolution',
                        'subtitle' => 'Integration helper usage'
                    ])
                        @slot('body')
                            <p class="text-xs text-on-surface/80 dark:text-on-surface-dark/80 mb-3 font-paragraph">
                                Configure your view paths dynamically using the package helper class:
                            </p>
                            <pre class="p-3 text-xs rounded-radius bg-surface-alt dark:bg-surface-dark-alt border border-outline dark:border-outline-dark overflow-x-auto font-mono"><code>use Juksgraphic\BladeComponents\BladeComponents;

$path = BladeComponents::path();</code></pre>
                        @endslot
                    @endcomponent
                </div>

            </div>

        </div>
    </div>
@endsection