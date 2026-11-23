<?php

declare(strict_types=1);

namespace Juksgraphic\BladeComponents\Components\Forms;

/**
 * Tailwind classes for the searchable select component.
 */
class SelectStyles
{
    /**
     * Select instance providing state and options.
     *
     * @var Select
     */
    private Select $select;

    /**
     * Create the styles helper.
     *
     * @param Select $select Select instance to style.
     */
    public function __construct(Select $select)
    {
        $this->select = $select;
    }

    /**
     * Get field wrapper classes.
     *
     * @return string
     */
    public function wrapper(): string
    {
        return 'flex flex-col gap-1.5 text-on-surface dark:text-on-surface-dark';
    }

    /**
     * Get label classes.
     *
     * @return string
     */
    public function label(): string
    {
        return 'w-fit text-sm font-semibold text-on-surface-strong dark:text-on-surface-dark-strong';
    }

    /**
     * Get dropdown positioning wrapper classes.
     *
     * @return string
     */
    public function dropdownWrapper(): string
    {
        return 'relative w-full';
    }

    /**
     * Get trigger box classes.
     *
     * @return string
     */
    public function trigger(): string
    {
        $classes = 'min-h-[42px] w-full cursor-pointer flex flex-wrap items-center gap-1.5 rounded-radius border border-outline bg-surface-alt px-3 py-2 text-sm text-on-surface focus:border-primary dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:text-on-surface-dark';

        if ($this->select->extraClass() !== '') {
            $classes .= ' ' . $this->select->extraClass();
        }

        return $classes;
    }

    /**
     * Get selected pill badge classes in multi-select mode.
     *
     * @return string
     */
    public function pill(): string
    {
        return 'inline-flex items-center gap-1 rounded bg-primary/10 px-2 py-0.5 text-xs font-medium text-primary dark:bg-primary-dark/20 dark:text-primary-dark';
    }

    /**
     * Get pill remove button classes.
     *
     * @return string
     */
    public function pillRemoveButton(): string
    {
        return 'hover:text-danger';
    }

    /**
     * Get placeholder text classes.
     *
     * @return string
     */
    public function placeholder(): string
    {
        return 'text-on-surface/40 dark:text-on-surface-dark/40';
    }

    /**
     * Get chevron icon classes.
     *
     * @return string
     */
    public function chevron(): string
    {
        return 'bx bx-chevron-down ml-auto shrink-0 text-xl text-on-surface/50 dark:text-on-surface-dark/50';
    }

    /**
     * Get floating dropdown menu classes.
     *
     * @return string
     */
    public function menu(): string
    {
        return 'absolute z-50 mt-1 max-h-60 w-full overflow-auto rounded-radius border border-outline bg-surface p-2 shadow-lg dark:border-outline-dark dark:bg-surface-dark';
    }

    /**
     * Get search input classes inside the menu.
     *
     * @return string
     */
    public function searchInput(): string
    {
        return 'mb-2 w-full rounded border border-outline bg-surface-alt px-2.5 py-1.5 text-xs text-on-surface focus:border-primary focus:outline-none dark:border-outline-dark dark:bg-surface-dark-alt dark:text-on-surface-dark';
    }

    /**
     * Get option row base classes.
     *
     * @return string
     */
    public function optionItem(): string
    {
        return 'flex cursor-pointer items-center justify-between rounded px-3 py-2 text-sm transition-colors hover:bg-primary/10 dark:hover:bg-primary-dark/20';
    }

    /**
     * Get Alpine `:class` binding for active option highlighting.
     *
     * @return string
     */
    public function optionActiveBinding(): string
    {
        return "isSelected(opt.id) ? 'font-semibold text-primary dark:text-primary-dark bg-primary/5' : ''";
    }

    /**
     * Get check icon classes for selected options.
     *
     * @return string
     */
    public function checkIcon(): string
    {
        return 'bx bx-check text-lg text-primary dark:text-primary-dark';
    }

    /**
     * Get empty search state item classes.
     *
     * @return string
     */
    public function emptyState(): string
    {
        return 'px-3 py-2 text-xs text-on-surface/50 dark:text-on-surface-dark/50';
    }
}