<?php

/*
|--------------------------------------------------------------------------
| Application Configuration
|--------------------------------------------------------------------------
|
| This file is the central configuration for the documentation site.
| By the time this file is loaded, PROJECT_ROOT is already defined
| and $app (the Flight Engine) has already been created by the bootstrap.
| Do not define PROJECT_ROOT or create a new Engine here.
|
*/

use eftec\bladeone\BladeOne;

/** @var \flight\Engine $app  Created by app/bootstrap.php before this file is loaded. */

/*
|--------------------------------------------------------------------------
| Application Timezone
|--------------------------------------------------------------------------
|
| Default timezone used by the PHP date and date-time functions.
| It is set to UTC to avoid environment-specific discrepancies.
|
*/

date_default_timezone_set('UTC');

/*
|--------------------------------------------------------------------------
| Error Reporting
|--------------------------------------------------------------------------
|
| E_ALL surfaces every warning and notice while developing the components.
| Consider narrowing this in production.
|
*/

/*
|--------------------------------------------------------------------------
| Default Character Encoding
|--------------------------------------------------------------------------
|
| Force UTF-8 for all multibyte string operations.
|
*/
if (function_exists('mb_internal_encoding')) {
	mb_internal_encoding('UTF-8');
}


/*
|--------------------------------------------------------------------------
| Flight Core Settings
|--------------------------------------------------------------------------
|
| Configure the core behaviour of the Flight framework engine.
| These values control routing, view rendering, error handling,
| and response headers for every incoming HTTP request.
|
*/

// Base URL for the application. Change this if the app lives in a subdirectory (e.g. '/myapp/').
$app->set('flight.base_url', PROJECT_ROOT . "/docs/public");

// When true, routes are matched with case sensitivity. Keep false for friendlier URLs.
$app->set('flight.case_sensitive', false);

// Log PHP errors to the application error file defined by flight.log_errors_file.
$app->set('flight.log_errors', true);

// Set to false so Tracy (or your custom handler) manages error rendering instead of Flight.
$app->set('flight.handle_errors', false);

// Absolute path to the directory that contains all Blade / PHP view templates.
$app->set('flight.views.path', PROJECT_ROOT . '/docs/app/views');

// File extension used when resolving view template files.
$app->set('flight.views.extension', '.php');

// Do not send a Content-Length header automatically. Set to true only when required by a proxy.
$app->set('flight.content_length', false);

/*
|--------------------------------------------------------------------------
| Content Security Policy (CSP) Nonce
|--------------------------------------------------------------------------
|
| A fresh cryptographic nonce is generated on every request. Embed this value
| in your <script> and <style> tags via $app->get('csp_nonce') and reference
| it in your Content-Security-Policy header so only trusted inline assets execute.
|
*/
$app->set('csp_nonce', bin2hex(random_bytes(16)));

/*
|--------------------------------------------------------------------------
| Main Configuration Array
|--------------------------------------------------------------------------
|
| Everything the documentation site needs is centralised here.
| This array is both returned (so callers can do $config = require 'config.php')
| and stored on the Flight instance for use anywhere via $app->get('config').
|
*/
$config = [

	/*
	|--------------------------------------------------------------------------
	| Application
	|--------------------------------------------------------------------------
	|
	| General information about the documentation site.
	|
	*/
	'app' => [
		'name'  => 'Blade Components',           // Site name shown in the sidebar and the <title>.
		'debug' => true,                         // Show detailed errors. Set to false in production.
	],

	/*
	|--------------------------------------------------------------------------
	| Paths
	|--------------------------------------------------------------------------
	|
	| Absolute paths used by the controllers and the view engine.
	|
	*/
	'paths' => [
		'root'  => PROJECT_ROOT,                        // Repository root (where vendor/ lives).
		'views' => PROJECT_ROOT . '/docs/views',        // Documentation pages (layout, partials, components).
		'cache' => PROJECT_ROOT . '/docs/cache',        // BladeOne compiled templates. Must be writable.
	],

	/*
	|--------------------------------------------------------------------------
	| Blade
	|--------------------------------------------------------------------------
	|
	| BladeOne rendering mode. MODE_AUTO recompiles a template when it changes.
	| Use MODE_SLOW while debugging and MODE_FAST in production.
	|
	*/
	'blade' => [
		'mode' => BladeOne::MODE_AUTO,
	],

];

$app->set('config', $config);

return $config;