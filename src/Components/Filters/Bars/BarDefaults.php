<?php

declare(strict_types=1);

namespace Juksgraphic\BladeComponents\Components\Filters\Bars;

/**
 * Default configuration values.
 */
final class BarDefaults
{
    /** Default values by name. */
    public const ITEMS = [
        'PAGE' => 1,
        'PER_PAGE' => 10,
        'DEBOUNCE' => 300,
        'PLACEHOLDER' => 'Recherche...',
        'VARIANT' => BarVariants::ITEMS['INLINE'],
        'SORT' => null,
        'DIRECTION' => BarDirections::ITEMS['ASC'],
    ];
}
