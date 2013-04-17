<?php defined('SYSPATH') or die('No direct access allowed.');

class Controller_System_Frontend extends Controller_System_Template
{
	public function before()
	{
		parent::before();

		if($this->auto_render === TRUE)
		{
			$this->styles = array(
				ADMIN_RESOURCES . 'libs/jgrowl/jquery.jgrowl.css',
				ADMIN_RESOURCES . 'libs/fancybox/jquery.fancybox.css',
				ADMIN_RESOURCES . 'css/common.css',
			);
			
			$this->scripts = array(
				ADMIN_RESOURCES . 'libs/jquery-1.9.1.min.js',
				ADMIN_RESOURCES . 'libs/underscore-min.js',
				ADMIN_RESOURCES . 'libs/backbone-min.js',
				ADMIN_RESOURCES . 'libs/bootstrap/js/bootstrap.min.js',
				ADMIN_RESOURCES . 'libs/jgrowl/jquery.jgrowl_minimized.js',
				ADMIN_RESOURCES . 'libs/fancybox/jquery.fancybox.pack.js',
				ADMIN_RESOURCES . 'js/backend.js',
			);
		}
	}
}