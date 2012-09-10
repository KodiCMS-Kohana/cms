<?php defined('SYSPATH') or die('No direct script access.');
/**
 * I18n_Date class
 * Provides date formatting and translation methods to achieve consistency with MooTools Date.format()
 * I18n_Date::get_time_phrase() based on MooTools Date.get_time_phrase()
 *
 * Create 'class Date extends I18n_Date{}' in your application to override Kohana_Date::fuzzy_span() method
 *
 * @package		I18n_Plural
 * @author		Korney Czukowski
 * @copyright	(c) 2010 Korney Czukowski
 * @license		http://kohanaframework.org/license
 */
class I18n_Date extends Kohana_Date
{
	/**
	 * Returns the difference between a time and now in a "fuzzy" way.
	 * Overrides Kohana_Date::fuzzy_span() method.
	 * @param integer $from UNIX timestamp
	 * @param integer $to UNIX timestamp, current timestamp is used when NULL
	 * @return string
	 */
	public static function fuzzy_span($from, $to = NULL)
	{
		if ( ! $from)
		{
			return ___('date.never');
		}
		if ($to === NULL)
		{
			$to = time();
		}
		return self::get_time_phrase($to - $from);
	}

	/**
	 * Returns verbose time interval based on time difference
	 * @param int $delta time difference in seconds
	 * @staticvar array $units
	 * @return string
	 */
	public static function get_time_phrase($delta)
	{
		$suffix = ($delta < 0) ? '_until' : '_ago';
		if ($delta < 0)
		{
			$delta *= -1;
		}

		static $units = array(
			'minute' => 60,
			'hour' => 60,
			'day' => 24,
			'week' => 7,
			'month' => 4.333333,
			'year' => 12,
			'eon' => INF,
		);

		$msg = 'less_than_minute';
		foreach ($units as $unit => $interval)
		{
			if ($delta < 1.5 * $interval)
			{
				if ($delta > 0.75 * $interval)
				{
					$msg = $unit;
					$delta /= $interval;
				}
				break;
			}
			$delta /= $interval;
			$msg = $unit;
		}
		$delta = (int) round($delta);

		return ___('date.'.$msg.$suffix, $delta, array('{delta}' => $delta));
	}

	/**
	 * Formats date and time.
	 * @param mixed timestamp, string with date representation or I18n_Date_Format object; current timestamp if NULL
	 * @param string format string or shorthand; '%x %X' if NULL
	 * @return string
	 */
	public static function format($timestamp = NULL, $format = NULL)
	{
		if ($timestamp === NULL)
		{
			$timestamp = time();
		}
		$time = new I18n_Date_Format($timestamp);
		return $time->format($format);
	}
}