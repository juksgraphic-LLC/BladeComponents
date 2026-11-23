<?php

declare(strict_types=1);

namespace Juksgraphic\BladeComponents\Components\Dropdowns;

use Juksgraphic\BladeComponents\Components\Component;

/**
 * Dropdown configuration and normalized state.
 */
class Dropdown extends Component
{
    /**
     * Allowed trigger types.
     *
     * @var array<int, string>
     */
    public const TRIGGERS = [
        'button', 'link', 'split',
    ];

    /**
     * Allowed button color variants.
     *
     * @var array<int, string>
     */
    public const COLORS = [
        'primary', 'secondary', 'success', 'danger',
        'warning', 'info', 'light', 'dark',
    ];

    /**
     * Allowed button size variants.
     *
     * @var array<int, string>
     */
    public const SIZES = [
        'lg', 'sm',
    ];

    /**
     * Allowed menu alignment variants.
     *
     * @var array<int, string>
     */
    public const ALIGNS = [
        'start', 'end', 'start-lg', 'end-lg',
        'dropstart', 'dropend', 'dropup',
    ];

    /**
     * Allowed dropdown item types.
     *
     * @var array<int, string>
     */
    public const ITEM_TYPES = [
        'link', 'divider', 'header', 'text', 'form',
    ];

    /**
     * Allowed dropdown item accent colors.
     *
     * @var array<int, string>
     */
    public const ITEM_COLORS = [
        'primary', 'secondary', 'success',
        'danger', 'warning', 'info',
    ];

    /**
     * Default chevron icon SVG for the trigger button.
     *
     * @var string
     */
    public const DEFAULT_ICON = '<svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>';

    /**
     * Default configuration values.
     *
     * @var array<string, mixed>
     */
    protected array $defaults = [
        'id' => null,
        'trigger' => 'button',
        'color' => 'primary',
        'size' => null,
        'darkMenu' => false,
        'align' => null,
        'autoClose' => true,
        'items' => [],
        'header' => null,
        'text' => null,
        'splitAction' => null,
        'buttonText' => 'Dropdown',
        'buttonIcon' => null,
        'menuClass' => '',
    ];

    /**
     * Create and sanitize the dropdown configuration.
     *
     * @param array<string, mixed> $config Custom configuration values.
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $trigger = (string) $this->config['trigger'];
        $size = $this->config['size'];
        $align = $this->config['align'];
        $buttonText = is_scalar($this->config['buttonText']) ? (string) $this->config['buttonText'] : 'Dropdown';
        $buttonIcon = $this->config['buttonIcon'];

        $this->config['id'] = $this->safeId($this->config['id']);
        $this->config['trigger'] = in_array($trigger, self::TRIGGERS, true) ? $trigger : 'button';
        $this->config['color'] = $this->validColor($this->config['color'], self::COLORS) ?? 'primary';
        $this->config['size'] = is_string($size) && in_array($size, self::SIZES, true) ? $size : null;
        $this->config['darkMenu'] = (bool) $this->config['darkMenu'];
        $this->config['align'] = is_string($align) && in_array($align, self::ALIGNS, true) ? $align : null;
        $this->config['autoClose'] = $this->normalizeAutoClose($this->config['autoClose']);
        $this->config['items'] = $this->normalizeItems((array) $this->config['items']);
        $this->config['header'] = $this->sanitizeNullableString($this->config['header']);
        $this->config['text'] = $this->sanitizeNullableString($this->config['text']);
        $this->config['splitAction'] = $this->sanitizeNullableString($this->config['splitAction']);
        $this->config['buttonText'] = $buttonText;
        $this->config['buttonIcon'] = is_scalar($buttonIcon) ? (string) $buttonIcon : self::DEFAULT_ICON;
        $this->config['menuClass'] = is_scalar($this->config['menuClass']) ? trim((string) $this->config['menuClass']) : '';
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
     * Get the trigger type.
     *
     * @return string
     */
    public function trigger(): string
    {
        return $this->config['trigger'];
    }

    /**
     * Check if trigger matches any given type.
     *
     * @param string ...$triggers Trigger types to compare.
     *
     * @return bool
     */
    public function isTrigger(string ...$triggers): bool
    {
        return in_array($this->config['trigger'], $triggers, true);
    }

    /**
     * Get the button color variant.
     *
     * @return string
     */
    public function color(): string
    {
        return $this->config['color'];
    }

    /**
     * Get the button size variant.
     *
     * @return string|null
     */
    public function size(): ?string
    {
        return $this->config['size'];
    }

    /**
     * Check whether dark menu appearance is forced.
     *
     * @return bool
     */
    public function isDarkMenu(): bool
    {
        return $this->config['darkMenu'];
    }

    /**
     * Get the dropdown menu alignment.
     *
     * @return string|null
     */
    public function align(): ?string
    {
        return $this->config['align'];
    }

    /**
     * Check whether clicking outside should close the menu.
     *
     * @return bool
     */
    public function closesOutside(): bool
    {
        return in_array($this->config['autoClose'], [true, 'outside'], true);
    }

    /**
     * Check whether clicking inside should close the menu.
     *
     * @return bool
     */
    public function closesInside(): bool
    {
        return in_array($this->config['autoClose'], [true, 'inside'], true);
    }

    /**
     * Get the normalized dropdown items.
     *
     * @return array<int, array<string, mixed>>
     */
    public function items(): array
    {
        return $this->config['items'];
    }

    /**
     * Get the optional menu header title.
     *
     * @return string|null
     */
    public function header(): ?string
    {
        return $this->config['header'];
    }

    /**
     * Get the optional static menu text.
     *
     * @return string|null
     */
    public function text(): ?string
    {
        return $this->config['text'];
    }

    /**
     * Get the split button primary action URL.
     *
     * @return string
     */
    public function splitAction(): string
    {
        return $this->config['splitAction'] ?? '#';
    }

    /**
     * Get the trigger button label text.
     *
     * @return string
     */
    public function buttonText(): string
    {
        return $this->config['buttonText'];
    }

    /**
     * Get the trigger button icon markup.
     *
     * @return string
     */
    public function buttonIcon(): string
    {
        return $this->config['buttonIcon'];
    }

    /**
     * Get extra CSS classes for the dropdown menu.
     *
     * @return string
     */
    public function menuClass(): string
    {
        return $this->config['menuClass'];
    }

    /**
     * Get Alpine initial state only.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'id' => $this->config['id'],
            'open' => false,
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

        return ($clean !== null && $clean !== '') ? $clean : 'jkc-dropdown-' . bin2hex(random_bytes(4));
    }

    /**
     * Return the color if allowed within the given palette.
     *
     * @param mixed              $color   Raw color value.
     * @param array<int, string> $allowed Allowed color list.
     *
     * @return string|null
     */
    private function validColor(mixed $color, array $allowed): ?string
    {
        return is_string($color) && in_array($color, $allowed, true) ? $color : null;
    }

    /**
     * Normalize autoClose behavior.
     *
     * @param mixed $value Raw autoClose value.
     *
     * @return bool|string
     */
    private function normalizeAutoClose(mixed $value): bool|string
    {
        if (in_array($value, [true, false, 'outside', 'inside'], true)) {
            return $value;
        }

        return true;
    }

    /**
     * Normalize a nullable non-empty string value.
     *
     * @param mixed $value Raw input value.
     *
     * @return string|null
     */
    private function sanitizeNullableString(mixed $value): ?string
    {
        if (! is_scalar($value)) {
            return null;
        }

        $string = (string) $value;

        return $string !== '' ? $string : null;
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

        foreach (array_values($items) as $item) {
            $item = (array) $item;
            $type = (string) ($item['type'] ?? 'link');

            $normalized[] = [
                'type' => in_array($type, self::ITEM_TYPES, true) ? $type : 'link',
                'text' => (string) ($item['text'] ?? ''),
                'href' => (string) ($item['href'] ?? '#'),
                'icon' => isset($item['icon']) && is_scalar($item['icon']) ? (string) $item['icon'] : null,
                'color' => $this->validColor($item['color'] ?? null, self::ITEM_COLORS),
                'active' => (bool) ($item['active'] ?? false),
                'disabled' => (bool) ($item['disabled'] ?? false),
                'content' => (string) ($item['content'] ?? ''),
            ];
        }

        return $normalized;
    }
}
