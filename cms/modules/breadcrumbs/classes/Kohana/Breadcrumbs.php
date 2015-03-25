<?php defined('SYSPATH') OR die('No direct script access!');

/**
 * @package		KodiCMS/Breadcrumbs
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright  (c) 2012-2014 butschster
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
abstract class Kohana_Breadcrumbs implements Countable, Iterator {

	/**
	 *
	 * @var array 
	 */
	protected $options = array();

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
		$local_options = Kohana::$config->load('breadcrumbs')->get('default');
		$this->options = Arr::merge($local_options, $options);
	}

	/**
	 * 
	 * @return boolean
	 */
	public function is_last()
	{
		$items = $this->_items;
		return $this->current() === end($items);
	}
	
	/**
	 * 
	 * @return boolean
	 */
	public function is_first()
	{
		$items = $this->_items;
		return $this->current() === reset($items);
	}

	/**
	 * 
	 * @param string $name
	 * @param string $url
	 * @param integer $position
	 * @return \Breadcrumbs
	 */
	public function add($name, $url = FALSE, $is_active = NULL, $position = NULL, array $data = array())
	{
		if (!empty($name))
		{
			$item = new Breadcrumbs_Item($name, $url, $is_active, $data);

			$position = $this->_get_next_positon($position);
			$this->_items[$position] = $item;
		}

		return $this;
	}

	/**
	 * 
	 * @param string $key
	 * @param atring $value
	 * 
	 * return Breadcrumbs_Item
	 */
	public function get_by($key, $value)
	{
		$position = $this->find_by($key, $value);

		if ($position === NULL)
		{
			return NULL;
		}

		return $this->_items[$position];
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
			if (is_array($value))
			{
				if (in_array($item->$key, $value))
				{
					return $pos;
				}
			}
			else if ($item->$key == $value)
			{
				return $pos;
			}
		}

		return NULL;
	}

	/**
	 * 
	 * @param string $name
	 * @param string $url
	 * @param integer $position
	 * @return \Breadcrumbs
	 */
	public function change_by($key, $value, $url = FALSE, $is_active = NULL, $position = NULL, array $data = array())
	{
		$item = $this->get_by($key, $value);
		if ($item === NULL)
		{
			return FALSE;
		}

		$item->url = $url;

		if ($is_active !== NULL)
		{
			$item->active = (bool) $is_active;
		}

		if (!empty($data))
		{
			$item->set($data);
		}

		if ($position !== NULL)
		{
			$position = $this->_get_next_positon($position);
			$this->_items[$position] = $item;
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
		return $this->delete_by('name', $name);
	}

	/**
	 * 
	 * @param string $key
	 * @param string $value
	 * @return boolean|\Kohana_Breadcrumbs
	 */
	public function delete_by($key, $value)
	{
		$position = $this->find_by($key, $value);

		if ($position === NULL)
		{
			return FALSE;
		}

		unset($this->_items[$position]);
		return $this;
	}

	/**
	 * 
	 * @param integer $position
	 * @return integer
	 */
	protected function _get_next_positon($position = NULL)
	{
		$position = (int) $position;
		while (isset($this->_items[$position]))
		{
			$position++;
		}

		return $position;
	}

	protected function _sort()
	{
		ksort($this->_items);
		return $this;
	}

	/**
	 * 
	 * @return  integer
	 */
	public function count()
	{
		return count($this->_items);
	}


	public function rewind()
	{
		reset($this->_items);
	}

	public function current()
	{
		$item = current($this->_items);
		return $item;
	}

	/**
	 * 
	 * @return integer
	 */
	public function key() 
	{
		$item = key($this->_items);
		return $item;
	}

	/**
	 * 
	 * @return Breadcrumbs_Item
	 */
	public function next() 
	{
		$item = next($this->_items);
		return $item;
	}

	/**
	 * 
	 * @return boolean
	 */
	public function valid()
	{
		$key = key($this->_items);
		return ($key !== NULL AND $key !== FALSE);
	}

	/**
	 * 
	 * @return View
	 */
	public function render()
	{
		$this->_sort();

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