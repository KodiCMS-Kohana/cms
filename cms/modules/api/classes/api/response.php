<?php defined('SYSPATH') or die('No direct script access.');

class API_Response {
	
	/**
	 *
	 * @var Response 
	 */
	protected $_response = NULL;

	/**
	 *
	 * @var integer 
	 */
	protected $_code = 200;

	/**
	 * 
	 * @param Response $response
	 */
	public function __construct(Response $response) 
	{
		$this->_response = $response;
		
		$data = $this->as_array();
		
		if( isset($data['code']) )
		{
			$this->_code = $data['code'];
		}
	}
	
	/**
	 * 
	 * @return array
	 */
	public function as_array()
	{
		return json_decode( $this->body(), TRUE );
	}
	
	/**
	 * 
	 * @return stdClass
	 */
	public function as_object()
	{
		return json_decode( $this->body() );
	}

	public function debug()
	{
		echo debug::vars($this->body());
	}
	
	/**
	 * 
	 * @return integer
	 */
	public function code()
	{
		return (int) $this->_code;
	}
	
	/**
	 * 
	 * @return mixed
	 */
	public function error()
	{
		if($this->status()) return NULL;

		$data = $this->as_array();
		
		return Arr::get($data, 'message');
	}
	
	/**
	 * 
	 * @return bool
	 */
	public function status()
	{
		return $this->code() == API::NO_ERROR;
	}
	
	/**
	 * 
	 * @return string
	 */
	public function body()
	{
		return $this->_response->body();
	}
	
	public function __toString() 
	{
		return $this->body();
	}
}