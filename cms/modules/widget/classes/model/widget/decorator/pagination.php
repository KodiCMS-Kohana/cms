<?php defined( 'SYSPATH' ) or die( 'No direct access allowed.' );

abstract class Model_Widget_Decorator_Pagination extends Model_Widget_Decorator {

	/**
	 *
	 * @var integer 
	 */
	public $list_offset = 0;
	
	/**
	 *
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
	 * @param string $value
	 * @return integer
	 */
	public function set_list_offset($value)
	{
		return (int) $value;
	}
	
	/**
	 * 
	 * @param string $value
	 * @return integer
	 */
	public function set_list_size($value)
	{
		return (int) $value;
	}
	
	/**
	 * @return integer
	 */
	abstract public function count_total();
}