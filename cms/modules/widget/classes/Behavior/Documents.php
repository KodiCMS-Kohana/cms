<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS/Widgets
 * @category	Behavior
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Behavior_Documents extends Behavior_Abstract
{
	/**
	 * 
	 * @return array
	 */
	public function routes()
	{
		return array(
			'/<id>' => array(
				'regex' => array(
					'id' => '[0-9]+'
				),
				'method' => 'page_by_id'
			),
			'/<slug>' => array(
				'regex' => array(
					'slug' => '.*'
				),
				'method' => 'page_by_slug'
			)
		);
	}
	
	public function page_by_id()
	{
		if (!$this->router()->param('id'))
		{
			return FALSE;
		}

		return $this->execute();
	}
	
	public function page_by_slug()
	{
		if (!$this->router()->param('slug'))
		{
			return FALSE;
		}

		if (($page = Model_Page_Front::find($slug, FALSE, $this->page())) === FALSE)
		{
			return $this->execute();
		}

		$this->_page = $page;
	}

	public function execute()
	{
		$page_id = $this->__get_item_page_id();

		if (empty($page_id) OR $this->router()->matched_route() === NULL)
		{
			return FALSE;
		}

		$this->_page = Model_Page_Front::findById($page_id);
	}
	
	/**
	 * 
	 * @return integer
	 */
	protected function __get_item_page_id()
	{
		return (int) $this->settings()->item_page_id;
	}
}