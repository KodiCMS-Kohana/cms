<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

$plugin = Model_Plugin_Item::factory( array(
	'id' => 'tagsinput',
	'title' => 'jQuery Tags Input',
	'author' => 'ButscH',
	'description' => 'Do you use tags to organize content on your site? This plugin will turn your boring tag list into a magical input that turns each tag into a style-able object with its own delete link. The plugin handles all the data - your form just sees a comma-delimited list of tags!',
	'css' => 'vendors/jquery-tags-input/jquery.tagsinput.css',
	'javascripts' => array(
		'vendors/jquery-tags-input/jquery.tagsinput.min.js',
		'tagsinput.js'
	)
) )
	->register();

if( $plugin->enabled() )
{
	if(IS_BACKEND)
	{
		Filter::add('redactor');
	}
	else
	{
		
	}
}