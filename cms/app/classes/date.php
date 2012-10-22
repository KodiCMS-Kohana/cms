<?php defined('SYSPATH') or die('No direct access allowed.');

class Date extends Kohana_Date 
{
	
	/**
	 * 
	 * @return array
	 */
	public static function timezones()
	{
		$zones = array();

		foreach(DateTimeZone::listIdentifiers() as $zone) 
		{
			$zones[$zone] = $zone;
		}

		return $zones;
	}

	/**
	 * 
	 * @param integer|string $date
	 * @param string $format
	 * @return string
	 */
	public static function format($date = NULL, $format = NULL)
	{
		if($format === NULL)
		{
			$format = Setting::get('date_format', 'Y-m-d H:I:s');
		}

		if( !Valid::numeric( $date ))
		{
			$date = strtotime($date);
		}

		return date( $format, $date );
	}
}