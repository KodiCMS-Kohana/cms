<?php defined('SYSPATH') or die('No direct access allowed.');

return array(
	'native' => array(
		'driver' => 'mysql',
		'full_text_search' => TRUE
	),
	'sphinx' => array(
		'driver' => 'sphinx',
		'host' => '127.0.0.1',
		'port' => 9312
	),
);