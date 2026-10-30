<?php

declare(strict_types=1);

namespace Juksgraphic\BladeComponents\Table;

/** Configuration and builder for the dynamic table component. */
final class Table
{
    /**
     * Summary of baseRoute
     * @var string
     */
    public string  $baseRoute = '';

    /**
     * Summary of endpoint
     * @var string|null
     */
    public ?string $endpoint = null;

    /**
     * Summary of headers
     * @var array
     */
    public array   $headers = [];

    /**
     * Summary of columns
     * @var array
     */
    public array $columns = [];

    /**
     * Summary of filters
     * @var array
     */
    public array  $filters = [];

    /**
     * Summary of withAction
     * @var array
     */
    public array $withAction = [];

    /**
     * Summary of selectable
     * @var bool
     */
    public bool  $selectable = false;

    /**
     * Summary of perPage
     * @var int
     */
    public int $perPage = 5;

    /**
     * Summary of tableId
     * @var string|null
     */
    public ?string $tableId = null;

    /**
     * Summary of labels
     * @var array
     */
    public array   $labels = [];

    /** Sets the base route, without surrounding whitespace nor trailing slash. */
    public function setBaseRoute(string $baseRoute): self
    {
        $this->baseRoute = rtrim(trim($baseRoute), '/');

        return $this;
    }

    /**
     * Summary of setEndpoint
     * @param string $endpoint
     * @return Table
     */
    public function setEndpoint(string $endpoint): self
    {
        $this->endpoint = rtrim(trim($endpoint), '/');

        return $this;
    }

    /** Sets headers or columns. A non-array value falls back to an empty list. */
    public function setHeaders(mixed $headers): self
    {
        $this->headers = is_array($headers) ? $headers : [];

        return $this;
    }

    /** Sets the columns alias. A non-array value falls back to an empty list. */
    public function setColumns(mixed $columns): self
    {
        $this->columns = is_array($columns) ? $columns : [];

        return $this;
    }

    /** Sets filters. A non-array value falls back to an empty list. */
    public function setFilters(mixed $filters): self
    {
        $this->filters = is_array($filters) ? $filters : [];

        return $this;
    }

    /** Sets row actions. A non-array value falls back to no actions. */
    public function setWithAction(mixed $withAction): self
    {
        $this->withAction = is_array($withAction) ? array_values($withAction) : [];

        return $this;
    }

    /** Enables or disables row selection. */
    public function setSelectable(bool $selectable = true): self
    {
        $this->selectable = $selectable;

        return $this;
    }

    /** Sets rows per page. */
    public function setPerPage(int $perPage): self
    {
        $this->perPage = max(1, $perPage);

        return $this;
    }

    /** Sets the table identifier. */
    public function setTableId(string $tableId): self
    {
        $this->tableId = trim($tableId);

        return $this;
    }

    /** Sets custom labels. A non-array value falls back to the defaults. */
    public function setLabels(mixed $labels): self
    {
        $this->labels = is_array($labels) ? $labels : [];

        return $this;
    }

    /**
     * Returns the resolved endpoint: the one defined by the caller, otherwise the base route as is.
     * No suffix is ever appended: the caller decides what the endpoint is.
     */
    public function getEndpoint(): string
    {
        return $this->endpoint ?? $this->baseRoute;
    }

    /** Returns the resolved table instance identifier. */
    public function id(): string
    {
        if ($this->tableId !== null && $this->tableId !== '') {
            return $this->tableId;
        }

        return 'jkc-table-' . hash('crc32b', $this->getEndpoint());
    }

    /** Returns the base route without trailing slash. */
    public function getBaseRoute(): string
    {
        return $this->baseRoute;
    }

    /** Returns whether row selection is enabled. */
    public function selectable(): bool
    {
        return $this->selectable;
    }

    /** Checks if a specific action is enabled. */
    public function hasAction(string $name): bool
    {
        return in_array($name, $this->withAction, true);
    }

    /** Returns the delete confirmation modal identifier. */
    public function deleteModalId(): string
    {
        return (string) preg_replace('/[^A-Za-z0-9_-]/', '', $this->id() . '-delete');
    }

    /** Returns the TableRow configuration handler. */
    public function row(): TableRow
    {
        return new TableRow($this->baseRoute, $this->withAction);
    }

    /** Returns normalized headers/columns array. */
    public function rawHeaders(): array
    {
        if (! empty($this->headers)) {
            return $this->headers;
        }

        return $this->columns;
    }

    /** Returns columns collection. */
    public function columns(): array
    {
        $columns = [];

        foreach ($this->rawHeaders() as $position => $definition) {
            $columns[] = TableColumn::from($definition, $position);
        }

        return $columns;
    }

    /** Returns the configuration array representation. */
    public function toArray(): array
    {
        $pagination = TablePagination::from($this->perPage);
        $filters = TableFilters::from($this->filters);
        $labels = TableLabels::from($this->labels);

        return [
            'endpoint' => $this->getEndpoint(),
            'headers' => $this->rawHeaders(),
            'filters' => $filters->toArray(),
            'withAction' => $this->withAction,
            'selectable' => $this->selectable,
            'perPage' => $pagination->perPage(),
            'perPageOptions' => $pagination->perPageOptions(),
            'labels' => $labels->toArray(),
        ];
    }

    /** Returns the configuration as a JSON string. */
    public function toJson(): string
    {
        return (string) json_encode(
            $this->toArray(),
            JSON_HEX_TAG |
                JSON_HEX_APOS |
                JSON_HEX_QUOT |
                JSON_HEX_AMP |
                JSON_UNESCAPED_UNICODE |
                JSON_INVALID_UTF8_SUBSTITUTE
        );
    }
}
