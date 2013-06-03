<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class KodiCMS_Controller_System_Security extends Controller_System_Controller 
{
	/**
	 *
	 * @var array
	 */
	public $secure_actions = FALSE;
	
	/**
	 *
	 * @var mixed 
	 */
	public $auth_required = FALSE;
	
	public function before()
	{
		parent::before();

		$action_name = $this->request->action();

		if ( (
				$this->auth_required !== FALSE
				AND
				$this->role( $this->auth_required ) === FALSE
			)
			OR
			(
				is_array( $this->secure_actions )
				AND
				array_key_exists( $action_name, $this->secure_actions )
				AND
				$this->role( $this->secure_actions[$action_name] ) === FALSE
			) )
		{
			if ( AuthUser::isLoggedIn() OR $this->request->is_ajax() )
			{
				// Forbidden / Model_Permission Deined
				throw HTTP_Exception::factory(403);
			}
			else
			{
				// Unauthorized / Login Requied
				throw HTTP_Exception::factory(401);
			}
		}
	}
	
	/**
	 * 
	 * @param mixed $role
	 * @return boolean
	 */
	public function role( $role )
	{
		if( ! AuthUser::isLoggedIn())
		{
			return FALSE;
		}

		return AuthUser::hasPermission( $role );
	}
	
}