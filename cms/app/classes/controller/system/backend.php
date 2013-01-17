<?php defined('SYSPATH') or die('No direct access allowed.');

class Controller_System_Backend extends Controller_System_Template
{
	public $auth_required = array('administrator', 'developer', 'editor');
	
	/**
	 *
	 * @var Model_Navigation_Page 
	 */
	public $page;

	public function before()
	{
		$page = strtolower(substr(get_class($this), 11));
		
		Model_Navigation::get_section('System')
			->add_page(new Model_Navigation_Page(array(
				'name' => __('Settings'), 
				'url' => URL::site('setting')
			)), 100)
			->add_page(new Model_Navigation_Page(array(
				'name' => __('Users'), 
				'url' => URL::site('user')
			)), 101);
		
		Model_Navigation::get_section('Design')
			->add_page(new Model_Navigation_Page(array(
				'name' => __('Layouts'), 
				'url' => URL::site('layout'),
				'permissions' => array('administrator','developer')
			)), 102)
			->add_page(new Model_Navigation_Page(array(
				'name' => __('Snippets'), 
				'url' => URL::site('snippet'),
				'permissions' => array('administrator','developer')
			)), 103);
		
		Model_Navigation::get_section('Content')
			->add_page(new Model_Navigation_Page(array(
				'name' => __('Pages'), 
				'url' => URL::site('page'),
				'permissions' => array('administrator','developer','editor')
			)), 104);
		
		

		parent::before();
		$navigation = Model_Navigation::get();
		$this->page = Model_Navigation::$current;
		
		if( $this->page !== NULL AND ! AuthUser::hasPermission( $this->page->permissions ))
		{
			throw new HTTP_Exception_403('Access denied');
		}
		
		if($this->auto_render === TRUE)
		{
			$this->template->set_global(array(
				'page_body_id' => $this->get_path(),
				'page_name' => $page,
				'navigation' => $navigation,
				'page' => $this->page
			));
			
			$this->styles = array(
				ADMIN_RESOURCES . 'libs/jquery-ui/css/custom-theme/jquery-ui-1.8.16.custom.css',
				ADMIN_RESOURCES . 'libs/jgrowl/jquery.jgrowl.css',
				ADMIN_RESOURCES . 'css/common.css',
			);
			
			$this->scripts = array(
				ADMIN_RESOURCES . 'libs/jquery-1.8.2.min.js',
				ADMIN_RESOURCES . 'libs/underscore-min.js',
				ADMIN_RESOURCES . 'libs/backbone-min.js',
				ADMIN_RESOURCES . 'libs/jquery-ui/js/jquery-ui-1.8.23.custom.min.js',
				ADMIN_RESOURCES . 'libs/bootstrap/js/bootstrap.min.js',
				ADMIN_RESOURCES . 'libs/jgrowl/jquery.jgrowl_minimized.js',
				ADMIN_RESOURCES . 'js/backend.js',
				ADMIN_RESOURCES . 'js/controller/' . $this->request->controller() . '.js',
			);
		}
	}
}