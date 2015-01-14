<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS/Hybrid
 * @category	Field
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
abstract class DataSource_Hybrid_Field_Primitive extends DataSource_Hybrid_Field {
	
	protected $_is_sortable = TRUE;

	public function __construct(array $data = NULL)
	{
		parent::__construct($data);
		$this->family = DataSource_Hybrid_Field::FAMILY_PRIMITIVE;
	}

	public function set_id($id)
	{
		if (in_array($id, array('id', 'header', 'created_on')))
		{
			$this->id = $id;
			return $this;
		}

		return parent::set_id($id);
	}

	public function set_length($value)
	{
		$this->length = (int) $value;
	}
	
	public function onCreateDocument(DataSource_Hybrid_Document $doc)
	{
		$this->onUpdateDocument($doc, $doc);
	}

	public function onValidateDocument(Validation $validation, DataSource_Hybrid_Document $doc)
	{
		if ($this->min !== NULL OR $this->max !== NULL)
		{
			$min = $this->min !== NULL ? $this->min : -99999999999;
			$max = $this->max !== NULL ? $this->max : 99999999999;

			$validation->rule($this->name, 'range', array(':value', $min, $max));
		}

		if (!empty($this->_props['regexp']))
		{
			if (strpos($this->regexp, '::') !== FALSE)
			{
				list($class, $method) = explode('::', $this->regexp);
			}
			else
			{
				$class = 'Valid';
				$method = $this->regexp;
			}

			if (method_exists($class, $method))
			{
				$validation->rule($this->name, array($class, $method));
			}
			else
			{
				$validation->rule($this->name, 'regex', array(':value', $this->regexp));
			}
		}

		return parent::onValidateDocument($validation, $doc);
	}

	public static function fetch_widget_field($widget, $field, $row, $fid, $recurse)
	{
		return $row[$fid];
	}

}