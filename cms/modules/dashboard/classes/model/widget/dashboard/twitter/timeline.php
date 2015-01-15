<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS/Dashboard
 * @category	Widget
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright  (c) 2012-2014 butschster
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Model_Widget_Dashboard_Twitter_Timeline extends Model_Widget_Decorator_Dashboard {	
	
	/**
	 *
	 * @var boolean
	 */
	protected $_multiple = TRUE;
	
	protected $_data = array(
		'height' => 250
	);
	
	protected $_size = array(
		'x' => 3,
		'y' => 4,
		'max_size' => array(4, 5),
		'min_size' => array(2, 3)
	);
	
	public function set_height($height) 
	{
		return (int) $height;
	}
	
	public function set_widget_id($widget_id) 
	{
		return Valid::numeric($widget_id) ? $widget_id : NULL;
	}
	
	public function fetch_data(){}
}