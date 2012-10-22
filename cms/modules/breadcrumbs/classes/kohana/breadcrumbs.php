<?php defined('SYSPATH') OR die('No direct script access!');

abstract class Kohana_Breadcrumbs implements Countable, Iterator, SeekableIterator, ArrayAccess {
	
	/**
	 *
	 * @var array 
	 */
	protected $options = array();
	
	/**
	 *
	 * @var integer
	 */
	protected $_current_key = 0;
	
	/**
	 *
	 * @var integer
	 */
	protected $_total_items = 0;
	
	/**
	 *
	 * @var array
	 */
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
	
	/**
	 * 
	 * @param array $options
	 */
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
		
		$position = $this->_set_positon($position);
		
		$this->_total_items++;
		$this->_items[$position] = $item;
		
		return $this;
	}

	/**
	 * 
	 * @param string $name
	 * @param string $url
	 * @param integer $position
	 * @return \Breadcrumbs
	 */
	public function change($name, $url = FALSE, $new_position = NULL)
	{
		$position = $this->find_by( 'name', $name );
		if($position === NULL)
		{
			return FALSE;
		}
		
		$item = $this->_items[$position];
		
		$item->url = $url;
		
		if($new_position !== NULL)
		{
			$new_position = $this->_set_positon($new_position);
			$this->_items[$new_position] = $item;
	
			unlink($this->_items[$position]);
		}
		
		return $this;
	}
	
	/**
	 * 
	 * @param string $name
	 * @return \Breadcrumbs
	 */
	public function delete($name)
	{
		$position = $this->find_by( 'name', $name );
		if($position === NULL)
		{
			return FALSE;
		}
		
		unlink($this->_items[$position]);
		return $this;
	}

	/**
	 * 
	 * @param integer $position
	 * @return integer
	 */
	protected function _set_positon($position = NULL)
	{
		$position = (int) $position;
		if(empty($position) || ! $this->offsetExists($position))
		{
			$position = $this->_total_items;
		}
		else
		{
			while(isset($this->_items[$position]))
			{
				$position++;
			}
		}
		
		return $position;
	}

	/**
	 * 
	 * @param string $key
	 * @param string $value
	 * @return integer|NULL
	 */
	public function find_by($key, $value)
	{
		foreach ($this->_items as $pos => $item)
		{
			if($item->$key == $value)
			{
				return $pos;
			}
		}
		
		return NULL;
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
