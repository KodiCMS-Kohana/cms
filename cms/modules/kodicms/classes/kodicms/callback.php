<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS
 * @category	Helper
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class KodiCMS_Callback
{
	/**
	 * 
	 * @param mixed $callback
	 * @param array $params
	 * @return mixed
	 */
	public static function invoke($callback, array $params = NULL)
	{
		if (is_array($callback) OR ! is_string($callback))
		{
			if(empty($params))
			{
				return call_user_func($callback);
			}
			else
			{
				return call_user_func_array($callback, $params);
			}
		}
		elseif (strpos($callback, '::') === FALSE)
		{
			return self::invoke_function($callback, $params);
		}
		else
		{
			return self::invoke_static_class($callback, $params);
		}
		
		return $default;
	}
	
	/**
	 * 
	 * @param string $callback
	 * @param array $params
	 * @return mixed
	 */
	public static function invoke_static_class($callback, array $params = NULL)
	{
		// Split the class and method of the rule
		list($class, $method) = explode('::', $callback, 2);

		// Use a static method call
		$method = new ReflectionMethod($class, $method);

		if (empty($params))
		{
			return $method->invoke(NULL);
		}
		else
		{
			return $method->invokeArgs(NULL, $params);
		}
	}

	/**
	 * 
	 * @param string $callback
	 * @param array $params
	 * @return mixed
	 */
	public static function invoke_function($callback, array $params = NULL)
	{
		$class = new ReflectionFunction($callback);

		if (empty($params))
		{
			return $class->invoke();
		}
		else
		{
			return $class->invokeArgs($params);
		}
	}

}