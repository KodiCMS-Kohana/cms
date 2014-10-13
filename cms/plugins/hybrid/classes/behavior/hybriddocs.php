<?php defined('SYSPATH') or die('No direct access allowed.');

class Behavior_HybridDocs extends Behavior_Abstract
{
	public function routes()
	{
		return array(
			'/<item>' => array(
				'regex' => array(
					'item' => '.*'
				),
				'method' => 'execute'
			),
		);
	}

	public function execute()
	{
		$slug = $this->router()->param('item');

		if (empty($slug))
		{
			return;
		}

		$item_page_id = $this->settings()->item_page_id;

		// Если не найдена внутрення страница по SLUG
		if (($this->_page = Model_Page_Front::findBySlug($slug, $this->page())) === FALSE)
		{
			// Производим поиск страницы которая укзана в настройках типа страницы
			if (!empty($item_page_id))
			{
				$this->_page = Model_Page_Front::findById($item_page_id);
				return;
			}

			Model_Page_Front::not_found();
		}
	}
}