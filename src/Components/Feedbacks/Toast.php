<?php

declare(strict_types=1);

namespace Juksgraphic\BladeComponents\Components\Feedbacks;

use Juksgraphic\BladeComponents\Components\Component;

/**
 * Toast notification configuration, sanitization, and state helper.
 */
class Toast extends Component
{
    /**
     * Allowed semantic color variants.
     *
     * @var array<int, string>
     */
    public const VARIANTS = [
        'default', 'info', 'success', 'warning', 'danger', 'message',
    ];

    /**
     * Allowed layout types.
     *
     * @var array<int, string>
     */
    public const TYPES = [
        'default', 'translucent', 'bordered', 'message',
    ];

    /**
     * Default SVG icons keyed by variant.
     *
     * @var array<string, string>
     */
    public const ICONS = [
        'info' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5" aria-hidden="true"><path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-7-4a1 1 0 1 1-2 0 1 1 0 0 1 2 0ZM9 9a.75.75 0 0 0 0 1.5h.253a.25.25 0 0 1 .244.304l-.459 2.066A1.75 1.75 0 0 0 10.747 15H11a.75.75 0 0 0 0-1.5h-.253a.25.25 0 0 1-.244-.304l.459-2.066A1.75 1.75 0 0 0 9.253 9H9Z" clip-rule="evenodd" /></svg>',
        'success' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z" clip-rule="evenodd" /></svg>',
        'warning' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5" aria-hidden="true"><path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495ZM10 5a.75.75 0 0 1 .75.75v3.5a.75.75 0 0 1-1.5 0v-3.5A.75.75 0 0 1 10 5Zm0 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2Z" clip-rule="evenodd" /></svg>',
        'danger' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16ZM8.28 7.22a.75.75 0 0 0-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 1 0 1.06 1.06L10 11.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L11.06 10l1.72-1.72a.75.75 0 0 0-1.06-1.06L10 8.94 8.28 7.22Z" clip-rule="evenodd" /></svg>',
        'message' => '',
        'default' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-5" aria-hidden="true"><path fill-rule="evenodd" d="M18 10a8 8 0 1 1-16 0 8 8 0 0 1 16 0Zm-7-4a1 1 0 1 1-2 0 1 1 0 0 1 2 0ZM9 9a.75.75 0 0 0 0 1.5h.253a.25.25 0 0 1 .244.304l-.459 2.066A1.75 1.75 0 0 0 10.747 15H11a.75.75 0 0 0 0-1.5h-.253a.25.25 0 0 1-.244-.304l.459-2.066A1.75 1.75 0 0 0 9.253 9H9Z" clip-rule="evenodd" /></svg>',
    ];

    /**
     * Default configuration values.
     *
     * @var array<string, mixed>
     */
    protected array $defaults = [
        'id' => null,
        'variant' => 'info',
        'type' => 'default',
        'title' => 'Notification',
        'message' => 'Hello, world! This is a toast message.',
        'time' => 'Just now',
        'senderName' => null,
        'senderAvatar' => null,
        'dismissible' => true,
        'translucent' => false,
        'duration' => null,
        'live' => false,
        'stacked' => false,
    ];

    /**
     * Create and sanitize the toast configuration.
     *
     * @param array<string, mixed> $config Custom configuration values.
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $variant = (string) $this->config['variant'];
        $type = (string) $this->config['type'];
        $duration = $this->config['duration'];

        $this->config['id'] = $this->safeId($this->config['id']);
        $this->config['variant'] = in_array($variant, self::VARIANTS, true) ? $variant : 'info';
        $this->config['type'] = in_array($type, self::TYPES, true) ? $type : 'default';
        $this->config['title'] = is_scalar($this->config['title']) ? (string) $this->config['title'] : 'Notification';
        $this->config['message'] = is_scalar($this->config['message']) ? (string) $this->config['message'] : '';
        $this->config['time'] = is_scalar($this->config['time']) ? (string) $this->config['time'] : 'Just now';
        $this->config['senderName'] = $this->sanitizeNullableString($this->config['senderName']);
        $this->config['senderAvatar'] = $this->sanitizeUrl($this->config['senderAvatar']);
        $this->config['dismissible'] = (bool) $this->config['dismissible'];
        $this->config['translucent'] = (bool) $this->config['translucent'];
        $this->config['duration'] = is_numeric($duration) && (int) $duration > 0 ? (int) $duration : null;
        $this->config['live'] = (bool) $this->config['live'];
        $this->config['stacked'] = (bool) $this->config['stacked'];
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
     * Get the semantic color variant.
     *
     * @return string
     */
    public function variant(): string
    {
        return $this->config['variant'];
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
     * Check whether the layout type matches any of the given types.
     *
     * @param string ...$types Layout types to check.
     *
     * @return bool
     */
    public function isType(string ...$types): bool
    {
        return in_array($this->config['type'], $types, true);
    }

    /**
     * Get the toast title.
     *
     * @return string
     */
    public function title(): string
    {
        return $this->config['title'];
    }

    /**
     * Get the toast message HTML content.
     *
     * @return string
     */
    public function message(): string
    {
        return $this->config['message'];
    }

    /**
     * Get the timestamp string.
     *
     * @return string
     */
    public function time(): string
    {
        return $this->config['time'];
    }

    /**
     * Get the optional sender name for message toasts.
     *
     * @return string|null
     */
    public function senderName(): ?string
    {
        return $this->config['senderName'];
    }

    /**
     * Get the sanitized sender avatar URL.
     *
     * @return string|null
     */
    public function senderAvatar(): ?string
    {
        return $this->config['senderAvatar'];
    }

    /**
     * Check whether the close button should be shown.
     *
     * @return bool
     */
    public function isDismissible(): bool
    {
        return $this->config['dismissible'];
    }

    /**
     * Check whether translucent glassmorphism background is enabled.
     *
     * @return bool
     */
    public function isTranslucent(): bool
    {
        return $this->config['translucent'];
    }

    /**
     * Get the auto-dismiss duration in milliseconds.
     *
     * @return int|null
     */
    public function duration(): ?int
    {
        return $this->config['duration'];
    }

    /**
     * Check whether the toast starts hidden until `jkc-open-toast` is dispatched.
     *
     * @return bool
     */
    public function isLive(): bool
    {
        return $this->config['live'];
    }

    /**
     * Check whether bottom margin spacing for container stacking is enabled.
     *
     * @return bool
     */
    public function isStacked(): bool
    {
        return $this->config['stacked'];
    }

    /**
     * Get the default SVG icon for the current variant.
     *
     * @return string
     */
    public function iconSvg(): string
    {
        return self::ICONS[$this->config['variant']] ?? self::ICONS['info'];
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
            'show' => ! $this->config['live'],
            'duration' => $this->config['duration'],
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

        return ($clean !== null && $clean !== '') ? $clean : 'toast-' . bin2hex(random_bytes(4));
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
     * Reject unsafe URI schemes on avatar URLs.
     *
     * @param mixed $url Raw URL value.
     *
     * @return string|null
     */
    private function sanitizeUrl(mixed $url): ?string
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
}
