<?php defined('SYSPATH') or die('No direct script access.');

/**
 * @package		KodiCMS/Sidebar
 * @category	Fields
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Sidebar_Fields_Input extends Sidebar_Fields_Abstract {
	
	protected $_template = 'input';
	
	public $_attributes = array(
		'class' => 'form-control'
	);

	protected $_options = array(
		'value', 'name', 'label'
	);
	
	public $_field = array(
		'value' => NULL
	);
}