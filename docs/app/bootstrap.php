<?php

/*
|--------------------------------------------------------------------------
| Project Root
|--------------------------------------------------------------------------
|
| Repository root (the folder that contains vendor/, docs/, components/).
|
*/
define('PROJECT_ROOT', dirname(__DIR__, 2));

/*
|--------------------------------------------------------------------------
| Flight Application Instance
|--------------------------------------------------------------------------
|
| Shared instance. It is available as $app in the files loaded below
| and anywhere else through Flight::app().
|
*/
if (empty($app)) {
    $app = Flight::app();
}

/*
|--------------------------------------------------------------------------
| Application Files
|--------------------------------------------------------------------------
|
| Loaded in this order: configuration first, then the services that use it,
| then the routes that use the services.
|
*/
require __DIR__ . '/config/config.php';
require __DIR__ . '/config/services.php';

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Hand the router instance to the routes file, which maps every URL
| pattern to its corresponding controller action or closure handler.
| The $router variable is available inside routes.php via the require scope.
|
*/
$router = $app->router();
require __DIR__ . '/config/routes.php';


$app->start();
