<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class KodiCMS_Controller_API_Dashboard extends Controller_System_Api {
	
	public function put_widget()
	{
		$widget_type = $this->param('widget_type', NULL, TRUE);
		
		$widget_settings = Model_User_Meta::get('dashboard_widget_settings', array());
		
		$widget = Widget_Manager::factory($widget_type);
		$widget->id = $widget_type . '::' . Text::random(NULL, 3);
		$widget->frontend_template_preffix = 'dashboard';
	
		$widget_settings[$widget->id] = $widget;
		Model_User_Meta::set('dashboard_widget_settings', $widget_settings);
		
		$this->response((string) $widget->run(array('comments' => FALSE, 'return' => TRUE)));
	}
	
	public function delete_widget()
	{
		$widget_id = $this->param('widget_id', NULL, TRUE);
		$widget_settings = Model_User_Meta::get('dashboard_widget_settings', array());
		
		unset($widget_settings[$widget_id]);
		Model_User_Meta::set('dashboard_widget_settings', $widget_settings);
		
		$this->response(TRUE);
	}
	
	public function post_widget()
	{
		$settings = $this->param('widget_settings', array(), TRUE);
		$widget_id = $this->param('widget_id', NULL, TRUE);
		
		$widget_settings = Model_User_Meta::get('dashboard_widget_settings', array());

		if(Arr::get($widget_settings, $widget_id) instanceof Model_Widget_Decorator)
		{
			$widget_settings[$widget_id] = $widget->set_values($settings);
		}

		Model_User_Meta::set('dashboard_widget_settings', $widget_settings);
		
		$this->response(TRUE);
	}
	
	public function get_widget_list()
	{
		$types = Widget_Manager::map('dashboard');

		$this->json = (string) View::factory( 'dashboard/widgets', array(
			'types' => $types
		));
	}
}