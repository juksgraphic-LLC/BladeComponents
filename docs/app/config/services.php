<?php

/*
|--------------------------------------------------------------------------
| Services
|--------------------------------------------------------------------------
|
| Objects shared by the whole application. By the time this file is loaded,
| $app and the configuration (config.php) are already available.
|
*/

use eftec\bladeone\BladeOne;
use Juksgraphic\BladeComponents\BladeComponents;

/**
 *  @var \flight\Engine $app 
 *  Created by app/bootstrap.php before this file is loaded.
 */

$config = $app->get('config');

/*
|--------------------------------------------------------------------------
| View Engine (BladeOne)
|--------------------------------------------------------------------------
|
| Shared instance available as $app->blade(). The package components are
| registered first, then the documentation pages.
|
*/
$app->register('view', BladeOne::class, [
	[BladeComponents::path(), $config['paths']['views']],
	$config['paths']['cache'],
	BladeOne::MODE_DEBUG,
]);

$app->map(
	'render',
	function (string $template, array $data = []) use ($app): void {
		/**
		 * @var BladeOne $blade
		 */
		$blade = $app->view();
		echo $blade->run($template, $data);
	}
);

/**
 * @var BladeOne $view
 */
$view = $app->view();
$view->share("app" , $app);

