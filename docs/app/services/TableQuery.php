<?php

namespace Docs\services;

use flight\net\Request;

/**
 * Immutable description of what the dynamic table asked for:
 * page, page size, search text, sort and active filters.
 *
 * Single responsibility: turn the raw query string into safe, typed values.
 */
final class TableQuery
{
    public const DEFAULT_PER_PAGE = 5;
    public const MAX_PER_PAGE = 100;
    public const SORT_ASCENDING = 'asc';
    public const SORT_DESCENDING = 'desc';

    /**
     * @param int                  $page          Requested page, starting at 1.
     * @param int                  $perPage       Rows per page, between 1 and MAX_PER_PAGE.
     * @param string               $search        Trimmed search text ('' when there is none).
     * @param string               $sortKey       Column key to sort by ('' when there is none).
     * @param string               $sortDirection Either SORT_ASCENDING or SORT_DESCENDING.
     * @param array<string,string> $filters       Active filters as [key => value], empty values removed.
     */
    public function __construct(
        public readonly int $page,
        public readonly int $perPage,
        public readonly string $search,
        public readonly string $sortKey,
        public readonly string $sortDirection,
        public readonly array $filters,
    ) {
    }

    /**
     * Builds the query from the query string of a Flight request.
     *
     * @param Request $request Flight request sent by the dynamic table component.
     */
    public static function fromRequest(Request $request): self
    {
        $queryParameters = $request->query->getData();

        return new self(
            page: self::parsePositiveInteger($queryParameters['page'] ?? null, 1),
            perPage: min(
                self::parsePositiveInteger($queryParameters['per_page'] ?? null, self::DEFAULT_PER_PAGE),
                self::MAX_PER_PAGE,
            ),
            search:  self::parseText($queryParameters['search'] ?? null),
            sortKey: self::parseText($queryParameters['sort'] ?? null),
            sortDirection: self::parseSortDirection($queryParameters['direction'] ?? null),
            filters: self::parseFilters($queryParameters['filters'] ?? null),
        );
    }

    /** Returns the value as an integer >= 1, or the default when it is missing or invalid. */
    private static function parsePositiveInteger(mixed $value, int $default): int
    {
        $integer = filter_var($value, FILTER_VALIDATE_INT);

        return ($integer !== false && $integer >= 1) ? $integer : $default;
    }

    /** Returns the trimmed text of a scalar value, or '' for anything else. */
    private static function parseText(mixed $value): string
    {
        return is_scalar($value) ? trim((string) $value) : '';
    }

    /** Returns a valid sort direction (ascending unless descending is explicitly requested). */
    private static function parseSortDirection(mixed $value): string
    {
        return strtolower(self::parseText($value)) === self::SORT_DESCENDING
            ? self::SORT_DESCENDING
            : self::SORT_ASCENDING;
    }

    /**
     * Keeps only the scalar filters that have a value.
     *
     * @return array<string,string>
     */
    private static function parseFilters(mixed $rawFilters): array
    {
        if (!is_array($rawFilters)) {
            return [];
        }

        $textFilters = array_map(
            fn (mixed $value): string => self::parseText($value),
            array_filter($rawFilters, 'is_scalar'),
        );

        return array_filter($textFilters, fn (string $value): bool => $value !== '');
    }
}