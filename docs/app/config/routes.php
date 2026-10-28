<?php

/*
|--------------------------------------------------------------------------
| Routes
|--------------------------------------------------------------------------
|
| All the routes of the documentation site. Controllers receive the
| Flight Engine in their constructor.
|
*/

use Docs\controllers\HomeController;
use Docs\controllers\TableController;
use Flight\Engine;
use flight\net\Router;

/**
 * @var Engine $app
 * @var Router $router
 */

$router->get("/", [HomeController::class, "index"]);
$router->get("/components/@component:[a-z0-9\-]+", [HomeController::class, "components"]);

$router->group("/tables", function () use ($router) {
    $router->get("/users/table",    [TableController::class, "usersTable"]);
    $router->get("/products/table", [TableController::class, "productsTable"]);
    $router->get("/orders/table",   [TableController::class, "ordersTable"]);
    $router->get("/empty/table",    [TableController::class, "emptyTable"]);
    $router->get("/failing/table",    [TableController::class, "failingTable"]);
});
