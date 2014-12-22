<?php defined('SYSPATH') or die('No direct script access.');

/**
 * @package		KodiCMS/Assets
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright  (c) 2012-2014 butschster
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Assets_Package implements Iterator {
	
	protected static $_list = array();

	/**
	 * Добавление пакета
	 * 
	 * @param string $name
	 * @param array $sources
	 * @return \Assets_Package
	 */
	public static function add($name)
	{
		return new Assets_Package($name);
	}
	
	/**
	 * Загрузка пакета
	 * 
	 * @param string $name
	 * @return \Assets_Package|NULL
	 */
	public static function load($name)
	{
		return Arr::get(Assets_Package::$_list, $name);
	}
	
	/**
	 * Получение списка всех пакетов
	 * 
	 * @return array
	 */
	public static function get_all()
	{
		return Assets_Package::$_list;
	}
	
	/**
	 * 
	 * @return array
	 */
	public static function select_choises()
	{
		$options = array_keys(Assets_Package::$_list);
		return array_combine($options, $options);
	}

	/**
	 * 
	 * @var string 
	 */
	protected $_handle = NULL;


	/**
	 *
	 * @var array 
	 */
	protected $_data = array();
	
	/**
	 *
	 * @var integer 
	 */
	private $position = 0;

	/**
	 * 
	 * @param string $name
	 * @param array $sources
	 */
	public function __construct($handle)
	{
		$this->_handle = $handle;
		Assets_Package::$_list[$handle] = $this;
	}
	
	/**
	 * 
	 * @return array
	 */
	public function css($handle = NULL, $src = NULL, $deps = NULL, $attrs = NULL)
	{
		if ($handle === NULL)
		{	
			$handle = $this->_handle;
		}
		
		// Set default media attribute
		if ( ! isset($attrs['media']))
		{
			$attrs['media'] = 'all';
		}
		
		$this->_data[] = array(
			'type'	=> 'css',
			'src'   => $src,
			'deps'  => (array) $deps,
			'attrs' => $attrs,
			'handle' => $handle,
			'type' => 'css'
		);
		
		return $this;
	}

	/**
	 * 
	 * @return array
	 */
	public function js($handle = FALSE, $src = NULL, $deps = NULL, $footer = FALSE)
	{
		if ($handle === NULL)
		{
			$handle = $this->_handle;
		}
		
		$this->_data[] = array(
			'type'	=> 'js',
			'src'    => $src,
			'deps'   => (array) $deps,
			'footer' => $footer,
			'handle' => $handle,
			'type' => 'js'
		);
		
		return $this;
	}

	public function __toString()
	{
		$string = '';

		foreach ($this->_data as $item)
		{
			switch($item['type'])
			{
				case 'css':
					$string .= HTML::style($item['src'], $item['attrs']);
					break;
				case 'js':
					$string .= HTML::script($item['src']);
					break;
			}
		}
		
		return $string;
	}
	
	function rewind()
	{
		$this->position = 0;
	}
	
	function current() 
	{
		return $this->_data[$this->position];
	}
	
	function key()
	{
		return $this->position;
	}
	
	function next() 
	{
		++$this->position;
	}
	
	function valid() 
	{
		return isset($this->_data[$this->position]);
	}
}