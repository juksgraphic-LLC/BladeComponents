<?php

declare(strict_types=1);

namespace Juksgraphic\BladeComponents\Components\Cards;

use Juksgraphic\BladeComponents\Components\Component;

/**
 * Card configuration and state helper.
 */
class Card extends Component
{
    /**
     * Default configuration values.
     *
     * @var array<string, mixed>
     */
    protected array $defaults = [
        'title'     => null,
        'subtitle'  => null,
        'noPadding' => false,
        'class'     => '',
        'body'      => '',
    ];

    /**
     * Create and sanitize the card configuration.
     *
     * @param array<string, mixed> $config Custom configuration values.
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $this->config['title']     = $this->sanitizeNullableString($this->config['title']);
        $this->config['subtitle']  = $this->sanitizeNullableString($this->config['subtitle']);
        $this->config['noPadding'] = (bool) $this->config['noPadding'];
        $this->config['class']     = is_scalar($this->config['class']) ? trim((string) $this->config['class']) : '';
        $this->config['body']      = is_scalar($this->config['body']) ? (string) $this->config['body'] : '';
    }

    /**
     * Get the card title.
     *
     * @return string|null
     */
    public function title(): ?string
    {
        return $this->config['title'];
    }

    /**
     * Get the card subtitle.
     *
     * @return string|null
     */
    public function subtitle(): ?string
    {
        return $this->config['subtitle'];
    }

    /**
     * Check whether body padding should be removed.
     *
     * @return bool
     */
    public function hasNoPadding(): bool
    {
        return $this->config['noPadding'];
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
     * Get the card body content.
     *
     * @return string
     */
    public function body(): string
    {
        return $this->config['body'];
    }

    /**
     * Check whether the card has a title or header action.
     *
     * @param bool $hasHeaderAction Whether a header action slot is provided.
     *
     * @return bool
     */
    public function hasHeader(bool $hasHeaderAction = false): bool
    {
        return $this->config['title'] !== null || $hasHeaderAction;
    }

    /**
     * Check whether the card body has non-empty content.
     *
     * @return bool
     */
    public function hasBody(): bool
    {
        return trim($this->config['body']) !== '';
    }

    /**
     * Check whether the card has non-empty footer content.
     *
     * @param string|null $footer Footer slot content.
     *
     * @return bool
     */
    public function hasFooter(?string $footer): bool
    {
        return $footer !== null && trim($footer) !== '';
    }

    /**
     * Normalize a nullable string value.
     *
     * @param mixed $value Raw input value.
     *
     * @return string|null
     */
    private function sanitizeNullableString(mixed $value): ?string
    {
        if (!is_scalar($value)) {
            return null;
        }

        $string = (string) $value;

        return $string !== '' ? $string : null;
    }
}