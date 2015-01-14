<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * @package		KodiCMS/Widgets
 * @category	Widget
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
abstract class Model_Widget_Decorator_Pagination extends Model_Widget_Decorator {

	/**
	 * Кол-во пропускаемых строк
	 * @var integer 
	 */
	public $list_offset = 0;
	
	/**
	 * Кол-во выводимых строк
	 * @var integer 
	 */
	public $list_size = 10;
	
	public function fetch_backend_content()
	{
		return View::factory('widgets/backend/pagination_decorator', array(
			'content' => parent::fetch_backend_content(),
			'widget' => $this
		));
	}

	/**
	 * 
	 * @param integer $value
	 * @return integer
	 */
	public function set_list_offset($value)
	{
		return (int) $value;
	}
	
	/**
	 * 
	 * @param integer $value
	 * @return integer
	 */
	public function set_list_size($value)
	{
		return (int) $value;
	}
	
	/**
	 * Метод возвращает кол-во записей выводимых данных без учета
	 * limit и offset
	 * 
	 * @return integer
	 */
	abstract public function count_total();
}