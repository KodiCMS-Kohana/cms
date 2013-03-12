<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

class Sheduler {
	
	protected static $_callbacks = array();
	
	public static function add($callback)
	{
		if ( ! is_callable($callback))
		{
			throw new Sheduler_Exception('Invalid Sheduler::callback specified');
		}
		
		self::$_callbacks[] = $callback;
	}
	
	public static function get($start, $end)
	{
		Observer::notify('sheduler_callbacks');
		
		$data = array();

		foreach (self::$_callbacks as $callback)
		{
			$result = call_user_func($callback, $start, $end);
			
			if(is_array($result))
			{
				$data = Arr::merge($data, $result);
			}
		}
		
		return $data;
	}
}