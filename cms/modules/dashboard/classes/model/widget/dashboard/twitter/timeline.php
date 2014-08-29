<?php defined('SYSPATH') or die('No direct access allowed.');

class Model_Widget_Dashboard_Twitter_Timeline extends Model_Widget_Decorator_Dashboard {	
	
	/**
	 *
	 * @var boolean
	 */
	protected $_multiple = TRUE;
	
	protected $_data = array(
		'height' => 250
	);
	
	public function set_height($height) 
	{
		return (int) $height;
	}
	
	public function set_widget_id($widget_id) 
	{
		return Valid::numeric($widget_id) ? $widget_id : NULL;
	}
	
	public function fetch_data(){}
}