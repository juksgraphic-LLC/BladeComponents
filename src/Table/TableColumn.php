<?php

declare(strict_types=1);

namespace Juksgraphic\BladeComponents\Table;

use Juksgraphic\BladeComponents\BladeComponents;

/**
 * One column of the `jkc.tables.dynamic-table` view, as the row partial needs it
 * to pick the partial that renders its cells.
 *
 * Pure PHP: no view, no framework. The column definition itself is left
 * untouched (it still reaches the browser through the JSON config); this class
 * only decides which cell partial applies and how a cell reaches its column.
 */
final class TableColumn
{
    private const VIEW_PREFIX = 'jkc-internal.tables.cells.';

    private const FALLBACK_TYPE = 'text';

    /**
     * @param array<string, mixed> $definition
     */
    private function __construct(
        private readonly array $definition,
        private readonly int|string $position,
    ) {
    }

    /**
     * @param mixed $definition Entry of `headers` (a malformed, non-array entry gives an empty column).
     * @param int|string $position Key of that entry in `headers`.
     */
    public static function from(mixed $definition, int|string $position): self
    {
        return new self(is_array($definition) ? $definition : [], $position);
    }

    /**
     * The cell type: the `type` of the column when a partial exists for it, "text" otherwise.
     * A type is available as soon as `cells/<type>.blade.php` exists: adding a file adds a type.
     */
    public function type(): string
    {
        $type = $this->definition['type'] ?? null;

        return is_string($type) && self::hasCellPartial($type) ? $type : self::FALLBACK_TYPE;
    }

    /**
     * Name of the view that renders the cells of this column: the optional
     * `view` key of the column, or the partial of its type.
     */
    public function view(): string
    {
        $custom = $this->definition['view'] ?? null;

        if (is_string($custom) && trim($custom) !== '') {
            return trim($custom);
        }

        return self::VIEW_PREFIX . $this->type();
    }

    /**
     * Alpine expression that evaluates to this column's definition,
     * e.g. `headers[0]` (the position is the key of the column in `headers`).
     */
    public function ref(): string
    {
        return 'headers[' . json_encode($this->position) . ']';
    }

    private static function hasCellPartial(string $type): bool
    {
        if (preg_match('/^[a-z0-9_]+$/', $type) !== 1) {
            return false;
        }

        return is_file(BladeComponents::path() . '/jkc-internal/tables/cells/' . $type . '.blade.php');
    }
}
