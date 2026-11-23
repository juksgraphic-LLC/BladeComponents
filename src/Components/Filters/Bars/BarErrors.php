<?php

declare(strict_types=1);

namespace Juksgraphic\BladeComponents\Components\Filters\Bars;

/**
 * Translation keys for errors.
 */
final class BarErrors
{
    /**
     * Error keys by name.
     * @var array
     */
    public const ITEMS = [
        'ENDPOINT_REQUIRED' => 'filterbar.error.endpoint_required',
        'FILTER_KEY_INVALID' => 'filterbar.error.filter_key_invalid',
        'FILTER_KEY_DUPLICATE' => 'filterbar.error.filter_key_duplicate',
        'FILTER_TYPE_INVALID' => 'filterbar.error.filter_type_invalid',
        'OPTIONS_INVALID' => 'filterbar.error.options_invalid',
        'VARIANT_INVALID' => 'filterbar.error.variant_invalid',
        'SORT_INVALID' => 'filterbar.error.sort_invalid',
        'DIRECTION_INVALID' => 'filterbar.error.direction_invalid',
    ];
}
