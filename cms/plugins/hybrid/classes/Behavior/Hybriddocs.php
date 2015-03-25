<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS/Hybrid
 * @category	Behavior
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
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
		if (($this->_page = Model_Page_Front::find($slug, FALSE, $this->page())) === FALSE)
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