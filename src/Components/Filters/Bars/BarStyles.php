<?php

declare(strict_types=1);

namespace Juksgraphic\BladeComponents\Components\Filters\Bars;

/**
 * Penguin UI class sets by variant.
 *
 * Data only, no HTML. Tailwind must scan this file.
 */
final class BarStyles
{
    /** Markup layouts, one Blade file each. */
    public const LAYOUTS = [
        'ROW' => 'row',
        'PANEL' => 'panel',
    ];

    /** Slots every variant must define. */
    public const SLOTS = [
        'layout', 'root', 'search', 'field', 'control',
        'popover', 'filter', 'label', 'title', 'reset',
    ];

    /** Shared class fragments. */
    public const PARTS = [
        'FOCUS' => 'focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary dark:focus-visible:outline-primary-dark',
        'LABEL' => 'text-sm font-medium text-on-surface-strong dark:text-on-surface-dark-strong',
        'TITLE' => 'text-sm font-semibold text-on-surface-strong dark:text-on-surface-dark-strong',
        'GHOST' => 'inline-flex items-center gap-1.5 rounded-radius text-on-surface hover:bg-on-surface/10 dark:text-on-surface-dark dark:hover:bg-on-surface-dark/10',
        'FIELD_ON_PAGE' => 'rounded-radius border border-outline bg-surface-alt text-on-surface dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:text-on-surface-dark',
        'FIELD_ON_PANEL' => 'rounded-radius border border-outline bg-surface text-on-surface dark:border-outline-dark dark:bg-surface-dark dark:text-on-surface-dark',
        'POPOVER_ON_PAGE' => 'border-outline bg-surface-alt dark:border-outline-dark dark:bg-surface-dark-alt',
        'POPOVER_ON_PANEL' => 'border-outline bg-surface dark:border-outline-dark dark:bg-surface-dark',
    ];

    /** Class sets by variant value. */
    public const ITEMS = [
        BarVariants::ITEMS['INLINE'] => [
            'layout' => self::LAYOUTS['ROW'],
            'root' => 'flex flex-wrap items-center gap-2',
            'search' => 'relative w-full sm:w-64',
            'field' => self::PARTS['FIELD_ON_PAGE'] . ' ' . self::PARTS['FOCUS'],
            'control' => 'py-2 text-sm',
            'popover' => self::PARTS['POPOVER_ON_PAGE'],
            'filter' => 'w-full sm:w-52',
            'label' => null,
            'title' => null,
            'reset' => self::PARTS['GHOST'] . ' px-3 py-2 text-sm ' . self::PARTS['FOCUS'],
        ],
        BarVariants::ITEMS['COMPACT'] => [
            'layout' => self::LAYOUTS['ROW'],
            'root' => 'flex flex-wrap items-center gap-1.5',
            'search' => 'relative w-full sm:w-48',
            'field' => self::PARTS['FIELD_ON_PAGE'] . ' ' . self::PARTS['FOCUS'],
            'control' => 'py-1.5 text-xs',
            'popover' => self::PARTS['POPOVER_ON_PAGE'],
            'filter' => 'w-full sm:w-40',
            'label' => null,
            'title' => null,
            'reset' => self::PARTS['GHOST'] . ' px-2.5 py-1.5 text-xs ' . self::PARTS['FOCUS'],
        ],
        BarVariants::ITEMS['STACKED'] => [
            'layout' => self::LAYOUTS['ROW'],
            'root' => 'flex w-full flex-col gap-4',
            'search' => 'relative w-full',
            'field' => self::PARTS['FIELD_ON_PAGE'] . ' ' . self::PARTS['FOCUS'],
            'control' => 'py-2 text-sm',
            'popover' => self::PARTS['POPOVER_ON_PAGE'],
            'filter' => 'flex w-full flex-col gap-1.5',
            'label' => self::PARTS['LABEL'],
            'title' => null,
            'reset' => self::PARTS['GHOST'] . ' self-start px-3 py-2 text-sm ' . self::PARTS['FOCUS'],
        ],
        BarVariants::ITEMS['SIDEBAR'] => [
            'layout' => self::LAYOUTS['PANEL'],
            'root' => 'w-full rounded-radius border border-outline bg-surface-alt p-4 text-on-surface dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark lg:sticky lg:top-4 lg:w-72 lg:self-start',
            'search' => 'relative w-full',
            'field' => self::PARTS['FIELD_ON_PANEL'] . ' ' . self::PARTS['FOCUS'],
            'control' => 'py-2 text-sm',
            'popover' => self::PARTS['POPOVER_ON_PANEL'],
            'filter' => 'flex w-full flex-col gap-1.5',
            'label' => self::PARTS['LABEL'],
            'title' => self::PARTS['TITLE'],
            'reset' => 'inline-flex w-full items-center justify-center gap-1.5 rounded-radius border border-outline px-3 py-2 text-sm text-on-surface hover:bg-on-surface/10 dark:border-outline-dark dark:text-on-surface-dark dark:hover:bg-on-surface-dark/10 ' . self::PARTS['FOCUS'],
        ],
    ];

    /**
     * Get the class set of a variant.
     *
     * @param string $variant A variant value, e.g. "inline".
     * @return array<string, string|null> Slot name => classes.
     */
    public static function forVariant(string $variant): array
    {
        return self::ITEMS[$variant] ?? self::ITEMS[BarVariants::ITEMS['INLINE']];
    }
}