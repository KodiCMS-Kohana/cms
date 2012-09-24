<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

$plugin = Model_Plugin_Item::factory( array(
	'id' => 'codemirror',
	'title' => 'Codemirror',
	'author' => 'ButscH',
	'description' => 'CodeMirror is a JavaScript component that provides a code editor in the browser. When a mode is available for the language you are coding in, it will color your code, and optionally help with indentation.',
	'javascripts' => array(
		'vendors/CodeMirror-2.33/lib/codemirror.js',
		'vendors/CodeMirror-2.33/mode/clike/clike.js',
		'vendors/CodeMirror-2.33/mode/xml/xml.js',
		'vendors/CodeMirror-2.33/mode/javascript/javascript.js',
		'vendors/CodeMirror-2.33/mode/css/css.js',
		'vendors/CodeMirror-2.33/mode/htmlmixed/htmlmixed.js',
		'vendors/CodeMirror-2.33/mode/php/php.js',
		'codemirror.js'
	),
	'css' => array(
		'vendors/CodeMirror-2.33/lib/codemirror.css',
		'codemirror.css',
	)
) )
	->register();

if( $plugin->enabled() )
{
	Filter::add('codemirror');
}