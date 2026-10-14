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
use Flight\Engine;
use flight\net\Router;

/**
 * @var Engine $app
 * @var Router $router
 */

$router->get("/" , [HomeController::class , "index"]);
$router->get("/components/@component:[a-z0-9\-]+" , [HomeController::class , "components"]);