<?php defined('SYSPATH') or die('No direct access allowed.');

class Controller_System_Backend extends Controller_System_Template
{
	protected static $_init = FALSE;

	public $auth_required = array('administrator', 'developer', 'editor');

	public function before()
	{
		$page = strtolower(substr(get_class($this), 11));

		if(!self::$_init) 
		{
			Model_Navigation::add_section('Content',  __('Pages'),    'page',    array('administrator','developer','editor'), 100);
			Model_Navigation::add_section('Design',   __('Layouts'),  'layout',  array('administrator','developer'), 100);
			Model_Navigation::add_section('Design',   __('Snippets'), 'snippet', array('administrator','developer'), 100);
			Model_Navigation::add_section('Settings', __('General'),  'setting', array('administrator'), 100);
			Model_Navigation::add_section('Settings', __('Plugins'),  'plugins', array('administrator'), 100);
			Model_Navigation::add_section('Settings', __('Users'),    'user',    array('administrator'), 100);
			
			self::$_init = TRUE;
		}
		
		$controller = $this->request->controller();
		$action = $this->request->action();
		$params = $this->request->param();

		parent::before();
		
		if($this->auto_render === TRUE)
		{
			$this->template->set_global(array(
				'page_body_id' => $controller .'_'. $action . ($controller == 'plugin' ? '_'. (empty($params) ? 'index' : $params['id']) : ''),
				'page_name' => $page,
				'controller' => $controller,
				'action' => $action,
				'params' => $params
			));
			
			$this->template->styles = array(
				ADMIN_RESOURCES . 'libs/bootstrap/css/bootstrap.css',
				ADMIN_RESOURCES . 'stylesheets/backend.css',
				ADMIN_RESOURCES . 'libs/jquery-ui/jquery-ui-1.8.12.css',
				ADMIN_RESOURCES . 'libs/jgrowl/jquery.jgrowl.css'
			);
			
			$this->template->scripts = array(
				ADMIN_RESOURCES . 'libs/jquery-1.7.2.min.js',
				ADMIN_RESOURCES . 'libs/jquery-ui/jquery-ui-1.8.12.js',
				ADMIN_RESOURCES . 'libs/bootstrap/js/bootstrap.min.js',
				ADMIN_RESOURCES . 'libs/jgrowl/jquery.jgrowl_minimized.js',
				ADMIN_RESOURCES . 'javascripts/backend.js'
			);
		}
	}
}