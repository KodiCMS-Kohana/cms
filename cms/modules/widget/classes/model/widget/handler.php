<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS/Widgets
 * @category	Other
 * @author		ButscHSter
 */
class Model_Widget_Handler extends Model_Widget_Decorator {

	public $use_caching = FALSE;

	public function on_page_load()
	{
		$this->_fetch_template();
		$this->_fetch_render()->render();
	}

	public function fetch_data()
	{

	}

	public function render(array $params = array())
	{
		
	}

}