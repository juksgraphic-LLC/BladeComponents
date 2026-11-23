<?php

declare(strict_types=1);

namespace Juksgraphic\BladeComponents\Components\Dropdowns;

/**
 * Tailwind classes for the dropdown component.
 */
class DropdownStyles
{
    /**
     * Trigger button color theme classes.
     *
     * @var array<string, string>
     */
    private const BUTTON_THEMES = [
        'primary'   => 'bg-primary text-on-primary hover:bg-primary/90 focus:ring-primary/30 dark:bg-primary-dark dark:text-on-primary-dark',
        'secondary' => 'bg-secondary text-on-secondary hover:bg-secondary/90 focus:ring-secondary/30 dark:bg-secondary-dark dark:text-on-secondary-dark',
        'success'   => 'bg-success text-on-success hover:bg-success/90 focus:ring-success/30',
        'danger'    => 'bg-danger text-on-danger hover:bg-danger/90 focus:ring-danger/30',
        'warning'   => 'bg-warning text-on-warning hover:bg-warning/90 focus:ring-warning/30',
        'info'      => 'bg-info text-on-info hover:bg-info/90 focus:ring-info/30',
        'light'     => 'bg-surface-alt text-on-surface border border-outline hover:bg-surface focus:ring-outline/30 dark:bg-surface-dark-alt dark:text-on-surface-dark dark:border-outline-dark dark:hover:bg-surface-dark',
        'dark'      => 'bg-surface-dark-alt text-on-surface-dark border border-outline-dark hover:bg-surface-dark focus:ring-outline-dark/30 dark:bg-surface-alt dark:text-on-surface dark:border-outline dark:hover:bg-surface',
    ];

    /**
     * Trigger button size padding and font classes.
     *
     * @var array<string, string>
     */
    private const BUTTON_SIZES = [
        'lg'      => 'px-5 py-3 text-base',
        'sm'      => 'px-3 py-1.5 text-xs',
        'default' => 'px-4 py-2 text-sm',
    ];

    /**
     * Menu alignment and drop-direction positioning classes.
     *
     * @var array<string, string>
     */
    private const MENU_ALIGNS = [
        'start'     => 'left-0',
        'end'       => 'right-0',
        'start-lg'  => 'right-0 lg:left-0 lg:right-auto',
        'end-lg'    => 'left-0 lg:right-0 lg:left-auto',
        'dropstart' => 'right-full top-0 mr-2',
        'dropend'   => 'left-full top-0 ml-2',
        'dropup'    => 'bottom-full mb-2 left-0',
        'default'   => 'left-0',
    ];

    /**
     * Item accent color classes.
     *
     * @var array<string, string>
     */
    private const ITEM_COLORS = [
        'primary'   => 'text-primary hover:bg-primary/10',
        'secondary' => 'text-secondary hover:bg-secondary/10',
        'success'   => 'text-success hover:bg-success/10',
        'danger'    => 'text-danger hover:bg-danger/10',
        'warning'   => 'text-warning hover:bg-warning/10',
        'info'      => 'text-info hover:bg-info/10',
    ];

    /**
     * Dropdown providing configuration and items.
     *
     * @var Dropdown
     */
    private Dropdown $dropdown;

    /**
     * Create the styles helper.
     *
     * @param Dropdown $dropdown Dropdown instance to style.
     */
    public function __construct(Dropdown $dropdown)
    {
        $this->dropdown = $dropdown;
    }

    /**
     * Get root wrapper classes.
     *
     * @return string
     */
    public function container(): string
    {
        return 'relative inline-flex' . ($this->dropdown->isTrigger('split') ? ' group' : '');
    }

    /**
     * Get standard trigger button classes.
     *
     * @return string
     */
    public function button(): string
    {
        $theme = self::BUTTON_THEMES[$this->dropdown->color()];
        $size  = self::BUTTON_SIZES[$this->dropdown->size() ?? 'default'];

        return $theme . ' ' . $size . ' inline-flex items-center justify-center font-medium rounded-radius transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 dark:focus:ring-offset-surface-dark';
    }

    /**
     * Get primary action link classes for a split button.
     *
     * @return string
     */
    public function splitPrimaryButton(): string
    {
        return $this->button() . ' rounded-r-none border-r border-white/20 dark:border-black/20';
    }

    /**
     * Get toggle button classes for a split button.
     *
     * @return string
     */
    public function splitToggleButton(): string
    {
        return $this->button() . ' rounded-l-none px-3';
    }

    /**
     * Get floating dropdown menu classes.
     *
     * @return string
     */
    public function menu(): string
    {
        $align = self::MENU_ALIGNS[$this->dropdown->align() ?? 'default'];
        $classes = 'absolute z-50 min-w-[12rem] mt-2 rounded-radius border shadow-lg overflow-hidden ' . $align;

        if ($this->dropdown->menuClass() !== '') {
            $classes .= ' ' . $this->dropdown->menuClass();
        }

        $classes .= $this->dropdown->isDarkMenu()
            ? ' bg-surface-dark border-outline-dark text-on-surface-dark'
            : ' bg-surface dark:bg-surface-dark border-outline dark:border-outline-dark text-on-surface dark:text-on-surface-dark';

        return $classes;
    }

    /**
     * Get top menu header classes.
     *
     * @return string
     */
    public function menuHeader(): string
    {
        $tone = $this->dropdown->isDarkMenu()
            ? 'text-on-surface-dark/60 border-outline-dark'
            : 'text-on-surface/60 dark:text-on-surface-dark/60 border-outline dark:border-outline-dark';

        return 'px-4 py-2 text-xs font-semibold font-title uppercase tracking-wider border-b ' . $tone;
    }

    /**
     * Get top menu static text classes.
     *
     * @return string
     */
    public function menuText(): string
    {
        $tone = $this->dropdown->isDarkMenu()
            ? 'text-on-surface-dark/80'
            : 'text-on-surface/80 dark:text-on-surface-dark/80';

        return 'px-4 py-3 text-sm ' . $tone;
    }

    /**
     * Get divider item classes.
     *
     * @return string
     */
    public function itemDivider(): string
    {
        $border = $this->dropdown->isDarkMenu()
            ? 'border-outline-dark'
            : 'border-outline dark:border-outline-dark';

        return 'my-1 border-t ' . $border;
    }

    /**
     * Get section header item classes.
     *
     * @return string
     */
    public function itemHeader(): string
    {
        $tone = $this->dropdown->isDarkMenu()
            ? 'text-on-surface-dark/60'
            : 'text-on-surface/60 dark:text-on-surface-dark/60';

        return 'px-4 py-2 text-xs font-semibold font-title uppercase tracking-wider ' . $tone;
    }

    /**
     * Get static text item classes.
     *
     * @return string
     */
    public function itemText(): string
    {
        $tone = $this->dropdown->isDarkMenu()
            ? 'text-on-surface-dark/80'
            : 'text-on-surface/80 dark:text-on-surface-dark/80';

        return 'block w-full px-4 py-2 text-sm font-paragraph transition-colors duration-150 ' . $tone;
    }

    /**
     * Get custom form container item classes.
     *
     * @return string
     */
    public function itemForm(): string
    {
        return 'px-4 py-2';
    }

    /**
     * Get interactive link item classes.
     *
     * @param int $index Item index.
     *
     * @return string
     */
    public function itemLink(int $index): string
    {
        $item    = $this->dropdown->items()[$index];
        $classes = 'block w-full px-4 py-2 text-sm font-paragraph transition-colors duration-150';

        if ($item['color'] !== null) {
            $classes .= ' ' . self::ITEM_COLORS[$item['color']];
        } else {
            $classes .= $this->dropdown->isDarkMenu()
                ? ' text-on-surface-dark hover:bg-white/5 hover:text-on-surface-dark-strong'
                : ' text-on-surface dark:text-on-surface-dark hover:bg-surface-alt dark:hover:bg-white/5 hover:text-on-surface-strong dark:hover:text-on-surface-dark-strong';
        }

        if ($item['active']) {
            $classes .= $this->dropdown->isDarkMenu()
                ? ' bg-white/10 text-on-surface-dark-strong'
                : ' bg-surface-alt dark:bg-white/10 text-on-surface-strong dark:text-on-surface-dark-strong';
        }

        if ($item['disabled']) {
            $classes .= ' opacity-50 pointer-events-none';
        }

        return $classes;
    }
}
