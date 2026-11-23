<?php

declare(strict_types=1);

namespace Juksgraphic\BladeComponents\Components\Forms;

/**
 * Tailwind classes for the checkbox switch and multi-checkbox component.
 */
class CheckBoxStyles
{
    /**
     * CheckBox instance providing state and options.
     *
     * @var CheckBox
     */
    private CheckBox $checkbox;

    /**
     * Create the styles helper.
     *
     * @param CheckBox $checkbox CheckBox instance to style.
     */
    public function __construct(CheckBox $checkbox)
    {
        $this->checkbox = $checkbox;
    }

    /**
     * Get outer wrapper classes.
     *
     * @return string
     */
    public function wrapper(): string
    {
        $classes = 'flex flex-col gap-2 text-on-surface dark:text-on-surface-dark';

        if ($this->checkbox->isMultiple()) {
            $classes .= ' col-span-full';
        }

        if ($this->checkbox->extraClass() !== '') {
            $classes .= ' ' . $this->checkbox->extraClass();
        }

        return $classes;
    }

    /**
     * Get switch row container classes.
     *
     * @return string
     */
    public function switchRow(): string
    {
        return 'flex items-center justify-between gap-4 py-1';
    }

    /**
     * Get switch label text classes.
     *
     * @return string
     */
    public function switchLabel(): string
    {
        return 'text-sm font-semibold text-on-surface-strong dark:text-on-surface-dark-strong';
    }

    /**
     * Get switch toggle hit area classes.
     *
     * @return string
     */
    public function switchButton(): string
    {
        return 'relative inline-flex h-6 w-11 shrink-0 cursor-pointer items-center';
    }

    /**
     * Get Alpine `:class` binding for the switch track.
     *
     * @return string
     */
    public function switchTrackBinding(): string
    {
        return "modelValue ? 'border-primary bg-primary dark:border-primary-dark dark:bg-primary-dark' : 'border-outline bg-surface-alt dark:border-outline-dark dark:bg-surface-dark-alt'";
    }

    /**
     * Get switch track base classes.
     *
     * @return string
     */
    public function switchTrack(): string
    {
        return 'absolute inset-0 rounded-full border transition-colors duration-200 ease-in-out';
    }

    /**
     * Get Alpine `:class` binding for the switch thumb.
     *
     * @return string
     */
    public function switchThumbBinding(): string
    {
        return "modelValue ? 'translate-x-5' : 'translate-x-0.5'";
    }

    /**
     * Get switch thumb base classes.
     *
     * @return string
     */
    public function switchThumb(): string
    {
        return 'absolute left-0.5 top-0.5 h-5 w-5 rounded-full bg-white shadow-sm transition-transform duration-200 ease-in-out';
    }

    /**
     * Get group label classes for multi-checkbox mode.
     *
     * @return string
     */
    public function groupLabel(): string
    {
        return 'w-fit text-sm font-semibold text-on-surface-strong dark:text-on-surface-dark-strong';
    }

    /**
     * Get options list container classes.
     *
     * @return string
     */
    public function optionsContainer(): string
    {
        return 'flex flex-wrap gap-4 pt-1';
    }

    /**
     * Get each multi-checkbox option row classes.
     *
     * @return string
     */
    public function optionItem(): string
    {
        return 'group inline-flex items-center gap-2.5 cursor-pointer select-none text-sm text-on-surface dark:text-on-surface-dark';
    }

    /**
     * Get Alpine `:class` binding for option box selection state.
     *
     * @param string $optionId Option identifier.
     *
     * @return string
     */
    public function optionBoxBinding(string $optionId): string
    {
        return "isChecked('{$optionId}') ? 'border-primary bg-primary dark:border-primary-dark dark:bg-primary-dark' : 'border-outline bg-surface-alt dark:border-outline-dark dark:bg-surface-dark-alt'";
    }

    /**
     * Get option box base classes.
     *
     * @return string
     */
    public function optionBox(): string
    {
        return 'flex h-5 w-5 shrink-0 items-center justify-center rounded border transition-all duration-150 group-hover:border-primary dark:group-hover:border-primary-dark';
    }

    /**
     * Get Alpine `:class` binding for option check icon visibility.
     *
     * @param string $optionId Option identifier.
     *
     * @return string
     */
    public function optionCheckBinding(string $optionId): string
    {
        return "isChecked('{$optionId}') ? 'opacity-100' : 'opacity-0'";
    }

    /**
     * Get check icon SVG classes.
     *
     * @return string
     */
    public function optionCheckIcon(): string
    {
        return 'h-3.5 w-3.5 text-white transition-opacity duration-150';
    }

    /**
     * Get option title text classes.
     *
     * @return string
     */
    public function optionTitle(): string
    {
        return 'group-hover:text-on-surface-strong dark:group-hover:text-on-surface-dark-strong transition-colors';
    }
}
