<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

class Model_Navigation_Abstract {
	
	protected $params = array();


	public function __construct($data = array())
	{
		foreach ( $data as $key => $value )
		{
			$this->params[$key] = $value;
		}
	}

	public function is_active()
	{
		return Arr::get($this->params, 'is_active', FALSE);
	}
	
	public function name()
	{
		return __(Arr::get($this->params, 'name'));
	}
	
	public function url()
	{
		return Arr::get($this->params, 'url');
	}
	
	public function set_active($status = TRUE)
	{
		$this->params['is_active'] = (bool) $status;
		
		return $this;
	}
}