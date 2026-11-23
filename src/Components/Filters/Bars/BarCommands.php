<?php

declare(strict_types=1);

namespace Juksgraphic\BladeComponents\Components\Filters\Bars;

/**
 * Commands received by the bar.
 */
final class BarCommands
{
    /** Command names by key. */
    public const ITEMS = [
        'SET_PAGE' => 'filterbar:set-page',
        'SET_PER_PAGE' => 'filterbar:set-per-page',
        'SET_SORT' => 'filterbar:set-sort',
        'REFRESH' => 'filterbar:refresh',
    ];
}
