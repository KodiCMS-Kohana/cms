<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Controller_System_API extends Controller_System_Ajax {

	public $json = array();
	
	public $fields = array();
	
	protected $_params = array();

	public function before()
	{
		parent::before();
		
		$this->json['code'] = API::NO_ERROR;
		
		$this->fields = $this->param('fields');
		
		if( strpos($this->request->headers('content-type'), 'application/json') !== FALSE )
		{
			$this->request->post(json_decode($this->request->body(), TRUE));
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
		$param = Arr::get($this->params(), $key, $default);
		
		if($is_required === TRUE AND empty($param))
		{
			throw HTTP_API_Exception::factory(API::ERROR_MISSING_PAPAM, 'Missing param :key', array(
				':key' => $key ));
		}
		
		return $param;
	}
	
	public function params($new_params = NULL)
	{
		$this->_params = Arr::merge($this->request->query(), $this->request->post(), $this->request->param());
		
		if(is_array($new_params))
		{
			$this->_params = Arr::merge($this->_params, $new_params);
		}
		
		return $this->_params;
	}

	/**
	 * 
	 * @return Response
	 * @throws HTTP_API_Exception
	 */
	public function execute()
	{
		if( ! IS_BACKEND AND Setting::get('api_mode') == 'no')
		{
			throw new HTTP_Exception_403('Forbiden');
		}

		// Execute the "before action" method
		$this->before();

		if($this->request->action() == 'index' OR $this->request->action() == '')
		{
			$action = 'rest_'.$this->request->method();
		}
		else
		{
			// Determine the action to use
			$action = $this->request->method() . '_' . $this->request->action();
		}
		
		$action = strtolower($action);

		try 
		{
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
		catch (HTTP_API_Exception $e)
		{
			$this->json = $e->get_response();
		}
		catch (API_Validation_Exception $e)
		{
			$this->json = $e->get_response();
		}
		catch (Exception $e)
		{
			$this->json['code'] = $e->getCode();
			$this->json['message'] = $e->getMessage();
			$this->json['response'] = NULL;
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
			
			if( ! isset($this->json['response']) )
			{
				$this->json['response'] = NULL;
			}
		
			$this->json = json_encode( $this->json );
		}

		$this->response->body( $this->json );
	}
	
	public function json_redirect($uri)
	{
		$this->json['redirect'] = URL::backend($uri);
	}

	/**
	 * 
	 * @param mixed $data
	 */
	public function response($data)
	{
		$this->json['type'] = $this->request->method();
		$this->json['response'] = $data;
	}
}
