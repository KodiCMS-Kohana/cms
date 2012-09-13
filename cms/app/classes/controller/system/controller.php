<?php defined('SYSPATH') or die('No direct access allowed.');

class Controller_System_Controller extends Kohana_Controller
{
	
	public function go_home()
	{
		if(IS_BACKEND)
		{
			$this->go_backend();
		}
		else
		{
			$this->go( Route::url( 'default' ) );
		}
	}
	
	public function go_backend()
	{
		$this->go( Route::url( 'admin', array(
			'controller' => str_replace(ADMIN_DIR_NAME . '/', '', Setting::get('default_tab')),
		) ) );
	}

	public function go_back()
	{
		if ( Valid::url( $this->request->referrer() ) )
		{
			$this->go( $this->request->referrer() );
		}
	}

	public function go( $url = NULL, $code = 302 )
	{
		$route = array(
			'controller' => $this->request->controller()
		);

		if ( is_array( $url ) )
		{
			$route = array_merge( $route, $url );
		}

		if ( $url === NULL OR is_array( $url ) )
		{
			$url = Route::url( 'default', $route );
		}

		$this->request->redirect( $url, $code );
	}
} // end Controller class