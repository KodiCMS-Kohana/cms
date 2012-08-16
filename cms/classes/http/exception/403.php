<?php defined('SYSPATH') or die('No direct access allowed.');

class HTTP_Exception_403 extends HTTP_Exception {

	/**
	 * @var   integer    HTTP 403 Forbidden
	 */
	protected $_code = 403;

}