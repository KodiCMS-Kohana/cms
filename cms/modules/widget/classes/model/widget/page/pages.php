<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS/Widgets
 * @category	Widget
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Model_Widget_Page_Pages extends Model_Widget_Decorator_Pagination {
	
	public $cache_tags = array('pages', 'page_parts', 'page_tags');
	
	public function set_values(array $data)
	{		
		$data['include_users_object'] = (bool) Arr::get($data, 'include_users_object');
		return parent::set_values($data);
	}
	
	public function on_page_load()
	{
		parent::on_page_load();

		$page = $this->get_current_page();

		if (!($page instanceof Model_Page_Front))
		{
			$this->_ctx->throw_404(__('Selected page in widget :widget_name not found', array(
				':widget_name' => $this->name
			)));
		}
	}
	
	public function backend_data()
	{
		$pages = Model_Page_Sitemap::get(TRUE);
		
		$select = array('-');
		foreach ($pages->flatten() as $page)
		{
			$uri = !empty($page['uri']) ? $page['uri'] : '/';
			$select[$page['id']] = $page['title'] . ' (' . $uri . ')';
		}

		return array(
			'select' => $select
		);
	}
	
	public function get_page()
	{
		return $this->_ctx->get_page();
	}
	
	public function get_page_id()
	{
		if ($this->page_id >= 1)
		{
			return $this->page_id;
		}
		else if ($this->page_id == 0 AND ( $page = $this->_ctx->get_page()) instanceof Model_Page_Front)
		{
			return $page->id;
		}

		return NULL;
	}
	
	/**
	 * 
	 * @return array [$pages]
	 */
	public function fetch_data()
	{
		$page = $this->get_current_page();

		$pages = array();

		if ($page instanceof Model_Page_Front)
		{
			$clause = array(
				'order_by' => array(array('page.created_on', 'desc'))
			);

			if ($this->list_offset > 0)
			{
				$clause['offset'] = $this->list_offset;
			}

			if ($this->list_size > 0)
			{
				$clause['limit'] = $this->list_size;
			}

			$pages = $page->children($clause);
		}

		if ($this->include_users_object)
		{
			$user_ids = array();
			$users = array();

			foreach ($pages as $page)
			{
				if (!empty($page->created_by_id))
				{
					$user_ids[] = $page->created_by_id;
				}

				if (!empty($page->updated_by_id))
				{
					$user_ids[] = $page->updated_by_id;
				}
			}

			$user_ids = array_unique($user_ids);

			if (!empty($user_ids))
			{
				$users = ORM::factory('user')->where('id', 'in', $user_ids)->find_all()->as_array('id');
			}

			foreach ($pages as $page)
			{
				$page->author = Arr::get($users, $page->created_by_id);
				$page->updator = Arr::get($users, $page->updated_by_id);
			}
		}

		return array(
			'pages' => $pages
		);
	}

	/**
	 * 
	 * @return integer
	 */
	public function count_total()
	{
		return $this->get_current_page()->children_count();
	}

	/**
	 * 
	 * @return Model_Page_Front
	 */
	public function get_current_page()
	{
		if (!$this->current_page)
		{
			$this->current_page = Model_Page_Front::findById($this->get_page_id());
		}

		return $this->current_page;
	}
	
	public function get_cache_id()
	{
		return 'Widget::' . $this->id . '::' . Request::current()->uri() . Request::current()->query('tag');
	}
	
	public function clear_cache()
	{
		$this->clear_cache_by_tags();

		return $this;
	}
}