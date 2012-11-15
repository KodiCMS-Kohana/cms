<?php defined('SYSPATH') or die('No direct script access.');

Route::set('api', 'api/<controller>.<action>')
	->defaults(array(
		'directory' => 'api'
	));

if(IS_BACKEND)
{
	Observer::observe('view_setting_plugins', 'behavior_api_mode_settings_page');
	Observer::observe('save_settings', 'behavior_api_mode_settings_save');
}

function behavior_api_mode_settings_save( $post )
{
	if(!isset($post['setting']['api_enable']))
	{
		Setting::saveFromData(array(
			'api_enable' => 'no'
		));
	}
}

function behavior_api_mode_settings_page( )
{
	echo View::factory('api/settings');
}
