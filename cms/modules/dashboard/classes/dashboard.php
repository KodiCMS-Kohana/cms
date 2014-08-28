<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * @package		KodiCMS
 * @category	Helpers
 * @author		ButscHSter
 */
class Dashboard {
	
	const WIDGET_BLOCKS_KEY = 'dashboard';
	const WIDGET_SETTINGS_KEY = 'dashboard_widget_settings';
	
	/**
	 * 
	 * @param string $widget_id
	 * @return \Model_Widget_Decorator_Dashboard|null
	 */
	public static function get_widget($id)
	{
		$widget_settings = Model_User_Meta::get(self::WIDGET_SETTINGS_KEY, array());
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
	public static function add_widget($type)
	{
		$widget_settings = Model_User_Meta::get(self::WIDGET_SETTINGS_KEY, array());
		
		$widget = Widget_Manager::factory($type);
		$widget->id = $type . '::' . Text::random(NULL, 3);
	
		$widget_settings[$widget->id] = $widget;
		Model_User_Meta::set(self::WIDGET_SETTINGS_KEY, $widget_settings);
		
		return $widget;
	}
	
	/**
	 * 
	 * @param string $id
	 * @param array $data
	 * @return boolean
	 */
	public static function update_widget($id, array $data)
	{
		$widget_settings = Model_User_Meta::get(self::WIDGET_SETTINGS_KEY, array());
		$widget = Arr::get($widget_settings, $widget_id);
		
		if($widget instanceof Model_Widget_Decorator_Dashboard)
		{
			$widget_settings[$widget_id] = $widget->set_values($data);
			Model_User_Meta::set(self::WIDGET_SETTINGS_KEY, $widget_settings);
			
			return TRUE;
		}

		return FALSE;
	}
	
	/**
	 * 
	 * @param string $id
	 * @return boolean
	 */
	public static function delete_widget($id)
	{
		$widget_settings = Model_User_Meta::get(self::WIDGET_SETTINGS_KEY, array());
		
		unset($widget_settings[$id]);
		Model_User_Meta::set(self::WIDGET_SETTINGS_KEY, $widget_settings);
		
		return TRUE;
	}
	
	/**
	 * 
	 * @param string $id
	 * @param string $column
	 * @return boolean
	 */
	public static function move_widget($id, $column)
	{
		$blocks =  Model_User_Meta::get(self::WIDGET_BLOCKS_KEY, array());
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
			Model_User_Meta::set(self::WIDGET_BLOCKS_KEY, $blocks);
			return TRUE;
		}
		
		return FALSE;
	}
}