<?php

declare(strict_types=1);

namespace Juksgraphic\BladeComponents\Components\Theme;

use Juksgraphic\BladeComponents\Components\Component;

/**
 * Dark/light theme switch configuration and state helper.
 */
class ThemeSwitch extends Component
{
    /**
     * Supported switch size keys.
     *
     * @var array<int, string>
     */
    public const SIZES = [
        'sm', 'md', 'lg',
    ];

    /**
     * Heroicons sun outline SVG path data.
     *
     * @var string
     */
    public const SUN_PATH = 'M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z';

    /**
     * Heroicons moon outline SVG path data.
     *
     * @var string
     */
    public const MOON_PATH = 'M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z';

    /**
     * Default configuration values.
     *
     * @var array<string, mixed>
     */
    protected array $defaults = [
        'id'          => null,
        'size'        => 'md',
        'initialDark' => false,
        'storageKey'  => null,
        'label'       => 'Toggle dark mode',
        'class'       => '',
        'attrs'       => '',
    ];

    /**
     * Create and sanitize the theme switch configuration.
     *
     * @param array<string, mixed> $config Custom configuration values.
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $size = is_string($this->config['size'] ?? null) && in_array($this->config['size'], self::SIZES, true)
            ? $this->config['size']
            : 'md';

        $this->config['id']          = $this->sanitizeId($this->config['id']);
        $this->config['size']        = $size;
        $this->config['initialDark'] = (bool) $this->config['initialDark'];
        $this->config['storageKey']  = $this->sanitizeStorageKey($this->config['storageKey']);
        $this->config['label']       = is_scalar($this->config['label']) && (string) $this->config['label'] !== ''
            ? (string) $this->config['label']
            : 'Toggle dark mode';
        $this->config['class']       = is_scalar($this->config['class']) ? trim((string) $this->config['class']) : '';
        $this->config['attrs']       = is_scalar($this->config['attrs']) ? trim((string) $this->config['attrs']) : '';
    }

    /**
     * Get optional sanitized HTML identifier.
     *
     * @return string|null
     */
    public function id(): ?string
    {
        return $this->config['id'];
    }

    /**
     * Get switch size key (`sm`, `md`, or `lg`).
     *
     * @return string
     */
    public function size(): string
    {
        return $this->config['size'];
    }

    /**
     * Check whether dark mode is enabled by default.
     *
     * @return bool
     */
    public function initialDark(): bool
    {
        return (bool) $this->config['initialDark'];
    }

    /**
     * Get optional sanitized localStorage key.
     *
     * @return string|null
     */
    public function storageKey(): ?string
    {
        return $this->config['storageKey'];
    }

    /**
     * Get accessible switch label.
     *
     * @return string
     */
    public function label(): string
    {
        return $this->config['label'];
    }

    /**
     * Get extra button CSS classes.
     *
     * @return string
     */
    public function extraClass(): string
    {
        return $this->config['class'];
    }

    /**
     * Get extra button HTML attributes.
     *
     * @return string
     */
    public function attrs(): string
    {
        return $this->config['attrs'];
    }

    /**
     * Get Alpine.js initial data payload.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'isDark'     => $this->initialDark(),
            'storageKey' => $this->storageKey(),
            'ready'      => false,
        ];
    }

    /**
     * Sanitize an optional HTML identifier.
     *
     * @param mixed $id Raw ID input.
     *
     * @return string|null
     */
    private function sanitizeId(mixed $id): ?string
    {
        if (!is_scalar($id)) {
            return null;
        }

        $clean = preg_replace('/[^A-Za-z0-9_-]/', '', (string) $id);

        return ($clean !== null && $clean !== '') ? $clean : null;
    }

    /**
     * Sanitize an optional localStorage key.
     *
     * @param mixed $key Raw storage key input.
     *
     * @return string|null
     */
    private function sanitizeStorageKey(mixed $key): ?string
    {
        if (!is_string($key)) {
            return null;
        }

        $clean = preg_replace('/[^A-Za-z0-9_.-]/', '', $key);

        return ($clean !== null && $clean !== '') ? $clean : null;
    }
}