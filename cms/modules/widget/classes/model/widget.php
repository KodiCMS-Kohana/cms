<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * @package    Kodi
 */
class Model_Widget extends ORM {

	protected $_reload_on_wakeup = FALSE;
	
	protected $_created_column = array(
		'format' => 'Y-m-d H:i:s',
		'column' => 'created_on'
	);


	public function rules() 
	{
		return array(
			'name' => array(
				array('not_empty'),
				array('min_length', array(':value', 2)),
				array('max_length', array(':value', 100))
			)
		);
	}
	
	public function type()
	{
		$widget_types = Widget_Manager::map();
		
		$type = $this->type;

		foreach($widget_types as $group => $types)
		{
			if(isset($types[$type])) $type = $types[$type];
		}
		
		return $type;
	}
}