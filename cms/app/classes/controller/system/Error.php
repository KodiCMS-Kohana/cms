<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Controller_System_Error extends Controller_System_Controller {

	public $template = 'layouts/404';

	public function before()
	{
		parent::before();
		
		$this->template = View::factory( $this->template );

		$uri = URL::site( rawurldecode( Request::initial()->uri() ) );
		$this->template->message = __( 'Critical error' );
		$this->template->uri = $uri;

		if ( Request::initial() !== Request::current() )
		{
			if ( $message = rawurldecode( $this->request->param( 'message' ) ) )
			{
				$this->template->message = $message;
			}
		}
		
		$this->template->code = (int) $this->request->param( 'code' );
		$this->template->error_type = Arr::get(Response::$messages, $this->template->code, 'Not found' );
	}
	
	
	public function action_index()
	{
		
	}

	/**
	 * Assigns the template [View] as the request response.
	 */
	public function after()
	{
		parent::after();

		$this->response->body( $this->template );
	}
}
