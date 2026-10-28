<?php

namespace Docs\controllers;

use Docs\services\DeletedRowsStore;
use Docs\services\FakeTableData;
use Docs\services\TableService;
use flight\Engine;

class TableController
{
    /**
     * Name of the Blade view of the demo page (adapt it to your view folder).
     */
    protected const DEMO_VIEW = 'tables.dynamic-table-demo';

    /**
     * Flight application engine.
     * @var Engine
     */
    protected Engine $app;

    /**
     * Builds the JSON payloads of the demo tables.
     * @var TableService
     */
    protected TableService $tableService;

    /**
     * @param Engine $app Flight application engine.
     */
    public function __construct(Engine $app)
    {
        $this->app = $app;
        $this->tableService = new TableService(
            new FakeTableData(),
            new DeletedRowsStore()
        );
    }

    /**
     * Renders the demo page of the dynamic table component.
     */
    public function index(): void
    {
        $this->app->render(self::DEMO_VIEW);
    }

    /**
     * Returns the paginated users as JSON.
     */
    public function usersTable(): void
    {
        $this->app->json($this->tableService->getUsersTable($this->app->request()));
    }

    /**
     * Returns the paginated products as JSON.
     */
    public function productsTable(): void
    {
        $this->app->json($this->tableService->getProductsTable($this->app->request()));
    }

    /**
     * Returns the paginated orders as JSON.
     */
    public function ordersTable(): void
    {
        $this->app->json($this->tableService->getOrdersTable($this->app->request()));
    }

    /**
     * Returns an empty paginated table as JSON (empty state demo).
     */
    public function emptyTable(): void
    {
        $this->app->json($this->tableService->getEmptyTable($this->app->request()));
    }

    /**
     * Simulates a server failure (error banner demo).
     */
    public function failingTable(): void
    {
        $this->app->json(['message' => 'Simulated server error.'], 500);
    }

    /**
     * Fakes the deletion of a row: 200 when it existed, 404 otherwise.
     *
     * @param string $table Name of the fake table (users, products, orders).
     * @param string $id    Id of the row to delete.
     */
    public function destroy(string $table, string $id): void
    {
        $isDeleted = $this->tableService->deleteRow($table, (int) $id);

        $this->app->json(['deleted' => $isDeleted], $isDeleted ? 200 : 404);
    }
}
