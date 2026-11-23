<?php

declare(strict_types=1);

namespace Juksgraphic\BladeComponents\Components\Filters\Bars;

use InvalidArgumentException;

/**
 * Normalizes bar-level settings.
 */
final class BarNormalizer
{
    /**
     * Validates the endpoint.
     * @param mixed $endpoint Raw endpoint prop.
     * @return string
     * @throws InvalidArgumentException When endpoint is empty.
     */
    public static function endpoint($endpoint): string
    {
        $endpoint = is_string($endpoint) ? trim($endpoint) : '';

        if ($endpoint === '') {
            throw new InvalidArgumentException(BarErrors::ITEMS['ENDPOINT_REQUIRED']);
        }

        return $endpoint;
    }

    /**
     * Returns given or generated id.
     * @param mixed $id Raw id prop.
     * @return string
     */
    public static function id($id): string
    {
        if (is_string($id) && $id !== '') {
            return $id;
        }

        return 'filterbar-' . bin2hex(random_bytes(4));
    }

    /**
     * Normalizes the search setting.
     * @param mixed $search Raw search prop.
     * @return array<string, mixed>
     */
    public static function search($search): array
    {
        $settings = is_array($search) ? $search : [];
        $enabled = is_array($search) ? (bool) ($settings['enabled'] ?? true) : (bool) $search;

        return [
            'enabled' => $enabled,
            'placeholder' => (string)  ($settings['placeholder'] ?? BarDefaults::ITEMS['PLACEHOLDER']),
            'debounce' => max(0, (int) ($settings['debounce'] ?? BarDefaults::ITEMS['DEBOUNCE'])),
        ];
    }

    /**
     * Validates the style variant.
     *
     * @param mixed $variant Raw variant prop.
     *
     * @return string
     *
     * @throws InvalidArgumentException When variant is unknown.
     */
    public static function variant($variant): string
    {
        $variant = $variant ?? BarDefaults::ITEMS['VARIANT'];

        if (! in_array($variant, BarVariants::ITEMS, true)) {
            throw new InvalidArgumentException(BarErrors::ITEMS['VARIANT_INVALID']);
        }

        return $variant;
    }

    /**
     * Returns a positive items-per-page value.
     * @param mixed $perPage Raw perPage prop.
     * @return int
     */
    public static function perPage($perPage): int
    {
        return max(1, (int) ($perPage ?? BarDefaults::ITEMS['PER_PAGE']));
    }

    /**
     * Normalizes the initial sort.
     *
     * Accepts a column name or ['column' => ..., 'direction' => ...].
     * A null column means no sort is sent.
     *
     * @param mixed $sort Raw sort prop.
     * @return array{column: string|null, direction: string}
     * @throws InvalidArgumentException On bad column or direction.
     */
    public static function sort($sort): array
    {
        $settings = is_array($sort) ? $sort : ['column' => $sort];
        $column = $settings['column'] ?? BarDefaults::ITEMS['SORT'];
        $direction = $settings['direction'] ?? BarDefaults::ITEMS['DIRECTION'];

        if ($column === '') {
            $column = null;
        }

        if ($column !== null && (! is_string($column) || ! preg_match(FilterNormalizer::KEY_PATTERN, $column))) {
            throw new InvalidArgumentException(BarErrors::ITEMS['SORT_INVALID']);
        }

        $direction = is_string($direction) ? strtolower($direction) : $direction;

        if (! in_array($direction, BarDirections::ITEMS, true)) {
            throw new InvalidArgumentException(BarErrors::ITEMS['DIRECTION_INVALID']);
        }

        return ['column' => $column, 'direction' => $direction];
    }
}
