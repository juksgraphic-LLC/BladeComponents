<?php

declare(strict_types=1);

namespace Juksgraphic\BladeComponents\Components\Navigation;

/**
 * Tailwind classes for the tabs root, tab list, tab buttons, badges, and panels.
 */
class TabsStyles
{
    /**
     * Tabs instance providing variant, color, and classes.
     *
     * @var Tabs
     */
    private Tabs $tabs;

    /**
     * Resolved color token.
     *
     * @var string
     */
    private string $color;

    /**
     * Create the styles helper.
     *
     * @param Tabs $tabs Tabs instance to style.
     */
    public function __construct(Tabs $tabs)
    {
        $this->tabs = $tabs;
        $this->color = $tabs->color();
    }

    /**
     * Get root container classes.
     *
     * @return string
     */
    public function root(): string
    {
        $layout = $this->tabs->isVertical() ? 'flex gap-4' : 'flex flex-col';
        $classes = $layout;

        if ($this->tabs->tabClass() !== '') {
            $classes .= ' ' . $this->tabs->tabClass();
        }

        $normalized = preg_replace('/\s+/', ' ', trim($classes));

        return $normalized ?? trim($classes);
    }

    /**
     * Get tablist container classes.
     *
     * @return string
     */
    public function tabList(): string
    {
        $c = $this->color;

        if ($this->tabs->isVertical()) {
            return 'flex flex-col gap-y-0.5 min-w-[160px]';
        }

        return match ($this->tabs->variant()) {
            'pills', 'custom', 'colored', 'outline' => 'flex flex-wrap gap-1',
            'pills-justified' => 'flex w-full gap-1',
            'card-header' => 'flex flex-wrap border-b border-outline dark:border-outline-dark',
            'card-footer' => 'flex flex-wrap border-t border-outline dark:border-outline-dark',
            'custom-tabs' => "flex flex-wrap gap-x-1 border-b border-{$c}",
            'animation' => 'flex flex-wrap border-b border-outline dark:border-outline-dark gap-x-2',
            'border-top', 'border-top-justified' => 'flex flex-wrap',
            'custom-hover' => 'flex flex-wrap border-b border-outline dark:border-outline-dark',
            default => 'flex flex-wrap border-b border-outline dark:border-outline-dark',
        };
    }

    /**
     * Get tab button classes for a given active and disabled state.
     *
     * @param bool $isActive   Whether the tab is currently active.
     * @param bool $isDisabled Whether the tab is disabled.
     *
     * @return string
     */
    public function button(bool $isActive, bool $isDisabled): string
    {
        $base = 'inline-flex items-center gap-1.5 text-sm font-medium font-paragraph transition-all duration-200 focus:outline-none ';
        $disabled = $isDisabled ? 'opacity-40 pointer-events-none cursor-not-allowed ' : '';
        $shape = $this->shapeClasses();
        $state = $isActive ? $this->activeClasses() : $this->inactiveClasses();

        return trim($base . $shape . $disabled . $state);
    }

    /**
     * Get icon wrapper classes inside a tab button.
     *
     * @return string
     */
    public function icon(): string
    {
        return 'shrink-0 w-4 h-4';
    }

    /**
     * Get badge pill classes inside a tab button.
     *
     * @return string
     */
    public function badge(): string
    {
        $c = $this->color;

        return "ml-1 inline-flex items-center justify-center px-1.5 py-0.5 rounded-full text-[10px] font-semibold leading-none bg-{$c} text-on-{$c}";
    }

    /**
     * Get underline indicator classes for the `animation` variant.
     *
     * @return string
     */
    public function animationBar(): string
    {
        return "absolute bottom-0 left-0 right-0 h-0.5 bg-{$this->color} rounded-full origin-left";
    }

    /**
     * Get trailing arrow wrapper classes for the `arrow` variant.
     *
     * @return string
     */
    public function arrowIndicator(): string
    {
        return 'ml-auto';
    }

    /**
     * Get content panels wrapper classes.
     *
     * @return string
     */
    public function panelsWrapper(): string
    {
        $classes = 'flex-1';

        if ($this->tabs->contentClass() !== '') {
            $classes .= ' ' . $this->tabs->contentClass();
        }

        $normalized = preg_replace('/\s+/', ' ', trim($classes));

        return $normalized ?? trim($classes);
    }

    /**
     * Get individual tabpanel container classes.
     *
     * @return string
     */
    public function panel(): string
    {
        return 'py-3';
    }

    /**
     * Get padding and border-shape classes for the active tab variant.
     *
     * @return string
     */
    private function shapeClasses(): string
    {
        return match ($this->tabs->variant()) {
            'justified' => 'px-4 py-2.5 border-b-2 flex-1 justify-center ',
            'pills' => 'px-4 py-1.5 rounded-full ',
            'pills-justified' => 'px-4 py-1.5 rounded-full flex-1 justify-center ',
            'vertical' => 'px-4 py-2.5 rounded-radius w-full justify-start ',
            'card-header' => 'px-4 py-2.5 border-b-2 ',
            'card-footer' => 'px-4 py-2.5 border-t-2 ',
            'arrow' => 'px-4 py-2.5 rounded-radius ',
            'custom-tabs' => 'px-4 py-2.5 border-2 rounded-t-radius ',
            'custom-hover' => 'px-5 py-3 border-b-2 ',
            'custom-vertical' => 'px-4 py-3 border-l-4 w-full justify-start ',
            'animation' => 'px-4 py-2.5 relative ',
            'border-top' => 'px-4 py-2.5 border-t-2 ',
            'border-top-justified' => 'px-4 py-2.5 border-t-2 flex-1 justify-center ',
            'outline' => 'px-4 py-1.5 rounded-full border ',
            'custom' => 'px-4 py-1.5 rounded-full ',
            'colored' => 'px-4 py-1.5 rounded-full ',
            'light' => 'px-4 py-2.5 border-b-2 ',
            default => 'px-4 py-2.5 border-b-2 ',
        };
    }

    /**
     * Get active tab state classes for the active variant and color.
     *
     * @return string
     */
    private function activeClasses(): string
    {
        $c = $this->color;

        return match ($this->tabs->variant()) {
            'default', 'justified', 'card-footer', 'border-top', 'border-top-justified' => "border-{$c} text-{$c}",
            'pills', 'pills-justified', 'vertical', 'arrow', 'colored' => "bg-{$c} text-on-{$c}",
            'card-header' => "border-{$c} text-{$c} bg-surface dark:bg-surface-dark -mb-px",
            'custom-tabs' => "border-{$c} border-b-surface dark:border-b-surface-dark text-{$c} bg-surface dark:bg-surface-dark",
            'custom-hover', 'custom-vertical', 'outline' => "border-{$c} text-{$c} bg-{$c}/5",
            'animation' => "text-{$c}",
            'custom' => "bg-{$c} text-on-{$c} shadow-md shadow-{$c}/30",
            'light' => "border-{$c}/40 text-{$c}/80",
            default => "border-{$c} text-{$c}",
        };
    }

    /**
     * Get inactive tab state classes for the active variant.
     *
     * @return string
     */
    private function inactiveClasses(): string
    {
        return match ($this->tabs->variant()) {
            'pills', 'pills-justified', 'vertical', 'arrow', 'custom' => 'text-on-surface/60 dark:text-on-surface-dark/60 hover:bg-surface-alt dark:hover:bg-white/10 hover:text-on-surface dark:hover:text-on-surface-dark',
            'card-header', 'card-footer', 'custom-tabs', 'animation', 'border-top', 'border-top-justified' => 'border-transparent text-on-surface/60 dark:text-on-surface-dark/60 hover:text-on-surface dark:hover:text-on-surface-dark',
            'custom-hover', 'custom-vertical' => 'border-transparent text-on-surface/60 dark:text-on-surface-dark/60 hover:bg-surface-alt dark:hover:bg-white/5 hover:text-on-surface dark:hover:text-on-surface-dark',
            'colored' => 'bg-surface-alt dark:bg-white/5 text-on-surface dark:text-on-surface-dark hover:bg-surface-alt/80 dark:hover:bg-white/10',
            'light' => 'border-transparent text-on-surface/40 dark:text-on-surface-dark/40 hover:border-outline/50 hover:text-on-surface/70 dark:hover:text-on-surface-dark/70',
            default => 'border-transparent text-on-surface/60 dark:text-on-surface-dark/60 hover:border-outline dark:hover:border-outline-dark hover:text-on-surface dark:hover:text-on-surface-dark',
        };
    }
}
