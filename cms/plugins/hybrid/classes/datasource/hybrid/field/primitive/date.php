<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS/Hybrid
 * @category	Field
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class DataSource_Hybrid_Field_Primitive_Date extends DataSource_Hybrid_Field_Primitive {
	
	protected $_props = array(
		'default' => NULL
	);
	
	protected $_format = 'Y-m-d';
	
	public function booleans()
	{
		return array('set_current');
	}	
	
	public function onSetValue($value, DataSource_Hybrid_Document $doc)
	{
		if($this->set_current === TRUE)
		{
			$this->default = date($this->_format);
		}

		return parent::onSetValue($value, $doc);
	}

	public function onUpdateDocument(DataSource_Hybrid_Document $old = NULL, DataSource_Hybrid_Document $new)
	{
		$new->set($this->name, $this->format_date($new->get($this->name))); 
	}
	
	public function convert_value($value)
	{
		return $this->format_date($value);
	}
	
	public function format_date($value)
	{
		if (!empty($value))
		{
			$time = (int) strtotime($value);
		}
		else
		{
			$time = 0;
		}

		return $time > 0 ? date($this->_format, $time) : date($this->_format);
	}
	
	public function onValidateDocument( Validation $validation, DataSource_Hybrid_Document $doc )
	{
		$validation->rule($this->name, 'date');
			
		return parent::onValidateDocument($validation, $doc);
	}
	
	public function get_type() 
	{
		return 'DATE NOT NULL';
	}
	
	public function fetch_headline_value( $value, $document_id )
	{
		if(!empty($value))
		{
			return Date::format($value);
		}

		return parent::fetch_headline_value($value, $document_id);
	}
}