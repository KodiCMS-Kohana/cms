<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

$plugin = Model_Plugin_Item::factory( array(
	'id' => 'redactor',
	'title' => 'Redactor',
	'author' => 'ButscH',
	'description' => 'Create word-processed text on the web using a reliable, fast and unbelievably beautiful editor.',
	'javascripts' => array(
		'vendors/redactor/ru.js',
		'vendors/redactor/redactor.min.js',
		'redactor.js'
	),
	'css' => 'vendors/redactor/redactor.css'
) )
	->register();

if( $plugin->enabled() )
{
	Filter::add('redactor');
}