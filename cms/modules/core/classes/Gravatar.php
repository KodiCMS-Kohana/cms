<?php defined('SYSPATH') or die('No direct script access.');

/**
 * @package		KodiCMS
 * @category	Helper
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Gravatar {
	
	/**
	 *
	 * @var array 
	 */
	protected static $_cache = array();

	/**
	 * 
	 * @param string $email
	 * @param integer $size
	 * @param string $default
	 * @param array $attributes
	 * @return string
	 */
	public static function load($email, $size = 40, $default = NULL, array $attributes = NULL)
	{
		if (empty($email))
		{
			$email = 'test@test.com';
		}

		if ($default === NULL)
		{
			$default = 'mm';
		}

		$hash = md5(strtolower(trim($email)));
		$query_params = URL::query(array(
			'd' => $default,
			's' => (int) $size
		));

		if (!isset(self::$_cache[$email][$size]))
		{
			self::$_cache[$email][$size] = HTML::image('http://www.gravatar.com/avatar/' . $hash . $query_params, $attributes);
		}

		return self::$_cache[$email][$size];
	}
}