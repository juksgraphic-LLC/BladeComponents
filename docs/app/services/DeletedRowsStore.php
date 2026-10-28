<?php

namespace Docs\services;

/**
 * Remembers which fake rows were "deleted" during the demo, in the PHP session,
 * so a deleted row stays hidden when the table reloads.
 *
 * Single responsibility: store and read deleted row ids per table.
 */
final class DeletedRowsStore
{
    private const SESSION_KEY = 'docs_tables_deleted_rows';

    /**
     * Marks a row as deleted.
     *
     * @param string $tableName Name of the fake table (users, products, orders).
     * @param int    $rowId     Id of the deleted row.
     */
    public function markAsDeleted(string $tableName, int $rowId): void
    {
        $this->ensureSessionStarted();
        $_SESSION[self::SESSION_KEY][$tableName][$rowId] = true;
    }

    /**
     * Returns the ids of the rows deleted in a table.
     *
     * @param  string $tableName Name of the fake table.
     * @return array<int, int>
     */
    public function getDeletedIds(string $tableName): array
    {
        $this->ensureSessionStarted();

        return array_keys($_SESSION[self::SESSION_KEY][$tableName] ?? []);
    }

    /** Starts the PHP session when it is not already active. */
    private function ensureSessionStarted(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
}
