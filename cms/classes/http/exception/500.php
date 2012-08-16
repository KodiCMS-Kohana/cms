<?php defined('SYSPATH') or die('No direct access allowed.');

class HTTP_Exception_500 extends HTTP_Exception {

	/**
	 * @var   integer    HTTP 500 Internal Server Error
	 */
	protected $_code = 500;

}