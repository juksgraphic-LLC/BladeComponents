<?php

declare(strict_types=1);

namespace Juksgraphic\BladeComponents\Components\Overlays;

use Juksgraphic\BladeComponents\Components\Component;

/**
 * Offcanvas drawer configuration, directional transitions, and state helper.
 */
class Offcanvas extends Component
{
    /**
     * Supported placement sides.
     *
     * @var array<int, string>
     */
    public const PLACEMENTS = [
        'start',
        'end',
        'top',
        'bottom',
    ];

    /**
     * Supported panel size keys.
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
     * Default configuration values.
     *
     * @var array<string, mixed>
     */
    protected array $defaults = [
        'id' => null,
        'placement' => 'start',
        'size' => null,
        'backdrop' => true,
        'scroll' => false,
        'title' => 'Offcanvas',
        'showClose' => true,
        'class' => '',
        'attrs' => '',
    ];

    /**
     * Create and sanitize the offcanvas configuration.
     *
     * @param array<string, mixed> $config Custom configuration values.
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $placement = is_string($this->config['placement'] ?? null) && in_array($this->config['placement'], self::PLACEMENTS, true)
            ? $this->config['placement']
            : 'start';
        $size = is_string($this->config['size'] ?? null) && in_array($this->config['size'], self::SIZES, true)
            ? $this->config['size']
            : null;

        $this->config['id'] = $this->safeId($this->config['id']);
        $this->config['placement'] = $placement;
        $this->config['size'] = $size;
        $this->config['backdrop'] = (bool) $this->config['backdrop'];
        $this->config['scroll'] = (bool) $this->config['scroll'];
        $this->config['title'] = is_scalar($this->config['title']) ? (string) $this->config['title'] : 'Offcanvas';
        $this->config['showClose'] = (bool) $this->config['showClose'];
        $this->config['class'] = is_scalar($this->config['class']) ? trim((string) $this->config['class']) : '';
        $this->config['attrs'] = is_scalar($this->config['attrs']) ? trim((string) $this->config['attrs']) : '';
    }

    /**
     * Get the sanitized offcanvas identifier.
     *
     * @return string
     */
    public function id(): string
    {
        return $this->config['id'];
    }

    /**
     * Get the placement side (`start`, `end`, `top`, `bottom`).
     *
     * @return string
     */
    public function placement(): string
    {
        return $this->config['placement'];
    }

    /**
     * Get the panel size key (`null` maps to medium).
     *
     * @return string|null
     */
    public function size(): ?string
    {
        return $this->config['size'];
    }

    /**
     * Check whether the dimmed backdrop overlay is shown.
     *
     * @return bool
     */
    public function hasBackdrop(): bool
    {
        return (bool) $this->config['backdrop'];
    }

    /**
     * Check whether body overflow is allowed while open.
     *
     * @return bool
     */
    public function allowsScroll(): bool
    {
        return (bool) $this->config['scroll'];
    }

    /**
     * Get the panel header title.
     *
     * @return string
     */
    public function title(): string
    {
        return $this->config['title'];
    }

    /**
     * Check whether the close button is shown in the header.
     *
     * @return bool
     */
    public function showsClose(): bool
    {
        return (bool) $this->config['showClose'];
    }

    /**
     * Get extra panel CSS classes.
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
     * Get starting transform classes when opening the panel.
     *
     * @return string
     */
    public function enterStart(): string
    {
        return match ($this->placement()) {
            'end' => 'translate-x-full',
            'top' => '-translate-y-full',
            'bottom' => 'translate-y-full',
            default => '-translate-x-full',
        };
    }

    /**
     * Get settled transform classes when open.
     *
     * @return string
     */
    public function enterEnd(): string
    {
        return 'translate-x-0 translate-y-0';
    }

    /**
     * Get the starting transform classes so the panel returns to its origin on close.
     *
     * @return string
     */
    public function leaveEnd(): string
    {
        return $this->enterStart();
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

        return ($clean !== null && $clean !== '') ? $clean : 'offcanvas-' . bin2hex(random_bytes(4));
    }
}
