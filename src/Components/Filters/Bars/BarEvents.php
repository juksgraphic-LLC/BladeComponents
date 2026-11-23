<?php

declare(strict_types=1);

namespace Juksgraphic\BladeComponents\Components\Filters\Bars;

/**
 * Events emitted by the bar.
 */
final class BarEvents
{
    /** Event names by key. */
    public const ITEMS = [
        'CHANGE'   => 'filterbar:change',
        'LOADING'  => 'filterbar:loading',
        'LOADED'   => 'filterbar:loaded',
        'ERROR'    => 'filterbar:error',
        'RESET'    => 'filterbar:reset',
    ];
}