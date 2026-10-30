<?php

declare(strict_types=1);

namespace Juksgraphic\BladeComponents\Table;

/** Handles filter definitions and options normalization. */
final class TableFilters
{
    /**
     * Summary of __construct
     * @param array<int, array<string, mixed>> $filters
     */
    public function __construct(
        private readonly array $filters,
    ) {
    }

    /** Creates filters configuration from raw input. */

    /**
     * Summary of from
     * @param mixed $value
     * @return TableFilters
     */
    public static function from(mixed $value): self
    {
        if (! is_array($value)) {
            return new self([]);
        }

        $filters = [];
        foreach ($value as $filter) {
            if (is_array($filter)) {
                $filters[] = self::normalizeFilter($filter);
            }
        }

        return new self($filters);
    }

    /**
     * Returns the normalized filters array.
     * @return array[]
     */
    public function toArray(): array
    {
        return $this->filters;
    }

    /**
     * Checks if any filters are defined
     * @return bool
     */
    public function hasFilters(): bool
    {
        return ! empty($this->filters);
    }

    /**
     * Summary of normalizeFilter
     * @param array<string, mixed> $filter
     * @return array
     */
    private static function normalizeFilter(array $filter): array
    {
        $options = [];
        $given = is_array($filter['options'] ?? null) ? $filter['options'] : [];

        foreach ($given as $value => $label) {
            $options[] = is_array($label)
                ? $label
                : [
                    'value' => (string) $value,
                    'label' => (string) $label,
                ];
        }

        $filter['options'] = $options;

        return $filter;
    }
}
