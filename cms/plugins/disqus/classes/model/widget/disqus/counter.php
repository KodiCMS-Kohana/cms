<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS/Widgets
 * @category	Other
 * @author		ButscHSter
 */
class Model_Widget_Disqus_Counter extends Model_Widget_Decorator {

	/**
	 * 
	 * @return array [$plugin]
	 */
	public function fetch_data()
	{
		$plugin = Plugins::get_registered('disqus');
		
		return array(
			'plugin' => $plugin
		);
	}
}