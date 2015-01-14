<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS/Hybrid
 * @category	Field
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class DataSource_Hybrid_Field_Primitive_Float extends DataSource_Hybrid_Field_Primitive {
	
	protected $_props = array(
		'default' => 0,
		'min' => 0, 
		'max' => 500,
		'length' => 10,
		'after_coma_num' => 2
	);
	
	public function set_value($value)
	{
		$value = number_format((float) $value, $this->after_coma_num, '.', '');

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

	public function set_default($value)
	{
		$this->default = $this->set_value($value);
	}
	
	public function set_after_coma_num($number)
	{
		$this->after_coma_num = (int) $number;

		if ($this->after_coma_num > 6 OR $this->after_coma_num < 1)
		{
			$this->after_coma_num = 2;
		}
	}
	
	public function set_min($value)
	{
		$this->min = Num::format((float) $value, $this->after_coma_num);
	}
	
	public function set_max($value)
	{
		$this->max = Num::format((float) $value, $this->after_coma_num);
	}
	
	public function onUpdateDocument(DataSource_Hybrid_Document $old = NULL, DataSource_Hybrid_Document $new)
	{
		$new->set($this->name, Num::format($new->get($this->name), $this->after_coma_num));
	}

	public function onValidateDocument(Validation $validation, DataSource_Hybrid_Document $doc)
	{
		$validation->rule($this->name, 'numeric');

		return parent::onValidateDocument($validation, $doc);
	}

	public function get_type() 
	{
		if ($this->length < 1 OR $this->length > 11)
		{
			$this->length = 10;
		}

		return 'NUMERIC(' . $this->length . ', ' . $this->after_coma_num . ') NOT NULL';
	}
}