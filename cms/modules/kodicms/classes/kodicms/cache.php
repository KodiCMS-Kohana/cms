<?php defined('SYSPATH') or die('No direct script access.');

abstract class KodiCMS_Cache extends Kohana_Cache {
	
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