<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Controller_System_API extends Controller_System_Ajax {

	public $json = array();
	
	public $fields = array();

	public function before()
	{
		parent::before();
		
		$this->json['code'] = API::NO_ERROR;
		
		$this->fields = $this->param('fields');

		if($this->request->method() === Request::PUT)
		{
			$this->param('id', NULL, TRUE);
		}
	}
	
	/**
	 * 
	 * @param string $key
	 * @param mixed $default
	 * @param bool $is_required
	 * @return string
	 * @throws HTTP_API_Exception
	 */
	public function param($key, $default = NULL, $is_required = FALSE)
	{
		$params = Arr::merge($this->request->query(), $this->request->post());
		$param = Arr::get($params, $key, $default);
		
		if($is_required === TRUE AND empty($param))
		{
			throw HTTP_API_Exception::factory(API::ERROR_MISSING_PAPAM, 'Missing param :key', array(
				':key' => $key ));
		}
		
		return $param;
	}

	/**
	 * 
	 * @return Response
	 * @throws HTTP_API_Exception
	 */
	public function execute()
	{
		if(Setting::get('api_mode') != 'yes')
		{
			throw new HTTP_Exception_403('Forbiden');
		}

		// Execute the "before action" method
		$this->before();
			
		try 
		{
			// Determine the action to use
			$action = $this->request->method() . '_' . $this->request->action();

			// If the action doesn't exist, it's a 404
			if ( ! method_exists($this, $action))
			{
				throw HTTP_API_Exception::factory(API::ERROR_PAGE_NOT_FOUND,
					'The requested method :method was not found on this server.',
					array(':method' => $this->request->controller() . '.' . $this->request->action())
				)->request($this->request);
			}

			// Execute the action itself
			$this->{$action}();
		}
		catch (Exception $e)
		{
			$this->json['code'] = $e->getCode();
			$this->json['message'] = $e->getMessage();
			
		}
		
		// Execute the "after action" method
		$this->after();

		// Return the response
		return $this->response;
	}

	public function after()
	{
		if($this->param('debug') !== NULL)
		{
			$this->response->body( debug::vars($this->json) );
			return;
		}
		
		if ( is_array( $this->json ) )
		{
			$this->request->headers( 'Content-type', 'application/json' );
			$this->json = json_encode( $this->json );
		}

		$this->response->body( $this->json );
	}
	
	/**
	 * 
	 * @param mixed $data
	 */
	public function response($data)
	{
		$this->json['response'] = $data;
	}
}