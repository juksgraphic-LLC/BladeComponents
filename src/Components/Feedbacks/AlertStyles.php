<?php

declare(strict_types=1);

namespace Juksgraphic\BladeComponents\Components\Feedbacks;

/**
 * Tailwind classes for the alert component.
 */
class AlertStyles
{
    /**
     * Color theme tokens for each alert variant.
     *
     * @var array<string, array<string, string>>
     */
    private const THEMES = [
        'primary' => [
            'softBg'     => 'bg-primary/10 dark:bg-primary-dark/10',
            'softText'   => 'text-primary dark:text-primary-dark',
            'softBorder' => 'border-primary/20 dark:border-primary-dark/20',
            'solidBg'    => 'bg-primary dark:bg-primary-dark',
            'solidText'  => 'text-on-primary dark:text-on-primary-dark',
            'border'     => 'border-primary dark:border-primary-dark',
            'outlineBg'  => 'bg-surface dark:bg-surface-dark',
            'badgeBg'    => 'bg-primary/15 dark:bg-primary-dark/15',
        ],
        'secondary' => [
            'softBg'     => 'bg-secondary/10 dark:bg-secondary-dark/10',
            'softText'   => 'text-secondary dark:text-secondary-dark',
            'softBorder' => 'border-secondary/20 dark:border-secondary-dark/20',
            'solidBg'    => 'bg-secondary dark:bg-secondary-dark',
            'solidText'  => 'text-on-secondary dark:text-on-secondary-dark',
            'border'     => 'border-secondary dark:border-secondary-dark',
            'outlineBg'  => 'bg-surface dark:bg-surface-dark',
            'badgeBg'    => 'bg-secondary/15 dark:bg-secondary-dark/15',
        ],
        'success' => [
            'softBg'     => 'bg-success/10',
            'softText'   => 'text-success',
            'softBorder' => 'border-success/20',
            'solidBg'    => 'bg-success',
            'solidText'  => 'text-on-success',
            'border'     => 'border-success',
            'outlineBg'  => 'bg-surface dark:bg-surface-dark',
            'badgeBg'    => 'bg-success/15',
        ],
        'danger' => [
            'softBg'     => 'bg-danger/10',
            'softText'   => 'text-danger',
            'softBorder' => 'border-danger/20',
            'solidBg'    => 'bg-danger',
            'solidText'  => 'text-on-danger',
            'border'     => 'border-danger',
            'outlineBg'  => 'bg-surface dark:bg-surface-dark',
            'badgeBg'    => 'bg-danger/15',
        ],
        'warning' => [
            'softBg'     => 'bg-warning/10',
            'softText'   => 'text-warning',
            'softBorder' => 'border-warning/20',
            'solidBg'    => 'bg-warning',
            'solidText'  => 'text-on-warning',
            'border'     => 'border-warning',
            'outlineBg'  => 'bg-surface dark:bg-surface-dark',
            'badgeBg'    => 'bg-warning/15',
        ],
        'info' => [
            'softBg'     => 'bg-info/10',
            'softText'   => 'text-info',
            'softBorder' => 'border-info/20',
            'solidBg'    => 'bg-info',
            'solidText'  => 'text-on-info',
            'border'     => 'border-info',
            'outlineBg'  => 'bg-surface dark:bg-surface-dark',
            'badgeBg'    => 'bg-info/15',
        ],
        'light' => [
            'softBg'     => 'bg-surface-alt',
            'softText'   => 'text-on-surface-strong',
            'softBorder' => 'border-outline',
            'solidBg'    => 'bg-surface-alt',
            'solidText'  => 'text-on-surface-strong',
            'border'     => 'border-outline-strong',
            'outlineBg'  => 'bg-surface',
            'badgeBg'    => 'bg-surface',
        ],
        'dark' => [
            'softBg'     => 'bg-surface-dark-alt',
            'softText'   => 'text-on-surface-dark',
            'softBorder' => 'border-outline-dark',
            'solidBg'    => 'bg-surface-dark',
            'solidText'  => 'text-on-surface-dark',
            'border'     => 'border-outline-dark-strong',
            'outlineBg'  => 'bg-surface-dark',
            'badgeBg'    => 'bg-surface-dark',
        ],
    ];

    /**
     * Alert providing type, style, and state.
     *
     * @var Alert
     */
    private Alert $alert;

    /**
     * Create the styles helper.
     *
     * @param Alert $alert Alert instance to style.
     */
    public function __construct(Alert $alert)
    {
        $this->alert = $alert;
    }

    /**
     * Get root alert container classes.
     *
     * @return string
     */
    public function container(): string
    {
        $theme = $this->theme();

        $variantClasses = match ($this->alert->style()) {
            'borderless'  => "{$theme['softBg']} {$theme['softText']}",
            'outline'     => "{$theme['outlineBg']} border {$theme['border']} {$theme['softText']}",
            'left-border' => "{$theme['softBg']} border-l-4 {$theme['border']} {$theme['softText']}",
            'top-border'  => "{$theme['softBg']} border-t-4 {$theme['border']} {$theme['softText']}",
            'solid'       => "{$theme['solidBg']} {$theme['solidText']}",
            default       => "{$theme['softBg']} border {$theme['softBorder']} {$theme['softText']}",
        };

        $classes = 'relative flex w-full items-start rounded-radius p-4 font-paragraph text-sm ' . $variantClasses;

        if ($this->alert->isDismissible()) {
            $classes .= ' pr-12';
        }

        if ($this->alert->extraClass() !== '') {
            $classes .= ' ' . $this->alert->extraClass();
        }

        $normalized = preg_replace('/\s+/', ' ', trim($classes));

        return $normalized ?? trim($classes);
    }

    /**
     * Get badge wrapper classes for `label-*` styles.
     *
     * @return string
     */
    public function iconBadge(): string
    {
        $radius = $this->alert->isStyle('rounded-label') ? 'rounded-full' : 'rounded-lg';

        return "mr-3 inline-flex size-8 shrink-0 items-center justify-center {$radius} {$this->theme()['badgeBg']}";
    }

    /**
     * Get simple icon wrapper classes.
     *
     * @return string
     */
    public function iconSimple(): string
    {
        return 'mr-3 mt-0.5 shrink-0';
    }

    /**
     * Get main content block classes.
     *
     * @return string
     */
    public function content(): string
    {
        $classes = 'min-w-0 flex-1';

        if ($this->alert->isLabel()) {
            $classes .= ' self-center';
        }

        return $classes;
    }

    /**
     * Get title heading classes.
     *
     * @return string
     */
    public function title(): string
    {
        return 'mb-1 font-title text-base font-semibold';
    }

    /**
     * Get divider rule classes under title.
     *
     * @return string
     */
    public function titleDivider(): string
    {
        return 'mb-2 border-current opacity-20';
    }

    /**
     * Get optional inline link classes.
     *
     * @return string
     */
    public function link(): string
    {
        return 'ml-1 font-medium underline underline-offset-2 hover:opacity-80 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-current';
    }

    /**
     * Get trailing arrow icon classes for `label-icon-arrow`.
     *
     * @return string
     */
    public function arrow(): string
    {
        return 'ml-2 size-4 shrink-0 self-center';
    }

    /**
     * Get dismiss button classes.
     *
     * @return string
     */
    public function dismissButton(): string
    {
        return 'absolute right-3 top-3 rounded-lg p-1.5 transition-colors hover:bg-black/5 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-current dark:hover:bg-white/10';
    }

    /**
     * Get active color theme array.
     *
     * @return array<string, string>
     */
    private function theme(): array
    {
        return self::THEMES[$this->alert->type()] ?? self::THEMES['primary'];
    }
}