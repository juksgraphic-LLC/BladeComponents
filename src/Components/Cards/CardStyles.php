<?php

declare(strict_types=1);

namespace Juksgraphic\BladeComponents\Components\Cards;

/**
 * Tailwind classes for the card component.
 */
class CardStyles
{
    /**
     * Card instance providing state and options.
     *
     * @var Card
     */
    private Card $card;

    /**
     * Create the styles helper.
     *
     * @param Card $card Card instance to style.
     */
    public function __construct(Card $card)
    {
        $this->card = $card;
    }

    /**
     * Get root container classes.
     *
     * @return string
     */
    public function container(): string
    {
        $classes = 'bg-surface dark:bg-surface-dark rounded-radius border border-outline dark:border-outline-dark overflow-hidden';

        if ($this->card->extraClass() !== '') {
            $classes .= ' ' . $this->card->extraClass();
        }

        return $classes;
    }

    /**
     * Get header wrapper classes.
     *
     * @return string
     */
    public function header(): string
    {
        return 'flex items-center justify-between gap-4 px-6 py-4 border-b border-outline dark:border-outline-dark';
    }

    /**
     * Get title heading classes.
     *
     * @return string
     */
    public function title(): string
    {
        return 'font-title text-base font-semibold text-on-surface dark:text-on-surface-dark';
    }

    /**
     * Get subtitle text classes.
     *
     * @return string
     */
    public function subtitle(): string
    {
        return 'font-paragraph text-sm text-on-surface/60 dark:text-on-surface-dark/60 mt-0.5';
    }

    /**
     * Get header action wrapper classes.
     *
     * @return string
     */
    public function headerAction(): string
    {
        return 'shrink-0';
    }

    /**
     * Get body wrapper classes.
     *
     * @return string
     */
    public function body(): string
    {
        return $this->card->hasNoPadding() ? '' : 'px-6 py-4';
    }

    /**
     * Get footer wrapper classes.
     *
     * @return string
     */
    public function footer(): string
    {
        return 'px-6 py-4 border-t border-outline dark:border-outline-dark';
    }
}
