<?php defined('SYSPATH') or die('No direct access allowed.');

class Date extends Kohana_Date 
{
	/**
	 *
	 * @var array 
	 */
	protected static $_translate = array();

	protected static function _translate()
	{
		if( empty(self::$_translate) )
		{
			$array = array(
				'am', 'pm', 'AM', 'PM',
				'Monday', 'Mon', 'Tuesday', 'Tue', 'Wednesday', 'Wed',
				'Thursday', 'Thu', 'Friday', 'Fri', 'Saturday', 'Sat',
				'Sunday', 'Sun', 'January', 'Jan', 'February', 'Feb',
				'March', 'Mar', 'April', 'Apr', 'May', 'June', 'Jun',
				'July', 'Jul', 'August', 'Aug', 'September', 'Sep',
				'October', 'Oct', 'November', 'Nov', 'December' , 'Dec',
				'st', 'nd', 'rd', 'th'
			);

			foreach ($array as $value)
			{
				self::$_translate[$value] = __($value);
			}
		}
		
		return self::$_translate;
	}
	
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
		if( $format === NULL )
		{
			$format = Setting::get('date_format', 'Y-m-d H:I:s');
		}

		if( ! Valid::numeric( $date ) )
		{
			$date = strtotime($date);
		}
		
		if( ! $date)
		{
			return __('Never');
		}

		return strtr( date( $format, $date ), self::_translate() );
	}
}