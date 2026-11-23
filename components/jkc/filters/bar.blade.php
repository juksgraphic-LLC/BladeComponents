@use(Juksgraphic\BladeComponents\Components\Filters\Bars\Bar)
@use(Juksgraphic\BladeComponents\Components\Filters\Bars\BarStyles)
@use(Juksgraphic\BladeComponents\Components\Filters\Bars\BarViews)
@use(Juksgraphic\BladeComponents\BladeComponents)

@if (BladeComponents::shouldInline('jkc-filters'))
    <script>
        {!! BladeComponents::inline('jkc-filters') !!}
    </script>
@endif


@php
    $config = new Bar($options);
    $data   = $config->toArray();
    $style  = BarStyles::forVariant($config->variant());
    $views  = BarViews::ITEMS;
    $layout = BarViews::layout($style['layout']);
    $texts  = array_merge(
        [
            'title' => 'Filtres',
            'reset' => 'Réinitialiser',
            'search' => 'Rechercher…',
            'empty' => 'Aucun résultat',
            'clear' => 'Effacer',
            'all' => 'Tous',
            'selected' => 'Sélection',
        ],
        $texts ?? [],
    );
@endphp

@if (!$config->isVisible())
    <div x-data="filterBar({{ $config->toJson() }})" hidden></div>
@else
    @include($layout, compact('config', 'data', 'style', 'texts', 'views'))
@endif
