<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS/Hybrid
 * @category	Field
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class DataSource_Hybrid_Field_Primitive_String extends DataSource_Hybrid_Field_Primitive {
	
	protected $_use_as_document_id = TRUE;
	
	protected $_props = array(
		'default' => NULL,
		'length' => 32,
		'regexp' => NULL,
		'use_filemanager' => FALSE
	);
	
	public function booleans()
	{
		return array('use_filemanager');
	}
	
	public function get_type() 
	{
		if($this->length < 1 OR $this->length > 255)
		{
			$this->length = 32;
		}

		return 'VARCHAR (' . $this->length . ') NOT NULL';
	}
}