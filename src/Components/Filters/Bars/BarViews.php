<?php

declare(strict_types=1);

namespace Juksgraphic\BladeComponents\Components\Filters\Bars;

/**
 * Blade view names, single source.
 *
 * Names only, no HTML. Layout keys are BarStyles::LAYOUTS values.
 */
final class BarViews
{
    /** Dot-notation prefix of every filter bar view. */
    public const BASE = 'jkc-internal.filters';

    /** Partial view names by key. */
    public const ITEMS = [
        'SEARCH' => self::BASE . '.partials.search',
        'FILTERS' => self::BASE . '.partials.filters',
        'SELECT' => self::BASE . '.partials.select',
        'RESET' => self::BASE . '.partials.reset',
    ];

    /** Layout view names by layout value. */
    public const LAYOUTS = [
        BarStyles::LAYOUTS['ROW'] => self::BASE . '.layouts.row',
        BarStyles::LAYOUTS['PANEL'] => self::BASE . '.layouts.panel',
    ];

    /**
     * Gets the view name of a layout.
     *
     * @param string $layout A layout value, e.g. "row".
     * @return string Dot-notation view name.
     */
    public static function layout(string $layout): string
    {
        return self::LAYOUTS[$layout] ?? self::LAYOUTS[BarStyles::LAYOUTS['ROW']];
    }
}
