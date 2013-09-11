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