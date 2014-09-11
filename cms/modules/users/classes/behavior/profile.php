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
					'user_id' => '[0-9]+'
				),
				'method' => 'execute'
			),
			'/<username>' => array(
				'regex' => array(
					'username' => '[a-zA-Z\_]+'
				),
				'method' => 'execute'
			)
		);
	}
	
	public function execute()
	{
		$slug = $this->router()->param('username');

		$inner_page = Model_Page_Front::findBySlug($slug, $this->page());

		// Если не найдена внутрення страница по SLUG
		if($inner_page)
		{
			$this->_page = $inner_page;
			return;
		}
	}
}