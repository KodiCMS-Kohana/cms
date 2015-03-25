<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * @package		KodiCMS
 * @category	Helper
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
final class Observer {

	static protected $events = array( );

	/**
	 * 
	 * @param array|string $event_names
	 * @param string|callable $callback
	 */
	public static function observe($event_names, $callback)
	{
		if (!is_array($event_names))
		{
			$event_names = array($event_names);
		}

		$args = array_slice(func_get_args(), 2);

		foreach ($event_names as $event)
		{
			if (!isset(self::$events[$event]))
			{
				self::$events[$event] = array();
			}

			self::$events[$event][] = array($callback, $args);
		}
	}

	/**
	 * 
	 * @param string $event_name
	 * @param string $callback
	 */
	public static function stopObserving($event_name, $callback)
	{
		if (isset(self::$events[$event_name][$callback]))
		{
			unset(self::$events[$event_name][$callback]);
		}
	}

	/**
	 * 
	 * @param string $event_name
	 */
	public static function clearObservers($event_name)
	{
		self::$events[$event_name] = array();
	}

	/**
	 * 
	 * @param string $event_name
	 * @return array
	 */
	public static function getObserverList($event_name)
	{
		return Arr::get(self::$events, $event_name, array());
	}

	/**
	 * If your event does not need to process the return values from any observers use this instead of getObserverList()
	 * 
	 * @param string $event_name
	 */
	public static function notify($event_name)
	{
		$args = array_slice(func_get_args(), 1); // removing event name from the arguments

		foreach (self::getObserverList($event_name) as $callback)
		{
			list($class, $class_args) = $callback;

			if (Kohana::$profiling === TRUE)
			{
				$benchmark = Profiler::start('Observer notify', $event_name);
			}

			if (is_array($class))
			{
				forward_static_call_array($class, Arr::merge($args, $class_args));
			}
			else
			{
				call_user_func_array($class, Arr::merge($args, $class_args));
			}

			if (isset($benchmark))
			{
				Profiler::stop($benchmark);
			}
		}
	}

}