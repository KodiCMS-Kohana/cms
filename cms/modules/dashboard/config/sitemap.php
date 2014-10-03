<?php defined('SYSPATH') or die('No direct access allowed.');

return array(
	array(
		'name' => 'Dashboard',
		'icon' => 'dashboard',
		'url' => Route::get('backend')->uri(),
		'priority' => 0,
	)
);
