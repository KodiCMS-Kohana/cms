<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS/Widgets
 * @category	Other
 * @author		ButscHSter
 */
class Model_Widget_Disqus_Comments extends Model_Widget_Decorator {

	public function fetch_data()
	{
		$plugin = Plugins::get_registered('disqus');
		
		return array(
			'plugin' => $plugin
		);
	}
}