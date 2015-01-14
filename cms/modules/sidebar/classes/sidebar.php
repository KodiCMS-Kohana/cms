<?php defined('SYSPATH') or die('No direct script access.');

/**
 * @package		KodiCMS/Sidebar
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Sidebar {

	/**
	 *
	 * @var array 
	 */
	protected $_fields = array();
	
	/**
	 *
	 * @var array 
	 */
	protected $_data = array();
	
	/**
	 *
	 * @var Sidebar_Form
	 */
	protected $_form = NULL;
	
	/**
	 *
	 * @var string 
	 */
	protected $_template = 'sidebar/template';

	/**
	 * 
	 * @param array $fields
	 * @return \Sidebar
	 */
	public function factory($fields = array())
	{
		return new Sidebar($fields);
	}

	/**
	 * 
	 * @param array $fields
	 * @throws Kohana_Exception
	 */
	public function __construct(array $fields = array()) 
	{
		$this->_fields = $fields;
		$this->_build();
	}

	protected function _build()
	{
		foreach ($this->_fields as $field)
		{
			if ($field instanceof Sidebar_Form)
			{
				$this->_form = $field;
				continue;
			}

			if (!($field instanceof Sidebar_Fields_Abstract))
			{
				continue;
			}

			$this->_data[] = $field;
		}

		if ($this->_form === NULL)
		{
			$this->_form = new Sidebar_Form(array(
				'action' => Request::current(),
				'method' => Request::GET
			));
		}
	}
	
	/**
	 * 
	 * @param string $template
	 */
	public function set_template($template)
	{
		$this->_template = $template;
	}

	/**
	 * 
	 * @return string
	 */
	public function render()
	{	
		return View::factory($this->_template, array(
			'fields' => $this->_data,
			'form' => $this->_form
		));
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