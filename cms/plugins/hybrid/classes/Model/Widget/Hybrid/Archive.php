<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS/Widgets
 * @category	Widget
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Model_Widget_Hybrid_Archive extends Model_Widget_Decorator {

	/**
	 *
	 * @var string 
	 */
	public $date_field = 'created_on';
	
	/**
	 *
	 * @var string 
	 */
	public $order_by = 'desc';
	
	/**
	 *
	 * @var string 
	 */
	public $archive_type = 'month';
	
	public function get_date_fields()
	{
		$fields = DataSource_Hybrid_Field_Factory::get_section_fields($this->ds_id, array('primitive_date', 'primitive_datetime'));
		
		$return = array(
			'created_on' => __('Created on')
		);

		foreach ($fields as $field)
		{
			$return[$field->name] = $field->header;
		}
		
		return $return;
	}

	/**
	 * 
	 * @return array [$archive]
	 */
	public function fetch_data()
	{
		$datasource = Datasource_Data_Manager::load($this->ds_id);

		if ($datasource === NULL)
		{
			return array();
		}

		$is_system = TRUE;
		
		if ($this->date_field == 'created_on')
		{
			$field = 'd.' . $this->date_field;
		}
		else
		{
			$field = 'ds.' . $this->date_field;
			$is_system = FALSE;
		}

		switch ($this->archive_type)
		{
			case 'day':
				$type = '%Y/%m/%d';
				break;
			case 'year':
				$type = '%Y';
				break;
			default:
				$type = '%Y/%m';
		}
		
		$query = DB::select(array(DB::expr('DATE_FORMAT(' . Database::instance()->quote_column($field) . ', "' . $type . '")'), 'date'))
			->select(array(DB::expr('COUNT(*)'), 'total'))
			->distinct(TRUE)
			->from(array('dshybrid', 'd'))
			->where('d.published', '=', 1)
			->where('d.ds_id', '=', $this->ds_id)
			->group_by('date')
			->order_by($field, $this->order_by == 'asc' ? 'asc' : 'desc');

		if($is_system === FALSE)
		{
			$query
				->join(array('dshybrid_' . $this->ds_id, 'ds'))
				->on('ds.id', '=', 'd.id');
		}
		
		$result = $query
			->execute()
			->as_array();
		
		return array(
			'archive' => $result
		);
	}
}