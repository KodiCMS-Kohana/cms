<?php defined('SYSPATH') or die('No direct script access.');

/**
 * @package		KodiCMS/Users
 * @category	Model
 * @author		ButscHSter
 */
class KodiCMS_Model_User_Meta {
	
	/**
	 *
	 * @var array 
	 */
	protected static $_cache = array();

	/**
	 * 
	 * @param string $key
	 * @param mixed $default
	 * @param integer $user_id
	 * @return mixed
	 */
	public static function get($key, $default = NULL, $user_id = NULL)
	{
		if ($user_id === NULL)
		{
			$user_id = AuthUser::getId();
		}

		self::_load($user_id);

		$value = Arr::path(self::$_cache, $user_id . '.' . $key);
		
		if($value !== NULL)
		{
			$value = @unserialize($value);
		}

		if ($value === FALSE)
		{
			$value = $default;
		}

		return $value;
	}
	
	/**
	 * 
	 * @param string $key
	 * @param mixed $value
	 * @param integer $user_id
	 * @return boolean
	 */
	public static function set($key, $value, $user_id = NULL)
	{
		if ($user_id === NULL)
		{
			$user_id = AuthUser::getId();
		}

		self::_load($user_id);

		self::_clear_cache($user_id);

		if (isset(self::$_cache[$user_id][$key]))
		{
			return (bool) DB::update('user_meta')
				->value('value', serialize($value))
				->where('key', '=', $key)
				->where('user_id', '=', $user_id)
				->execute();
		}
		else
		{
			return (bool) DB::insert('user_meta')
				->columns(array(
					'key', 'value', 'user_id'
				))
				->values(array($key, serialize($value), $user_id))
				->execute();
		}
	}
	
	/**
	 * 
	 * @param string $key
	 * @param integer $user_id
	 * @return boolean
	 */
	public static function delete($key, $user_id = NULL)
	{
		if ($user_id === NULL)
		{
			$user_id = AuthUser::getId();
		}

		self::_clear_cache($user_id);

		return (bool) DB::delete('user_meta')
			->where('user_id', '=', $user_id)
			->where('key', '=', $key)
			->execute();
	}
	
	/**
	 * 
	 * @param integer $user_id
	 * @return boolean
	 */
	public static function clear($user_id = NULL)
	{
		if ($user_id === NULL)
		{
			$user_id = AuthUser::getId();
		}

		self::_clear_cache($user_id);

		return (bool) DB::delete('user_meta')
			->where('user_id', '=', $user_id)
			->execute();
	}
	
	/**
	 * 
	 * @param integer $user_id
	 * @return array
	 */
	protected static function _load($user_id = NULL)
	{
		if ($user_id === NULL)
		{
			$user_id = AuthUser::getId();
		}

		if (Arr::get(self::$_cache, $user_id) === NULL)
		{
			self::$_cache[$user_id] = DB::select('key', 'value')
				->from('user_meta')
				->where('user_id', '=', $user_id)
				->cache_key('user_meta' . $user_id)
				->cached(Date::DAY)
				->execute()
				->as_array('key', 'value');
		}

		return self::$_cache[$user_id];
	}
	
	protected static function _clear_cache($user_id = NULL)
	{
		if ($user_id === NULL)
		{
			$user_id = AuthUser::getId();
		}
		
		Cache::instance()->delete('Database::cache(user_meta' . $user_id . ')');
	}
}
