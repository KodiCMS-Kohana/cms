<?php defined( 'SYSPATH' ) or die( 'No direct script access.' );

/**
 * @package		KodiCMS/Dashboard
 * @category	API
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Controller_API_Dashboard extends Controller_System_Api {
	
	public function put_widget()
	{
		$widget_type = $this->param('widget_type', NULL, TRUE);
		$widget = Dashboard::add_widget($widget_type);
		
		if (!empty($widget->media_packages))
		{
			$this->json['media'] = Assets_Package::get_scripts($widget->media_packages);
		}
		
		$this->json['size'] = $widget->size();
		$this->json['id'] = $widget->id;

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

		if ($widget !== NULL)
		{
			$this->json['update_settings'] = $widget->is_update_settings_page();
			$this->response((string) $widget->run());
		}
	}
	
	public function get_widget_list()
	{
		$widget_settings = Model_User_Meta::get(Dashboard::WIDGET_SETTINGS_KEY, array());
		$types = Widget_Manager::map('dashboard_widgets');

		$attached_types = array();
	
		foreach ($widget_settings as $widget)
		{
			$attached_types[$widget->type()] = $widget->is_multiple();
		}
		
		foreach ($types as $key => $data)
		{
			if (Arr::get($attached_types, $key) === FALSE)
			{
				unset($types[$key]);
			}
		}

		$this->json = (string) View::factory('dashboard/widgets', array(
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
	
	public function rest_delete()
	{
		if (!ACL::check('dshboard.empty'))
		{
			throw HTTP_API_Exception::factory(API::ERROR_PERMISSIONS, 'You don\'t have permission to :permission', array(
				':permission' => __('Empty dashboard')
			));;
		}

		Dashboard::remove_data();
		Cache::register_shutdown_function();

		Kohana::$log->add(Log::INFO, ':user empty dashboard')->write();

		$this->message('Dashboard is empty!');
	}
}