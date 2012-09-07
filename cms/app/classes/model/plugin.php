<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Model_Plugin {

	protected static $_plugins = array();
	protected static $_registered = array();
	protected static $_settings = array();
	protected static $_settings_loaded = FALSE;
	public static $javascripts = array();
	public static $styles = array();

	public static function init()
	{
		self::$_plugins = unserialize(Model_Setting::get( 'plugins', 'a:0:{}' ) );

		$plugins = array();
		foreach ( self::$_plugins as $plugin_id => $tmp )
		{
			$plugins[$plugin_id] = PLUGPATH . $plugin_id . DIRECTORY_SEPARATOR;
		}

		if ( self::$_settings_loaded === FALSE )
		{
			self::get_settings();
		}

		Kohana::modules( Kohana::modules() + $plugins );
	}

	public static function get_loaded()
	{
		return self::$_plugins;
	}

	public static function get( $plugin_id )
	{
		return Arr::get(self::$_plugins, $plugin_id);
	}

	public static function register( Model_Plugin_Item $plugin )
	{
		if ( isset( self::$_registered[$plugin->id] ) )
		{
			throw new Kohana_Exception( 'Плагин с таким ключом уже зарегестрирован' );
		}

		self::$_registered[$plugin->id] = $plugin;

		return TRUE;
	}

	public static function get_registered( $plugin_id = NULL )
	{
		if ( $plugin_id === NULL )
		{
			return self::$_registered;
		}

		return Arr::get( self::$_registered, $plugin_id );
	}

	public static function activate( $plugin_id )
	{
		$file = PLUGPATH . $plugin_id . DIRECTORY_SEPARATOR . 'enable.php';
		if ( file_exists( $file ) )
		{
			require_once $file;
		}

		self::$_plugins[$plugin_id] = 1;

		self::_save();
	}

	public static function deactivate( $plugin_id )
	{
		if ( isset( self::$_plugins[$plugin_id] ) )
		{
			$file = PLUGPATH . $plugin_id . DIRECTORY_SEPARATOR . 'disable.php';
			if ( file_exists( $file ) )
			{
				require_once $file;
			}

			unset( self::$_plugins[$plugin_id] );

			self::_save();
		}
	}

	protected static function _save()
	{
		$data = array(
			'plugins' => serialize( self::$_plugins )
		);

		Model_Setting::save_from_array( $data );
	}

	public static function find_all()
	{
		$dir = PLUGPATH;

		if ( $handle = opendir( $dir ) )
		{
			while ( FALSE !== ($plugin_id = readdir( $handle )) ) {

				if ( is_dir( $dir . $plugin_id ) && strpos( $plugin_id, '.' ) !== 0 )
				{
					$file = PLUGPATH . $plugin_id . DIRECTORY_SEPARATOR . 'init.php';
					if ( file_exists( $file ) )
					{
						require_once $file;
					}
				}
			}
			closedir( $handle );
		}

		ksort( self::$_registered );
		return self::$_registered;
	}

	public static function add_javascript( $plugin_id, $file )
	{
		if ( self::is_enabled( $plugin_id ) )
			self::$javascripts[] = 'plugins/' . $plugin_id . '/' . $file;
	}

	public static function add_style( $plugin_id, $file )
	{
		if ( self::is_enabled( $plugin_id ) )
			self::$styles[] = 'plugins/' . $plugin_id . '/' . $file;
	}

	/**
	 * Returns TRUE if a plugin is enabled for use.
	 *
	 * @param string $plugin_id
	 */
	public static function is_enabled( $plugin_id )
	{
		if ( array_key_exists( $plugin_id, self::$_plugins ) && self::$_plugins[$plugin_id] == 1 )
		{
			return TRUE;
		}

		return FALSE;
	}

	/**
	 * Stores all settings from a name<->value pair array in the database.
	 *
	 * @param array $settings Array of name-value pairs
	 * @param string $plugin_id     The folder name of the plugin
	 */
	public static function set_all_settings( array $array, $plugin_id )
	{
		$existing_settings = array();

		$sql = DB::select( 'name' )
				->from( 'plugin_settings' )
				->where( 'plugin_id', '=', $plugin_id )
				->as_object()
				->execute();

		foreach ( $sql as $setting )
		{
			$existing_settings[$setting->name] = $setting->name;
		}

		foreach ( $array as $name => $value )
		{
			if ( array_key_exists( $name, $existing_settings ) )
			{
				DB::update( 'plugin_settings' )
					->set( array('value' => $value) )
					->where( 'name', '=', $name )
					->where( 'plugin_id', '=', $plugin_id )
					->execute();
			}
			else
			{
				DB::insert( 'plugin_settings' )
						->columns( array(
							'value', 'name', 'plugin_id'
						) )
						->values( array(
							'value' => $value,
							'name' => $name,
							'plugin_id' => $plugin_id
						) )
						->execute();
			}
		}

		Kohana::cache( 'plugin_get_settings', NULL, -1 );

		return TRUE;
	}

	/**
	 * Allows you to store a single setting in the database.
	 *
	 * @param string $name          Setting name
	 * @param string $value         Setting value
	 * @param string $plugin_id     Plugin folder name
	 */
	public static function set_setting( $name, $value, $plugin_id )
	{
		$sql = DB::select( 'name' )
			->from( 'plugin_settings' )
			->where( 'plugin_id', '=', $plugin_id )
			->as_object()
			->execute();

		$name = URL::title( $name, '_' );

		foreach ( $sql as $setting )
		{
			$existing_settings[$setting->name] = $setting->name;
		}

		if ( in_array( $name, $existing_settings ) )
		{
			DB::update( 'plugin_settings' )
				->set( array('value' => $value) )
				->where( 'name', '=', $name )
				->where( 'plugin_id', '=', $plugin_id )
				->execute();
		}
		else
		{
			DB::insert( 'plugin_settings' )
				->values( array(
					'value' => $value,
					'name' => $name,
					'plugin_id' => $plugin_id
				) )
				->execute();
		}

		Kohana::cache( 'plugin_get_settings', NULL, -1 );

		return TRUE;
	}

	/**
	 * Retrieves all settings for a plugin and returns an array of name-value pairs.
	 * Returns empty array when unsuccessful in retrieving the settings.
	 *
	 * @param <type> $plugin_id
	 */
	public static function get_settings( $plugin_id = NULL )
	{
		if ( self::$_settings_loaded === FALSE )
		{
			$db_settings = DB::select( 'name', 'value', 'plugin_id' )
					->from( 'plugin_settings' )
					->as_object()
					->cached( 3600, FALSE, 'plugin_get_settings' )
					->execute();

			foreach ( $db_settings as $setting )
			{
				self::$_settings[$setting->plugin_id][$setting->name] = $setting->value;
			}
		}

		if ( $plugin_id !== NULL )
		{
			return Arr::get(self::$_settings, $plugin_id, array());
		}

		self::$_settings_loaded = TRUE;

		return self::$_settings;
	}

	/**
	 * Returns the value for a specified setting.
	 * Returns FALSE when unsuccessful in retrieving the setting.
	 *
	 * @param <type> $name
	 * @param <type> $plugin_id
	 */
	static function get_setting( $name, $plugin_id, $default = NULL )
	{
		$name = URL::title( $name, '_' );

		return Arr::path(self::$_settings, $plugin_id.'.'.$name, $default);
	}

}