<?php

declare(strict_types=1);

namespace Juksgraphic\BladeComponents\Components\Feedbacks;

use Juksgraphic\BladeComponents\Components\Component;

/**
 * Alert configuration, sanitization, and state helper.
 */
class Alert extends Component
{
    /**
     * Allowed color variants.
     *
     * @var array<int, string>
     */
    public const TYPES = [
        'primary', 'secondary', 'success', 'danger',
        'warning', 'info', 'light', 'dark',
    ];

    /**
     * Allowed layout styles.
     *
     * @var array<int, string>
     */
    public const STYLES = [
        'default', 'borderless', 'outline', 'left-border',
        'top-border', 'solid', 'label-icon', 'label-icon-arrow',
        'rounded-label',
    ];

    /**
     * Layout variants that render a badge icon container.
     *
     * @var array<int, string>
     */
    public const LABEL_STYLES = [
        'label-icon', 'label-icon-arrow', 'rounded-label',
    ];

    /**
     * Default SVG icons keyed by semantic alert type.
     *
     * @var array<string, string>
     */
    public const DEFAULT_ICONS = [
        'info' => '<svg class="size-5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>',
        'success' => '<svg class="size-5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>',
        'danger' => '<svg class="size-5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>',
        'warning' => '<svg class="size-5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>',
    ];

    /**
     * Default icon key per color variant.
     *
     * @var array<string, string>
     */
    private const TYPE_ICONS = [
        'primary' => 'info',
        'secondary' => 'info',
        'success' => 'success',
        'danger' => 'danger',
        'warning' => 'warning',
        'info' => 'info',
        'light' => 'info',
        'dark' => 'info',
    ];

    /**
     * Default configuration values.
     *
     * @var array<string, mixed>
     */
    protected array $defaults = [
        'type' => 'primary',
        'style' => 'default',
        'dismissible' => false,
        'icon' => false,
        'title' => null,
        'message' => '',
        'link' => null,
        'linkText' => 'Learn more',
        'live' => false,
        'id' => null,
        'class' => '',
        'attrs' => '',
        'closeLabel' => 'Close',
    ];

    /**
     * Create and sanitize the alert configuration.
     *
     * @param array<string, mixed> $config Custom configuration values.
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $type = (string) $this->config['type'];
        $style = (string) $this->config['style'];
        $icon = $this->config['icon'];
        $linkText = is_scalar($this->config['linkText']) ? (string) $this->config['linkText'] : 'Learn more';
        $closeLabel = is_scalar($this->config['closeLabel']) ? (string) $this->config['closeLabel'] : 'Close';

        $this->config['type'] = in_array($type, self::TYPES, true) ? $type : 'primary';
        $this->config['style'] = in_array($style, self::STYLES, true) ? $style : 'default';
        $this->config['dismissible'] = (bool) $this->config['dismissible'];
        $this->config['icon'] = is_string($icon) || is_bool($icon) ? $icon : (bool) $icon;
        $this->config['title'] = $this->sanitizeNullableString($this->config['title']);
        $this->config['message'] = is_scalar($this->config['message']) ? (string) $this->config['message'] : '';
        $this->config['link'] = $this->sanitizeLink($this->config['link']);
        $this->config['linkText'] = $linkText !== '' ? $linkText : 'Learn more';
        $this->config['live'] = (bool) $this->config['live'];
        $this->config['id'] = $this->sanitizeId($this->config['id']);
        $this->config['class'] = is_scalar($this->config['class']) ? trim((string) $this->config['class']) : '';
        $this->config['attrs'] = is_scalar($this->config['attrs']) ? trim((string) $this->config['attrs']) : '';
        $this->config['closeLabel'] = $closeLabel !== '' ? $closeLabel : 'Close';
    }

    /**
     * Get the alert color variant.
     *
     * @return string
     */
    public function type(): string
    {
        return $this->config['type'];
    }

    /**
     * Get the alert layout style.
     *
     * @return string
     */
    public function style(): string
    {
        return $this->config['style'];
    }

    /**
     * Check whether the alert style matches any of the given styles.
     *
     * @param string ...$styles Style names to check.
     *
     * @return bool
     */
    public function isStyle(string ...$styles): bool
    {
        return in_array($this->config['style'], $styles, true);
    }

    /**
     * Check whether the alert can be dismissed.
     *
     * @return bool
     */
    public function isDismissible(): bool
    {
        return $this->config['dismissible'];
    }

    /**
     * Check whether the alert is driven by the window `jkc-alert` event.
     *
     * @return bool
     */
    public function isLive(): bool
    {
        return $this->config['live'];
    }

    /**
     * Check whether Alpine.js reactivity is required.
     *
     * @return bool
     */
    public function usesAlpine(): bool
    {
        return $this->config['live'] || $this->config['dismissible'];
    }

    /**
     * Check whether the current style uses a label icon badge.
     *
     * @return bool
     */
    public function isLabel(): bool
    {
        return in_array($this->config['style'], self::LABEL_STYLES, true);
    }

    /**
     * Check whether an icon should be displayed.
     *
     * @return bool
     */
    public function hasIcon(): bool
    {
        return $this->isLabel() || ! empty($this->config['icon']);
    }

    /**
     * Resolve the icon SVG/HTML markup.
     *
     * @return string
     */
    public function iconHtml(): string
    {
        $icon = $this->config['icon'];

        if (is_string($icon) && str_starts_with(ltrim($icon), '<')) {
            return $icon;
        }

        $iconKey = self::TYPE_ICONS[$this->config['type']] ?? 'info';

        return self::DEFAULT_ICONS[$iconKey];
    }

    /**
     * Get the optional alert title.
     *
     * @return string|null
     */
    public function title(): ?string
    {
        return $this->config['title'];
    }

    /**
     * Get the alert message HTML content.
     *
     * @return string
     */
    public function message(): string
    {
        return $this->config['message'];
    }

    /**
     * Get the sanitized optional link URL.
     *
     * @return string|null
     */
    public function link(): ?string
    {
        return $this->config['link'];
    }

    /**
     * Get the optional link text.
     *
     * @return string
     */
    public function linkText(): string
    {
        return $this->config['linkText'];
    }

    /**
     * Get the sanitized optional HTML identifier.
     *
     * @return string|null
     */
    public function id(): ?string
    {
        return $this->config['id'];
    }

    /**
     * Get extra CSS classes.
     *
     * @return string
     */
    public function extraClass(): string
    {
        return $this->config['class'];
    }

    /**
     * Get extra HTML attributes.
     *
     * @return string
     */
    public function attrs(): string
    {
        return $this->config['attrs'];
    }

    /**
     * Get accessible close button label.
     *
     * @return string
     */
    public function closeLabel(): string
    {
        return $this->config['closeLabel'];
    }

    /**
     * Get ARIA role based on alert severity.
     *
     * @return string
     */
    public function role(): string
    {
        return in_array($this->config['type'], ['danger', 'warning'], true) ? 'alert' : 'status';
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
     * Sanitize optional HTML ID.
     *
     * @param mixed $id Raw ID value.
     *
     * @return string|null
     */
    private function sanitizeId(mixed $id): ?string
    {
        if (! is_scalar($id) || (string) $id === '') {
            return null;
        }

        $clean = preg_replace('/[^A-Za-z0-9_-]/', '', (string) $id);

        return ($clean !== null && $clean !== '') ? $clean : null;
    }

    /**
     * Reject unsafe URI schemes on optional link URLs.
     *
     * @param mixed $link Raw link value.
     *
     * @return string|null
     */
    private function sanitizeLink(mixed $link): ?string
    {
        if (! is_string($link)) {
            return null;
        }

        $trimmed = trim($link);

        if ($trimmed === '' || preg_match('/^\s*(javascript|data|vbscript):/i', $trimmed)) {
            return null;
        }

        return $trimmed;
    }
}
