<?php

declare(strict_types=1);

namespace Juksgraphic\BladeComponents\Components\Navigation;

/**
 * Tailwind classes for the breadcrumb container, links, separators, and current item.
 */
class BreadCrumbStyles
{
    /**
     * BreadCrumb instance providing variant and classes.
     *
     * @var BreadCrumb
     */
    private BreadCrumb $breadcrumb;

    /**
     * Create the styles helper.
     *
     * @param BreadCrumb $breadcrumb BreadCrumb instance to style.
     */
    public function __construct(BreadCrumb $breadcrumb)
    {
        $this->breadcrumb = $breadcrumb;
    }

    /**
     * Get `<nav>` wrapper classes.
     *
     * @return string
     */
    public function container(): string
    {
        $base = match ($this->breadcrumb->style()) {
            'background' => 'rounded-radius bg-surface-alt dark:bg-surface-dark-alt px-4 py-2.5 text-xs font-medium text-on-surface dark:text-on-surface-dark',
            default => 'text-xs font-medium text-on-surface dark:text-on-surface-dark',
        };

        $classes = $base;

        if ($this->breadcrumb->extraClass() !== '') {
            $classes .= ' ' . $this->breadcrumb->extraClass();
        }

        $normalized = preg_replace('/\s+/', ' ', trim($classes));

        return $normalized ?? trim($classes);
    }

    /**
     * Get ordered list (`<ol>`) classes.
     *
     * @return string
     */
    public function list(): string
    {
        return 'flex flex-wrap items-center gap-1.5';
    }

    /**
     * Get non-last step `<li>` container classes.
     *
     * @return string
     */
    public function stepItem(): string
    {
        return 'flex items-center gap-1.5';
    }

    /**
     * Get step anchor link classes.
     *
     * @return string
     */
    public function link(): string
    {
        return 'inline-flex items-center gap-1.5 hover:text-primary dark:hover:text-primary-dark transition-colors';
    }

    /**
     * Get icon wrapper classes.
     *
     * @return string
     */
    public function icon(): string
    {
        return 'shrink-0';
    }

    /**
     * Get slash separator classes.
     *
     * @return string
     */
    public function slashSeparator(): string
    {
        return 'opacity-40 select-none font-normal';
    }

    /**
     * Get bullet separator classes.
     *
     * @return string
     */
    public function bulletSeparator(): string
    {
        return 'opacity-40 select-none font-bold text-xs';
    }

    /**
     * Get chevron SVG separator classes.
     *
     * @return string
     */
    public function chevronSeparator(): string
    {
        return 'size-3.5 opacity-50 shrink-0';
    }

    /**
     * Get current/last step `<li>` classes.
     *
     * @return string
     */
    public function currentItem(): string
    {
        return 'inline-flex items-center gap-1.5 font-bold text-on-surface-strong dark:text-on-surface-dark-strong';
    }
}
