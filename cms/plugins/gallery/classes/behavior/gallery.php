<?php defined('SYSPATH') or die('No direct access allowed.');

class Behavior_Gallery extends Behavior_Abstract
{
	protected $_routes = array(
		'/<category_id>/<photo_id>' => array(
			'method' => 'category',
			'regex' => array(
				'category_id' => '[0-9]+'
			)
		),
		'/<category_id>' => array(
			'method' => 'category',
			'regex' => array(
				'category_id' => '[0-9]+',
			)
		),
		'' => array(),
	);
	
	public function execute() 
	{
		
	}
	
	public function category()
	{
		
	}
	
}