<?php

declare(strict_types=1);

namespace Juksgraphic\BladeComponents\Components\Overlays;

/**
 * Tailwind classes for the offcanvas wrapper, backdrop, panel, and sections.
 */
class OffcanvasStyles
{
    /**
     * Panel width utility map for horizontal placements.
     *
     * @var array<string, string>
     */
    private const SIZE_MAP = [
        'sm' => 'w-80',
        'md' => 'w-96',
        'lg' => 'w-[32rem]',
        'xl' => 'w-[40rem]',
        'fullscreen' => 'w-full',
    ];

    /**
     * Offcanvas instance providing layout and state options.
     *
     * @var Offcanvas
     */
    private Offcanvas $offcanvas;

    /**
     * Create the styles helper.
     *
     * @param Offcanvas $offcanvas Offcanvas instance to style.
     */
    public function __construct(Offcanvas $offcanvas)
    {
        $this->offcanvas = $offcanvas;
    }

    /**
     * Get outer viewport wrapper classes.
     *
     * @return string
     */
    public function wrapper(): string
    {
        $overflow = $this->offcanvas->allowsScroll() ? '' : ' overflow-hidden';

        return "fixed inset-0 z-50{$overflow}";
    }

    /**
     * Get backdrop overlay classes.
     *
     * @return string
     */
    public function backdrop(): string
    {
        return 'fixed inset-0 bg-black/50 backdrop-blur-sm';
    }

    /**
     * Get sliding panel container classes.
     *
     * @return string
     */
    public function panel(): string
    {
        $sizeKey = $this->offcanvas->size() ?? 'md';
        $sizeClass = self::SIZE_MAP[$sizeKey] ?? self::SIZE_MAP['md'];

        $placementClass = match ($this->offcanvas->placement()) {
            'end' => "top-0 right-0 h-full {$sizeClass}",
            'top' => 'top-0 left-0 right-0 w-full h-auto max-h-[50vh]',
            'bottom' => 'bottom-0 left-0 right-0 w-full h-auto max-h-[50vh]',
            default => "top-0 left-0 h-full {$sizeClass}",
        };

        $extra = $this->offcanvas->extraClass() !== '' ? ' ' . $this->offcanvas->extraClass() : '';
        $classes = "fixed {$placementClass} bg-surface dark:bg-surface-dark shadow-2xl border border-outline dark:border-outline-dark overflow-y-auto{$extra}";

        $normalized = preg_replace('/\s+/', ' ', trim($classes));

        return $normalized ?? trim($classes);
    }

    /**
     * Get panel header bar classes.
     *
     * @return string
     */
    public function header(): string
    {
        return 'flex items-center justify-between px-5 py-4 border-b border-outline dark:border-outline-dark';
    }

    /**
     * Get panel title heading classes.
     *
     * @return string
     */
    public function title(): string
    {
        return 'font-title text-lg font-semibold text-on-surface dark:text-on-surface-dark';
    }

    /**
     * Get header close button classes.
     *
     * @return string
     */
    public function closeButton(): string
    {
        return 'p-1.5 rounded-lg text-on-surface/50 dark:text-on-surface-dark/50 hover:text-on-surface dark:hover:text-on-surface-dark hover:bg-surface-alt dark:hover:bg-surface-dark-alt transition-colors focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-current';
    }

    /**
     * Get panel body container classes.
     *
     * @return string
     */
    public function body(): string
    {
        return 'px-5 py-4';
    }

    /**
     * Get panel footer bar classes.
     *
     * @return string
     */
    public function footer(): string
    {
        return 'px-5 py-4 border-t border-outline dark:border-outline-dark';
    }
}
