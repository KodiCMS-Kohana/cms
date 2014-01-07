<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

Plugin::factory('test', array(
	'title' => 'Тестовый сайт',
	'version' => '1.0.0',
	'description' => 'DO NOT INSTALL TO PRODUCTION SERVER',
))->register();