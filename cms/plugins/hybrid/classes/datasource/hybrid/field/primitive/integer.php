<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS/Hybrid
 * @category	Field
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class DataSource_Hybrid_Field_Primitive_Integer extends DataSource_Hybrid_Field_Primitive_Primary {
	
	protected $_use_as_document_id = TRUE;
	
	protected $_is_required = TRUE;
	
	protected $_props = array(
		'default' => 0,
		'min' => 0, 
		'max' => 500,
		'length' => 10,
		'auto_increment' => FALSE,
		'unique' => FALSE,
		'increment_step' => 1
	);
	
	public function booleans()
	{
		return array('auto_increment');
	}
	
	public function db_default_value()
	{
		return $this->set_value($this->default);
	}

	public function set_value($value)
	{
		$value = (int) $value;

		if (!empty($this->min) AND $value < $this->min)
		{
			$value = $this->min;
		}
		else if (!empty($this->max) AND $value > $this->max)
		{
			$value = $this->max;
		}

		return $value;
	}
	
	public function set_increment_step($value)
	{
		$value = (int) $value;
		if($value === 0)
		{
			$value = 1;
		}
		
		$this->increment_step = $value;
	}
	
	public function set_min($value)
	{
		$this->min = (int) $value;
	}
	
	public function set_max($value)
	{
		$this->max = (int) $value;
	}
	
	public function onCreate() {}
	
	public function onCreateDocument(DataSource_Hybrid_Document $doc)
	{
		$this->onUpdateDocument($doc, $doc);
	}

	public function onReadDocumentValue(array $data, DataSource_Hybrid_Document $document)
	{
		$value = Arr::get($data, $this->name);
		$value = (int) preg_replace('/[^\d]/', '', $value);

		$document->set($this->name, $value);
		return $this;
	}
	
	public function get_type()
	{
		if ($this->length < 1 OR $this->length > 11)
		{
			$this->length = 10;
		}

		return 'INT(' . $this->length . ') UNSIGNED NOT NULL';
	}
}