<?php

declare(strict_types=1);

namespace Juksgraphic\BladeComponents\Table;

/** Handles pagination settings and options for the table. */
final class TablePagination
{
    private const DEFAULT_PER_PAGE = 5;

    private const BASE_PER_PAGE_OPTIONS = [5, 10, 25, 50, 100];
    

    /** @param array<int, int> $perPageOptions */
    public function __construct(
        private readonly int $perPage,
        private readonly array $perPageOptions,
    ) {}

 
    /**
     * Summary of from
     * @param mixed $perPageValue
     * @return TablePagination
     */
    public static function from(mixed $perPageValue): self
    {
        $perPage = self::resolvePerPage($perPageValue);
        return new self($perPage, self::resolvePerPageOptions($perPage));
    }


    /**
     * Returns items count per page.
     * @return int
     */
    public function perPage(): int
    {
        return $this->perPage;
    }



    /**
     * Returns available per page options.
     * @return int[]
     */
    public function perPageOptions(): array
    {
        return $this->perPageOptions;
    }

    /**
     * Summary of resolvePerPage
     * @param mixed $value
     * @return int
     */
    private static function resolvePerPage(mixed $value): int
    {
        return max(1, (int) ($value ?? self::DEFAULT_PER_PAGE));
    }



    /**
     * Summary of resolvePerPageOptions
     * @param int $perPage
     * @return array
     */
    private static function resolvePerPageOptions(int $perPage): array
    {
        $options = array_values(array_unique(array_merge(self::BASE_PER_PAGE_OPTIONS, [$perPage])));
        sort($options);

        return $options;
    }
}