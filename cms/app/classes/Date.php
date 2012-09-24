<?php defined('SYSPATH') or die('No direct access allowed.');

class Date extends Kohana_Date {
	
	protected static $_replace_monts = array(
		"января"		=> "Январь",
		"февраля"		=> "Февраль",
		"марта"			=> "Март",
		"апреля"		=> "Апрель",
		"мая"			=> "Май",
		"июня"			=> "Июнь",
		"июля"			=> "Июль",
		"августа"		=> "Август",
		"сентября"		=> "Сентябрь",
		"октября"		=> "Октябрь",
		"ноября"		=> "Ноябрь",
		"декабря"		=> "Декабрь",
	);
	
	protected static $_replace = array (
		"January"		=> "января",
		"February"		=> "февраля",
		"March"			=> "марта",
		"April"			=> "апреля",
		"May"			=> "мая",
		"June"			=> "июня",
		"July"			=> "июля",
		"August"		=> "августа",
		"September"		=> "сентября",
		"October"		=> "октября",
		"November"		=> "ноября",
		"December"		=> "декабря",	

		"Sunday"		=> "воскресенье",
		"Monday"		=> "понедельник",
		"Tuesday"		=> "вторник",
		"Wednesday"		=> "среда",
		"Thursday"		=> "четверг",
		"Friday"		=> "пятница",
		"Saturday"		=> "суббота",

		"Sun"			=> "воскресенье",
		"Mon"			=> "понедельник",
		"Tue"			=> "вторник",
		"Wed"			=> "среда",
		"Thu"			=> "четверг",
		"Fri"			=> "пятница",
		"Sat"			=> "суббота",

		"th"			=> "",
		"st"			=> "",
		"nd"			=> "",
		"rd"			=> ""
	);
	
	public static function format($date = NULL, $format = NULL, $decl = FALSE)
	{
		if($format === NULL)
		{
			$format = Setting::get('date_format', 'Y-m-d H:I:s');
		}

		if(  is_string( $date ))
		{
			$date = strtotime($date);
		}
		
		$date = date($format, $date);
		$date = strtr($date, self::$_replace);
		
		if($decl)
		{
			$date = strtr($date, self::$_replace_monts);
		}

		return $date;
	}
}