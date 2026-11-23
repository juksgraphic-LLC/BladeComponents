<?php

declare(strict_types=1);

namespace Juksgraphic\BladeComponents\Components\Forms;

/**
 * Tailwind classes for the standard and Quill textarea component.
 */
class TextareaStyles
{
    /**
     * Textarea instance providing state and options.
     *
     * @var Textarea
     */
    private Textarea $textarea;

    /**
     * Create the styles helper.
     *
     * @param Textarea $textarea Textarea instance to style.
     */
    public function __construct(Textarea $textarea)
    {
        $this->textarea = $textarea;
    }

    /**
     * Get field wrapper classes.
     *
     * @return string
     */
    public function wrapper(): string
    {
        return 'flex flex-col gap-1.5 col-span-full text-on-surface dark:text-on-surface-dark';
    }

    /**
     * Get label classes.
     *
     * @return string
     */
    public function label(): string
    {
        return 'w-fit pl-0.5 text-sm font-semibold text-on-surface-strong dark:text-on-surface-dark-strong';
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
     * Get Quill editor outer frame classes.
     *
     * @return string
     */
    public function quillFrame(): string
    {
        return 'overflow-hidden rounded-radius border border-outline dark:border-outline-dark';
    }

    /**
     * Get Quill editor surface classes.
     *
     * @return string
     */
    public function quillSurface(): string
    {
        return 'max-h-[160px] bg-surface-alt dark:bg-surface-dark-alt/50 font-paragraph text-sm';
    }

    /**
     * Get standard `<textarea>` classes.
     *
     * @return string
     */
    public function control(): string
    {
        $classes = 'min-h-[120px] w-full placeholder:text-on-surface dark:placeholder:text-on-surface-dark rounded-radius border border-outline bg-surface-alt p-3 text-sm outline-none transition-all focus:border-primary focus:ring-2 focus:ring-primary/20 dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:text-on-surface-dark';

        if ($this->textarea->extraClass() !== '') {
            $classes .= ' ' . $this->textarea->extraClass();
        }

        $normalized = preg_replace('/\s+/', ' ', trim($classes));

        return $normalized ?? trim($classes);
    }
}