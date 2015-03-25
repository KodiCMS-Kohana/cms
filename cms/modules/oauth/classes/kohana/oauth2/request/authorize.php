<?php defined('SYSPATH') OR die('No direct access allowed.');

abstract class Kohana_OAuth2_Request_Authorize extends OAuth2_Request {

	protected $name = 'authorize';

	public function execute(array $options = NULL)
	{
		return Request::current()->redirect($this->as_url());
	}

}
