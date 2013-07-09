<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package    Kodi
 */

class KodiCMS_Setting
{
	const TABLE_NAME = 'settings';

	/**
	 *
	 * @var array
	 */
	public static $settings = array();
	
	/**
	 *
	 * @var array 
	 */
	protected static $_new_settings = array();

	/**
	 *
	 * @var boolean 
	 */
	public static $is_loaded = FALSE;

	/**
	 * 
	 * @return array
	 */
	public static function init()
	{
		if ( ! self::$is_loaded)
		{
			self::$settings = DB::select()
				->from(self::TABLE_NAME)
				->cache_key(self::TABLE_NAME)
				->cached(Date::DAY)
				->execute()
				->as_array('name', 'value');

			self::$is_loaded = true;
		}

		return self::$settings;
	}

	/**
	 * Get the value of a setting
	 *
	 * @param name  string  The setting name
	 * @return string the value of the setting name
	 */
	public static function get($key, $default = NULL)
	{
		$config = Kohana::$config->load('global');
		return Arr::get(self::$settings, $key, $config->get($key, $default));
	}
	
	/**
	 * 
	 * @param string $name
	 * @param string $value
	 */
	public static function set($name, $value)
	{
		if( ! isset(self::$settings[$name]) )
		{
			self::$_new_settings[] = $name;
		}

		self::$settings[$name] = $value;
	}

	/**
	 * 
	 * @param array $data
	 */
	public static function saveFromData(array $data)
	{
		foreach( $data as $name => $value )
		{
			self::set($name, $value);
		}
		
		self::save();
	}

	public static function save()
	{
		$insert = DB::insert(self::TABLE_NAME)
			->columns(array('name', 'value'));

		$i = 0;
		foreach (self::$settings as $key => $value)
		{
			if(in_array($key, self::$_new_settings))
			{
				$insert->values(array($key, $value));
				$i++;
			}
			else
			{
				DB::update(self::TABLE_NAME)
					->set(array('value' => $value))
					->where('name', '=', $key)
					->execute();
			}
		}
		
		if($i > 0)
		{
			$insert->execute();
		}
		
		Cache::instance()->delete('Database::cache('.self::TABLE_NAME.')');
	}

} // end Setting class