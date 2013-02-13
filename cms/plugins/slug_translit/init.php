<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

$plugin = Plugins_Item::factory( array(
	'id' => 'slug_translit',
	'title' => 'Slug translit',
	'author' => 'ButscH',
	'javascripts' => array(
		'slug_translit.js'
	)
) )
	->register();