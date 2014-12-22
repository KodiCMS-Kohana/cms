<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * @package		KodiCMS/Users
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
abstract class KodiCMS_Auth extends Kohana_Auth {

	const EMAIL = 'email';
	const USERNAME = 'username';

	/**
	 * 
	 * @return boolean
	 */
	public static function is_logged_in($role = NULL)
	{
		return Auth::instance()->logged_in($role);
	}

	/**
	 * 
	 * @param object $default
	 * 
	 * @return ORM
	 */
	public static function get_record($default = NULL)
	{
		return Auth::instance()->get_user($default);
	}

	/**
	 * 
	 * @return integer
	 */
	public static function get_id()
	{
		return self::is_logged_in() ? self::get_record()->id : NULL;
	}

	/**
	 * 
	 * @return string
	 */
	public static function get_username()
	{
		return self::is_logged_in() ? self::get_record()->username : NULL;
	}

	/**
	 * 
	 * @return array
	 */
	public static function get_permissions()
	{
		return self::get_record()
			->roles
			->find_all()
			->as_array('id', 'name');
	}

	/**
	 * Checks if user has (one of) the required permissions.
	 * 
	 * @param array|string $permissions
	 * @param boolean $all_required
	 * @return boolean
	 */
	public static function has_permissions($permissions, $all_required = FALSE)
	{
		if (empty($permissions))
		{
			return TRUE;
		}

		if (!is_array($permissions))
		{
			$permissions = explode(',', $permissions);
		}

		return self::get_record() ? self::get_record()->has_role($permissions, $all_required) : FALSE;
	}
}
