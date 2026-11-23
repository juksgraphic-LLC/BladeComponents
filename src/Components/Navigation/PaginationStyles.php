<?php

declare(strict_types=1);

namespace Juksgraphic\BladeComponents\Components\Navigation;

/**
 * Tailwind classes for pagination links, active states, disabled buttons, and nav items.
 */
class PaginationStyles
{
    /**
     * Pagination instance providing variant and state.
     *
     * @var Pagination
     */
    private Pagination $pagination;

    /**
     * Create the styles helper.
     *
     * @param Pagination $pagination Pagination instance to style.
     */
    public function __construct(Pagination $pagination)
    {
        $this->pagination = $pagination;
    }

    /**
     * Get `<nav>` wrapper classes.
     *
     * @return string
     */
    public function nav(): string
    {
        $classes = 'mt-8 flex justify-center';

        if ($this->pagination->extraClass() !== '') {
            $classes .= ' ' . $this->pagination->extraClass();
        }

        $normalized = preg_replace('/\s+/', ' ', trim($classes));

        return $normalized ?? trim($classes);
    }

    /**
     * Get page list (`<ul>`) classes.
     *
     * @return string
     */
    public function list(): string
    {
        return 'flex shrink-0 items-center gap-1.5';
    }

    /**
     * Get base classes for numbered page items.
     *
     * @return string
     */
    public function pageItemBase(): string
    {
        return match ($this->pagination->style()) {
            'pill' => 'flex size-8 items-center justify-center rounded-full text-sm font-medium transition',
            'bordered' => 'flex size-8 items-center justify-center rounded-radius border border-outline dark:border-outline-dark text-sm font-medium transition',
            'minimal' => 'flex size-8 items-center justify-center text-sm font-medium transition hover:text-primary dark:hover:text-primary-dark',
            default => 'flex size-8 items-center justify-center rounded-radius text-sm font-medium transition',
        };
    }

    /**
     * Get classes for the active current page item.
     *
     * @return string
     */
    public function activePage(): string
    {
        $activeCls = match ($this->pagination->style()) {
            'minimal' => 'text-primary dark:text-primary-dark font-bold underline underline-offset-4',
            default => 'bg-primary text-on-primary dark:bg-primary-dark dark:text-on-primary-dark font-bold shadow-sm',
        };

        return $this->pageItemBase() . ' ' . $activeCls;
    }

    /**
     * Get classes for an inactive clickable page link.
     *
     * @return string
     */
    public function inactivePage(): string
    {
        $inactiveCls = 'text-on-surface hover:bg-surface-alt dark:text-on-surface-dark dark:hover:bg-surface-dark-alt';

        return $this->pageItemBase() . ' ' . $inactiveCls;
    }

    /**
     * Get ellipsis spacer classes.
     *
     * @return string
     */
    public function ellipsis(): string
    {
        return 'flex size-8 items-center justify-center text-on-surface/60 dark:text-on-surface-dark/60';
    }

    /**
     * Get previous/next button classes for active or disabled state.
     *
     * @param bool $disabled Whether the button is disabled.
     *
     * @return string
     */
    public function navButton(bool $disabled): string
    {
        $base = match ($this->pagination->style()) {
            'bordered' => 'flex items-center gap-1 px-3 py-1.5 rounded-radius border border-outline dark:border-outline-dark text-sm font-medium transition',
            'pill' => 'flex items-center gap-1 px-3 py-1.5 rounded-full text-sm font-medium transition',
            default => 'flex items-center gap-1 px-3 py-1.5 rounded-radius text-sm font-medium transition',
        };

        $stateCls = $disabled
            ? 'text-on-surface/40 cursor-not-allowed dark:text-on-surface-dark/40 hover:bg-transparent'
            : 'text-on-surface hover:bg-surface-alt dark:text-on-surface-dark dark:hover:bg-surface-dark-alt';

        return "{$base} {$stateCls}";
    }
}
