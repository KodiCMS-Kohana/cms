<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

class AuthUser {

	const SESSION_KEY = 'frog_auth_user';
	const COOKIE_KEY = 'frog_auth_user';
	const ALLOW_LOGIN_WITH_EMAIL = FALSE;
	const COOKIE_LIFE = 1209600; // 2 weeks

	static protected $is_logged_in = FALSE;
	static protected $user_id = FALSE;
	static protected $is_admin = FALSE;
	static protected $record = FALSE;
	static protected $permissions = array( );

	static public function load()
	{
		if ( Session::instance()->get( self::SESSION_KEY ) )
		{
			$user = User::findBy( 'username', Session::instance()->get( self::SESSION_KEY ) );
		}
		else if ( Cookie::get( self::COOKIE_KEY ) )
		{
			$user = Cookie::get( self::COOKIE_KEY );
		}
		else
			return FALSE;

		if ( !$user )
		{
			return self::logout();
		}

		self::setInfos( $user );
	}

	static public function setInfos( Record $user )
	{
		Session::instance()->set( self::SESSION_KEY, $user->username );

		self::$record = $user;
		self::$is_logged_in = true;
		self::$permissions = $user->getPermissions();
		self::$is_admin = self::hasPermission( 'admin' );
	}

	static public function isLoggedIn()
	{
		return self::$is_logged_in;
	}

	static public function getRecord()
	{
		return self::$record ? self::$record : FALSE;
	}

	static public function getId()
	{
		return self::$record ? self::$record->id : FALSE;
	}

	static public function getUserName()
	{
		return self::$record ? self::$record->username : FALSE;
	}

	static public function getPermissions()
	{
		return self::$permissions;
	}

	/**
	 * Checks if user has (one of) the required permissions.
	 *
	 * @param string $permission Can contain a single permission or comma seperated list of permissions.
	 * @return boolean
	 */
	static public function hasPermission( $permissions )
	{
		if ( empty( $permissions ) )
		{
			return TRUE;
		}

		if ( !is_array( $permissions ) )
		{
			$permissions = explode( ',', (string) $permissions );
		}

		foreach ( $permissions as $permission )
		{
			if ( in_array( strtolower( $permission ), self::$permissions ) )
			{
				return TRUE;
			}
		}

		return FALSE;
	}

	static public function login( $username, $password, $set_cookie = FALSE )
	{
		self::logout();

		$user = User::findBy( 'username', $username );

		if ( !$user instanceof User && self::ALLOW_LOGIN_WITH_EMAIL )
		{
			$user = User::findBy( 'email', $username );
		}

		if ( $user instanceof User && $user->password == sha1( $password ) )
		{
			$user->last_login = date( 'Y-m-d H:i:s' );
			$user->save();

			if ( $set_cookie === TRUE )
			{
				$time = $_SERVER['REQUEST_TIME'] + self::COOKIE_LIFE;
				Cookie::set( self::COOKIE_KEY, $user->username, $time );
			}

			self::setInfos( $user );
			return true;
		}

		return FALSE;
	}

	static public function logout()
	{
		Session::instance()->restart();
		Cookie::delete( self::COOKIE_KEY );

		self::$record = FALSE;
		self::$user_id = FALSE;
		self::$is_admin = FALSE;
		self::$permissions = array( );
	}

}

// end AuthUser class