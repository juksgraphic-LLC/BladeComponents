<?php
/*
|--------------------------------------------------------------------------
| Project root
|--------------------------------------------------------------------------
|
| On the server, the code lives in ../app (outside public_html).
| Locally, it is the repository root (docs/public -> ../..).
|
*/
$root = is_file(dirname(__DIR__) . '/app/vendor/autoload.php')
    ? dirname(__DIR__) . '/app'
    : dirname(__DIR__, 2);

/*
|--------------------------------------------------------------------------
| Composer Autoload
|--------------------------------------------------------------------------
*/
require $root . '/vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Bootstrap
|--------------------------------------------------------------------------
|
| Defines PROJECT_ROOT, creates the Flight instance and loads the
| config, services and routes files.
|
*/
require $root . '/docs/app/bootstrap.php';