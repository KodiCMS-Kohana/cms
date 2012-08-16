<?php defined('SYSPATH') or die('No direct access allowed.');

class HTTP_Exception_407 extends HTTP_Exception {

	/**
	 * @var   integer    HTTP 407 Proxy Authentication Required
	 */
	protected $_code = 407;

}