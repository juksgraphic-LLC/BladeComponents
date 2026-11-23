<?php

declare(strict_types=1);

namespace Juksgraphic\BladeComponents\Components\Filters\Pagers;

/**
 * Penguin UI class sets for the pager.
 *
 * Data only, no HTML. Tailwind must scan this file.
 */
final class PagerStyles
{
    /** Shared class fragments. */
    public const PARTS = [
        'FOCUS' => 'focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary dark:focus-visible:outline-primary-dark',
        'MUTED' => 'text-on-surface/70 dark:text-on-surface-dark/70',
        'BUTTON' => 'inline-flex h-9 items-center justify-center rounded-radius border transition-all duration-150',
    ];

    /** Class sets by slot. */
    public const ITEMS = [
        'root' => 'flex flex-wrap items-center justify-between gap-4',
        'start' => 'flex flex-wrap items-center gap-x-5 gap-y-2',
        'summary' => 'text-xs text-on-surface/70 dark:text-on-surface-dark/70',
        'perPage' => 'flex items-center gap-2.5',
        'label' => 'whitespace-nowrap text-xs font-medium text-on-surface/70 dark:text-on-surface-dark/70',
        'select' => 'rounded-radius border border-outline bg-surface px-3 py-1.5 text-xs font-medium text-on-surface disabled:opacity-60 dark:border-outline-dark dark:bg-surface-dark dark:text-on-surface-dark ' . self::PARTS['FOCUS'],
        'nav' => 'ml-auto',
        'list' => 'flex flex-wrap items-center gap-1.5 text-xs font-medium',
        'step' => self::PARTS['BUTTON'] . ' cursor-pointer gap-1 border-outline bg-surface px-3 text-on-surface hover:bg-surface-alt disabled:cursor-not-allowed disabled:opacity-40 dark:border-outline-dark dark:bg-surface-dark dark:text-on-surface-dark dark:hover:bg-surface-dark-alt ' . self::PARTS['FOCUS'],
        'page' => self::PARTS['BUTTON'] . ' min-w-9 px-3 disabled:cursor-default ' . self::PARTS['FOCUS'],
        'pageActive' => 'border-primary bg-primary text-on-primary dark:border-primary-dark dark:bg-primary-dark dark:text-on-primary-dark',
        'pageIdle' => 'cursor-pointer border-outline bg-surface text-on-surface hover:bg-surface-alt dark:border-outline-dark dark:bg-surface-dark dark:text-on-surface-dark dark:hover:bg-surface-dark-alt',
        'gap' => 'inline-flex h-9 min-w-6 items-center justify-center text-on-surface/50 dark:text-on-surface-dark/50',
    ];
}
