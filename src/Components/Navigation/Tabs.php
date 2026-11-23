<?php

declare(strict_types=1);

namespace Juksgraphic\BladeComponents\Components\Navigation;

use Juksgraphic\BladeComponents\Components\Component;

/**
 * Tabs navigation configuration, tab item normalization, and active index helper.
 */
class Tabs extends Component
{
    /**
     * Supported tab layout variants.
     *
     * @var array<int, string>
     */
    public const VARIANTS = [
        'default', 'pills', 'pills-justified', 'justified', 'vertical',
        'card-header', 'card-footer', 'arrow', 'custom-tabs', 'custom-hover',
        'custom-vertical', 'animation', 'border-top', 'border-top-justified',
        'outline', 'custom', 'colored', 'light',
    ];

    /**
     * Supported color tokens.
     *
     * @var array<int, string>
     */
    public const COLORS = [
        'primary', 'secondary', 'success', 'danger', 'warning', 'info',
    ];

    /**
     * Variants arranged vertically alongside content panels.
     *
     * @var array<int, string>
     */
    private const VERTICAL_VARIANTS = [
        'vertical', 'custom-vertical',
    ];

    /**
     * Default configuration values.
     *
     * @var array<string, mixed>
     */
    protected array $defaults = [
        'id' => null,
        'variant' => 'default',
        'color' => 'primary',
        'tabs' => [],
        'tabClass' => '',
        'contentClass' => '',
    ];

    /**
     * Create and sanitize the tabs configuration.
     *
     * @param array<string, mixed> $config Custom configuration values.
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $variant = is_string($this->config['variant'] ?? null) && in_array($this->config['variant'], self::VARIANTS, true)
            ? $this->config['variant']
            : 'default';
        $color = is_string($this->config['color'] ?? null) && in_array($this->config['color'], self::COLORS, true)
            ? $this->config['color']
            : 'primary';

        $normalizedTabs = $this->normalizeTabs((array) ($this->config['tabs'] ?? []));
        $activeIndex = $this->resolveActiveIndex($normalizedTabs);

        $this->config['id'] = $this->safeId($this->config['id']);
        $this->config['variant'] = $variant;
        $this->config['color'] = $color;
        $this->config['tabs'] = $normalizedTabs;
        $this->config['activeIndex'] = $activeIndex;
        $this->config['tabClass'] = is_scalar($this->config['tabClass']) ? trim((string) $this->config['tabClass']) : '';
        $this->config['contentClass'] = is_scalar($this->config['contentClass']) ? trim((string) $this->config['contentClass']) : '';
    }

    /**
     * Get the sanitized group identifier.
     *
     * @return string
     */
    public function id(): string
    {
        return $this->config['id'];
    }

    /**
     * Get the active layout variant name.
     *
     * @return string
     */
    public function variant(): string
    {
        return $this->config['variant'];
    }

    /**
     * Get the active color token (`primary`, `secondary`, `success`, `danger`, `warning`, `info`).
     *
     * @return string
     */
    public function color(): string
    {
        return $this->config['color'];
    }

    /**
     * Check whether tabs are rendered in a vertical sidebar column.
     *
     * @return bool
     */
    public function isVertical(): bool
    {
        return in_array($this->config['variant'], self::VERTICAL_VARIANTS, true);
    }

    /**
     * Get normalized tab definitions.
     *
     * @return array<int, array<string, mixed>>
     */
    public function tabs(): array
    {
        return $this->config['tabs'];
    }

    /**
     * Get the initially active tab index.
     *
     * @return int
     */
    public function activeIndex(): int
    {
        return (int) $this->config['activeIndex'];
    }

    /**
     * Get extra CSS classes for the root wrapper.
     *
     * @return string
     */
    public function tabClass(): string
    {
        return $this->config['tabClass'];
    }

    /**
     * Get extra CSS classes for the content panels wrapper.
     *
     * @return string
     */
    public function contentClass(): string
    {
        return $this->config['contentClass'];
    }

    /**
     * Get Alpine.js initial data payload.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'activeTab' => $this->activeIndex(),
        ];
    }

    /**
     * Sanitize or generate a safe HTML identifier.
     *
     * @param mixed $id Raw ID input.
     *
     * @return string
     */
    private function safeId(mixed $id): string
    {
        $clean = is_scalar($id) ? preg_replace('/[^A-Za-z0-9_-]/', '', (string) $id) : '';

        return ($clean !== null && $clean !== '') ? $clean : 'jkc-tabs-' . bin2hex(random_bytes(4));
    }

    /**
     * Normalize raw tab items into uniform arrays.
     *
     * @param array<int, mixed> $tabs Raw tabs array.
     *
     * @return array<int, array<string, mixed>>
     */
    private function normalizeTabs(array $tabs): array
    {
        $normalized = [];

        foreach (array_values($tabs) as $tab) {
            $item = (array) $tab;

            $normalized[] = [
                'label' => isset($item['label']) && is_scalar($item['label']) ? (string) $item['label'] : '',
                'icon' => isset($item['icon']) && is_scalar($item['icon']) && (string) $item['icon'] !== ''
                    ? (string) $item['icon']
                    : null,
                'badge' => isset($item['badge']) && is_scalar($item['badge']) && (string) $item['badge'] !== ''
                    ? (string) $item['badge']
                    : null,
                'active' => (bool) ($item['active'] ?? false),
                'disabled' => (bool) ($item['disabled'] ?? false),
                'content' => isset($item['content']) && is_scalar($item['content']) ? (string) $item['content'] : '',
            ];
        }

        return $normalized;
    }

    /**
     * Determine the first enabled tab marked active, defaulting to index 0.
     *
     * @param array<int, array<string, mixed>> $tabs Normalized tab items.
     *
     * @return int
     */
    private function resolveActiveIndex(array $tabs): int
    {
        foreach ($tabs as $index => $tab) {
            if ($tab['active'] && ! $tab['disabled']) {
                return $index;
            }
        }

        return 0;
    }
}
