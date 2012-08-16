<?php defined('SYSPATH') or die('No direct access allowed.');

class HTTP_Exception_401 extends HTTP_Exception {

	/**
	 * @var   integer    HTTP 401 Unauthorized
	 */
	protected $_code = 401;

}