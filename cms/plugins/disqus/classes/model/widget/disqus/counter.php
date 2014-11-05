<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS/Widgets
 * @category	Widget
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
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