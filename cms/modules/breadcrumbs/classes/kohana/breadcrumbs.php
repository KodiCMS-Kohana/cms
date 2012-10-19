<?php defined('SYSPATH') OR die('No direct script access!');

abstract class Kohana_Breadcrumbs implements Countable, Iterator, SeekableIterator, ArrayAccess {
	
	protected $options = array();
	
	protected $_current_key = 0;
	
	protected $_total_items = 0;
	
	protected $_items = array();
	
	/**
	 * 
	 * @param array $options
	 * @return \Breadcrumbs
	 */
	public static function factory($options = array())
	{
		return new Breadcrumbs($options);
	}
	
	public function __construct($options = array())
	{
		$this->options = Arr::merge(Kohana::$config->load('breadcrumbs')->get('default'), $options);
	}
	
	/**
	 * 
	 * @param string $name
	 * @param string $url
	 * @param integer $position
	 * @return \Breadcrumbs
	 */
	public function add($name, $url = FALSE, $position = NULL)
	{
		$item = new Breadcrumbs_Item($this->options['urls'], $name, $url);
		if(empty($position) || ! $this->offsetExists($position))
		{
			$position = $this->_total_items;
		}
		else
		{
			array_splice($this->_items, $position, 0, $item);
		}
		$this->_total_items++;
		$this->_items[$position] = $item;
		
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
		return $this->_total_items;
	}

	/**
	 * Implements [ArrayAccess::offsetExists], determines if row exists.
	 *
	 *     if (isset($result[10]))
	 *     {
	 *         // Row 10 exists
	 *     }
	 *
	 * @return  boolean
	 */
	public function offsetExists($offset)
	{
		return ($offset >= 0 AND $offset < $this->_total_items);
	}

	/**
	 * Implements [ArrayAccess::offsetGet], gets a given row.
	 *
	 *     $row = $result[10];
	 *
	 * @return  mixed
	 */
	public function offsetGet($offset)
	{
		if ( ! $this->seek($offset))
			return NULL;

		return $this->current();
	}

	/**
	 * Implements [ArrayAccess::offsetSet], throws an error.
	 *
	 * [!!] You cannot modify a database result.
	 *
	 * @return  void
	 * @throws  Kohana_Exception
	 */
	final public function offsetSet($offset, $value)
	{
		throw new Kohana_Exception('Breadcrumbs are read-only');
	}

	/**
	 * Implements [ArrayAccess::offsetUnset], throws an error.
	 *
	 * [!!] You cannot modify a database result.
	 *
	 * @return  void
	 * @throws  Kohana_Exception
	 */
	final public function offsetUnset($offset)
	{
		throw new Kohana_Exception('Breadcrumbs are read-only');
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
		return $this->_current_key;
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
		return $this->_items[$this->_current_key];
	}
	
	/**
	 * Implements [SeekableIterator::seek], changes the key to a position
	 * @param int $position The position to seek to.
	 * @return bool
	 */
	public function seek($position)
	{
		if($this->offsetExists($position))
		{
			$this->_current_key = $position;
			return true;
		}
		else
		{
			return false;
		}
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
		++$this->_current_key;
		return $this;
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
		return $this;
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
		$this->_current_key = 0;
		return $this;
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
		return $this->offsetExists($this->_current_key);
	}
	
	public function render()
	{
		return View::factory($this->options['view'], array(
			'breadcrumbs' => $this,
			'active_class' => $this->options['active_class'],
			'set_urls' => $this->options['urls']
		));
	}

	public function __toString()
	{
		return (string) $this->render();
	}
	
}
