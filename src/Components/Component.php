<?php

declare(strict_types=1);

namespace Juksgraphic\BladeComponents\Components;

use BackedEnum;
use Closure;
use JsonSerializable;
use UnitEnum;

/**
 * Base class for all components.
 */
abstract class Component implements JsonSerializable
{
    /**
     * Safe JSON flags for HTML attributes.
     *
     * @var int
     */
    public const JSON_FLAGS = JSON_HEX_TAG
        | JSON_HEX_APOS
        | JSON_HEX_QUOT
        | JSON_HEX_AMP
        | JSON_UNESCAPED_UNICODE
        | JSON_UNESCAPED_SLASHES;

    /**
     * Default configuration values.
     *
     * @var array<string, mixed>
     */
    protected array $defaults = [];

    /**
     * Final configuration (initial state).
     *
     * @var array<string, mixed>
     */
    protected array $config = [];

    /**
     * Create the component configuration.
     *
     * @param array<string, mixed> $config Custom configuration values.
     */
    public function __construct(array $config = [])
    {
        $this->config = array_merge($this->defaults, $config);
    }

    /**
     * Get a configuration value.
     *
     * @param string $key     Configuration key.
     * @param mixed  $default Fallback when key is missing.
     *
     * @return mixed
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->config[$key] ?? $default;
    }

    /**
     * Convert configuration to array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return $this->normalize($this->config);
    }

    /**
     * Convert configuration to JSON.
     *
     * @param int $flags JSON encoding flags.
     *
     * @return string
     */
    public function toJson(int $flags = self::JSON_FLAGS): string
    {
        return json_encode($this->toArray(), $flags | JSON_THROW_ON_ERROR);
    }

    /**
     * Provide data for json_encode.
     *
     * @return array<string, mixed>
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }

    /**
     * Make a value JSON-safe.
     *
     * @param mixed $value Value to normalize.
     *
     * @return mixed
     */
    protected function normalize(mixed $value): mixed
    {
        return match (true) {
            is_array($value)             => array_map(fn(mixed $item): mixed => $this->normalize($item), $value),
            $value instanceof self       => $value->toArray(),
            $value instanceof BackedEnum => $value->value,
            $value instanceof UnitEnum   => $value->name,
            $value instanceof Closure    => null,
            default                      => $value,
        };
    }
}
