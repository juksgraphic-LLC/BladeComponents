<?php

declare(strict_types=1);

namespace Juksgraphic\BladeComponents\Components\Lists;

/**
 * Tailwind classes for the list group component.
 */
class ListGroupStyles
{
    /**
     * Color theme tokens for active items, badges, and icons.
     *
     * @var array<string, array<string, string>>
     */
    private const THEMES = [
        'primary' => [
            'activeBg' => 'bg-primary/5 dark:bg-primary/10',
            'activeText' => 'text-primary dark:text-primary-dark',
            'activeBorder' => 'border-primary/20 dark:border-primary/30',
            'badgeBg' => 'bg-primary/10 text-primary dark:bg-primary-dark/15 dark:text-primary-dark',
            'iconActive' => 'text-primary dark:text-primary-dark',
        ],
        'secondary' => [
            'activeBg' => 'bg-secondary/5 dark:bg-secondary/10',
            'activeText' => 'text-secondary dark:text-secondary-dark',
            'activeBorder' => 'border-secondary/20 dark:border-secondary/30',
            'badgeBg' => 'bg-secondary/10 text-secondary dark:bg-secondary-dark/15 dark:text-secondary-dark',
            'iconActive' => 'text-secondary dark:text-secondary-dark',
        ],
        'success' => [
            'activeBg' => 'bg-success/5 dark:bg-success/10',
            'activeText' => 'text-success',
            'activeBorder' => 'border-success/20 dark:border-success/30',
            'badgeBg' => 'bg-success/10 text-success',
            'iconActive' => 'text-success',
        ],
        'danger' => [
            'activeBg' => 'bg-danger/5 dark:bg-danger/10',
            'activeText' => 'text-danger',
            'activeBorder' => 'border-danger/20 dark:border-danger/30',
            'badgeBg' => 'bg-danger/10 text-danger',
            'iconActive' => 'text-danger',
        ],
        'warning' => [
            'activeBg' => 'bg-warning/5 dark:bg-warning/10',
            'activeText' => 'text-warning',
            'activeBorder' => 'border-warning/20 dark:border-warning/30',
            'badgeBg' => 'bg-warning/10 text-warning',
            'iconActive' => 'text-warning',
        ],
        'info' => [
            'activeBg' => 'bg-info/5 dark:bg-info/10',
            'activeText' => 'text-info',
            'activeBorder' => 'border-info/20 dark:border-info/30',
            'badgeBg' => 'bg-info/10 text-info',
            'iconActive' => 'text-info',
        ],
        'light' => [
            'activeBg' => 'bg-surface-alt dark:bg-surface-dark-alt',
            'activeText' => 'text-on-surface-strong dark:text-on-surface-dark-strong',
            'activeBorder' => 'border-outline dark:border-outline-dark',
            'badgeBg' => 'bg-surface-alt text-on-surface dark:bg-surface-dark-alt dark:text-on-surface-dark',
            'iconActive' => 'text-on-surface-strong dark:text-on-surface-dark-strong',
        ],
        'dark' => [
            'activeBg' => 'bg-surface-dark dark:bg-surface',
            'activeText' => 'text-on-surface-dark-strong dark:text-on-surface-strong',
            'activeBorder' => 'border-outline-dark-strong dark:border-outline-strong',
            'badgeBg' => 'bg-surface text-on-surface-strong dark:bg-surface-dark dark:text-on-surface-dark-strong',
            'iconActive' => 'text-on-surface-dark-strong dark:text-on-surface-strong',
        ],
    ];

    /**
     * ListGroup instance providing type, color, and items.
     *
     * @var ListGroup
     */
    private ListGroup $list;

    /**
     * Create the styles helper.
     *
     * @param ListGroup $list ListGroup instance to style.
     */
    public function __construct(ListGroup $list)
    {
        $this->list = $list;
    }

    /**
     * Get root container classes.
     *
     * @return string
     */
    public function container(): string
    {
        $variantClasses = match ($this->list->type()) {
            'flush' => '',
            'horizontal' => 'flex flex-row flex-wrap gap-0',
            'custom' => 'space-y-3',
            default => 'rounded-radius border border-outline dark:border-outline-dark overflow-hidden bg-surface dark:bg-surface-dark',
        };

        $classes = $variantClasses;

        if ($this->list->extraClass() !== '') {
            $classes .= ' ' . $this->list->extraClass();
        }

        $normalized = preg_replace('/\s+/', ' ', trim($classes));

        return $normalized ?? trim($classes);
    }

    /**
     * Get full classes for an item element at the given index.
     *
     * @param int $index Item index.
     *
     * @return string
     */
    public function item(int $index): string
    {
        $item = $this->list->items()[$index];
        $itemTheme = $this->themeFor($item['color'] ?? null);
        $itemBase = 'relative flex items-center gap-3 px-5 py-3 text-sm font-medium transition-all duration-200';

        $classes = match ($this->list->type()) {
            'flush'
                => "{$itemBase} border-b border-outline dark:border-outline-dark last:border-0 text-on-surface dark:text-on-surface-dark hover:bg-surface-alt dark:hover:bg-surface-dark-alt",
            'horizontal'
                => "{$itemBase} border border-outline dark:border-outline-dark text-on-surface dark:text-on-surface-dark hover:bg-surface-alt dark:hover:bg-surface-dark-alt px-4 py-2",
            'custom'
                => 'flex items-start gap-4 p-4 rounded-radius border border-outline dark:border-outline-dark bg-surface dark:bg-surface-dark hover:bg-surface-alt dark:hover:bg-surface-dark-alt transition-all duration-200',
            'contextual', 'colored'
                => "{$itemBase} border-b border-outline dark:border-outline-dark last:border-0",
            default
            => "{$itemBase} border-b border-outline dark:border-outline-dark last:border-0 text-on-surface dark:text-on-surface-dark hover:bg-surface-alt dark:hover:bg-surface-dark-alt",
        };

        if ($this->list->isType('button')) {
            $classes .= ' w-full text-left';
        } elseif ($this->list->isType('checkbox', 'radio')) {
            $classes .= ' cursor-pointer';
        }

        if ($item['active']) {
            if ($this->list->isType('custom')) {
                $classes .= ' bg-primary/5 border-primary/20 dark:bg-primary/10 dark:border-primary/35';
            } else {
                $classes .= " {$itemTheme['activeBg']} {$itemTheme['activeText']} {$itemTheme['activeBorder']}";
            }
        }

        if ($item['disabled']) {
            $classes .= ' opacity-50 cursor-not-allowed pointer-events-none';
        }

        return $classes;
    }

    /**
     * Get checkbox input classes.
     *
     * @return string
     */
    public function checkboxInput(): string
    {
        return 'size-4 rounded border-outline dark:border-outline-dark text-primary focus:ring-primary/20';
    }

    /**
     * Get radio input classes.
     *
     * @return string
     */
    public function radioInput(): string
    {
        return 'size-4 border-outline dark:border-outline-dark text-primary focus:ring-primary/20';
    }

    /**
     * Get index badge classes for numbered list items.
     *
     * @return string
     */
    public function numberBadge(): string
    {
        return 'flex size-6 shrink-0 items-center justify-center rounded-full bg-surface-alt dark:bg-surface-dark-alt font-semibold text-xs text-on-surface/60 dark:text-on-surface-dark/60';
    }

    /**
     * Get avatar image classes for custom list items.
     *
     * @return string
     */
    public function avatar(): string
    {
        return 'size-10 shrink-0 rounded-full object-cover';
    }

    /**
     * Get leading icon wrapper classes for an item.
     *
     * @param int $index Item index.
     *
     * @return string
     */
    public function itemIcon(int $index): string
    {
        $item = $this->list->items()[$index];
        $itemTheme = $this->themeFor($item['color'] ?? null);
        $tone = $item['active']
            ? $itemTheme['iconActive']
            : 'text-on-surface/50 dark:text-on-surface-dark/50';

        return "shrink-0 {$tone}";
    }

    /**
     * Get custom card title heading classes.
     *
     * @return string
     */
    public function customTitle(): string
    {
        return 'font-title font-semibold text-sm text-on-surface dark:text-on-surface-dark';
    }

    /**
     * Get custom card subtitle classes.
     *
     * @return string
     */
    public function customSubtitle(): string
    {
        return 'mb-1 font-paragraph text-xs text-on-surface/60 dark:text-on-surface-dark/60';
    }

    /**
     * Get custom card body text classes.
     *
     * @return string
     */
    public function customContent(): string
    {
        return 'font-paragraph text-sm text-on-surface/80 dark:text-on-surface-dark/80';
    }

    /**
     * Get standard item title classes.
     *
     * @param int $index Item index.
     *
     * @return string
     */
    public function itemTitle(int $index): string
    {
        $item = $this->list->items()[$index];
        $itemTheme = $this->themeFor($item['color'] ?? null);
        $activeCls = ($item['active'] && $this->list->isType('contextual', 'colored'))
            ? ' ' . $itemTheme['activeText']
            : '';

        return 'truncate' . $activeCls;
    }

    /**
     * Get badge pill classes for an item.
     *
     * @param int $index Item index.
     *
     * @return string
     */
    public function badge(int $index): string
    {
        $item = $this->list->items()[$index];
        $itemTheme = $this->themeFor($item['color'] ?? null);
        $badgeTheme = $this->themeFor($item['badgeColor'] ?? null, $itemTheme);

        return "shrink-0 rounded-full px-2 py-0.5 font-medium text-xs {$badgeTheme['badgeBg']}";
    }

    /**
     * Get trailing chevron classes for link and button items.
     *
     * @param int $index Item index.
     *
     * @return string
     */
    public function chevron(int $index): string
    {
        $item = $this->list->items()[$index];
        $itemTheme = $this->themeFor($item['color'] ?? null);
        $tone = $item['active']
            ? $itemTheme['iconActive']
            : 'text-on-surface/40 dark:text-on-surface-dark/40';

        return "size-4 shrink-0 {$tone}";
    }

    /**
     * Resolve color tokens for a given color name or fallback theme.
     *
     * @param string|null                $color    Optional color override.
     * @param array<string, string>|null $fallback Optional fallback token map.
     *
     * @return array<string, string>
     */
    private function themeFor(?string $color, ?array $fallback = null): array
    {
        $default = $fallback ?? (self::THEMES[$this->list->color()] ?? self::THEMES['primary']);

        if ($color === null) {
            return $default;
        }

        return self::THEMES[$color] ?? $default;
    }
}
