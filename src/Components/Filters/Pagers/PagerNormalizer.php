<?php

declare(strict_types=1);

namespace Juksgraphic\BladeComponents\Components\Filters\Pagers;


use InvalidArgumentException;

/**
 * Normalizes pager settings.
 */
final class PagerNormalizer
{
    /**
     * Validates the followed bar id.
     *
     * @param mixed $bar Raw bar prop.
     * @return string
     * @throws InvalidArgumentException When bar id is empty.
     */
    public static function bar($bar): string
    {
        $bar = is_string($bar) ? trim($bar) : '';

        if ($bar === '') {
            throw new InvalidArgumentException(PagerErrors::ITEMS['BAR_REQUIRED']);
        }

        return $bar;
    }

    /**
     * Returns given or generated DOM id.
     *
     * @param mixed $id Raw id prop.
     * @return string
     */
    public static function id($id): string
    {
        if (is_string($id) && $id !== '') {
            return $id;
        }

        return 'pager-' . bin2hex(random_bytes(4));
    }

    /**
     * Validates the page size choices.
     *
     * An empty list is allowed: no size select is rendered.
     *
     * @param mixed $options Raw perPageOptions prop.
     * @return array<int, int>
     * @throws InvalidArgumentException On a non-positive-integer entry.
     */
    public static function perPageOptions($options): array
    {
        if ($options === null) {
            return PagerDefaults::ITEMS['PER_PAGE_OPTIONS'];
        }

        if (!is_array($options)) {
            throw new InvalidArgumentException(PagerErrors::ITEMS['PER_PAGE_OPTIONS_INVALID']);
        }

        $sizes = [];

        foreach ($options as $option) {
            if (!is_int($option) && !(is_string($option) && ctype_digit($option))) {
                throw new InvalidArgumentException(PagerErrors::ITEMS['PER_PAGE_OPTIONS_INVALID']);
            }

            if ((int) $option < 1) {
                throw new InvalidArgumentException(PagerErrors::ITEMS['PER_PAGE_OPTIONS_INVALID']);
            }

            $sizes[] = (int) $option;
        }

        return array_values(array_unique($sizes));
    }

    /**
     * Returns a non-negative sibling count.
     *
     * @param mixed $siblings Raw siblings prop.
     * @return int
     */
    public static function siblings($siblings): int
    {
        return max(0, (int) ($siblings ?? PagerDefaults::ITEMS['SIBLINGS']));
    }

    /**
     * Casts a part flag to bool.
     *
     * @param mixed $value   Raw flag prop.
     * @param bool  $default Value when the prop is missing.
     * @return bool
     */
    public static function part($value, bool $default): bool
    {
        return $value === null ? $default : (bool) $value;
    }

    /**
     * Merges custom texts over the defaults.
     *
     * Unknown keys and non-scalar values are ignored.
     *
     * @param mixed $texts Raw texts prop.
     * @return array<string, string>
     */
    public static function texts($texts): array
    {
        $merged = PagerDefaults::TEXTS;

        if (!is_array($texts)) {
            return $merged;
        }

        foreach (array_intersect_key($texts, $merged) as $key => $text) {
            if (is_scalar($text)) {
                $merged[$key] = (string) $text;
            }
        }

        return $merged;
    }
}
