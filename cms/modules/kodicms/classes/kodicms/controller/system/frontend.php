<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS
 * @category	System Controller
 * @author		ButscHSter
 */
class KodiCMS_Controller_System_Frontend extends Controller_System_Template
{
	public function before()
	{
		parent::before();

		if ($this->auto_render === TRUE)
		{
			Assets::js('jquery', ADMIN_RESOURCES . 'libs/jquery.min.js');

			Assets::package(array('jquery-ui', 'backbone', 'notify', 'underscore', 'select2', 'core', 'bootstrap'));
		}
	}
}