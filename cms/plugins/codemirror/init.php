<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

$plugin = Plugins_Item::factory( array(
	'id' => 'codemirror',
	'title' => 'Codemirror',
	'author' => 'ButscH',
	'description' => 'CodeMirror is a JavaScript component that provides a code editor in the browser. When a mode is available for the language you are coding in, it will color your code, and optionally help with indentation.',
	'javascripts' => array(
		'vendors/CodeMirror-3.01/lib/codemirror.js',
		'vendors/CodeMirror-3.01/mode/clike/clike.js',
		'vendors/CodeMirror-3.01/mode/xml/xml.js',
		'vendors/CodeMirror-3.01/mode/javascript/javascript.js',
		'vendors/CodeMirror-3.01/mode/css/css.js',
		'vendors/CodeMirror-3.01/mode/htmlmixed/htmlmixed.js',
		'vendors/CodeMirror-3.01/mode/php/php.js',
		'codemirror.js'
	),
	'css' => array(
		'vendors/CodeMirror-3.01/lib/codemirror.css',
		'codemirror.css',
	)
) )
	->register();

if( $plugin->enabled() )
{
	Filter::add('codemirror');
}