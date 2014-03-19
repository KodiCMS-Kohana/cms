<?php defined('SYSPATH') or die('No direct access allowed.');

class Behavior_HybridDocs extends Behavior_Abstract
{
	
	public function routes()
	{
		return array(
			'/tag/<tag>' => array(
				'method' => 'execute'
			),
			'/<item>' => array(
				'method' => 'execute'
			),
		);
	}

	public function execute()
	{
		$slug = $this->router()->param('item');
		if( empty($slug) ) return;

		if(!empty($this->settings()->item_page_id))
			$this->_page = Model_Page_Front::findById($this->settings()->item_page_id);
		
		if(($this->_page = Model_Page_Front::findBySlug($slug, $this->page())) === FALSE )
		{
            Model_Page_Front::not_found();
		}
	}
}