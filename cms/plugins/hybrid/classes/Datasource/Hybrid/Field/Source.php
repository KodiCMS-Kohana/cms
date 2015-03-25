<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS/Hybrid
 * @category	Field
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
abstract class DataSource_Hybrid_Field_Source extends DataSource_Hybrid_Field {

	protected $_section = NULL;

	public function __construct(array $data = NULL)
	{
		parent::__construct( $data );
		$this->family = DataSource_Hybrid_Field::FAMILY_SOURCE;
	}
	
	public function onCreateDocument(DataSource_Hybrid_Document $doc)
	{
		$this->onUpdateDocument($doc, $doc);
	}
	
	public function section() 
	{
		if ($this->_section === NULL)
		{
			$this->_section = Datasource_Data_Manager::load($this->from_ds);
		}

		return $this->_section;
	}
}