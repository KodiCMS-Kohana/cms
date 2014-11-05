<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * @package		KodiCMS/Dashboard
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright  (c) 2012-2014 butschster
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Dashboard {
	
	const WIDGET_BLOCKS_KEY = 'dashboard';
	const WIDGET_SETTINGS_KEY = 'dashboard_widget_settings';
	
	/**
	 * 
	 * @param string $widget_id
	 * @return \Model_Widget_Decorator_Dashboard|null
	 */
	public static function get_widget($id, $user_id = NULL)
	{
		$widget_settings = Model_User_Meta::get(self::WIDGET_SETTINGS_KEY, array(), $user_id);
		$widget = Arr::get($widget_settings, $id);
		
		if (!($widget instanceof Model_Widget_Decorator_Dashboard))
		{
			return NULL;
		}

		return $widget;
	}
	
	/**
	 * 
	 * @param string $type
	 * @return Model_Widget_Decorator_Dashboard
	 */
	public static function add_widget($type, array $data = NULL, $user_id = NULL)
	{
		$widget_settings = Model_User_Meta::get(self::WIDGET_SETTINGS_KEY, array(), $user_id);
		
		$widget = Widget_Manager::factory($type);
		$widget->id = uniqid();
	
		if($data !== NULL)
		{
			$widget->set_values($data);
		}
		
		$widget_settings[$widget->id] = $widget;
		Model_User_Meta::set(self::WIDGET_SETTINGS_KEY, $widget_settings, $user_id);
		
		return $widget;
	}
	
	/**
	 * 
	 * @param string $id
	 * @param array $data
	 * @return boolean
	 */
	public static function update_widget($id, array $data, $user_id = NULL)
	{
		$widget_settings = Model_User_Meta::get(self::WIDGET_SETTINGS_KEY, array(), $user_id);
		$widget = Arr::get($widget_settings, $id);
		
		if($widget instanceof Model_Widget_Decorator_Dashboard)
		{
			$widget_settings[$id] = $widget->set_values($data);
			Model_User_Meta::set(self::WIDGET_SETTINGS_KEY, $widget_settings, $user_id);
			
			return $widget;
		}

		return NULL;
	}
	
	/**
	 * 
	 * @param string $id
	 * @return boolean
	 */
	public static function delete_widget($id, $user_id = NULL)
	{
		$widget_settings = Model_User_Meta::get(self::WIDGET_SETTINGS_KEY, array(), $user_id);
		
		unset($widget_settings[$id]);
		Model_User_Meta::set(self::WIDGET_SETTINGS_KEY, $widget_settings, $user_id);
		
		return TRUE;
	}
	
	/**
	 * 
	 * @param string $id
	 * @param string $column
	 * @return boolean
	 */
	public static function move_widget($id, $column, $user_id = NULL)
	{
		$blocks =  Model_User_Meta::get(self::WIDGET_BLOCKS_KEY, array(), $user_id);
		$found = FALSE;

		foreach ($blocks as $_column => $ids)
		{
			foreach ($ids as $i => $_id)
			{
				if($_id = $id AND $_column != $column)
				{
					$found = TRUE;
					unset($blocks[$_column][$i]);
					break;
				}
			}
		}
		
		if($found === TRUE)
		{
			$blocks[$column][] = $id;
			Model_User_Meta::set(self::WIDGET_BLOCKS_KEY, $blocks, $user_id);
			return TRUE;
		}
		
		return FALSE;
	}
}