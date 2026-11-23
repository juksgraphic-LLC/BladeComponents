<?php

declare(strict_types=1);

namespace Juksgraphic\BladeComponents\Components\Lists;

use Juksgraphic\BladeComponents\Components\Component;

/**
 * List group configuration, item normalization, and state helper.
 */
class ListGroup extends Component
{
    /**
     * Allowed list layout and variant types.
     *
     * @var array<int, string>
     */
    public const TYPES = [
        'default', 'flush', 'horizontal', 'link', 'button',
        'custom', 'numbered', 'icon', 'checkbox', 'radio',
        'badge', 'contextual', 'colored',
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
        'id' => null,
        'type' => 'default',
        'color' => 'primary',
        'active' => null,
        'disabled' => [],
        'items' => [],
        'class' => '',
        'attrs' => '',
    ];

    /**
     * Create and sanitize the list group configuration.
     *
     * @param array<string, mixed> $config Custom configuration values.
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $type = (string) $this->config['type'];
        $active = isset($this->config['active']) && is_numeric($this->config['active']) ? (int) $this->config['active'] : null;
        $disabledList = $this->normalizeDisabledIndices((array) ($this->config['disabled'] ?? []));

        $this->config['id'] = $this->safeId($this->config['id']);
        $this->config['type'] = in_array($type, self::TYPES, true) ? $type : 'default';
        $this->config['color'] = $this->validColor($this->config['color']) ?? 'primary';
        $this->config['active'] = $active;
        $this->config['disabled'] = $disabledList;
        $this->config['items'] = $this->normalizeItems((array) $this->config['items'], $active, $disabledList);
        $this->config['class'] = is_scalar($this->config['class']) ? trim((string) $this->config['class']) : '';
        $this->config['attrs'] = is_scalar($this->config['attrs']) ? trim((string) $this->config['attrs']) : '';
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
     * Get the list layout type.
     *
     * @return string
     */
    public function type(): string
    {
        return $this->config['type'];
    }

    /**
     * Check whether the list type matches any of the given types.
     *
     * @param string ...$types List types to compare.
     *
     * @return bool
     */
    public function isType(string ...$types): bool
    {
        return in_array($this->config['type'], $types, true);
    }

    /**
     * Get the default color variant.
     *
     * @return string
     */
    public function color(): string
    {
        return $this->config['color'];
    }

    /**
     * Get the active item index if set.
     *
     * @return int|null
     */
    public function activeIndex(): ?int
    {
        return $this->config['active'];
    }

    /**
     * Get normalized list items.
     *
     * @return array<int, array<string, mixed>>
     */
    public function items(): array
    {
        return $this->config['items'];
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
     * Build a safe HTML identifier.
     *
     * @param mixed $id Raw identifier value.
     *
     * @return string
     */
    private function safeId(mixed $id): string
    {
        $clean = is_scalar($id) ? preg_replace('/[^A-Za-z0-9_-]/', '', (string) $id) : '';

        return ($clean !== null && $clean !== '') ? $clean : 'list-group-' . bin2hex(random_bytes(4));
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
     * Normalize list of disabled item indices into integers.
     *
     * @param array<int, mixed> $disabled Raw disabled list.
     *
     * @return array<int, int>
     */
    private function normalizeDisabledIndices(array $disabled): array
    {
        $indices = [];

        foreach ($disabled as $entry) {
            if (is_numeric($entry)) {
                $indices[] = (int) $entry;
            }
        }

        return $indices;
    }

    /**
     * Reject unsafe URI schemes on item links and avatars.
     *
     * @param mixed $url Raw URI value.
     *
     * @return string|null
     */
    private function sanitizeUri(mixed $url): ?string
    {
        if (! is_string($url)) {
            return null;
        }

        $trimmed = trim($url);

        if ($trimmed === '' || preg_match('/^\s*(javascript|data|vbscript):/i', $trimmed)) {
            return null;
        }

        return $trimmed;
    }

    /**
     * Normalize raw items with defaults, active/disabled states, and safe links.
     *
     * @param array<int, mixed> $items        Raw items array.
     * @param int|null          $activeIndex  Global active index.
     * @param array<int, int>   $disabledList Global disabled indices.
     *
     * @return array<int, array<string, mixed>>
     */
    private function normalizeItems(array $items, ?int $activeIndex, array $disabledList): array
    {
        $normalized = [];

        foreach (array_values($items) as $index => $item) {
            $item = (array) $item;
            $isActive = ($activeIndex !== null && $activeIndex === $index) || (bool) ($item['active'] ?? false);
            $isDisabled = in_array($index, $disabledList, true) || (bool) ($item['disabled'] ?? false);
            $safeHref = $this->sanitizeUri($item['href'] ?? null);
            $safeAvatar = $this->sanitizeUri($item['avatar'] ?? null);

            $normalized[] = [
                'title' => isset($item['title']) && is_scalar($item['title']) && (string) $item['title'] !== ''
                    ? (string) $item['title']
                    : 'Item ' . ($index + 1),
                'subtitle' => isset($item['subtitle']) && is_scalar($item['subtitle']) && (string) $item['subtitle'] !== ''
                    ? (string) $item['subtitle']
                    : null,
                'content' => isset($item['content']) && is_scalar($item['content']) && (string) $item['content'] !== ''
                    ? (string) $item['content']
                    : null,
                'icon' => isset($item['icon']) && is_scalar($item['icon']) && (string) $item['icon'] !== ''
                    ? (string) $item['icon']
                    : null,
                'badge' => isset($item['badge']) && is_scalar($item['badge']) && (string) $item['badge'] !== ''
                    ? (string) $item['badge']
                    : null,
                'badgeColor' => $this->validColor($item['badgeColor'] ?? null),
                'color' => $this->validColor($item['color'] ?? null),
                'avatar' => $safeAvatar,
                'action' => isset($item['action']) && is_scalar($item['action']) && trim((string) $item['action']) !== ''
                    ? trim((string) $item['action'])
                    : null,
                'href' => ($isDisabled || $safeHref === null) ? '#' : $safeHref,
                'active' => $isActive,
                'disabled' => $isDisabled,
                'number' => $index + 1,
            ];
        }

        return $normalized;
    }
}
