<?php defined('SYSPATH') or die('No direct access allowed.');

class Behavior_Profile extends Behavior_Abstract
{
	/**
	 * 
	 * @return array
	 */
	public function routes()
	{
		return array(
			'/<user_id>' => array(
				'regex' => array(
					'id' => '[0-9]+'
				),
				'method' => 'execute'
			),
			'/<username>' => array(
				'regex' => array(
					'slug' => '[a-zA-Z\_]+'
				),
				'method' => 'execute'
			)
		);
	}
	
	public function execute(){}
}