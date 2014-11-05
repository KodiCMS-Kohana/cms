<?php defined('SYSPATH') or die('No direct script access.');

/**
 * @package		KodiCMS/Sidebar
 * @category	Fields
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Sidebar_Fields_DateRange extends Sidebar_Fields_Abstract {

	protected $_template = 'daterange';

	protected $_options = array(
		'label', 'range', 'name'
	);
	
	public $_field = array(
		'name' => NULL
	);
	
	public $_attributes = array(
		'class' => array('form-control col-sm-auto'),
		'size' => '10'
	);

	public function render()
	{
		$range = array();
		Text::alternate();

		foreach ($this->_field['range'] as $pair)
		{
			$date = $pair;
		
			$date = Arr::merge($date, $this->_attributes);
			$date['id'] = Text::alternate('date_from', 'date_to');
			
			if(!isset($date['name']))
			{
				$date['name'] = $date['id'];
			}
			
			if($this->_field['name'] !== NULL)
			{
				$date['name'] = $this->_field['name'].'['.$date['name'].']';
			}
			
			$date['inline'] = TRUE;

			$range[] = new Sidebar_Fields_Date($date);
		}
		
		unset($this->_field['range']);
		
		$this->_view->set('range', $range);
		
		return parent::render();
	}
}