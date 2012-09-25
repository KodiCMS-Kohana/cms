<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

class Model_Navigation_Section extends Model_Navigation_Abstract {
	
	protected $_pages = array();
	
	public function id()
	{
		return Arr::get($this->params, 'name');
	}
		
	public function get_pages()
	{
		return $this->_pages;
	}

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
	
	public function sort_pages()
	{
		ksort($this->_pages);
		
		return $this;
	}
}