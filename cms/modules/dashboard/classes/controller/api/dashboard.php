<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

class Controller_API_Dashboard extends Controller_System_Api {
	
	public function put_widget()
	{
		$widget_type = $this->param('widget_type', NULL, TRUE);
		$widget = Dashboard::add_widget($widget_type);
		$this->response((string) $widget->run());
	}
	
	public function delete_widget()
	{
		$widget_id = $this->param('id', NULL, TRUE);
		Dashboard::delete_widget($widget_id);
		$this->response(TRUE);
	}
	
	public function post_widget()
	{
		$widget_id = $this->param('id', NULL, TRUE);
		$settings = $this->params();

		$widget = Dashboard::update_widget($widget_id, $settings);

		if($widget !== NULL)
		{
			$this->response((string) $widget->run());
		}
	}
	
	public function get_widget_list()
	{
		$widget_settings = Model_User_Meta::get(Dashboard::WIDGET_SETTINGS_KEY, array());
		$types = Widget_Manager::map('dashboard');

		$attached_types = array();
	
		foreach ($widget_settings as $widget)
		{
			$attached_types[$widget->type] = $widget->is_multiple();
		}
		
		foreach ($types as $title => $subtypes)
		{
			foreach ($subtypes as $key => $label)
			{
				if(Arr::get($attached_types, $key) === FALSE)
				{
					unset($types[$title][$key]);
				}
			}
		}

		$this->json = (string) View::factory( 'dashboard/widgets', array(
			'types' => $types
		));
	}
	
	public function get_widget()
	{
		$widget_id = $this->param('id', NULL, TRUE);

		$widget = Dashboard::get_widget($widget_id);

		if ($widget === NULL)
		{
			$this->response(FALSE);
		}

		$this->response((string) View::factory('dashboard/widget_settings', array(
			'widget' => $widget
		)));
	}

}