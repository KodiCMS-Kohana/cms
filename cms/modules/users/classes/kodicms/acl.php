<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * @package		KodiCMS/Users
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class KodiCMS_ACL {

	const DENY = FALSE;
	const ALLOW = TRUE;
	
	const ADMIN_USER = 1;
	const ADMIN_ROLE = 'administrator';
	
	/**
	 * Список прав
	 * @var array 
	 */
	protected static $_permissions = array();
	
	/**
	 * Получение спсика доступных прав из конфига
	 * 
	 * @return array
	 */
	public static function get_permissions()
	{
		$permissions = array();

		foreach (Kohana::$config->load('permissions')->as_array() as $module => $actions)
		{
			if (isset($actions['title']))
			{
				$title = $actions['title'];
			}
			else
			{
				$title = $module;
			}

			foreach ($actions as $action)
			{
				if (is_array($action))
				{
					$permissions[$title][$module . '.' . $action['action']] = $action['description'];
				}
			}
		}

		return $permissions;
	}
	
	/**
	 * 
	 * @param Model_User $user
	 * @return boolean
	 */
	public static function is_admin($user = NULL)
	{
		if ($user === NULL)
		{
			$user = Auth::get_record();
		}

		if ($user instanceof Model_User)
		{
			$user_id = $user->id;
			$roles = $user->roles();
		}
		else
		{
			$user_id = (int) $user;
			$roles = array('login');
		}

		if ($user_id == self::ADMIN_USER OR in_array(self::ADMIN_ROLE, $roles))
		{
			return TRUE;
		}

		return FALSE;
	}

	/**
	 * Проверка прав на доступ
	 * 
	 * @param string|Request $action
	 * @param Model_User $user
	 * @return boolean
	 */
	public static function check($action, Model_User $user = NULL)
	{
		if ($user === NULL)
		{
			$user = Auth::get_record();
		}

		if (!( $user instanceof Model_User ))
		{
			return self::DENY;
		}

		if (empty($action))
		{
			return self::ALLOW;
		}

		if (self::is_admin($user))
		{
			return self::ALLOW;
		}

		if ($action instanceof Request)
		{
			$params = array();
			$directory = $action->directory();
			if (!empty($directory) AND $directory != ADMIN_DIR_NAME)
			{
				$params[] = $action->directory();
			}

			$params[] = $action->controller();
			$params[] = $action->action();
			$action = $params;
		}

		if (is_array($action))
		{
			$action = strtolower(implode('.', $action));
		}

		if (!isset(self::$_permissions[$user->id]))
		{
			self::_set_permissions($user);
		}

		return isset(self::$_permissions[$user->id][$action]);
	}
	
	/**
	 * Проверка прав доступа по массиву 
	 * 
	 * @param array $actions
	 * @param Model_User $user
	 * @return boolean
	 */
	public static function check_array(array $actions, Model_User $user = NULL)
	{
		foreach ($actions as $action)
		{
			if (self::check($action, $user))
			{
				return TRUE;
			}
		}

		return FALSE;
	}
	
	/**
	 * Загрузка прав доступа для пользователя
	 * 
	 * @param Model_User $user
	 */
	protected static function _set_permissions(Model_User $user)
	{
		self::$_permissions[$user->id] = array_flip($user->permissions());
	}
}