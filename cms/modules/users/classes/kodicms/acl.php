<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

class KodiCMS_ACL {

	const DENY = FALSE;
	const ALLOW = TRUE;
	
	const ADMIN_USER = 1;
	const ADMIN_ROLE = 'admin';
	
	protected static $_permissions = array();
	
	public static function get_permissions()
	{
		$permissions = array();
		
		foreach(Kohana::$config->load('permissions')->as_array() as $module => $actions)
		{
			foreach($actions as $action)
			{
				$permissions[$module][$module.'.'.$action['action']] = $action['description'];
			}
		}
		
		return $permissions;
	}

	public static function check( $action, Model_User $user = NULL)
	{
		if($user === NULL)
		{
			$user = Auth::instance()->get_user();
		}
		
		if( ! ( $user instanceof Model_User ) )
		{
			return self::DENY;
		}
		
		if( empty($action) )
		{
			return self::ALLOW;
		}
		
		if($user->id == self::ADMIN_USER OR in_array( self::ADMIN_ROLE, $user->roles() ))
		{
			return self::ALLOW;
		}
		
		if( $action instanceof Request)
		{
			$params = array();
			$directory = $action->directory();
			if( !empty($directory) AND $directory != ADMIN_DIR_NAME )
			{
				$params[] = $action->directory();
			}

			$params[] = strtolower($action->controller());
			$params[] = $action->action();
			$action = $params;
		}
		
		if( is_array( $action ))
		{
			$action = implode('.', $action);
		}

		if( !isset( self::$_permissions[$user->id] ))
		{
			self::_set_permissions($user);
		}
		
		return isset(self::$_permissions[$user->id][$action]);
	}
	
	public static function check_array( array $actions, Model_User $user = NULL)
	{
		foreach($actions as $action)
		{
			if(self::check( $action, $user ))
			{
				return TRUE;
			}
		}
		
		return FALSE;
	}
	
	protected static function _set_permissions( Model_User $user )
	{		
		self::$_permissions[$user->id] = array_flip($user->permissions());
	}
}