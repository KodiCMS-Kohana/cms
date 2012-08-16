<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Controller_System_Error extends Controller_System_Backend {

	public $template = 'system/error/layout';

	public function before()
	{
		parent::before();

		$uri = URL::site( rawurldecode( Request::initial()->uri() ) );
		$message = __( 'Critical error' );
		$this->template->content->page = $uri;

		if ( Request::initial() !== Request::current() )
		{
			if ( $message = rawurldecode( $this->request->param( 'message' ) ) )
			{
				$this->template->content->message = $message;
			}
		}

		$this->response->status( 404 );
		$this->template->content->action = 404;
	}
	
	public function action_index()
	{
		$this->ctx->page->title = __('Page not found');
	}
	
	public function action_404()
	{
		$this->ctx->page->title = __('Page not found');
	}

	public function action_500()
	{
		$this->ctx->page->title = __('Internal Server Error');
	}
}
