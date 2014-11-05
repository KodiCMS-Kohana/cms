<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

/**
 * @package		KodiCMS/Plugins
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Plugins {

	/**
	 * Активированные плагины
	 * 
	 * @var array
	 */
	protected static $_activated = array();
	
	/**
	 * Найденные в системе плагины
	 * @var array
	 */
	protected static $_registered = array();

	/**
	 * Инициализация модуля.
	 * 
	 * При инициализации активированные плагины подключаются через
	 * Kohana::modules
	 * 
	 */
	public static function init()
	{	
		self::$_activated = self::_load_from_db();

		$plugins = array();

		foreach ( self::$_activated as $plugin_id => $tmp )
		{
			if(  is_dir( PLUGPATH . $plugin_id ) )
			{
				$plugins['plugin_' . $plugin_id] = PLUGPATH . $plugin_id;
			}
		}

		Kohana::modules( $plugins + Kohana::modules() );
	}

	/**
	 * 
	 * 
	 * @param string $plugin_id
	 */
	public static function activate( Plugin $plugin )
	{
		self::$_activated[$plugin->id()] = TRUE;
	}

	/**
	 * 
	 * @param string $plugin_id
	 */
	public static function deactivate( Plugin $plugin )
	{
		if ( isset( self::$_activated[$plugin->id()] ) )
		{
			unset( self::$_activated[$plugin->id()] );
		}
	}
	
	/**
	 * Получение спсика активированных плагинов
	 * 
	 * @return array
	 */
	public static function activated()
	{
		return self::$_activated;
	}

	/**
	 * Активирован ли плагин
	 * 
	 * @param string $plugin_id
	 * @return boolean
	 */
	public static function is_activated( $plugin_id )
	{
		if($plugin_id instanceof Plugin)
		{
			$plugin_id = $plugin_id->id();
		}

		return isset(self::$_activated[$plugin_id]);
	}

	/**
	 * Получение списка всех имеющихся плагинов
	 * 
	 * @return array
	 */
	public static function find_all()
	{
		$dir = PLUGPATH;

		if ( $handle = opendir( $dir ) )
		{
			while ( FALSE !== ($plugin_id = readdir( $handle )) ) 
			{
				$path = $dir . $plugin_id . DIRECTORY_SEPARATOR;
				if(  file_exists( $path . 'init' . EXT))
				{
					include_once $path . 'init' . EXT;
					
					// If exists plugin model, include them
					if($file = Kohana::find_file( $path . 'plugin', $plugin_id ))
					{
						include_once $file;
					}
				}
			}

			closedir( $handle );
		}

		ksort( self::$_registered );
		return self::$_registered;
	}

	/**
	 * Регистрация плагина в системе
	 * 
	 * @param Plugins_Item $plugin
	 * @return boolean
	 */
	public static function register( Plugin $plugin )
	{
		self::$_registered[$plugin->id()] = $plugin;
		return TRUE;
	}

	/**
	 * Получение списка или одного плагина по ID
	 * 
	 * @param string $plugin_id
	 * @return Plugin_Decorator
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
	 * @param string $key
	 * @param mixed $default
	 * @return mixed
	 */
	public static function setting( $plugin_id, $key, $default = NULL)
	{
		$plugin = self::get_registered($plugin_id);
		
		if($plugin instanceof Plugin)
		{
			return $plugin->get($key, $default);
		}
		
		return $default;
	}

	/**
	 * Получение списка плагинов из БД
	 * 
	 * @cache_key plugins::list
	 * $cache Date::DAY
	 * @return array
	 */
	protected static function _load_from_db()
	{
		return DB::select('id')
			->from(Plugin::TABLE_NAME)
			->cache_key(Plugin::CACHE_KEY . '::list')
			->cached(Date::DAY)
			->execute()
			->as_array('id', 'id');
	}
}