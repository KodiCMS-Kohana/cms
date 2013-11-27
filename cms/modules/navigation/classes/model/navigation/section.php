<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * @package		KodiCMS/Navigation
 * @category	Model
 * @author		ButscHSter
 */
class Model_Navigation_Section extends Model_Navigation_Abstract implements Countable, Iterator {
	
	/**
	 *
	 * @var array
	 */
	protected $_pages = array();
	
	/**
	 *
	 * @var integer
	 */
	protected $_current_key = 0;
	
	/**
	 * 
	 * @param array $options
	 * @return Model_Navigation_Section
	 */
	public static function factory($data = array())
	{
		return new Model_Navigation_Section($data);
	}
	
	/**
	 * 
	 * @return string
	 */
	public function id()
	{
		return Arr::get($this->_params, 'name');
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
	public function add_page( Model_Navigation_Page $page, $priority = 0)
	{
		$priority = (int) $priority;
		
		if(isset($page->priority))
		{
			$priority = (int) $page->priority;
		}
		
		if ( isset( $this->_pages[$priority] ) )
		{
			while ( isset( $this->_pages[$priority] ) )
			{
				$priority++;
			}
		}
		
		$page->set_section($this);
		$this->_pages[$priority] = & $page;

		return $this
			->update()
			->sort_pages();
	}
	
	public function & find_page_by_uri( $uri )
	{
		$_page = NULL;
		
		foreach ($this->get_pages() as $page)
		{
			if($page->url() == $uri)
			{
				return $page;
			}
		}
		
		return $_page;
	}
	
	public function update()
	{
		$this->counter = 0;
		$this->permissions = array();

		foreach ($this->get_pages() as $page)
		{
			$this->counter += (int) $page->counter;
		}
		
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
	
	/**
	 * Implements [Countable::count], returns the total number of rows.
	 *
	 *     echo count($result);
	 *
	 * @return  integer
	 */
	public function count()
	{
		return count($this->_pages);
	}

	/**
	 * Implements [Iterator::key], returns the current row number.
	 *
	 *     echo key($result);
	 *
	 * @return  integer
	 */
	public function key()
	{
		return key($this->_pages);
	}
	
	/**
	 * Implements [Iterator::key], returns the current breadcrumb item.
	 *
	 *     echo key($result);
	 *
	 * @return  integer
	 */
	public function current()
	{
		return current($this->_pages);
	}

	/**
	 * Implements [Iterator::next], moves to the next row.
	 *
	 *     next($result);
	 *
	 * @return  $this
	 */
	public function next()
	{
		next($this->_pages);
	}

	/**
	 * Implements [Iterator::prev], moves to the previous row.
	 *
	 *     prev($result);
	 *
	 * @return  $this
	 */
	public function prev()
	{
		--$this->_current_key;
	}

	/**
	 * Implements [Iterator::rewind], sets the current row to zero.
	 *
	 *     rewind($result);
	 *
	 * @return  $this
	 */
	public function rewind()
	{
		reset($this->_pages);
	}

	/**
	 * Implements [Iterator::valid], checks if the current row exists.
	 *
	 * [!!] This method is only used internally.
	 *
	 * @return  boolean
	 */
	public function valid()
	{
		$key = key($this->_pages);
		return ($key !== NULL AND $key !== FALSE);
	}
}