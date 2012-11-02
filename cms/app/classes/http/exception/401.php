<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class HTTP_Exception_401 extends Kohana_HTTP_Exception_401 
{

	/**
	* Generate a Response for the 401 Exception.
	* 
	* The user should be redirect to a login page.
	* 
	* @return Response
	*/
	public function get_response()
	{
		$response = Response::factory()
			->status(401)
			->headers('Location', Route::get( 'user' )->uri( array(
				'action' => 'login',
				'next_url' => rawurldecode( Request::current()->uri() )
			) ));
	
		return $response;
	}
}