<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * @package    Kodi/Navigation
 */
class Model_Navigation_Section extends Model_Navigation_Abstract {
	
	/**
	 *
	 * @var array
	 */
	protected $_pages = array();
	
	/**
	 * 
	 * @return string
	 */
	public function id()
	{
		return Arr::get($this->params, 'name');
	}
	
	/**
	 * 
	 * @return array
	 */
	public function get_pages()
	{
		return $this->_pages;
	}

	/**
	 * 
	 * @param Model_Navigation_Page $page
	 * @param integer $priority
	 * @return \Model_Navigation_Section
	 */
	public function add_page(  Model_Navigation_Page $page, $priority = 0 )
	{
		$priority = (int) $priority;
		
		if ( isset( $this->_pages[$priority] ) )
		{
			while ( isset( $this->_pages[$priority] ) )
			{
				$priority++;
			}
		}
		
		$page->set_section($this);
		
		$this->_pages[$priority] = $page;
		
		$this->sort_pages();
		
		return $this;
	}
	
	/**
	 * 
	 * @return \Model_Navigation_Section
	 */
	public function sort_pages()
	{
		ksort($this->_pages);
		
		return $this;
	}
}