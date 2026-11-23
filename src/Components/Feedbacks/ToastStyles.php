<?php

declare(strict_types=1);

namespace Juksgraphic\BladeComponents\Components\Feedbacks;

/**
 * Tailwind classes for the toast notification component.
 */
class ToastStyles
{
    /**
     * Color theme tokens keyed by semantic variant.
     *
     * @var array<string, array<string, string>>
     */
    private const THEMES = [
        'info' => [
            'border' => 'border-info',
            'text' => 'text-info',
            'bgSoft' => 'bg-info/10',
            'bgBadge' => 'bg-info/15',
        ],
        'success' => [
            'border' => 'border-success',
            'text' => 'text-success',
            'bgSoft' => 'bg-success/10',
            'bgBadge' => 'bg-success/15',
        ],
        'warning' => [
            'border' => 'border-warning',
            'text' => 'text-warning',
            'bgSoft' => 'bg-warning/10',
            'bgBadge' => 'bg-warning/15',
        ],
        'danger' => [
            'border' => 'border-danger',
            'text' => 'text-danger',
            'bgSoft' => 'bg-danger/10',
            'bgBadge' => 'bg-danger/15',
        ],
        'message' => [
            'border' => 'border-outline',
            'text' => 'text-on-surface',
            'bgSoft' => 'bg-surface-alt',
            'bgBadge' => 'bg-primary/15',
        ],
        'default' => [
            'border' => 'border-outline',
            'text' => 'text-on-surface',
            'bgSoft' => 'bg-surface-alt',
            'bgBadge' => 'bg-primary/15',
        ],
    ];

    /**
     * Toast instance providing variant, type, and flags.
     *
     * @var Toast
     */
    private Toast $toast;

    /**
     * Create the styles helper.
     *
     * @param Toast $toast Toast instance to style.
     */
    public function __construct(Toast $toast)
    {
        $this->toast = $toast;
    }

    /**
     * Get root toast wrapper classes.
     *
     * @return string
     */
    public function container(): string
    {
        $theme = $this->theme();

        $classes = match ($this->toast->type()) {
            'bordered' => "pointer-events-auto relative rounded-radius border {$theme['border']} bg-surface text-on-surface dark:bg-surface-dark dark:text-on-surface-dark",
            'translucent' => 'pointer-events-auto relative rounded-radius border border-outline bg-white/80 text-on-surface backdrop-blur-sm dark:border-outline-dark dark:bg-surface-dark/80 dark:text-on-surface-dark',
            default => 'pointer-events-auto relative rounded-radius border border-outline bg-surface text-on-surface dark:border-outline-dark dark:bg-surface-dark dark:text-on-surface-dark',
        };

        if ($this->toast->isStacked()) {
            $classes .= ' mb-3';
        }

        if ($this->toast->isTranslucent()) {
            $classes .= ' bg-white/80 dark:bg-surface-dark/80 backdrop-blur-sm';
        }

        return $classes;
    }

    /**
     * Get inner body layout classes for `bordered` and `message` toasts.
     *
     * @return string
     */
    public function inner(): string
    {
        $theme = $this->theme();

        return match ($this->toast->type()) {
            'bordered' => "flex w-full items-center gap-2.5 {$theme['bgSoft']} rounded-radius p-4 transition-all duration-300",
            'translucent' => 'flex w-full items-center gap-2.5 rounded-radius p-4 transition-all duration-300',
            'message' => 'flex w-full items-start gap-3 rounded-radius p-4 transition-all duration-300',
            default => 'flex w-full flex-col rounded-radius transition-all duration-300',
        };
    }

    /**
     * Get badge wrapper classes for `bordered` toast icon.
     *
     * @return string
     */
    public function borderedIconBadge(): string
    {
        $theme = $this->theme();

        return "rounded-full {$theme['bgBadge']} p-0.5 {$theme['text']}";
    }

    /**
     * Get title heading classes for `bordered` toast.
     *
     * @return string
     */
    public function borderedTitle(): string
    {
        return "text-sm font-semibold {$this->theme()['text']}";
    }

    /**
     * Get dismiss button classes for `bordered` and `message` toasts.
     *
     * @return string
     */
    public function inlineDismissButton(): string
    {
        return 'ml-auto text-on-surface dark:text-on-surface-dark hover:text-danger transition-colors focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-current';
    }

    /**
     * Get header bar classes for `default` and `translucent` toasts.
     *
     * @return string
     */
    public function defaultHeader(): string
    {
        return 'flex items-center justify-between px-4 py-3 border-b border-outline dark:border-outline-dark';
    }

    /**
     * Get title text classes for `default` and `translucent` toasts.
     *
     * @return string
     */
    public function defaultTitle(): string
    {
        return 'text-sm font-semibold text-on-surface-strong dark:text-on-surface-dark-strong truncate';
    }

    /**
     * Get timestamp text classes for `default` and `translucent` toasts.
     *
     * @return string
     */
    public function defaultTime(): string
    {
        return 'text-xs text-on-surface/60 dark:text-on-surface-dark/60';
    }

    /**
     * Get dismiss button classes for `default` and `translucent` toasts.
     *
     * @return string
     */
    public function defaultDismissButton(): string
    {
        return 'text-on-surface/50 dark:text-on-surface-dark/50 hover:text-danger transition-colors focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-current';
    }

    /**
     * Get body content classes for `default` and `translucent` toasts.
     *
     * @return string
     */
    public function defaultBody(): string
    {
        return 'px-4 py-3 text-sm text-on-surface dark:text-on-surface-dark';
    }

    /**
     * Get active color theme array.
     *
     * @return array<string, string>
     */
    private function theme(): array
    {
        return self::THEMES[$this->toast->variant()] ?? self::THEMES['info'];
    }
}
