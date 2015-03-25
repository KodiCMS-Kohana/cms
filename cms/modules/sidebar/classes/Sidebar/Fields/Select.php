<?php defined('SYSPATH') or die('No direct script access.');

/**
 * @package		KodiCMS/Sidebar
 * @category	Fields
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Sidebar_Fields_Select extends Sidebar_Fields_Abstract {
	
	const AUTO_SIZE = 'auto';
	const MIN_SIZE = 3;
	const MAX_SIZE = 50;
	
	protected $_template = 'select';

	protected $_options = array(
		'name', 'label', 'selected', 'options'
	);
	
	public $_field = array(
		'selected' => NULL,
		'options' => array()
	);
	
	public $_attributes = array(
		'size' => 10,
		'class' => 'input-block-level'
	);
	
	public function __construct($field) 
	{
		parent::__construct($field);

		if ($this->size == Sidebar_Fields_Select::AUTO_SIZE)
		{
			$this->size = count($this->_field['options']);
		}
	}
}