<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Controller_System_Security extends Controller_System_Controller {
	
	public $secure_actions = FALSE;
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

				throw new HTTP_Exception_403( 'У вас нет прав доступа к текущей странице' );
			}
			else
			{
				$this->go( Route::url( 'user', array(
					'action' => 'login',
					'next_url' => rawurldecode( Request::current()->uri() )
				) ) );
			}
		}
	}
	
	public function role( $role )
	{
		if( ! AuthUser::isLoggedIn())
		{
			return FALSE;
		}

		return AuthUser::hasPermission( $role );
	}
	
}