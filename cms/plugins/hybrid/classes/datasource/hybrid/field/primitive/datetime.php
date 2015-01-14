<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS/Hybrid
 * @category	Field
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class DataSource_Hybrid_Field_Primitive_DateTime extends DataSource_Hybrid_Field_Primitive_Date {
	
	protected $_format = 'Y-m-d H:i:s';
	
	public function get_type() 
	{
		return 'DATETIME NOT NULL';
	}
	
	public function fetch_headline_value($value, $document_id)
	{
		if (!empty($value))
		{
			return Date::format($value, 'j F Y H:i:s');
		}

		return parent::fetch_headline_value($value, $document_id);
	}
}