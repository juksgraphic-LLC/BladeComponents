<?php

declare(strict_types=1);

namespace Juksgraphic\BladeComponents\Components\Filters\Pagers;

/**
 * Translation keys for errors.
 */
final class PagerErrors
{
    /**
     * Error keys by name.
     * @var array
     */
    public const ITEMS = [
        'BAR_REQUIRED'             => 'pager.error.bar_required',
        'PER_PAGE_OPTIONS_INVALID' => 'pager.error.per_page_options_invalid',
    ];
}