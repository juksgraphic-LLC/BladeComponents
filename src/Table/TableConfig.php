<?php

declare(strict_types=1);

namespace Juksgraphic\BladeComponents\Table;

/**
 * Normalizes the variables of the `jkc.tables.dynamic-table` view into the
 * configuration read by the Alpine components.
 *
 * Pure PHP: no view, no framework. The view only gathers its variables and
 * delegates here, so every rule below can be unit tested.
 */
final class TableConfig
{
    private const DEFAULT_PER_PAGE = 5;

    private const BASE_PER_PAGE_OPTIONS = [5, 10, 25, 50, 100];

    private const JSON_FLAGS = JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP
        | JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE;

    /**
     * @param array{
     *     endpoint: string,
     *     headers: array<int|string, mixed>,
     *     filters: array<int, array<string, mixed>>,
     *     withAction: array<int, mixed>,
     *     selectable: bool,
     *     perPage: int,
     *     perPageOptions: array<int, int>
     * } $config
     */
    private function __construct(
        private readonly array $config,
        private readonly string $id,
        private readonly string $baseRoute,
    ) {
    }

    /**
     * Builds the configuration from the raw view variables.
     *
     * Recognized keys: baseRoute, endpoint, headers (alias: columns), filters,
     * withAction, selectable, perPage, tableId. Anything missing or null falls
     * back to its default.
     *
     * @param array<string, mixed> $input
     */
    public static function fromInput(array $input): self
    {
        $baseRoute = self::baseRoute($input['baseRoute'] ?? null);
        $endpoint = self::endpoint($input['endpoint'] ?? null, $baseRoute);
        $perPage = self::perPage($input['perPage'] ?? null);

        return new self(
            [
                'endpoint' => $endpoint,
                'headers' => self::headers($input['headers'] ?? null, $input['columns'] ?? null),
                'filters' => self::filters($input['filters'] ?? null),
                'withAction' => self::withAction($input['withAction'] ?? null),
                'selectable' => (bool) ($input['selectable'] ?? false),
                'perPage' => $perPage,
                'perPageOptions' => self::perPageOptions($perPage),
            ],
            self::resolveId($input['tableId'] ?? null, $endpoint),
            $baseRoute,
        );
    }

    /**
     * The configuration consumed by the Alpine components.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return $this->config;
    }

    /**
     * The configuration as JSON, safe to place inside an HTML attribute.
     */
    public function toJson(): string
    {
        return (string) json_encode($this->config, self::JSON_FLAGS);
    }

    /**
     * Identifier of this table instance (given, or derived from the endpoint).
     */
    public function id(): string
    {
        return $this->id;
    }

    /**
     * Base URL of the resource, without trailing slash ("" when not given).
     * Row links are built from it; it travels in its own attribute, not in the JSON config.
     */
    public function getBaseRoute(): string
    {
        return $this->baseRoute;
    }

    /**
     * One column per entry of `headers`, in the same order and under the same
     * position as in the JSON config (cells reference it as `headers[position]`).
     *
     * @return array<int, TableColumn>
     */
    public function columns(): array
    {
        $columns = [];

        foreach ($this->config['headers'] as $position => $definition) {
            $columns[] = TableColumn::from($definition, $position);
        }

        return $columns;
    }

    private static function baseRoute(mixed $value): string
    {
        return rtrim((string) ($value ?? ''), '/');
    }

    private static function endpoint(mixed $value, string $baseRoute): string
    {
        return (string) ($value ?? $baseRoute . '/table');
    }

    /**
     * @return array<int|string, mixed>
     */
    private static function headers(mixed $headers, mixed $columns): array
    {
        if (is_array($headers)) {
            return $headers;
        }

        return is_array($columns) ? $columns : [];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private static function filters(mixed $value): array
    {
        if (! is_array($value)) {
            return [];
        }

        $filters = [];

        foreach ($value as $filter) {
            if (is_array($filter)) {
                $filters[] = self::filter($filter);
            }
        }

        return $filters;
    }

    /**
     * Options can be a [value => label] map or a list of ['value', 'label'].
     *
     * @param array<string, mixed> $filter
     * @return array<string, mixed>
     */
    private static function filter(array $filter): array
    {
        $options = [];
        $given = is_array($filter['options'] ?? null) ? $filter['options'] : [];

        foreach ($given as $value => $label) {
            $options[] = is_array($label)
                ? $label
                : ['value' => (string) $value, 'label' => (string) $label];
        }

        $filter['options'] = $options;

        return $filter;
    }

    /**
     * @return array<int, mixed>
     */
    private static function withAction(mixed $value): array
    {
        return is_array($value) ? array_values($value) : [];
    }

    private static function perPage(mixed $value): int
    {
        return max(1, (int) ($value ?? self::DEFAULT_PER_PAGE));
    }

    /**
     * @return array<int, int>
     */
    private static function perPageOptions(int $perPage): array
    {
        $options = array_values(array_unique(array_merge(self::BASE_PER_PAGE_OPTIONS, [$perPage])));
        sort($options);

        return $options;
    }

    private static function resolveId(mixed $value, string $endpoint): string
    {
        $given = trim((string) ($value ?? ''));

        return $given !== '' ? $given : 'jkc-table-' . hash('crc32b', $endpoint);
    }
}
