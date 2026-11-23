<?php

declare(strict_types=1);

namespace Juksgraphic\BladeComponents\Components\Forms;

/**
 * Tailwind classes for the text input field component.
 */
class InputStyles
{
    /**
     * Input instance providing state and options.
     *
     * @var Input
     */
    private Input $input;

    /**
     * Create the styles helper.
     *
     * @param Input $input Input instance to style.
     */
    public function __construct(Input $input)
    {
        $this->input = $input;
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
     * Get required asterisk classes.
     *
     * @return string
     */
    public function requiredMarker(): string
    {
        return 'text-danger dark:text-danger-dark';
    }

    /**
     * Get input control classes.
     *
     * @return string
     */
    public function control(): string
    {
        $classes = 'w-full rounded-radius border border-outline bg-surface-alt px-3.5 py-2 text-sm text-on-surface focus:border-primary focus:outline-none dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:text-on-surface-dark dark:focus:border-primary-dark';

        if ($this->input->extraClass() !== '') {
            $classes .= ' ' . $this->input->extraClass();
        }

        $normalized = preg_replace('/\s+/', ' ', trim($classes));

        return $normalized ?? trim($classes);
    }
}
