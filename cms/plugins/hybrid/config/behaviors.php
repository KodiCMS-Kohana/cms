<?php defined('SYSPATH') or die('No direct access allowed.');

return array(
	'hybrid_docs' => array(
		'routes' => array(
			'/archive/<year>/<month>/<day>' => array(
				'regex' => array(
					'year' => '[0-9]{4}',
					'month' => '[0-9]{2}',
					'day' => '[0-9]{2}'
				),
				'method' => 'stub'
			),
			'/archive/<year>/<month>' => array(
				'regex' => array(
					'year' => '[0-9]{4}',
					'month' => '[0-9]{2}'
				),
				'method' => 'stub'
			),
			'/archive/<year>' => array(
				'regex' => array(
					'year' => '[0-9]{4}'
				),
				'method' => 'stub'
			),
			'/tag/<tag>' => array(
				'regex' => array(
					'tag' => '.*'
				),
				'method' => 'stub'
			)
		)
	),
);