<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Controller_API_Dashboard extends Controller_System_Api {
	
	public function put_widget()
	{
		$widget_type = $this->param('widget_type', NULL, TRUE);
		
		$widget_settings = Model_User_Meta::get('dashboard_widget_settings', array());
		
		$widget = Widget_Manager::factory($widget_type);
		$widget->id = $widget_type . '::' . Text::random(NULL, 3);
	
		$widget_settings[$widget->id] = $widget;
		Model_User_Meta::set('dashboard_widget_settings', $widget_settings);
		
		$this->response((string) $widget->run());
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
		$widget_id = $this->param('widget_id', NULL, TRUE);
		$settings = $this->params();
		
		$widget = $this->_get_widget($widget_id);
		
		$widget_settings = Model_User_Meta::get('dashboard_widget_settings', array());
		$widget = Arr::get($widget_settings, $widget_id);
		
		if($widget instanceof Model_Widget_Decorator_Dashboard)
		{
			$widget_settings[$widget_id] = $widget->set_values($settings);
		}

		Model_User_Meta::set('dashboard_widget_settings', $widget_settings);
		$this->response((string) $widget->run());
	}
	
	public function get_widget_list()
	{
		$types = Widget_Manager::map('dashboard');

		$this->json = (string) View::factory( 'dashboard/widgets', array(
			'types' => $types
		));
	}
	
	public function get_widget()
	{
		$widget_id = $this->param('widget_id', NULL, TRUE);

		$widget = $this->_get_widget($widget_id);
		
		if ($widget === NULL)
		{
			$this->response(FALSE);
		}

		$this->response((string) View::factory('dashboard/widget_settings', array(
					'widget' => $widget
		)));
	}
	
	protected function _get_widget($widget_id)
	{
		$widget_settings = Model_User_Meta::get('dashboard_widget_settings', array());
		$widget = Arr::get($widget_settings, $widget_id);
		
		if (!($widget instanceof Model_Widget_Decorator_Dashboard))
		{
			return NULL;
		}

		return $widget;
	}
}