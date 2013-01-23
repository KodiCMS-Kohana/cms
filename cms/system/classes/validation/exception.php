<?php defined('SYSPATH') or die('No direct script access.');

class API_Validation_Exception extends Kohana_HTTP_Exception 
{
	public function __construct($errors, $message = 'Failed to validate array', array $values = NULL)
	{
		$this->_errors = $errors;
		parent::__construct($message, $values, 130);
	}
	
	public function get_response()
    {
		// Lets log the Exception, Just in case it's important!
		Kohana_Exception::log($this);

		$params = array
		(
			'code'  => API::ERROR_VALIDATION,
			'message' => rawurlencode($this->getMessage()),
			'errors' => $this->_errors,
			'response' => NULL
		);

		try
		{
			return json_encode($params);
		}
		catch ( Exception $e )
		{
			return parent::get_response();
		}
    }
}
