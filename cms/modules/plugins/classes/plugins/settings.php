<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

/**
 * @package    Plugins
 */

class Plugins_Settings {

	/**
	 *
	 * @var array
	 */
	protected static $_settings = array();

	/**
	 *
	 * @var boolean
	 */
	protected static $_loaded = FALSE;
	
	/**
	 * Stores all settings from a name<->value pair array in the database.
	 *
	 * @param array $settings Array of name-value pairs
	 * @param string $plugin_id     The folder name of the plugin
	 * 
	 * @return boolean
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
		
		self::_clear_cache();

		return TRUE;
	}

	/**
	 * Allows you to store a single setting in the database.
	 *
	 * @param string $name          Setting name
	 * @param string $value         Setting value
	 * @param string $plugin_id     Plugin folder name
	 * 
	 * @return boolean
	 */
	public static function set_setting( $name, $value, $plugin_id )
	{
		$sql = DB::select( 'name' )
			->from( 'plugin_settings' )
			->where( 'plugin_id', '=', $plugin_id )
			->as_object()
			->execute();

		$name = URL::title( $name, '_' );
		
		$existing_settings = array();

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
				->columns( array('value', 'name', 'plugin_id'))
				->values( array(
					'value' => $value,
					'name' => $name,
					'plugin_id' => $plugin_id
				) )
				->execute();
		}
		
		self::_clear_cache();

		return TRUE;
	}

	/**
	 * Retrieves all settings for a plugin and returns an array of name-value pairs.
	 * Returns empty array when unsuccessful in retrieving the settings.
	 *
	 * @param string $plugin_id
	 * 
	 * @return array
	 */
	public static function get_settings( $plugin_id = NULL )
	{
		if ( self::$_loaded === FALSE )
		{
			$db_settings = DB::select( 'name', 'value', 'plugin_id' )
					->from( 'plugin_settings' )
					->as_object()
					->cache_key('plugin_get_settings')
					->cached( 3600 )
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

		self::$_loaded = TRUE;

		return self::$_settings;
	}

	/**
	 * Returns the value for a specified setting.
	 * Returns FALSE when unsuccessful in retrieving the setting.
	 *
	 * @param string $name
	 * @param string $plugin_id
	 * 
	 * @return string|$default
	 */
	public static function get_setting( $name, $plugin_id, $default = NULL )
	{
		$name = URL::title( $name, '_' );

		return Arr::path(self::$_settings, $plugin_id.'.'.$name, $default);
	}
	
	/**
	 * 
	 * @return boolean
	 */
	public static function is_loaded()
	{
		return self::$_loaded;
	}

	protected static function _clear_cache()
	{
		Kohana::cache('Database::cache(plugin_get_settings)', NULL, -1);
	}
}