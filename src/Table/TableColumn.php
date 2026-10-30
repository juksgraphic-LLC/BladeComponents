<?php

declare(strict_types=1);

namespace Juksgraphic\BladeComponents\Table;

use Juksgraphic\BladeComponents\BladeComponents;

/**
 * Represents a table column definition and delegates cell behavior to TableCell.
 */
final class TableColumn
{
    private const VIEW_PREFIX = 'jkc-internal.tables.cells.';

    private const FALLBACK_TYPE = 'text';

    private readonly TableCell $cell;

    /**
     * @param array<string, mixed> $definition
     */
    private function __construct(
        private readonly array $definition,
        private readonly int|string $position,
    ) {
        $this->cell = new TableCell($this->definition);
    }

    /**
     * Creates a column instance from raw header input definition.
     */
    public static function from(mixed $definition, int|string $position): self
    {
        return new self(is_array($definition) ? $definition : [], $position);
    }

    /**
     * Returns the resolved cell type or text fallback.
     */
    public function type(): string
    {
        $type = $this->definition['type'] ?? null;

        return is_string($type) && self::hasCellPartial($type) ? $type : self::FALLBACK_TYPE;
    }

    /**
     * Returns the Blade view partial path responsible for rendering this column's cells.
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
     * Returns the cell configuration helper instance.
     */
    public function cell(): TableCell
    {
        return $this->cell;
    }

    /**
     * Checks if a custom cell partial template exists on disk.
     */
    private static function hasCellPartial(string $type): bool
    {
        if (preg_match('/^[a-z0-9_]+$/', $type) !== 1) {
            return false;
        }

        return is_file(BladeComponents::path() . '/jkc-internal/tables/cells/' . $type . '.blade.php');
    }
}