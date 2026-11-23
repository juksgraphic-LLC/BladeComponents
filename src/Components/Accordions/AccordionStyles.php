<?php

declare(strict_types=1);

namespace Juksgraphic\BladeComponents\Components\Accordions;

/**
 * Tailwind classes for the accordion.
 */
class AccordionStyles
{
    /**
     * Border and background color classes.
     *
     * @var array<string, string>
     */
    private const COLORS = [
        'primary' => 'text-primary border-primary/20 bg-primary/5 dark:text-primary-dark dark:border-primary-dark/20 dark:bg-primary-dark/5',
        'secondary' => 'text-secondary border-secondary/20 bg-secondary/5 dark:text-secondary-dark dark:border-secondary-dark/20 dark:bg-secondary-dark/5',
        'success' => 'text-success border-success/20 bg-success/5',
        'danger' => 'text-danger border-danger/20 bg-danger/5',
        'warning' => 'text-warning border-warning/20 bg-warning/5',
        'info' => 'text-info border-info/20 bg-info/5',
        'light' => 'text-on-surface border-outline bg-surface-alt dark:text-on-surface-dark dark:border-outline-dark dark:bg-surface-dark-alt',
        'dark' => 'text-on-surface-strong border-outline-strong bg-surface dark:text-on-surface-dark-strong dark:border-outline-dark-strong dark:bg-surface-dark',
    ];

    /**
     * Text color classes.
     *
     * @var array<string, string>
     */
    private const TEXTS = [
        'primary' => 'text-primary dark:text-primary-dark',
        'secondary' => 'text-secondary dark:text-secondary-dark',
        'success' => 'text-success',
        'danger' => 'text-danger',
        'warning' => 'text-warning',
        'info' => 'text-info',
        'light' => 'text-on-surface dark:text-on-surface-dark',
        'dark' => 'text-on-surface-strong dark:text-on-surface-dark-strong',
    ];

    /**
     * Toggle icon rotation and path.
     *
     * @var array<string, array{rotate: string, path: string}>
     */
    private const TOGGLES = [
        'plus' => ['rotate' => 'rotate-45', 'path' => 'M12 6v6m0 0v6m0-6h6m-6 0H6'],
        'arrow' => ['rotate' => 'rotate-90', 'path' => 'M9 5l7 7-7 7'],
        'default' => ['rotate' => 'rotate-180', 'path' => 'M19 9l-7 7-7-7'],
    ];

    /**
     * Accordion providing type and items.
     *
     * @var Accordion
     */
    private Accordion $accordion;

    /**
     * Create the styles helper.
     *
     * @param Accordion $accordion Accordion to style.
     */
    public function __construct(Accordion $accordion)
    {
        $this->accordion = $accordion;
    }

    /**
     * Get root container classes.
     *
     * @return string
     */
    public function container(): string
    {
        return match ($this->accordion->type()) {
            'flush' => '',
            'nested' => 'space-y-2',
            default => 'rounded-radius border border-outline dark:border-outline-dark overflow-hidden bg-surface dark:bg-surface-dark',
        };
    }

    /**
     * Get item wrapper classes.
     *
     * @param int $index Item index.
     *
     * @return string
     */
    public function item(int $index): string
    {
        $classes = $this->accordion->is('nested')
            ? 'rounded-radius border border-outline dark:border-outline-dark overflow-hidden bg-surface-alt dark:bg-surface-dark-alt'
            : 'border-b border-outline dark:border-outline-dark last:border-0';

        if ($this->accordion->is('custom')) {
            $classes .= ' rounded-radius border ' . $this->activeColor($index);
        }

        if ($this->accordion->items()[$index]['disabled']) {
            $classes .= ' opacity-50 cursor-not-allowed pointer-events-none';
        }

        return $classes;
    }

    /**
     * Get header button classes.
     *
     * @param int $index Item index.
     *
     * @return string
     */
    public function header(int $index): string
    {
        $classes = 'w-full flex items-center justify-between px-5 py-4 text-left font-title font-medium text-sm transition-colors duration-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-primary/20 text-on-surface dark:text-on-surface-dark';

        $classes .= $this->accordion->is('nested')
            ? ' hover:bg-surface dark:hover:bg-surface-dark'
            : ' hover:bg-surface-alt dark:hover:bg-surface-dark-alt';

        return $classes . ($this->accordion->items()[$index]['disabled'] ? '' : ' cursor-pointer');
    }

    /**
     * Get header classes when open.
     *
     * @param int $index Item index.
     *
     * @return string
     */
    public function headerOpen(int $index): string
    {
        return $this->accordion->is('custom') ? $this->activeColor($index) : '';
    }

    /**
     * Get title text classes.
     *
     * @param int $index Item index.
     *
     * @return string
     */
    public function title(int $index): string
    {
        return $this->accordion->is('custom') ? $this->titleColor($index) : '';
    }

    /**
     * Get item icon classes.
     *
     * @param int $index Item index.
     *
     * @return string
     */
    public function icon(int $index): string
    {
        $classes = 'flex-shrink-0 ' . $this->titleColor($index);

        return $this->accordion->is('icon') ? $classes . ' transition-transform duration-200' : $classes;
    }

    /**
     * Get toggle icon wrapper classes.
     *
     * @param int $index Item index.
     *
     * @return string
     */
    public function toggle(int $index): string
    {
        return 'flex-shrink-0 ' . ($this->accordion->is('custom')
            ? $this->titleColor($index)
            : 'text-on-surface/50 dark:text-on-surface-dark/50');
    }

    /**
     * Get toggle icon rotation class.
     *
     * @return string
     */
    public function toggleRotate(): string
    {
        return $this->toggleIcon()['rotate'];
    }

    /**
     * Get toggle icon SVG path.
     *
     * @return string
     */
    public function togglePath(): string
    {
        return $this->toggleIcon()['path'];
    }

    /**
     * Get panel content classes.
     *
     * @return string
     */
    public function content(): string
    {
        $classes = 'px-5 pb-4 text-sm text-on-surface/80 dark:text-on-surface-dark/80 font-paragraph';

        return $this->accordion->is('flush') ? $classes : $classes . ' bg-surface dark:bg-surface-dark';
    }

    /**
     * Get active color classes for item.
     *
     * @param int $index Item index.
     *
     * @return string
     */
    private function activeColor(int $index): string
    {
        return self::COLORS[$this->colorOf($index)];
    }

    /**
     * Get title color classes for item.
     *
     * @param int $index Item index.
     *
     * @return string
     */
    private function titleColor(int $index): string
    {
        return self::TEXTS[$this->colorOf($index)];
    }

    /**
     * Resolve item color or fallback.
     *
     * @param int $index Item index.
     *
     * @return string
     */
    private function colorOf(int $index): string
    {
        return $this->accordion->items()[$index]['color'] ?? $this->accordion->color();
    }

    /**
     * Get toggle icon definition.
     *
     * @return array{rotate: string, path: string}
     */
    private function toggleIcon(): array
    {
        return self::TOGGLES[$this->accordion->type()] ?? self::TOGGLES['default'];
    }
}
