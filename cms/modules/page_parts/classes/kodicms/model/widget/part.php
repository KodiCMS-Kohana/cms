<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS/Page_Parts
 * @category	Widget
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class KodiCMS_Model_Widget_Part {
	
	public $block = NULL;
	protected $_html = NULL;
	
	public function __construct($block, $html)
	{
		$this->block = $block;
		$this->_html = $html;
	}

	public function __toString()
	{
		return (string) $this->_html;
	}
}