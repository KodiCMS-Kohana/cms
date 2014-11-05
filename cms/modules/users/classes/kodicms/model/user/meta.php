<?php defined('SYSPATH') or die('No direct script access.');

/**
 * @package		KodiCMS/Users
 * @category	Model
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class KodiCMS_Model_User_Meta extends Model {
	
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
			$user_id = Auth::get_id();
		}

		self::_load($user_id);

		$value = Arr::path(self::$_cache, $user_id . '.' . $key);
		
		if($value !== NULL)
		{
			return @Kohana::unserialize($value);
		}
		else
		{
			if($user_id === 0)
			{
				return $default;
			}

			self::_load(0);
			$value = Arr::path(self::$_cache, 0 . '.' . $key);
			
			if($value !== NULL)
			{
				return @Kohana::unserialize($value);
			}
			else
			{
				return $default;
			}
		}
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
			$user_id = Auth::get_id();
		}

		self::_load($user_id);
		$value = Kohana::serialize($value);
		if (isset(self::$_cache[$user_id][$key]))
		{
			$status = (bool) DB::update('user_meta')
				->value('value', $value)
				->where('key', '=', $key)
				->where('user_id', '=', ($user_id === 0) ? NULL : $user_id)
				->execute();
		}
		else
		{
			$status = (bool) DB::insert('user_meta')
				->columns(array(
					'key', 'value', 'user_id'
				))
				->values(array($key, $value, ($user_id === 0) ? NULL : $user_id))
				->execute();
		}
		
		self::_clear_cache($user_id);
		return $status;
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
			$user_id = Auth::get_id();
		}

		self::_clear_cache($user_id);

		return (bool) DB::delete('user_meta')
			->where('user_id', '=', ($user_id === 0) ? NULL : $user_id)
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
			$user_id = Auth::get_id();
		}

		self::_clear_cache($user_id);

		return (bool) DB::delete('user_meta')
			->where('user_id', '=', ($user_id === 0) ? NULL : $user_id)
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
			$user_id = Auth::get_id();
		}

		if (!isset(self::$_cache[$user_id]))
		{
			self::$_cache[$user_id] = DB::select('key', 'value')
				->from('user_meta')
				->where('user_id', '=', ($user_id === 0) ? NULL : $user_id)
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
			$user_id = Auth::get_id();
		}
		
		unset(self::$_cache[$user_id]);
		Cache::instance()->delete('Database::cache(user_meta' . $user_id . ')');
	}
}
