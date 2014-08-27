<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

/**
 * @package		KodiCMS/Widgets
 * @category	Decorator
 * @author		ButscHSter
 */
abstract class Model_Widget_Decorator_Dashboard_Pagination extends Model_Widget_Decorator_Dashboard {

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
		return View::factory( 'widgets/backend/pagination_decorator', array(
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