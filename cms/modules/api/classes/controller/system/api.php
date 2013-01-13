<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Controller_System_API extends Controller_System_Ajax {

	public $json = array();

	public function before()
	{
		parent::before();
	}
	
	public function param($key, $default = NULL, $is_required = FALSE)
	{
		$param = Arr::get($_REQUEST, $key, $default);
		
		if($is_required === TRUE AND empty($param))
		{
			throw HTTP_Exception::factory(API::ERROR_MISSING_PAPAM, 'Missing param :key', array(
				':key' => $key ));
		}
		
		return $param;
	}
	
	public function execute()
	{
		// Execute the "before action" method
		$this->before();
			
		try 
		{
			// Determine the action to use
			$action = $this->request->method() . '_' . $this->request->action();

			// If the action doesn't exist, it's a 404
			if ( ! method_exists($this, $action))
			{
				throw HTTP_Exception::factory(404,
					'The requested method :method was not found on this server.',
					array(':method' => $this->request->controller() . '.' . $this->request->action())
				)->request($this->request);
			}

			// Execute the action itself
			$this->{$action}();
		}
		catch (Exception $e)
		{
			$this->json['error'] = array(
				'code' => $e->getCode(),
				'message' => $e->getMessage()
			);
			
		}
		
		// Execute the "after action" method
		$this->after();

		// Return the response
		return $this->response;
	}

	public function after()
	{
		if($this->request->query('debug') !== NULL)
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
	
	public function response($data)
	{
		$this->json['response'] = $data;
	}
}