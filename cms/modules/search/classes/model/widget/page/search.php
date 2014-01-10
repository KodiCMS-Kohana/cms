<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS/Widgets
 * @category	Page
 * @author		ButscHSter
 */
class Model_Widget_Page_Search extends Model_Widget_Decorator_Pagination {
	
	public $cache_tags = array('pages');
	
	protected $_total = 0;

	public function fetch_data()
	{
		$keyword = $this->_ctx->get($this->search_key);

		$return = array(
			'total_found' => 0,
			'pages' => array(),
			'keyword', HTML::chars($keyword)
		);

		if(empty($keyword)) return $return;
		
		$ids = Search::by_keyword($keyword, FALSE, 'pages', $this->list_size, $this->list_offset);

		if(empty($ids['pages'])) return array('pages' => array());
		
		$pages = array();
		foreach ($ids['pages'] as $id)
		{
			if(($page = Model_Page_Front::findById($id)) !== FALSE )
			{
				$pages[$id] = $page;
			}
		}
		
		$return['total_found'] = $this->_total;
		$return['pages'] = $pages;

		return $return;
	}
	
	/**
	 * 
	 * @return integer
	 */
	public function count_total()
	{
		$keyword = $this->_ctx->get($this->search_key);
		$this->_total = Search::count_by_keyword($keyword, FALSE, 'pages');
		
		return $this->_total;
	}
}