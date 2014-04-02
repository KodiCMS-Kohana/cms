<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * @package		KodiCMS
 * @category	Helper
 * @author		ButscHSter
 */
class KodiCMS_Fragment extends Kohana_Fragment {
	
	/**
	 * 
	 * @param string $name
	 * @param string $i18n
	 * @return string
	 */
	public static function is_cached($name, $i18n = NULL)
	{
		return Cache::instance()->get(self::_cache_key($name, $i18n)) !== NULL;
	}
	
	/**
	 * 
	 * @param   string  $name       fragment name
	 * @param   integer $lifetime   fragment cache lifetime
	 * @param   boolean $i18n       multilingual fragment support
	 * @return  string
	 */
	public static function get($name, $lifetime = NULL, $i18n = NULL)
	{
		// Set the cache lifetime
		$lifetime = ($lifetime === NULL) ? Fragment::$lifetime : (int) $lifetime;
		
		// Get the cache key name
		$cache_key = Fragment::_cache_key($name, $i18n);
		
		// If cache lifetime < 0 then clear
		if( $lifetime < 0 ) Fragment::delete( $name, $i18n );
		
		return Cache::instance()->get($cache_key);
	}

	/**
	 * Load a fragment from cache and display it. Multiple fragments can
	 * be nested with different life times.
	 *
	 *     if ( ! Fragment::load('footer')) {
	 *         // Anything that is echo'ed here will be saved
	 *         Fragment::save();
	 *     }
	 *
	 * @param   string  $name       fragment name
	 * @param   integer $lifetime   fragment cache lifetime
	 * @param   boolean $i18n       multilingual fragment support
	 * @return  boolean
	 */
	public static function load($name, $lifetime = NULL, $i18n = NULL)
	{
		if ($fragment = Fragment::get($name, $lifetime, $i18n))
		{
			// Display the cached fragment now
			echo $fragment;

			return TRUE;
		}
		else
		{
			// Start the output buffer
			ob_start();

			// Store the cache key by the buffer level
			Fragment::$_caches[ob_get_level()] = $cache_key;

			return FALSE;
		}
	}
	
	/**
	 * Saves the currently open fragment in the cache.
	 *
	 *     Fragment::save();
	 * 
	 * @param   array  $tags       cache tags
	 * @return  void
	 */
	public static function save_with_tags($lifetime = NULL, $tags = array())
	{
		// Get the buffer level
		$level = ob_get_level();

		if (isset(Fragment::$_caches[$level]))
		{
			// Get the cache key based on the level
			$cache_key = Fragment::$_caches[$level];

			// Delete the cache key, we don't need it anymore
			unset(Fragment::$_caches[$level]);

			// Get the output buffer and display it at the same time
			$fragment = ob_get_flush();
			
			// Set the cache lifetime
			$lifetime = ($lifetime === NULL) ? Fragment::$lifetime : (int) $lifetime;

			// Cache the fragment
			Cache::instance()
				->set_with_tags($cache_key, $fragment, $lifetime, $tags);
		}
	}
	
	/**
	 * Saves the currently open fragment in the cache.
	 *
	 *     Fragment::save();
	 *
	 * @return  void
	 */
	public static function save()
	{
		// Get the buffer level
		$level = ob_get_level();

		if (isset(Fragment::$_caches[$level]))
		{
			// Get the cache key based on the level
			$cache_key = Fragment::$_caches[$level];

			// Delete the cache key, we don't need it anymore
			unset(Fragment::$_caches[$level]);

			// Get the output buffer and display it at the same time
			$fragment = ob_get_flush();

			// Cache the fragment
			Cache::instance()->set($cache_key, $fragment);
		}
	}
	
	/**
	 * Delete a cached fragment.
	 *
	 *     Fragment::delete($key);
	 *
	 * @param   string  $name   fragment name
	 * @param   boolean $i18n   multilingual fragment support
	 * @return  void
	 */
	public static function delete($name, $i18n = NULL)
	{
		// Invalid the cache
		Cache::instance()->delete(Fragment::_cache_key($name, $i18n));
	}
}
