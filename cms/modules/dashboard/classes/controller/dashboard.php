<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * @package		KodiCMS
 * @category	Controller
 * @author		ButscHSter
 */
class Controller_Dashboard extends Controller_System_Backend {
	
	public function action_index()
	{
		Assets::package('jquery-ui');
		
		$widgets_array = Model_User_Meta::get('dashboard', array());
		$widget_settings = Model_User_Meta::get('dashboard_widget_settings', array());
		
		foreach ($widgets_array as $column => $widgets)
		{
			foreach ($widgets as $i => $widget)
			{
				$widget_object = Arr::get($widget_settings, $widget);
				if (!($widget_object instanceof Model_Widget_Decorator_Dashboard))
				{
					unset($widgets_array[$column][$i]);
					continue;
				}

				$widgets_array[$column][$i] = $widget_object;
			}
		}
		
		$this->set_title(__('Dashboard'), FALSE);

		$this->template->content = View::factory('dashboard/index', array(
			'widgets' => $widgets_array,
			'columns' => array(
				'left' => 'col-sm-6 col-lg-4',
				'center' => 'col-sm-6 col-lg-4',
				'right' => 'col-sm-12 col-md-12 col-lg-4',
				'bottom' => 'col-sm-12',
			)
		));
	}
}