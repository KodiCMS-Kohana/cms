<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Set the default cache driver
 */
Cache::$default = defined('CACHE_TYPE') ? CACHE_TYPE : 'file';

//Observer::observe('modules::afer_load', function() {
//	$driver = defined('CACHE_TYPE') ? CACHE_TYPE : 'file';
//	
//	if( Config::get('cache', 'driver') === NULL ) 
//	{
//		Config::set('cache', 'driver', $driver);
//	}
//
//	Cache::$default = Config::get('cache', 'driver');
//});

if(IS_BACKEND)
{
	if( ACL::check('cache.settings'))
	{
		Observer::observe('view_setting_plugins', function() {
			echo View::factory('cache/settings');
		});

		Observer::observe('save_settings', function($post) {
			$cache_settings = Arr::path($post, 'setting.cache', array());

			foreach ($cache_settings as $key => $value)
			{
				$post['setting']['cache'][$key] = (int) $value;
			}
		});
	}
}