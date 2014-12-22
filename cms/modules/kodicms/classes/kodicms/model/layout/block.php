<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS
 * @category	Model
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class KodiCMS_Model_Layout_Block extends ORM {
	
	protected $_sorting = array(
		'position' => 'asc'
	);

	/**
	 * 
	 * @param string $name
	 * @return array
	 */
	public function find_by_layout( $name )
	{
		return DB::select('block')
			->from($this->table_name())
			->where('layout_name', '=', $name)
			->execute()
			->as_array('block', 'block');
	}
}