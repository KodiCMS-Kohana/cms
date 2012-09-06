<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * The Controller class should be the parent class of all of your Controller sub classes
 * that contain the business logic of your application (render a blog post, log a user in,
 * delete something and redirect, etc).
 *
 * In the Frog class you can define what urls / routes map to what Controllers and
 * methods. Each method can either:
 *
 * - return a string response
 * - redirect to another method
 */
class Controller_System_Controller
{
	/**
	 * @var  Request  Request that created the controller
	 */
	public $request;

	/**
	 * @var  Response The response that will be returned from controller
	 */
	public $response;

	/**
	 * Creates a new controller instance. Each controller must be constructed
	 * with the request object that created it.
	 *
	 * @param   Request   $request  Request that created the controller
	 * @param   Response  $response The request's response
	 * @return  void
	 */
	public function __construct(Request $request, Response $response)
	{
		// Assign the request to the controller
		$this->request = $request;

		// Assign a response to the controller
		$this->response = $response;
	}

	/**
	 * Loads the template [View] object.
	 */
	public function before(){}

	/**
	 * Assigns the template [View] as the request response.
	 */
	public function after() {}
	
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
			'controller' => str_replace('admin/', '', Setting::get('default_tab')),
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