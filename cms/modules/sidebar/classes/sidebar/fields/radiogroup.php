<?php defined('SYSPATH') or die('No direct script access.');

/**
 * @package		KodiCMS/Sidebar
 * @category	Fields
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Sidebar_Fields_RadioGroup extends Sidebar_Fields_Abstract {
	
	protected $_template = 'radiogroup';
	
	protected $_options = array(
		'name', 'label', 'options'
	);
	
	public $_field = array(
		'label' => NULL
	);

	public function render()
	{
		$options = array();

		$_options = $this->_field['options'];
		$this->_view->set('label', $this->_field['label']);

		unset($this->_field['label'], $this->_field['options']);

		foreach ($_options as $option)
		{
			if ($option instanceof Sidebar_Fields_Abstract)
			{
				$options[] = $option;
				continue;
			}

			$option = Arr::merge($option, $this->_field);

			if (isset($this->param) AND $this->param == $option['value'])
			{
				$option['selected'] = TRUE;
			}

			$option['inline'] = TRUE;

			$options[] = new Sidebar_Fields_Radio($option);
		}

		$this->_view->set('options', $options);

		return parent::render();
	}
}