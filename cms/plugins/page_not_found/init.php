<?php defined('SYSPATH') or die('No direct access allowed.');

$plugin = Plugins_Item::factory( array(
	'id' => 'page_not_found',
	'title' => 'Page not found',
	'description' => 'Provides Page not found type.'
) )->register();