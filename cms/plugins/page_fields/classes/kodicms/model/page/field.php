<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS/Page_Field
 * @category	Model
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class KodiCMS_Model_Page_Field extends ORM {
	
	public function labels()
	{
		return array(
			'title' => __('Field title'),
			'key' => __('Field key'),
			'value' => __('Field value')
		);
	}

	public function rules()
	{
		return array(
			'title' => array(
				array('not_empty'),
				array('min_length', array(':value', 2)),
				array('max_length', array(':value', 32))
			),
			'key' => array(
				array('not_empty'),
				array('min_length', array(':value', 2)),
				array('max_length', array(':value', 50)),
				array(array($this, 'unique_field')),
			),
		);
	}
	
	public function filters()
	{
		return array(
			'key' => array(
				array('URL::title', array(':value', '_'))
			)
		);
	}
	
	public function get_by_page_id($page_id)
	{
		return $this
			->where('page_id', '=', (int) $page_id)
			->find_all();
	}
	
	public static function copy( $from_page_id, $to_page_id ) 
	{
		$fields = DB::select()
			->from('page_fields')
			->where('page_id', '=', (int) $from_page_id)
			->execute()
			->as_array();
		
		if (count($fields) > 0)
		{
			$insert = DB::insert('page_fields')
				->columns(array('page_id', 'title', 'key', 'value'));

			foreach ($fields as $field)
			{
				unset($field['id']);

				$field['page_id'] = (int) $to_page_id;
				$insert->values($field);
			}

			list($insert_id, $total_rows) = $insert->execute();

			return $total_rows;
		}

		return FALSE;
	}
	
	public function unique_field($field_key)
	{
		return !((bool) DB::select(array(DB::expr('COUNT(*)'), 'total_count'))
			->from($this->_table_name)
			->where('key', '=', $field_key)
			->where('page_id', '=', $this->page_id)
			->execute($this->_db)
			->get('total_count'));
	}
}