<?php

declare(strict_types=1);

namespace Juksgraphic\BladeComponents\Components\Overlays;

/**
 * Tailwind classes for the modal backdrop, positioning, dialog, and sections.
 */
class ModalStyles
{
    /**
     * Size key to max-width utility mapping.
     *
     * @var array<string, string>
     */
    private const SIZE_MAP = [
        'sm' => 'max-w-sm',
        'md' => 'max-w-lg',
        'lg' => 'max-w-2xl',
        'xl' => 'max-w-4xl',
        'fullscreen' => 'max-w-full m-4 w-full h-[calc(100vh-2rem)]',
    ];

    /**
     * Position key to flex alignment and viewport padding mapping.
     *
     * @var array<string, string>
     */
    private const POSITION_MAP = [
        'center' => 'items-center justify-center p-4 sm:p-6',
        'center-top' => 'items-start justify-center pt-8 sm:pt-12 px-4 sm:px-6',
        'center-bottom' => 'items-end justify-center pb-8 sm:pb-12 px-4 sm:px-6',
        'left-top' => 'items-start justify-start pt-6 sm:pt-8 pl-4 sm:pl-8 pr-4',
        'right-top' => 'items-start justify-end pt-6 sm:pt-8 pr-4 sm:pr-8 pl-4',
        'left-bottom' => 'items-end justify-start pb-6 sm:pb-8 pl-4 sm:pl-8 pr-4',
        'right-bottom' => 'items-end justify-end pb-6 sm:pb-8 pr-4 sm:pr-8 pl-4',
    ];

    /**
     * Modal instance providing layout and state options.
     *
     * @var Modal
     */
    private Modal $modal;

    /**
     * Create the styles helper.
     *
     * @param Modal $modal Modal instance to style.
     */
    public function __construct(Modal $modal)
    {
        $this->modal = $modal;
    }

    /**
     * Get backdrop overlay and viewport alignment classes.
     *
     * @return string
     */
    public function backdrop(): string
    {
        $positionClasses = self::POSITION_MAP[$this->modal->position()] ?? self::POSITION_MAP['center'];

        return "fixed inset-0 z-50 flex bg-black/50 backdrop-blur-sm transition-opacity duration-200 ease-out motion-reduce:transition-opacity {$positionClasses}";
    }

    /**
     * Get dialog container classes.
     *
     * @return string
     */
    public function dialog(): string
    {
        $sizeKey = $this->modal->size() ?? 'md';
        $sizeCls = self::SIZE_MAP[$sizeKey] ?? self::SIZE_MAP['md'];
        $scroll = $this->modal->isScrollable() ? ' flex flex-col max-h-[calc(100vh-2rem)]' : '';
        $extra = $this->modal->extraClass() !== '' ? ' ' . $this->modal->extraClass() : '';
        $classes = "relative w-full {$sizeCls} bg-surface dark:bg-surface-dark rounded-radius shadow-2xl border border-outline dark:border-outline-dark overflow-hidden{$scroll}{$extra}";

        $normalized = preg_replace('/\s+/', ' ', trim($classes));

        return $normalized ?? trim($classes);
    }

    /**
     * Get modal header bar classes.
     *
     * @return string
     */
    public function header(): string
    {
        $shrink = $this->modal->isScrollable() ? ' flex-shrink-0' : '';

        return "flex items-center justify-between px-6 py-4 border-b border-outline dark:border-outline-dark{$shrink}";
    }

    /**
     * Get default modal title heading classes.
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
        return 'ml-auto p-1 rounded-lg text-on-surface/50 dark:text-on-surface-dark/50 hover:text-on-surface dark:hover:text-on-surface-dark hover:bg-surface-alt dark:hover:bg-surface-dark-alt transition-colors focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-current';
    }

    /**
     * Get modal body container classes.
     *
     * @return string
     */
    public function body(): string
    {
        $scroll = $this->modal->isScrollable() ? ' overflow-y-auto flex-1' : '';

        return "px-6 py-4{$scroll}";
    }

    /**
     * Get modal footer bar classes.
     *
     * @return string
     */
    public function footer(): string
    {
        $shrink = $this->modal->isScrollable() ? ' flex-shrink-0' : '';

        return "flex items-center justify-end gap-2 px-6 py-4 border-t border-outline dark:border-outline-dark{$shrink}";
    }

    /**
     * Get secondary cancel/close footer button classes.
     *
     * @return string
     */
    public function secondaryButton(): string
    {
        return 'px-4 py-2 text-sm font-medium text-on-surface dark:text-on-surface-dark bg-surface-alt dark:bg-surface-dark-alt hover:bg-surface dark:hover:bg-surface-dark border border-outline dark:border-outline-dark rounded-radius transition-colors';
    }

    /**
     * Get primary action footer button classes.
     *
     * @return string
     */
    public function primaryButton(): string
    {
        return 'px-4 py-2 text-sm font-medium text-on-primary bg-primary hover:bg-primary/90 rounded-radius transition-colors';
    }
}
