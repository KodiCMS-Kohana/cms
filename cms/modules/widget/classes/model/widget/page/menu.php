<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS/Widgets
 * @category	Page
 * @author		ButscHSter
 */
class Model_Widget_Page_Menu extends Model_Widget_Decorator {
	
	public $exclude = array();
	
	public $cache_tags = array('pages');
	
	protected $_data = array(
		'page_level' => 0,
		'exclude' => array(),
		'match_all_paths' => FALSE,
		'include_hidden' => FALSE
	);

	public function backend_data()
	{
		$pages = Model_Page_Sitemap::get(TRUE);
		
		$select = array('-');
		foreach($pages->flatten() as $page)
		{
			$uri = !empty($page['uri']) ? $page['uri'] : '/';
			$select[$page['id']] = $page['title'] . ' (' . $uri . ')';
		}
		
		return array(
			'select' => $select,
			'pages' => $pages->flatten(),
		);
	}
	
	public function set_values(array $data)
	{
		if( empty( $data['exclude'] ))
		{
			$this->exclude = array();
		}

		parent::set_values($data);
		
		$this->match_all_paths = (bool) Arr::get($data, 'match_all_paths');
		$this->include_hidden = (bool) Arr::get($data, 'include_hidden');
		
		return $this;
	}
	
	public function set_page_level($level)
	{
		return (int) $level;
	}

	/**
	 * 
	 * @return array [$pages]
	 */
	public function fetch_data()
	{
		$pages = Model_Page_Sitemap::get( (bool) $this->include_hidden );
		
		if( ($page_id = $this->get_page_id()) !== NULL )
		{
			$pages->find($page_id);
		}

		$pages->exclude( $this->exclude );

		return array(
			'pages' => $pages->children()->as_array( $this->match_all_paths == 1 )
		);
	}
	
	public function get_page_id()
	{
		if($this->page_id > 1)
		{
			return $this->page_id;
		}
		else if($this->page_id == 0 AND ($page = $this->_ctx->get_page()) instanceof Model_Page_Front)
		{
			if($this->page_level > 0)
			{
				return $page->parent($this->page_level)->id();
			}

			return $page->id();
		}
		
		return NULL;
	}

	public function get_cache_id()
	{
		return 'Widget::' . $this->id . '::' . Request::current()->uri();
	}
	
	public function clear_cache()
	{
		$this->clear_cache_by_tags();

		return $this;
	}
}