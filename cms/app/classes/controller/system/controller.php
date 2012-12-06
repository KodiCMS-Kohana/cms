<?php defined('SYSPATH') or die('No direct access allowed.');

class Controller_System_Controller extends Kohana_Controller
{
	public function execute()
	{
		// Execute the "before action" method
		$this->before();
		
		$action = 'action_'.$this->request->action();

		// If the action doesn't exist, it's a 404
		if ( ! method_exists($this, $action))
		{
			throw HTTP_Exception::factory(404,
				'The requested URL :uri was not found on this server.',
				array(':uri' => $this->request->uri())
			)->request($this->request);
		}

		// Execute the action itself
		$this->{$action}();

		// Execute the "after action" method
		$this->after();

		// Return the response
		return $this->response;
	}

	public function before()
	{
		parent::before();
		
		/*
		 * Set current lang
		 */
		I18n::lang( Setting::get( 'default_locale', I18n::detect_lang() ) );
		
		I18n::available_langs();
	}
	
	public function go_home()
	{
		if(IS_BACKEND)
		{
			$this->go_backend();
		}
		else
		{
			$this->go( Route::get( 'default' )->uri() );
		}
	}
	
	public function go_backend()
	{
		$this->go( Route::get( 'admin' )->uri(array(
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
			$url = Route::get( 'default' )->uri( $route );
		}

		$this->redirect( $url, $code );
	}
} // end Controller class