<?php defined('SYSPATH') or die('No direct script access.');

$api_key = ORM::factory('api_key')->generate('KodiCMS API key');

Kohana::$config->load('installer')->set('default_config', array(
	'api' => array(
		'key' => $api_key
	)
));