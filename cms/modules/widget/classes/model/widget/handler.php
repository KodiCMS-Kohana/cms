<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS/Widgets
 * @category	Other
 * @author		ButscHSter
 */
class Model_Widget_Handler extends Model_Widget_Decorator_Handler {

	protected $_use_caching = FALSE;
	protected $_use_template = TRUE;

	public function on_page_load()
	{
		$this->_fetch_template();
		$this->_fetch_render()->render();
	}
}