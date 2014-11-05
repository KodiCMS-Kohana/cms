<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS/Hybrid
 * @category	Field
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class DataSource_Hybrid_Field_Primitive_Slug extends DataSource_Hybrid_Field_Primitive {

	protected $_use_as_document_id = TRUE;
	
	protected $_props = array(
		'default' => NULL,
		'separator' => '-',
		'from_header' => FALSE,
		'unique' => FALSE
	);

	public function booleans()
	{
		return array('from_header', 'unique');
	}
	
	public function onUpdateDocument(DataSource_Hybrid_Document $old = NULL, DataSource_Hybrid_Document $new) 
	{
		$new->set($this->name, URL::title($new->get($this->name), $this->separator));
	}
	
	public function get_type() 
	{
		return 'VARCHAR (255) NOT NULL';
	}
}