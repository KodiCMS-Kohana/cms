<?php defined('SYSPATH') or die('No direct access allowed.');

return array(
	'hybrid_docs' => array(
		'routes' => array(
			'/tag/<tag>' => array(
				'regex' => array(
					'tag' => '.*'
				),
				'method' => 'stub'
			)
		)
	),
);