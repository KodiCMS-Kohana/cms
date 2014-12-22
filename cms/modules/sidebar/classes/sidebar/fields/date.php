<?php defined('SYSPATH') or die('No direct script access.');

/**
 * @package		KodiCMS/Sidebar
 * @category	Fields
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Sidebar_Fields_Date extends Sidebar_Fields_Input {
	
	protected $_template = 'date';
	
	public $_attributes = array(
		'class' => 'datepicker form-control',
		'size' => '10'
	);

	public function __construct(array $field = array()) 
	{
		parent::__construct($field);
	}
}