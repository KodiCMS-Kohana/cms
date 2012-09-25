<?php defined('SYSPATH') or die('No direct access allowed.');

class Date extends Kohana_Date {

	public static function format($date = NULL, $format = NULL)
	{
		if($format === NULL)
		{
			$format = Setting::get('date_format', 'Y-m-d H:I:s');
		}

		if(  is_string( $date ))
		{
			$date = strtotime($date);
		}

		return date( $format, $date );
	}
}