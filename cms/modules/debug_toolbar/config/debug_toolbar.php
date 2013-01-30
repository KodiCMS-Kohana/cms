<?php defined('SYSPATH') or die('No direct script access.');

/*
 * If true, the debug toolbar will be automagically displayed
 * NOTE: if IN_PRODUCTION is set to TRUE, the toolbar will
 * not automatically render, even if auto_render is TRUE
 */
$config['auto_render'] = (Kohana::$environment > Kohana::PRODUCTION AND ! IS_BACKEND AND Auth::instance()->logged_in());

/*
 * If true, the toolbar will default to the minimized position
 */
$config['minimized'] = FALSE;

/*
 * Log toolbar data to FirePHP
 */
$config['firephp_enabled'] = TRUE;

/*
 * Enable or disable specific panels
 */
$config['panels'] = array(
	'benchmarks'		=> TRUE,
	'database'			=> TRUE,
	'vars'				=> TRUE,
	'ajax'				=> TRUE,
	'files'				=> TRUE,
	'modules'			=> TRUE,
	'routes'			=> TRUE,
	'customs'           => TRUE,
);

/*
 * Toolbar alignment
 * options: right, left, center
 */
$config['align'] = 'right';

/*
 * Secret Key
 */
$config['secret_key'] = FALSE;

return $config;