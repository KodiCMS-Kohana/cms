<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

/**
 * @package    Plugins
 */
class Plugins {

	/**
	 *
	 * @var array
	 */
	protected static $_plugins = array();
	
	/**
	 *
	 * @var array
	 */
	protected static $_registered = array();

	public static function init()
	{
		$plugins = self::_get_list_from_db();
		
		
		self::$_plugins = empty($plugins) 
			? array()
			: unserialize($plugins);

		$plugins = array();

		foreach ( self::$_plugins as $plugin_id => $tmp )
		{
			if(  is_dir( self::path($plugin_id) ) )
			{
				$plugins[$plugin_id] = self::path($plugin_id);
			}
		}

		if ( ! Plugins_Settings::is_loaded() )
		{
			Plugins_Settings::get_settings();
		}

		Kohana::modules( Kohana::modules() + $plugins );
	}
	
	/**
	 * 
	 * @return string Serialized data
	 */
	protected static function _get_list_from_db()
	{
		return DB::select('value')
			->from(Setting::TABLE_NAME)
			->where( 'name', '=', 'plugins' )
			->cache_key('plugins_list')
			->cached(Date::DAY)
			->execute()
			->get('value');
	}
	
	/**
	 * 
	 * @return array Return the number of rows affected
	 */
	protected static function _save()
	{
		Cache::instance()->delete('Database::cache(plugins_list)');
		
		Kohana::cache('Route::cache()', NULL, -1);
		Kohana::cache('Kohana::find_file()', NULL, -1);

		return DB::update( Setting::TABLE_NAME)
			->set(array('value' => serialize( self::$_plugins )))
			->where('name', '=', 'plugins')
			->execute();
	}

	/**
	 * 
	 * @return array
	 */
	public static function get_loaded()
	{
		return self::$_plugins;
	}

	/**
	 * 
	 * @param string $plugin_id
	 * @return array
	 */
	public static function get( $plugin_id )
	{
		return Arr::get(self::$_plugins, $plugin_id);
	}

	/**
	 * 
	 * @param Plugins_Item $plugin
	 * @return boolean
	 */
	public static function register( Plugins_Item $plugin )
	{
		if ( isset( self::$_registered[$plugin->id] ) )
		{
			return NULL;
		}

		self::$_registered[$plugin->id] = $plugin;

		return TRUE;
	}

	/**
	 * 
	 * @param string $plugin_id
	 * @return array
	 */
	public static function get_registered( $plugin_id = NULL )
	{
		if ( $plugin_id === NULL )
		{
			return self::$_registered;
		}

		return Arr::get( self::$_registered, $plugin_id );
	}

	/**
	 * 
	 * @param string $plugin_id
	 */
	public static function activate( $plugin_id )
	{
		if ( self::is_exists($plugin_id, 'enable.php') )
		{
			require_once $file;
		}

		self::$_plugins[$plugin_id] = TRUE;

		self::_save();
	}

	/**
	 * 
	 * @param string $plugin_id
	 */
	public static function deactivate( $plugin_id, $uninstal = TRUE )
	{
		if ( isset( self::$_plugins[$plugin_id] ) )
		{
			if($uninstal === TRUE)
			{
				Plugins_Settings::delete_settings($plugin_id);

				if ( self::is_exists($plugin_id, 'disable.php') )
				{
					require_once self::path( $plugin_id, 'disable.php' );
				}
			}

			unset( self::$_plugins[$plugin_id] );

			self::_save();
		}
	}

	/**
	 * 
	 * @return array
	 */
	public static function find_all()
	{
		$dir = PLUGPATH;

		if ( $handle = opendir( $dir ) )
		{
			while ( FALSE !== ($plugin_id = readdir( $handle )) ) {

				if ( is_dir( $dir . $plugin_id ) && strpos( $plugin_id, '.' ) !== 0 )
				{
					if ( self::is_exists($plugin_id, 'init.php') )
					{
						require_once self::path( $plugin_id, 'init.php' );
					}
				}
			}
			closedir( $handle );
		}

		ksort( self::$_registered );
		return self::$_registered;
	}

	/**
	 * Returns TRUE if a plugin is enabled for use.
	 *
	 * @param string $plugin_id
	 */
	public static function is_enabled( $plugin_id )
	{
		if ( array_key_exists( $plugin_id, self::$_plugins ) AND self::$_plugins[$plugin_id] == 1 )
		{
			return TRUE;
		}

		return FALSE;
	}
	
	public static function path($plugin_id, $file = NULL)
	{
		return PLUGPATH . $plugin_id . DIRECTORY_SEPARATOR . $file;
	}
	
	public static function is_exists($plugin_id, $file)
	{
		return file_exists( self::path( $plugin_id, $file ) );
	}
}