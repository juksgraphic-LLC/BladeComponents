<?php

declare(strict_types=1);

namespace Juksgraphic\BladeComponents\Components\Accordions;

use Juksgraphic\BladeComponents\Components\Component;

/**
 * Accordion configuration and initial state.
 */
class Accordion extends Component
{
    /**
     * Allowed layout types.
     *
     * @var array<int, string>
     */
    public const TYPES = [
        'default', 'flush', 'always-open', 'icon', 'plus',
        'arrow', 'left-icon', 'custom', 'nested',
    ];

    /**
     * Allowed color variants.
     *
     * @var array<int, string>
     */
    public const COLORS = [
        'primary', 'secondary', 'success', 'danger',
        'warning', 'info', 'light', 'dark',
    ];

    /**
     * Default configuration values.
     *
     * @var array<string, mixed>
     */
    protected array $defaults = [
        'id'       => null,
        'type'     => 'default',
        'color'    => 'primary',
        'multiple' => false,
        'items'    => [],
    ];

    /**
     * Create and sanitize the accordion.
     *
     * @param array<string, mixed> $config Custom configuration values.
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $type = (string) $this->config['type'];

        $this->config['type']     = in_array($type, self::TYPES, true) ? $type : 'default';
        $this->config['color']    = $this->validColor($this->config['color']) ?? 'primary';
        $this->config['multiple'] = (bool) $this->config['multiple'] || $this->config['type'] === 'always-open';
        $this->config['id']       = $this->safeId($this->config['id']);
        $this->config['items']    = $this->normalizeItems((array) $this->config['items']);
    }

    /**
     * Get the HTML identifier.
     *
     * @return string
     */
    public function id(): string
    {
        return $this->config['id'];
    }

    /**
     * Get the layout type.
     *
     * @return string
     */
    public function type(): string
    {
        return $this->config['type'];
    }

    /**
     * Get the color variant.
     *
     * @return string
     */
    public function color(): string
    {
        return $this->config['color'];
    }

    /**
     * Get the normalized items.
     *
     * @return array<int, array<string, mixed>>
     */
    public function items(): array
    {
        return $this->config['items'];
    }

    /**
     * Check if type matches any given.
     *
     * @param string ...$types Types to compare.
     *
     * @return bool
     */
    public function is(string ...$types): bool
    {
        return in_array($this->config['type'], $types, true);
    }

    /**
     * Check if panel starts open.
     *
     * @param int $index Item index.
     *
     * @return bool
     */
    public function isExpanded(int $index): bool
    {
        return in_array($index, $this->expanded(), true);
    }

    /**
     * Get header element identifier.
     *
     * @param int $index Item index.
     *
     * @return string
     */
    public function headerId(int $index): string
    {
        return $this->config['id'] . '-header-' . $index;
    }

    /**
     * Get panel element identifier.
     *
     * @param int $index Item index.
     *
     * @return string
     */
    public function panelId(int $index): string
    {
        return $this->config['id'] . '-panel-' . $index;
    }

    /**
     * Get nested item state key.
     *
     * @param int $index  Item index.
     * @param int $nested Nested item index.
     *
     * @return string
     */
    public function nestedKey(int $index, int $nested): string
    {
        return $index . '-' . $nested;
    }

    /**
     * Get indexes of initially open panels.
     *
     * @return array<int, int>
     */
    public function expanded(): array
    {
        $open = [];

        foreach ($this->config['items'] as $index => $item) {
            if ($item['expanded'] && !$item['disabled']) {
                $open[] = $index;
            }
        }

        return $this->config['multiple'] ? $open : array_slice($open, 0, 1);
    }

    /**
     * Get indexes of disabled panels.
     *
     * @return array<int, int>
     */
    public function disabled(): array
    {
        $disabled = [];

        foreach ($this->config['items'] as $index => $item) {
            if ($item['disabled']) {
                $disabled[] = $index;
            }
        }

        return $disabled;
    }

    /**
     * Get Alpine initial state only.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id'       => $this->config['id'],
            'multiple' => $this->config['multiple'],
            'expanded' => $this->expanded(),
            'disabled' => $this->disabled(),
        ];
    }

    /**
     * Build a safe HTML identifier.
     *
     * @param mixed $id Raw identifier value.
     *
     * @return string
     */
    private function safeId(mixed $id): string
    {
        $clean = is_scalar($id) ? preg_replace('/[^A-Za-z0-9_-]/', '', (string) $id) : '';

        return $clean !== '' ? $clean : 'jkc-accordion-' . bin2hex(random_bytes(4));
    }

    /**
     * Return the color if allowed.
     *
     * @param mixed $color Raw color value.
     *
     * @return string|null
     */
    private function validColor(mixed $color): ?string
    {
        return is_string($color) && in_array($color, self::COLORS, true) ? $color : null;
    }

    /**
     * Fill missing item keys with defaults.
     *
     * @param array<int, mixed> $items Raw items list.
     *
     * @return array<int, array<string, mixed>>
     */
    private function normalizeItems(array $items): array
    {
        $normalized = [];

        foreach (array_values($items) as $index => $item) {
            $item = (array) $item;

            $normalized[] = [
                'title'    => (string) ($item['title'] ?? 'Item ' . ($index + 1)),
                'content'  => (string) ($item['content'] ?? ''),
                'icon'     => $item['icon'] ?? null,
                'disabled' => (bool) ($item['disabled'] ?? false),
                'expanded' => (bool) ($item['expanded'] ?? false),
                'color'    => $this->validColor($item['color'] ?? null),
                'nested'   => is_array($item['nested'] ?? null) ? array_values($item['nested']) : [],
            ];
        }

        return $normalized;
    }
}