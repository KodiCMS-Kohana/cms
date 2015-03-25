<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS/Widgets
 * @category	Widget
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class Model_Widget_Hybrid_Tags extends Model_Widget_Tags_Cloud {
	
	/**
	 *
	 * @var string 
	 */
	public $frontend_template = 'tags_cloud';
	
	/**
	 * 
	 * @return array
	 */
	public function options()
	{
		$datasources = Datasource_Data_Manager::get_all('hybrid');
		
		$options = array(__('--- Not set ---'));
		foreach ($datasources as $value)
		{
			$options[$value['id']] = $value['name'];
		}

		return $options;
	}
	
	public function get_doc_fields()
	{
		$fields = array();
		
		if (!$this->ds_id)
		{
			return $fields;
		}

		$datasource = Datasource_Data_Manager::load($this->ds_id);
		
		if ($datasource !== NULL)
		{
			foreach ($datasource->record()->fields() as $field)
			{
				if ($field instanceof DataSource_Hybrid_Field_Source_Tags)
				{
					$fields[$field->id] = $field->header;
				}
			}
		}

		return $fields;
	}

	/**
	 * 
	 * @return array [$tags]
	 */
	public function fetch_data()
	{
		$ids = DB::select('tag_id')
			->from('hybrid_tags')
			->where('field_id', '=', (int) $this->field_id)
			->execute()
			->as_array(NULL, 'tag_id');

		$this->set_ids($ids);

		return parent::fetch_data();
	}
}