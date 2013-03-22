<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package    Kodi/Model
 */

class Model_Layout_Block extends ORM {
	
	protected $_sorting = array(
		'position' => 'asc'
	);

	public function find_by_layout( $name )
	{
		return $this
			->where('layout_name', '=', $name)
			->find_all()
			->as_array(NULL, 'block');
	}
}