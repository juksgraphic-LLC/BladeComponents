<?php

declare(strict_types=1);

namespace Juksgraphic\BladeComponents\Components\Navigation;

use Juksgraphic\BladeComponents\Components\Component;

/**
 * Navigation breadcrumb configuration and item normalization helper.
 */
class Breadcrumb extends Component
{
    /**
     * Supported breadcrumb visual style variants.
     *
     * @var array<int, string>
     */
    public const STYLES = [
        'chevron',
        'slash',
        'bullet',
        'background',
    ];

    /**
     * Default configuration values.
     *
     * @var array<string, mixed>
     */
    protected array $defaults = [
        'items' => [],
        'style' => 'chevron',
        'class' => '',
        'attrs' => '',
    ];

    /**
     * Create and sanitize the breadcrumb configuration.
     *
     * @param array<string, mixed> $config Custom configuration values.
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $style = is_string($this->config['style'] ?? null) && in_array($this->config['style'], self::STYLES, true)
            ? $this->config['style']
            : 'chevron';

        $this->config['style'] = $style;
        $this->config['items'] = $this->normalizeItems((array) $this->config['items']);
        $this->config['class'] = is_scalar($this->config['class']) ? trim((string) $this->config['class']) : '';
        $this->config['attrs'] = is_scalar($this->config['attrs']) ? trim((string) $this->config['attrs']) : '';
    }

    /**
     * Get normalized breadcrumb items.
     *
     * @return array<int, array<string, mixed>>
     */
    public function items(): array
    {
        return $this->config['items'];
    }

    /**
     * Get total item count.
     *
     * @return int
     */
    public function totalItems(): int
    {
        return count($this->config['items']);
    }

    /**
     * Check whether the breadcrumb has items to display.
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return $this->totalItems() === 0;
    }

    /**
     * Get active style variant (`chevron`, `slash`, `bullet`, `background`).
     *
     * @return string
     */
    public function style(): string
    {
        return $this->config['style'];
    }

    /**
     * Get extra container CSS classes.
     *
     * @return string
     */
    public function extraClass(): string
    {
        return $this->config['class'];
    }

    /**
     * Get extra container HTML attributes.
     *
     * @return string
     */
    public function attrs(): string
    {
        return $this->config['attrs'];
    }

    /**
     * Sanitize item target URLs to block unsafe schemes.
     *
     * @param mixed $url Raw URL string.
     *
     * @return string
     */
    private function sanitizeUrl(mixed $url): string
    {
        if (! is_string($url)) {
            return '#';
        }

        $trimmed = trim($url);

        if ($trimmed === '' || preg_match('/^\s*(javascript|data|vbscript):/i', $trimmed)) {
            return '#';
        }

        return $trimmed;
    }

    /**
     * Normalize raw breadcrumb items with last-item flags and safe URLs.
     *
     * @param array<int, mixed> $items Raw items list.
     *
     * @return array<int, array<string, mixed>>
     */
    private function normalizeItems(array $items): array
    {
        $reindexed = array_values($items);
        $count = count($reindexed);
        $result = [];

        foreach ($reindexed as $index => $item) {
            $row = (array) $item;
            $isLast = $index === ($count - 1);

            $result[] = [
                'label' => isset($row['label']) && is_scalar($row['label']) ? (string) $row['label'] : '',
                'url' => $this->sanitizeUrl($row['url'] ?? null),
                'icon' => isset($row['icon']) && is_scalar($row['icon']) && (string) $row['icon'] !== ''
                    ? (string) $row['icon']
                    : null,
                'is_last' => $isLast,
            ];
        }

        return $result;
    }
}
