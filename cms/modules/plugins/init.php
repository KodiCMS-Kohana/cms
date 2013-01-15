<?php defined('SYSPATH') or die('No direct access allowed.');

$plugins = CMSPATH . 'plugins';

// Make the plugins relative to the docroot, for symlink'd index.php
if ( ! is_dir($plugins) AND is_dir(DOCROOT.$plugins))
	$plugins = DOCROOT.$plugins;

define('PLUGPATH', realpath($plugins).DIRECTORY_SEPARATOR);

// Init plugins
Plugins::init();

Model_Navigation::get_section('System')
	->add_page(new Model_Navigation_Page(array(
		'name' => __('Plugins'), 
		'url' => URL::site('plugins'),
		'divider' => TRUE,
	)), 103);

Route::set( 'plugins', ADMIN_DIR_NAME.'/plugins(/<action>(/<id>))', array(
	'id' => '.*'
) )
	->defaults( array(
		'controller' => 'plugins',
		'action' => 'index',
	) );

//Вставка JS и Стилей в шаблон
Observer::observe( 'layout_backend_head',  'plugins_header_meta');

function plugins_header_meta()
{
	foreach ( Plugins::$javascripts as $javascript )
	{
		echo HTML::script( $javascript ) . "\n";
	}
	foreach ( Plugins::$styles as $stylesheet )
	{
		echo HTML::style( $stylesheet ) . "\n";
	}
}