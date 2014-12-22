<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS/Hybrid
 * @category	Field
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class DataSource_Hybrid_Field_Primitive_Time extends DataSource_Hybrid_Field_Primitive_Date {
	
	protected $_format = 'H:i:s';
	
	public function get_type() 
	{
		return 'TIME NOT NULL';
	}
}