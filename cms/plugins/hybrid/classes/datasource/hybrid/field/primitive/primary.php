<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS/Hybrid
 * @category	Field
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class DataSource_Hybrid_Field_Primitive_Primary extends DataSource_Hybrid_Field_Primitive {
	
	protected $_use_as_document_id = TRUE;
	
	protected $_is_required = FALSE;
	
	protected $_props = array(
		'auto_increment' => TRUE,
		'unique' => TRUE,
		'increment_step' => 1
	);
	
	/**
	 * return string
	 */
	public function default_value()
	{
		if($this->loaded() AND $this->auto_increment === TRUE)
		{
			return $this->get_next_value();
		}

		return $this->db_default_value();
	}
	
	public function db_default_value()
	{
		return 0;
	}
	
	public function increment_step()
	{
		$increment_step = (int) $this->increment_step;
		
		if($increment_step === 0)
		{
			$increment_step = 1;
		}

		return (int) $increment_step;
	}

	public function get_next_value()
	{
		$max_value = (int) DB::select(array(DB::expr('MAX(:field)', array(':field' => DB::expr($this->name))), 'max'))
			->from($this->ds_table)
			->limit(1)
			->execute()
			->get('max');
		
		return $max_value + $this->increment_step();
	}

	public function get_type()
	{
		return 'INT(11) UNSIGNED NOT NULL';
	}
	
	public function onCreate()
	{
		parent::onCreate();

		if ($this->loaded() AND $this->auto_increment === TRUE)
		{
			DB::query(NULL, "SET @i = 0")->execute();
			DB::query(Database::UPDATE, "UPDATE :table SET :field = @i:=@i+:num ORDER BY id")
				->param(':num', $this->increment_step())
				->param(':table', DB::expr($this->ds_table))
				->param(':field', DB::expr($this->name))
				->execute();
		}
	}

	public function onCreateDocument(DataSource_Hybrid_Document $doc)
	{
		$doc->set($this->name, (int) $this->get_next_value());
	}
	
	public function onUpdateDocument(DataSource_Hybrid_Document $old = NULL, DataSource_Hybrid_Document $new) 
	{
		$new->set($this->name, (int) $new->get($this->name));
	}
	
	public function onValidateDocument(Validation $validation, DataSource_Hybrid_Document $doc)
	{
		$validation->rule($this->name, 'numeric');

		return parent::onValidateDocument($validation, $doc);
	}

	public static function fetch_widget_field($widget, $field, $row, $fid, $recurse)
	{
		return (int) $row[$fid];
	}
}