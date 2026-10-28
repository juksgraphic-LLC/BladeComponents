<?php

namespace Docs\services;

use flight\net\Request;

/**
 * Builds the paginated JSON payload expected by the dynamic table component:
 * { data, total, current_page, last_page, per_page }.
 *
 * One public method per kind of table. Each one only declares which keys can be
 * searched, sorted and filtered; the shared pipeline (search, filter, sort, paginate)
 * is made of small private methods that each do a single thing.
 */
final class TableService
{
    public function __construct(
        private readonly FakeTableData $fakeTableData,
        private readonly DeletedRowsStore $deletedRowsStore,
    ) {
    }

    /**
     * Users table: image, link, badge and nested (dotted) columns.
     *
     * @param  Request $request Flight request carrying the table query string.
     * @return array<string, mixed>
     */
    public function getUsersTable(Request $request): array
    {
        return $this->buildPaginatedResponse(
            tableQuery: TableQuery::fromRequest($request),
            rows: $this->loadAvailableRows(FakeTableData::TABLE_USERS),
            searchableKeys: ['name', 'email', 'city', 'company.name'],
            sortableKeys: ['name', 'email', 'role', 'status', 'city', 'company.name', 'created_at'],
            filterableKeys: ['role', 'status'],
        );
    }

    /**
     * Products table: numeric columns and two filters.
     *
     * @param  Request $request Flight request carrying the table query string.
     * @return array<string, mixed>
     */
    public function getProductsTable(Request $request): array
    {
        return $this->buildPaginatedResponse(
            tableQuery: TableQuery::fromRequest($request),
            rows: $this->loadAvailableRows(FakeTableData::TABLE_PRODUCTS),
            searchableKeys: ['sku', 'name', 'category'],
            sortableKeys: ['sku', 'name', 'category', 'price', 'stock', 'rating', 'availability'],
            filterableKeys: ['category', 'availability'],
        );
    }

    /**
     * Orders table: the biggest dataset, useful to test pagination with ellipsis.
     *
     * @param  Request $request Flight request carrying the table query string.
     * @return array<string, mixed>
     */
    public function getOrdersTable(Request $request): array
    {
        return $this->buildPaginatedResponse(
            tableQuery: TableQuery::fromRequest($request),
            rows: $this->loadAvailableRows(FakeTableData::TABLE_ORDERS),
            searchableKeys: ['reference', 'customer'],
            sortableKeys: ['reference', 'customer', 'total', 'status', 'payment_method', 'ordered_at'],
            filterableKeys: ['status', 'payment_method'],
        );
    }

    /**
     * Table without any row, to demo the empty state.
     *
     * @param  Request $request Flight request carrying the table query string.
     * @return array<string, mixed>
     */
    public function getEmptyTable(Request $request): array
    {
        return $this->buildPaginatedResponse(
            tableQuery: TableQuery::fromRequest($request),
            rows: [],
            searchableKeys: [],
            sortableKeys: [],
            filterableKeys: [],
        );
    }

    /**
     * Fakes a deletion: the row is hidden for the rest of the session, nothing else is persisted.
     *
     * @param  string $tableName Name of the fake table (users, products, orders).
     * @param  int    $rowId     Id of the row to delete.
     * @return bool   False when the table or the row does not exist.
     */
    public function deleteRow(string $tableName, int $rowId): bool
    {
        if (!$this->rowExists($tableName, $rowId)) {
            return false;
        }

        $this->deletedRowsStore->markAsDeleted($tableName, $rowId);

        return true;
    }

    /**
     * Runs the shared pipeline: search, then filters, then sort, then pagination.
     *
     * @param  TableQuery                       $tableQuery     Parsed query string.
     * @param  array<int, array<string, mixed>> $rows           Rows to process.
     * @param  array<int, string>               $searchableKeys Keys matched by the search text.
     * @param  array<int, string>               $sortableKeys   Keys allowed for sorting.
     * @param  array<int, string>               $filterableKeys Keys allowed for filtering.
     * @return array<string, mixed>
     */
    private function buildPaginatedResponse(
        TableQuery $tableQuery,
        array $rows,
        array $searchableKeys,
        array $sortableKeys,
        array $filterableKeys,
    ): array {
        $searchedRows = $this->applySearch($rows, $tableQuery->search, $searchableKeys);
        $filteredRows = $this->applyFilters($searchedRows, $tableQuery->filters, $filterableKeys);
        $sortedRows = $this->applySort($filteredRows, $tableQuery->sortKey, $tableQuery->sortDirection, $sortableKeys);

        return $this->paginate($sortedRows, $tableQuery->page, $tableQuery->perPage);
    }

    /**
     * Returns the rows of a fake table that were not deleted.
     *
     * @return array<int, array<string, mixed>>
     */
    private function loadAvailableRows(string $tableName): array
    {
        $deletedIds = $this->deletedRowsStore->getDeletedIds($tableName);

        return array_values(array_filter(
            $this->fakeTableData->getRows($tableName),
            fn (array $row): bool => !in_array($row['id'], $deletedIds, true),
        ));
    }

    /** Tells whether a row exists (and was not deleted) in a fake table. */
    private function rowExists(string $tableName, int $rowId): bool
    {
        if (!$this->fakeTableData->hasTable($tableName)) {
            return false;
        }

        return in_array($rowId, array_column($this->loadAvailableRows($tableName), 'id'), true);
    }

    /**
     * Keeps the rows where at least one searchable key contains the search text.
     *
     * @param  array<int, array<string, mixed>> $rows
     * @param  array<int, string>               $searchableKeys
     * @return array<int, array<string, mixed>>
     */
    private function applySearch(array $rows, string $searchText, array $searchableKeys): array
    {
        if ($searchText === '') {
            return $rows;
        }

        return array_values(array_filter(
            $rows,
            fn (array $row): bool => $this->rowMatchesSearch($row, $searchText, $searchableKeys),
        ));
    }

    /**
     * Tells whether at least one searchable key of the row contains the search text (case-insensitive).
     *
     * @param array<string, mixed> $row
     * @param array<int, string>   $searchableKeys
     */
    private function rowMatchesSearch(array $row, string $searchText, array $searchableKeys): bool
    {
        foreach ($searchableKeys as $key) {
            if (mb_stripos((string) $this->extractValue($row, $key), $searchText) !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * Keeps the rows matching every active filter. Filters on unknown keys are ignored.
     *
     * @param  array<int, array<string, mixed>> $rows
     * @param  array<string, string>            $activeFilters
     * @param  array<int, string>               $filterableKeys
     * @return array<int, array<string, mixed>>
     */
    private function applyFilters(array $rows, array $activeFilters, array $filterableKeys): array
    {
        $allowedFilters = array_intersect_key($activeFilters, array_flip($filterableKeys));

        if ($allowedFilters === []) {
            return $rows;
        }

        return array_values(array_filter(
            $rows,
            fn (array $row): bool => $this->rowMatchesFilters($row, $allowedFilters),
        ));
    }

    /**
     * Tells whether the row matches every filter exactly.
     *
     * @param array<string, mixed>  $row
     * @param array<string, string> $filters
     */
    private function rowMatchesFilters(array $row, array $filters): bool
    {
        foreach ($filters as $key => $expectedValue) {
            if ((string) $this->extractValue($row, $key) !== $expectedValue) {
                return false;
            }
        }

        return true;
    }

    /**
     * Sorts the rows by a key. Unknown or non-sortable keys leave the order unchanged.
     *
     * @param  array<int, array<string, mixed>> $rows
     * @param  array<int, string>               $sortableKeys
     * @return array<int, array<string, mixed>>
     */
    private function applySort(array $rows, string $sortKey, string $sortDirection, array $sortableKeys): array
    {
        if (!in_array($sortKey, $sortableKeys, true)) {
            return $rows;
        }

        $directionMultiplier = $sortDirection === TableQuery::SORT_DESCENDING ? -1 : 1;

        usort(
            $rows,
            fn (array $leftRow, array $rightRow): int => $directionMultiplier * $this->compareValues(
                $this->extractValue($leftRow, $sortKey),
                $this->extractValue($rightRow, $sortKey),
            ),
        );

        return $rows;
    }

    /** Compares two values: numerically when both are numeric, naturally and case-insensitively otherwise. */
    private function compareValues(mixed $leftValue, mixed $rightValue): int
    {
        if (is_numeric($leftValue) && is_numeric($rightValue)) {
            return (float) $leftValue <=> (float) $rightValue;
        }

        return strnatcasecmp((string) $leftValue, (string) $rightValue);
    }

    /**
     * Returns the requested page and the pagination metadata. The page is clamped to the valid range.
     *
     * @param  array<int, array<string, mixed>> $rows
     * @return array<string, mixed>
     */
    private function paginate(array $rows, int $page, int $perPage): array
    {
        $total = count($rows);
        $lastPage = max(1, (int) ceil($total / $perPage));
        $currentPage = min($page, $lastPage);
        $offset = ($currentPage - 1) * $perPage;

        return [
            'data' => array_slice($rows, $offset, $perPage),
            'total' => $total,
            'current_page' => $currentPage,
            'last_page' => $lastPage,
            'per_page' => $perPage,
        ];
    }

    /**
     * Reads a nested value from a row using a dotted path (e.g. company.name). Returns '' when missing.
     *
     * @param array<string, mixed> $row
     */
    private function extractValue(array $row, string $path): mixed
    {
        return array_reduce(
            explode('.', $path),
            fn (mixed $current, string $key): mixed => is_array($current) ? ($current[$key] ?? '') : '',
            $row,
        );
    }
}