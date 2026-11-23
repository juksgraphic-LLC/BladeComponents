<?php

declare(strict_types=1);

namespace Juksgraphic\BladeComponents\Components\Forms;

/**
 * Tailwind classes for the file input and preview card component.
 */
class FileInputStyles
{
    /**
     * FileInput instance providing state and options.
     *
     * @var FileInput
     */
    private FileInput $fileInput;

    /**
     * Create the styles helper.
     *
     * @param FileInput $fileInput FileInput instance to style.
     */
    public function __construct(FileInput $fileInput)
    {
        $this->fileInput = $fileInput;
    }

    /**
     * Get outer wrapper classes.
     *
     * @return string
     */
    public function wrapper(): string
    {
        return 'flex flex-col gap-2 col-span-full text-on-surface dark:text-on-surface-dark';
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
     * Get preview card container classes.
     *
     * @return string
     */
    public function previewCard(): string
    {
        return 'relative flex items-center gap-3.5 p-3 rounded-radius border border-outline bg-surface-alt dark:border-outline-dark dark:bg-surface-dark-alt/50 shadow-sm';
    }

    /**
     * Get image preview thumbnail classes.
     *
     * @return string
     */
    public function imagePreview(): string
    {
        return 'h-14 w-14 object-cover rounded-md border border-outline dark:border-outline-dark shrink-0';
    }

    /**
     * Get non-image file icon box classes.
     *
     * @return string
     */
    public function iconBox(): string
    {
        return 'flex h-14 w-14 items-center justify-center rounded-md border border-outline bg-surface dark:border-outline-dark dark:bg-surface-dark shrink-0 text-primary dark:text-primary-dark';
    }

    /**
     * Get file title classes.
     *
     * @return string
     */
    public function fileName(): string
    {
        return 'text-xs font-semibold text-on-surface-strong dark:text-on-surface-dark-strong truncate';
    }

    /**
     * Get file size/status subtitle classes.
     *
     * @return string
     */
    public function fileMeta(): string
    {
        return 'text-xs text-on-surface/70 dark:text-on-surface-dark/70';
    }

    /**
     * Get change file button classes.
     *
     * @return string
     */
    public function changeButton(): string
    {
        return 'px-3 py-1.5 text-xs font-medium rounded-radius bg-primary text-on-primary hover:opacity-90 dark:bg-primary-dark dark:text-on-primary-dark transition-opacity';
    }

    /**
     * Get remove file button classes.
     *
     * @return string
     */
    public function removeButton(): string
    {
        return 'p-1.5 rounded-radius text-danger hover:bg-danger/10 transition-colors';
    }

    /**
     * Get native file input classes.
     *
     * @return string
     */
    public function control(): string
    {
        $classes = 'w-full rounded-radius border border-outline bg-surface-alt px-3 py-2 text-sm text-on-surface cursor-pointer dark:border-outline-dark dark:bg-surface-dark-alt/50 dark:text-on-surface-dark file:mr-4 file:py-1 file:px-4 file:rounded-radius file:border-0 file:text-xs file:font-semibold file:bg-primary file:text-on-primary hover:file:opacity-90 dark:file:bg-primary-dark dark:file:text-on-primary-dark';

        if ($this->fileInput->extraClass() !== '') {
            $classes .= ' ' . $this->fileInput->extraClass();
        }

        $normalized = preg_replace('/\s+/', ' ', trim($classes));

        return $normalized ?? trim($classes);
    }

    /**
     * Get helper recommendation text classes.
     *
     * @return string
     */
    public function recommendedText(): string
    {
        return 'text-xs text-on-surface/60 dark:text-on-surface-dark/60 pl-0.5';
    }
}