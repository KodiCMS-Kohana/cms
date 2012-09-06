<?php defined('SYSPATH') or die('No direct access allowed.');

final class Observer {

	static protected $events = array();

	public static function observe($event_names, $callback) {
		
		if(!is_array($event_names))
		{
			$event_names = array($event_names);
		}
		
		foreach ( $event_names as $event_name )
		{
			if (!isset(self::$events[$event_name]))
				self::$events[$event_name] = array();

			self::$events[$event_name][] = $callback;
		}
	}

	public static function stopObserving($event_name, $callback) {
		if (isset(self::$events[$event_name][$callback]))
			unset(self::$events[$event_name][$callback]);
	}

	public static function clearObservers($event_name) {
		self::$events[$event_name] = array();
	}

	public static function getObserverList($event_name) {
		return (isset(self::$events[$event_name])) ? self::$events[$event_name] : array();
	}

	/**
	 * If your event does not need to process the return values from any observers use this instead of getObserverList()
	 */
	public static function notify($event_name, $args = array()) {
		//$args = array_slice(func_get_args(), 1); // removing event name from the arguments

		foreach (self::getObserverList($event_name) as $callback)
			call_user_func_array($callback, $args);
	}

}