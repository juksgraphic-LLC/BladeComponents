<?php

declare(strict_types=1);

namespace Juksgraphic\BladeComponents\Components\Forms;

/**
 * Tailwind classes for the custom radio group component.
 */
class RadioStyles
{
    /**
     * Radio instance providing state and options.
     *
     * @var Radio
     */
    private Radio $radio;

    /**
     * Create the styles helper.
     *
     * @param Radio $radio Radio instance to style.
     */
    public function __construct(Radio $radio)
    {
        $this->radio = $radio;
    }

    /**
     * Get outer wrapper classes.
     *
     * @return string
     */
    public function wrapper(): string
    {
        $classes = 'flex flex-col gap-2 col-span-full text-on-surface dark:text-on-surface-dark';

        if ($this->radio->extraClass() !== '') {
            $classes .= ' ' . $this->radio->extraClass();
        }

        return $classes;
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
     * Get required marker classes.
     *
     * @return string
     */
    public function requiredMarker(): string
    {
        return 'text-danger dark:text-danger-dark';
    }

    /**
     * Get options row container classes.
     *
     * @return string
     */
    public function optionsContainer(): string
    {
        return 'flex flex-wrap gap-4 pt-1';
    }

    /**
     * Get each radio option item classes.
     *
     * @return string
     */
    public function optionItem(): string
    {
        return 'group inline-flex items-center gap-2.5 cursor-pointer select-none text-sm text-on-surface dark:text-on-surface-dark';
    }

    /**
     * Get Alpine `:class` binding for the outer radio circle.
     *
     * @param string $optionId Option identifier.
     *
     * @return string
     */
    public function circleBinding(string $optionId): string
    {
        return "isSelected('{$optionId}') ? 'border-primary dark:border-primary-dark' : 'border-outline dark:border-outline-dark'";
    }

    /**
     * Get outer radio circle base classes.
     *
     * @return string
     */
    public function circle(): string
    {
        return 'flex h-5 w-5 shrink-0 items-center justify-center rounded-full border bg-surface-alt transition-all duration-150 group-hover:border-primary dark:bg-surface-dark-alt dark:group-hover:border-primary-dark';
    }

    /**
     * Get Alpine `:class` binding for the inner selected dot.
     *
     * @param string $optionId Option identifier.
     *
     * @return string
     */
    public function dotBinding(string $optionId): string
    {
        return "isSelected('{$optionId}') ? 'scale-100 opacity-100' : 'scale-0 opacity-0'";
    }

    /**
     * Get inner selected dot classes.
     *
     * @return string
     */
    public function dot(): string
    {
        return 'h-2 w-2 rounded-full bg-primary transition-all duration-150 dark:bg-primary-dark';
    }

    /**
     * Get option label text classes.
     *
     * @return string
     */
    public function optionTitle(): string
    {
        return 'group-hover:text-on-surface-strong dark:group-hover:text-on-surface-dark-strong transition-colors';
    }
}
