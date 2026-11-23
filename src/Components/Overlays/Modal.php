<?php

declare(strict_types=1);

namespace Juksgraphic\BladeComponents\Components\Overlays;

use Juksgraphic\BladeComponents\Components\Component;

/**
 * Modal dialog configuration, positioning, and transition helper.
 */
class Modal extends Component
{
    /**
     * Supported modal size keys.
     *
     * @var array<int, string>
     */
    public const SIZES = [
        'sm',
        'md',
        'lg',
        'xl',
        'fullscreen',
    ];

    /**
     * Supported modal viewport positions.
     *
     * @var array<int, string>
     */
    public const POSITIONS = [
        'center',
        'center-top',
        'center-bottom',
        'left-top',
        'right-top',
        'left-bottom',
        'right-bottom',
    ];

    /**
     * Legacy / shorthand position aliases mapped to canonical positions.
     *
     * @var array<string, string>
     */
    private const POSITION_ALIASES = [
        'top' => 'center-top',
        'bottom' => 'center-bottom',
        'top-right' => 'right-top',
        'bottom-right' => 'right-bottom',
        'top-left' => 'left-top',
        'bottom-left' => 'left-bottom',
    ];

    /**
     * Supported entrance/exit animation presets.
     *
     * @var array<int, string>
     */
    public const ANIMATIONS = [
        'fade',
        'fade-right',
        'fade-left',
        'fade-up',
        'flip',
        'zoom',
    ];

    /**
     * Default configuration values.
     *
     * @var array<string, mixed>
     */
    protected array $defaults = [
        'id' => null,
        'size' => null,
        'position' => null,
        'centered' => false,
        'static' => false,
        'scrollable' => false,
        'animation' => 'fade',
        'showClose' => true,
        'title' => 'Modal Title',
        'class' => '',
        'attrs' => '',
    ];

    /**
     * Create and sanitize the modal configuration.
     *
     * @param array<string, mixed> $config Custom configuration values.
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $size = is_string($this->config['size'] ?? null)
            && in_array(
                $this->config['size'],
                self::SIZES,
                true
            ) ? $this->config['size'] : null;

        $position = $this->normalizePosition($this->config['position'] ?? null, (bool) $this->config['centered']);

        $animation = is_string($this->config['animation'] ?? null)
            && in_array(
                $this->config['animation'],
                self::ANIMATIONS,
                true
            )
            ? $this->config['animation']
            : 'fade';

        $this->config['id'] = $this->safeId($this->config['id']);
        $this->config['size'] = $size;
        $this->config['position'] = $position;
        $this->config['centered'] = (bool) $this->config['centered'];
        $this->config['static'] = (bool) $this->config['static'];
        $this->config['scrollable'] = (bool) $this->config['scrollable'];
        $this->config['animation'] = $animation;
        $this->config['showClose'] = (bool) $this->config['showClose'];
        $this->config['title'] = is_scalar($this->config['title']) ? (string) $this->config['title'] : 'Modal Title';
        $this->config['class'] = is_scalar($this->config['class']) ? trim((string) $this->config['class']) : '';
        $this->config['attrs'] = is_scalar($this->config['attrs']) ? trim((string) $this->config['attrs']) : '';
    }

    /**
     * Get the sanitized modal identifier.
     *
     * @return string
     */
    public function id(): string
    {
        return $this->config['id'];
    }

    /**
     * Get the size key (`null` maps to medium).
     *
     * @return string|null
     */
    public function size(): ?string
    {
        return $this->config['size'];
    }

    /**
     * Get the resolved viewport position.
     *
     * @return string
     */
    public function position(): string
    {
        return $this->config['position'];
    }

    /**
     * Check whether closing on backdrop click is blocked.
     *
     * @return bool
     */
    public function isStatic(): bool
    {
        return (bool) $this->config['static'];
    }

    /**
     * Check whether the dialog body scrolls independently.
     *
     * @return bool
     */
    public function isScrollable(): bool
    {
        return (bool) $this->config['scrollable'];
    }

    /**
     * Get the animation preset name.
     *
     * @return string
     */
    public function animation(): string
    {
        return $this->config['animation'];
    }

    /**
     * Check whether the close icon button is shown in the header.
     *
     * @return bool
     */
    public function showsClose(): bool
    {
        return (bool) $this->config['showClose'];
    }

    /**
     * Get the default modal title text.
     *
     * @return string
     */
    public function title(): string
    {
        return $this->config['title'];
    }

    /**
     * Get extra dialog CSS classes.
     *
     * @return string
     */
    public function extraClass(): string
    {
        return $this->config['class'];
    }

    /**
     * Get extra wrapper HTML attributes.
     *
     * @return string
     */
    public function attrs(): string
    {
        return $this->config['attrs'];
    }

    /**
     * Get Alpine start/hidden state classes for the dialog box.
     *
     * @return string
     */
    public function dialogEnterStart(): string
    {
        return match ($this->animation()) {
            'fade-right' => 'opacity-0 translate-x-8 scale-95',
            'fade-left' => 'opacity-0 -translate-x-8 scale-95',
            'fade-up' => 'opacity-0 translate-y-8 scale-95',
            'flip' => 'opacity-0 scale-90',
            'zoom' => 'opacity-0 scale-75',
            default => 'opacity-0 scale-95',
        };
    }

    /**
     * Get Alpine active/visible state classes for the dialog box.
     *
     * @return string
     */
    public function dialogEnterEnd(): string
    {
        return match ($this->animation()) {
            'flip', 'zoom' => 'opacity-100 scale-100',
            default => 'opacity-100 translate-x-0 translate-y-0 scale-100',
        };
    }

    /**
     * Get smooth exit state classes when closing the modal.
     *
     * @return string
     */
    public function dialogLeaveEnd(): string
    {
        return $this->dialogEnterStart();
    }

    /**
     * Normalize position option and aliases into a canonical position.
     *
     * @param mixed $position Raw position value.
     * @param bool  $centered Legacy centered flag.
     *
     * @return string
     */
    private function normalizePosition(mixed $position, bool $centered): string
    {
        if (! is_string($position) || trim($position) === '') {
            return $centered ? 'center' : 'center';
        }

        $key = strtolower(trim($position));
        $key = self::POSITION_ALIASES[$key] ?? $key;

        return in_array($key, self::POSITIONS, true) ? $key : 'center';
    }

    /**
     * Sanitize or generate a safe HTML identifier.
     *
     * @param mixed $id Raw ID value.
     *
     * @return string
     */
    private function safeId(mixed $id): string
    {
        $clean = is_scalar($id) ? preg_replace('/[^A-Za-z0-9_-]/', '', (string) $id) : '';

        return ($clean !== null && $clean !== '') ? $clean : 'modal-' . bin2hex(random_bytes(4));
    }
}
