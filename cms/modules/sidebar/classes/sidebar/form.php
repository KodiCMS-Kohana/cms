<?php defined('SYSPATH') or die('No direct script access.');

/**
 * @package		KodiCMS/Sidebar
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Sidebar_Form {

	/**
	 *
	 * @var array 
	 */
	protected $_params = array(
		'action' => NULL,
		'enctype' => NULL,
		'method' => NULL,
	);

	/**
	 * 
	 * @param array $params
	 */
	public function __construct(array $params = array())
	{
		foreach ($this->_params as $key => $value)
		{
			$this->_params[$key] = Arr::get($params, $key, $value);
		}
	}
	
	/**
	 * 
	 * @return string
	 */
	public function action()
	{
		if ($this->_params['action'] instanceof Request)
		{
			// Use the current URI
			return $this->_params['action']->uri();
		}

		return $this->_params['action'];
	}
	
	/**
	 * 
	 * @return string
	 */
	public function method()
	{
		return $this->_params['method'];
	}

	/**
	 * 
	 * @return string
	 */
	public function render()
	{
		return Form::open($this->_params['action'], $this->_params);
	}
	
	/**
	 * 
	 * @return string
	 */
	public function __toString()
	{
		return (string) $this->render();
	}
}