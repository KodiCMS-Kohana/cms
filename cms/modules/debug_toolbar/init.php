<?php defined('SYSPATH') or die('No direct script access.');

// Load FirePHP if it enabled in config
if (Kohana::$config->load('debug_toolbar.firephp_enabled') === TRUE)
{
	$file    = 'firephp/lib/FirePHPCore/FirePHP.class';
	$firePHP = Kohana::find_file('vendor', $file);

	if ( ! $firePHP) 
	{
		throw new Kohana_Exception('The FirePHP :file could not be found', array(':file' => $file));
	}

	require_once $firePHP;
}
// Render Debug Toolbar on the end of application execution
if (Kohana::$config->load('debug_toolbar.auto_render') === TRUE)
{
	register_shutdown_function('Debugtoolbar::render');
}