<?php defined('SYSPATH') or die('No direct script access.');

/**
 * @package		KodiCMS/Api
 * @author		ButscHSter
 */
Route::set('api', 'api(/<directory>)-<controller>(.<action>)(/<id>)', array('directory' => '.*'))
	->filter(function($route, $params, $request) {
		if (strpos($params['directory'], 'Api') === FALSE)
		{
			$params['directory'] = 'Api/' . $params['directory'];
		}

		return $params;
	})
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
	if(!isset($post['setting']['api']['mode']))
	{
		Config::set('api', 'mode', Config::NO);
	}
}

function behavior_api_mode_settings_page( )
{
	echo View::factory('api/settings');
}