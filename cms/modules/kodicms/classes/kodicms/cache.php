<?php defined('SYSPATH') or die('No direct script access.');

/**
 * @package		KodiCMS
 * @category	Helper
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
abstract class KodiCMS_Cache extends Kohana_Cache {
	
	public static function drivers()
	{
		return array(
			'file' => __('File cache'), 
			'apc' => __('APC cache'), 
			'sqlite' => __('SQLite cache'), 
			'memcachetag' => __('Memcache')
		);
	}
	
	public static function register_shutdown_function()
	{
		// Enable the Kohana shutdown handler, which clear cache
		register_shutdown_function(array('Cache', 'clear_all'));
	}

	public static function clear_all()
	{
		Cache::instance()->delete_all();
		
		Cache::clear_file();
		Cache::clear_routes();
		Cache::clear_profiler();
	}
	
	public static function clear_routes()
	{
		Kohana::cache('Route::cache()', NULL, -1);
	}
	
	public static function clear_profiler()
	{
		Kohana::cache('profiler_application_stats', NULL, -1);
	}
	
	public static function clear_file()
	{
		Kohana::cache('Kohana::find_file()', NULL, -1);
	}
}