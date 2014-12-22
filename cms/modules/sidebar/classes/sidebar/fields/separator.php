<?php defined('SYSPATH') or die('No direct script access.');

/**
 * @package		KodiCMS/Sidebar
 * @category	Fields
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Sidebar_Fields_Separator extends Sidebar_Fields_Abstract {

	protected $_template = 'separator';
	
	public function __construct($header = NULL, $render = TRUE) 
	{
		$this->_view = View::factory('sidebar/fields/'.$this->_template, array(
			'header' => $header
		));
	}
}