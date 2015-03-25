<?php defined('SYSPATH') OR die('No direct script access.');

/**
 * @package		KodiCMS
 * @category	Helper
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class KodiCMS_Download {

	/**
	 * 
	 * @param string $path
	 * @return string
	 */
	public static function secure_path($path)
	{
		return Encrypt::instance()->encode($path);
	}

	/**
	 * 
	 * @param string $path
	 * @return string
	 */
	public static function decode_path($path)
	{
		return Encrypt::instance()->decode($path);
	}

}
