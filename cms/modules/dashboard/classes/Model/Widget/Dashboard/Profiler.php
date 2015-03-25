<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS/Dashboard
 * @category	Widget
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright  (c) 2012-2014 butschster
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Model_Widget_Dashboard_Profiler extends Model_Widget_Decorator_Dashboard {	
	
	/**
	 *
	 * @var boolean
	 */
	protected $_multiple = FALSE;
	
	protected $_size = array(
		'x' => 3,
		'y' => 2,
		'max_size' => array(5, 2),
		'min_size' => array(3, 2)
	);
	
	public function fetch_data()
	{
		return array(
			'stats' => Profiler::application(),
			'application_cols' => array('min', 'max', 'average')
		);
	}

}