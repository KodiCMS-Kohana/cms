<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

class AuthUser {

	static public function isLoggedIn()
	{
		return Auth::instance()->logged_in();
	}

	static public function getRecord()
	{
		return Auth::instance()->get_user(FALSE);
	}

	static public function getId()
	{
		return self::getRecord() ? self::getRecord()->id : FALSE;
	}

	static public function getUserName()
	{
		return self::getRecord() ? self::getRecord()->username : FALSE;
	}

	static public function getPermissions()
	{
		$roles = self::getRecord() ? self::getRecord()->roles->find_all() : FALSE;
		
		$array = array();
		if($roles)
		{
			foreach ( $roles as $role )
			{
				$array[$role->id] = $role->name;
			}
		}
		return $array;
	}

	/**
	 * Checks if user has (one of) the required permissions.
	 *
	 * @param string $permission Can contain a single permission or comma seperated list of permissions.
	 * @return boolean
	 */
	static public function hasPermission( $permissions )
	{
		if(empty($permissions))
		{
			return TRUE;
		}
		
		if(!is_array( $permissions ))
		{
			$permissions = explode(',', $permissions);
		}

		return self::getRecord() ? self::getRecord()->has_role($permissions, FALSE) : FALSE;
	}

	static public function login( $username, $password, $remember = FALSE )
	{
		$user = ORM::factory( 'user' );
		
		// Attempt to load the user
		$user
			->where( 'username', '=', $username )
			->find();
		
		if(
			$user->loaded()
			AND
			Auth::instance()->login($user, $password, $remember))
		{
			return TRUE;
		}

		return FALSE;
	}

	static public function logout()
	{
		Auth::instance()->logout();
		Session::instance()->destroy();
	}

}

// end AuthUser class