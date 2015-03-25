<?php defined('SYSPATH') or die('No direct script access.');

/**
 * @package		KodiCMS/Sidebar
 * @category	Fields
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Sidebar_Fields_Radio extends Sidebar_Fields_Abstract {
	
	protected $_template = 'radio';
	
	protected $_options = array(
		'name', 'label', 'value', 'selected'
	);
	
	public $_field = array(
		'selected' => FALSE
	);
}