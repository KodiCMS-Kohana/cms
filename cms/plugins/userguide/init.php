<?php defined('SYSPATH') or die('No direct script access.');

Plugin::factory('userguide', array(
	'title' => __('User Guide'),
	'version' => '1.0.0',
))->register();

// Static file serving (CSS, JS, images)
Route::set('docs/media', ADMIN_DIR_NAME.'/guide/media(/<file>)', array('file' => '.+'))
	->defaults(array(
		'controller' => 'userguide',
		'action'     => 'media',
		'file'       => NULL,
	));

// API Browser, if enabled
if (Kohana::$config->load('userguide.api_browser') === TRUE)
{
	Route::set('docs/api', ADMIN_DIR_NAME.'/guide/api(/<class>)', array('class' => '[a-zA-Z0-9_]+'))
		->defaults(array(
			'controller' => 'userguide',
			'action'     => 'api',
			'class'      => NULL,
		));
}

// User guide pages, in modules
Route::set('docs/guide', ADMIN_DIR_NAME.'/guide/doc(/<module>(/<page>))', array(
		'page' => '.+',
	))
	->defaults(array(
		'controller' => 'userguide',
		'action'     => 'docs',
		'module'     => '',
	));

// Simple autoloader used to encourage PHPUnit to behave itself.
class Markdown_Autoloader {
	public static function autoload($class)
	{
		if ($class == 'Markdown_Parser' OR $class == 'MarkdownExtra_Parser')
		{
			include_once Kohana::find_file('vendor', 'markdown/markdown');
		}
	}
}

// Register the autoloader
spl_autoload_register(array('Markdown_Autoloader', 'autoload'));