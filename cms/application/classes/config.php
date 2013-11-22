<?php defined('SYSPATH') OR die('No direct script access.');

class Config extends Kohana_Config {
	
	const YES = 'yes';
	const NO = 'no';
	
	/**
	 * Get the value of a setting
	 * @param group string  The setting name
	 * @param key  string  The setting name
	 * @return string the value of the setting name
	 */
	public static function get($group, $key = NULL, $default = NULL)
	{
		$config = Kohana::$config->load($group);
		
		if($key === NULL)
		{
			return $config;
		}
		
		return $config->get($key, $default);
	}
	
	/**
	 * 
	 * @param string $name
	 * @param string $value
	 */
	public static function set($group, $key, $value)
	{
		$config = Kohana::$config->load($group)->set($key, $value);
		Cache::instance()->delete('Database::cache(config.group.' . $group . ')');
		
		return $config;
	}
	
	/**
	 * 
	 * @param array $array
	 */
	public static function set_from_array(array $array)
	{
		foreach($array as $group => $values)
		{
			if(is_array($values))
			{
				foreach($values as $key => $value)
				{
					Config::set($group, $key, $value);
				}
			}
			else
			{
				Config::set('site', $group, $values);
			}
		}
	}
}
