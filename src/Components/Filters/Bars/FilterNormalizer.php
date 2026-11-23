<?php

declare(strict_types=1);

namespace Juksgraphic\BladeComponents\Components\Filters\Bars;

use InvalidArgumentException;

/**
 * Normalizes filter definitions.
 */
final class FilterNormalizer
{
    /**
     * Allowed filter key pattern.
     * @var string
     */
    public const KEY_PATTERN = '/^[A-Za-z0-9_.\-]+$/';

    /**
     * Normalizes the filters list.
     *
     * @param mixed $rawFilters Raw filters prop.
     * @return array<int, array<string, mixed>>
     * @throws InvalidArgumentException On invalid or duplicate filter.
     */
    public static function normalizeAll($rawFilters): array
    {
        $filters = [];

        foreach ((array) $rawFilters as $rawFilter) {
            $filter = self::normalize((array) $rawFilter);

            if (isset($filters[$filter['key']])) {
                throw new InvalidArgumentException(BarErrors::ITEMS['FILTER_KEY_DUPLICATE']);
            }

            $filters[$filter['key']] = $filter;
        }

        return array_values($filters);
    }

    /**
     * Normalizes one filter definition.
     *
     * @param array<string, mixed> $raw Raw filter definition.
     * @return array<string, mixed>
     */
    private static function normalize(array $raw): array
    {
        $key = $raw['key'] ?? '';
        $type = $raw['type'] ?? BarTypes::ITEMS['SELECT'];

        if (! is_string($key) || ! preg_match(self::KEY_PATTERN, $key)) {
            throw new InvalidArgumentException(BarErrors::ITEMS['FILTER_KEY_INVALID']);
        }

        if (! in_array($type, BarTypes::ITEMS, true)) {
            throw new InvalidArgumentException(BarErrors::ITEMS['FILTER_TYPE_INVALID']);
        }

        return [
            'key' => $key,
            'label' => (string) ($raw['label'] ?? $key),
            'type' => $type,
            'options' => self::normalizeOptions($raw['options'] ?? []),
            'default' => self::normalizeDefault($raw['default'] ?? null, $type),
        ];
    }

    /**
     * Accepts a map or pair list.
     *
     * @param mixed $options Raw options.
     * @return array<int, array<string, string>>
     */
    private static function normalizeOptions($options): array
    {
        if (! is_array($options)) {
            throw new InvalidArgumentException(BarErrors::ITEMS['OPTIONS_INVALID']);
        }

        $normalized = [];

        foreach ($options as $value => $option) {
            $normalized[] = is_array($option)
                ? self::normalizeOption($option)
                : ['value' => (string) $value, 'label' => (string) $option];
        }

        return $normalized;
    }

    /**
     * Normalizes one value/label pair.
     *
     * @param array<string, mixed> $option Raw option.
     * @return array<string, string>
     */
    private static function normalizeOption(array $option): array
    {
        if (! array_key_exists('value', $option)) {
            throw new InvalidArgumentException(BarErrors::ITEMS['OPTIONS_INVALID']);
        }

        $value = (string) $option['value'];

        return ['value' => $value, 'label' => (string) ($option['label'] ?? $value)];
    }

    /**
     * Casts default by filter type.
     *
     * @param mixed  $default Raw default value.
     * @param string $type    Filter type.
     *
     * @return string|array<int, string>|null
     */
    private static function normalizeDefault($default, string $type)
    {
        if ($type === BarTypes::ITEMS['MULTISELECT']) {
            return array_values(array_filter(array_map('strval', (array) $default), 'strlen'));
        }

        return ($default === null || $default === '') ? null : (string) $default;
    }
}
