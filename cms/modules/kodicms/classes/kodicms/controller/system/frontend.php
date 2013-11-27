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

		if($this->auto_render === TRUE)
		{
			Assets::js('jquery', ADMIN_RESOURCES . 'libs/jquery-2.0.3.min.js');
			
			Assets::js('underscore', ADMIN_RESOURCES . 'libs/underscore-min.js', 'jquery');
			Assets::js('backbone', ADMIN_RESOURCES . 'libs/backbone-min.js', 'underscore');
			
			Assets::js('bootstrap', ADMIN_RESOURCES . 'libs/bootstrap/js/bootstrap.min.js', 'jquery');
			
			Assets::css('fancybox', ADMIN_RESOURCES . 'libs/fancybox/jquery.fancybox.css', 'jquery');
			Assets::js('fancybox', ADMIN_RESOURCES . 'libs/fancybox/jquery.fancybox.pack.js', 'jquery');
			
			Assets::css('jgrowl', ADMIN_RESOURCES . 'libs/jgrowl/jquery.jgrowl.css', 'jquery');
			Assets::js('jgrowl', ADMIN_RESOURCES . 'libs/jgrowl/jquery.jgrowl_minimized.js', 'jquery');
			
			Assets::css('global', ADMIN_RESOURCES . 'css/common.css');
			Assets::js('global', ADMIN_RESOURCES . 'js/backend.js', 'backbone');
		}
	}
}