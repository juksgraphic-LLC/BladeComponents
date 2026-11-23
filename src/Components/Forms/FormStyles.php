<?php

declare(strict_types=1);

namespace Juksgraphic\BladeComponents\Components\Forms;

/**
 * Tailwind classes for the dynamic form container component.
 */
class FormStyles
{
    /**
     * Form instance providing state and options.
     *
     * @var Form
     */
    private Form $form;

    /**
     * Create the styles helper.
     *
     * @param Form $form Form instance to style.
     */
    public function __construct(Form $form)
    {
        $this->form = $form;
    }

    /**
     * Get outer card wrapper classes.
     *
     * @return string
     */
    public function container(): string
    {
        $classes = 'w-full bg-surface p-6 rounded-radius border border-outline dark:bg-surface-dark-alt dark:border-outline-dark';

        if ($this->form->extraClass() !== '') {
            $classes .= ' ' . $this->form->extraClass();
        }

        $normalized = preg_replace('/\s+/', ' ', trim($classes));

        return $normalized ?? trim($classes);
    }

    /**
     * Get inner `<form>` grid classes.
     *
     * @return string
     */
    public function grid(): string
    {
        return 'grid grid-cols-1 md:grid-cols-2 gap-6';
    }

    /**
     * Get response container classes.
     *
     * @return string
     */
    public function responseBox(): string
    {
        return 'col-span-full';
    }

    /**
     * Get footer submit area classes.
     *
     * @return string
     */
    public function footer(): string
    {
        return 'col-span-full flex justify-end pt-6 border-t border-outline dark:border-outline-dark';
    }

    /**
     * Get default submit button classes.
     *
     * @return string
     */
    public function submitButton(): string
    {
        return 'bg-primary hover:bg-primary/90 text-on-primary font-medium px-6 py-2.5 rounded-radius text-sm transition-colors disabled:opacity-50 cursor-pointer dark:bg-primary-dark dark:text-on-primary-dark';
    }
}