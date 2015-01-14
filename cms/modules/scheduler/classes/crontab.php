<?php defined('SYSPATH') or die('No direct script access.');

/**
 * @package		KodiCMS/Jobs
 * @category	Helper
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Crontab {
	
	const REGEX = '/^((\*(\/[0-9]+)?)|[0-9\-\,\/]+)\s+((\*(\/[0-9]+)?)|[0-9\-\,\/]+)\s+((\*(\/[0-9]+)?)|[0-9\-\,\/]+)\s+((\*(\/[0-9]+)?)|[0-9\-\,\/]+)\s+((\*(\/[0-9]+)?)|[0-9\-\,\/]+)$/i';
	
	/**
	 * 
	 * @param strng $string
	 * @return boolean
	 */
	public static function valid($string)
	{
		return (bool) preg_match(Crontab::REGEX, trim($string));
	}

	/**
	 *  Finds next execution time(stamp) parsin crontab syntax, 
	 *  after given starting timestamp (or current time if ommited)
	 * 
	 *  @param string $cron_string:
	 *
	 *      0     1    2    3    4
	 *      *     *    *    *    *  
	 *      -     -    -    -    -
	 *      |     |    |    |    |
	 *      |     |    |    |    +----- day of week (0 - 6) (Sunday=0)
	 *      |     |    |    +------- month (1 - 12)
	 *      |     |    +--------- day of month (1 - 31)
	 *      |     +----------- hour (0 - 23)
	 *      +------------- min (0 - 59)
	 *  @param int $after_timestamp timestamp [default=current timestamp]
	 *  @return int unix timestamp - next execution time will be greater 
	 *              than given timestamp (defaults to the current timestamp)
	 *  @throws Kohana_Exception 
	 */
	public static function parse($cron_string, $after_timestamp = NULL)
	{
		if (!Crontab::valid($cron_string))
		{
			throw new Kohana_Exception('Invalid cron string: :string', array(
				':string' => $cron_string));
		}

		if ($after_timestamp !== NULL AND ! Valid::numeric($after_timestamp))
		{
			throw new Kohana_Exception("\$after_timestamp must be a valid unix timestamp (:string given)", array(
				':string' => $after_timestamp));
		}

		$cron = preg_split("/[\s]+/i", trim($cron_string));
		$start = empty($after_timestamp) 
			? time() 
			: $after_timestamp;

		$date = array(
			'minutes'	=> self::_parse_cron_numbers($cron[0], 0, 59),
			'hours'		=> self::_parse_cron_numbers($cron[1], 0, 23),
			'dom'		=> self::_parse_cron_numbers($cron[2], 1, 31),
			'month'		=> self::_parse_cron_numbers($cron[3], 1, 12),
			'dow'		=> self::_parse_cron_numbers($cron[4], 0, 6),
		);

		// limited to time()+366 - no need to check more than 1year ahead
		for ($i = 0; $i <= 60 * 60 * 24 * 366; $i += 60)
		{
			if (
					in_array(intval(date('j', $start + $i)), $date['dom']) 
				AND in_array(intval(date('n', $start + $i)), $date['month']) 
				AND in_array(intval(date('w', $start + $i)), $date['dow']) 
				AND in_array(intval(date('G', $start + $i)), $date['hours'])
				AND in_array(intval(date('i', $start + $i)), $date['minutes'])
			)
			{
				return $start + $i;
			}
		}

		return NULL;
	}

	/**
	 * get a single cron style notation and parse it into numeric value
	 *
	 * @param string $s cron string element
	 * @param int $min minimum possible value
	 * @param int $max maximum possible value
	 * @return int parsed number 
	 */
	protected static function _parse_cron_numbers($element, $min, $max)
	{
		$result = array();

		$value = explode(',', $element);
		foreach ($value as $vv)
		{
			$vvv = explode('/', $vv);
			$step = empty($vvv[1]) ? 1 : $vvv[1];
			$vvvv = explode('-', $vvv[0]);
			$_min = count($vvvv) == 2 ? $vvvv[0] : ($vvv[0] == '*' ? $min : $vvv[0]);
			$_max = count($vvvv) == 2 ? $vvvv[1] : ($vvv[0] == '*' ? $max : $vvv[0]);

			for ($i = $_min; $i <= $_max; $i += $step)
			{
				$result[$i] = intval($i);
			}
		}

		ksort($result);
		return $result;
	}
}
