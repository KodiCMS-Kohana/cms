<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS
 * @category	Helper
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class KodiCMS_Date extends Kohana_Date 
{
	const DATE_FORMAT = 'Y-m-d';
	const TIME_FORMAT = 'H:i:s';
	const DATETIME_FORMAT = 'Y-m-d H:i:s';
	const RFC2616_FORMAT = 'D, d M Y H:i:s \G\M\T';
	
	/**
	 *
	 * @var array 
	 */
	protected static $_translate = array();

	protected static function _translate_words()
	{
		if (empty(self::$_translate))
		{
			$array = array(
				'am', 'pm', 'AM', 'PM',
				'Monday', 'Mon', 'Tuesday', 'Tue', 'Wednesday', 'Wed',
				'Thursday', 'Thu', 'Friday', 'Fri', 'Saturday', 'Sat',
				'Sunday', 'Sun', 'January', 'Jan', 'February', 'Feb',
				'March', 'Mar', 'April', 'Apr', 'May', 'June', 'Jun',
				'July', 'Jul', 'August', 'Aug', 'September', 'Sep',
				'October', 'Oct', 'November', 'Nov', 'December', 'Dec',
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

		foreach (DateTimeZone::listIdentifiers() as $zone)
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
		if ($format === NULL)
		{
			$format = Config::get('site', 'date_format', 'Y-m-d H:I:s');
		}

		if (is_array($date))
		{
			$string = '';

			if (!empty($date['year']) AND !empty($date['month']) AND !empty($date['day']))
			{
				$string = $date['year'] . '-' . $date['month'] . '-' . $date['day'];
			}

			if (($date['hour']) AND !empty($date['minute']) AND !empty($date['second']))
			{
				$string .= ' ' . $date['hour'] . ':' . $date['minute'] . ':' . $date['second'];
			}

			$date = strtotime($string);
		}
		else if (!Valid::numeric($date))
		{
			$date = strtotime($date);
		}

		if (empty($date))
		{
			return __('Never');
		}

		return strtr(date($format, $date), self::_translate_words());
	}

	/**
	 * 
	 * @return array
	 */
	public static function formats()
	{
		$dates = array();
		foreach (Kohana::$config->load('global')->get('date_formats', array()) as $format)
		{
			$dates[$format] = Date::format(time(), $format);
		}

		return $dates;
	}
}