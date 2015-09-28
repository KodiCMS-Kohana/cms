<?php defined('SYSPATH') or die('No direct access allowed.');

/**
 * @package		KodiCMS/Hybrid
 * @category	Field
 * @author		butschster <butschster@gmail.com>
 * @link		http://kodicms.ru
 * @copyright	(c) 2012-2014 butschster
 * @license		http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt
 */
class DataSource_Hybrid_Field_Source_Array extends DataSource_Hybrid_Field_Source_OneToMany {

	protected $_is_searchable = FALSE;

	protected $_props = array(
		'isreq' => TRUE,
		'source' => NULL
	);

	public function include_media()
	{
		parent::include_media();

		Assets::package('jquery-ui');
	}
	
	public function create() 
	{
		parent::create();
		
		if (!$this->id)
		{
			return FALSE;
		}

		$ds = Datasource_Data_Manager::load($this->from_ds);		
		$this->update();
		
		return $this->id;
	}


	/**
	 * @param integer $doc_id
	 *
	 * @return array
	 */
	public function get_related_docs($doc_id)
	{
		return DB::select('related_id')
			->distinct('related_id')
			->from('dshybrid_relations')
			->where('related_id', '!=', 0)
			->where('document_id', '=', (int) $doc_id)
			->where('field_id', '=', $this->id)
			->order_by('position')
			->execute()
			->as_array(NULL, 'related_id');
	}


	/**
	 * @param integer $doc_id
	 *
	 * @return object
	 */
	public function delete_related_docs($doc_id)
	{
		return DB::delete('dshybrid_relations')
			->where('field_id', '=', $this->id)
			->where('document_id', '=', (int) $doc_id)
			->execute();
	}


	/**
	 * @param integer $doc_id
	 * @param array   $old_ids
	 * @param array   $new_ids
	 * @param array   $doc_ids
	 *
	 * @throws Kohana_Exception
	 */
	public function update_related_docs($doc_id, array $old_ids = array(), array $new_ids = array(), array $doc_ids = array())
	{
		intval($doc_id);

		if (!empty($old_ids))
		{
			DB::delete('dshybrid_relations')
				->where('field_id', '=', $this->id)
				->where('document_id', '=', $doc_id)
				->where('related_id', 'in', $old_ids)
				->execute();
		}
		
		if (!empty($new_ids))
		{
			$insert = DB::insert('dshybrid_relations');

			foreach ($new_ids as $position => $id)
			{
				$insert
					->columns(array('document_id', 'related_id', 'field_id', 'position'))
					->values(array($doc_id, $id, $this->id, $position));
			}

			$insert->execute();
		}

		foreach ($doc_ids as $position => $id)
		{
			DB::update('dshybrid_relations')
				->where('document_id', '=', $doc_id)
				->where('related_id', '=', $id)
				->where('field_id', '=', $this->id)
				->set(array('position' => $position))
				->execute();
		}
	}


	/**
	 * @param DataSource_Hybrid_Document|null $old
	 * @param DataSource_Hybrid_Document      $new
	 */
	public function onUpdateDocument(DataSource_Hybrid_Document $old = NULL, DataSource_Hybrid_Document $new) 
	{
		$new_docs = $new->get($this->name);

		$current = $this->get_related_docs($old->id);
		$new_ids = empty($new_docs) ? array() : explode(',', $new->get($this->name));

		$old_ids = array_diff($current, $new_ids);

		$this->update_related_docs($new->id, $old_ids, array_diff($new_ids, $current), $new_ids);

		if ($this->one_to_many AND !empty($old_ids))
		{
			DataSource_Hybrid_Factory::remove_documents($old_ids);
		}
	}


	/**
	 * @param DataSource_Hybrid_Document $doc
	 */
	public function onRemoveDocument(DataSource_Hybrid_Document $doc)
	{
		$ids = explode(',', $doc->get($this->name));
		$this->delete_related_docs($doc->id);
		
		if ($this->one_to_many AND !empty($ids))
		{
			DataSource_Hybrid_Factory::remove_documents($ids);
		}
	}


	/**
	 * @param string $value
	 *
	 * @return array
	 */
	public function convert_value( $value ) 
	{
		$ids = !empty($value) ? explode(',', $value) : array();
		return DataSource_Hybrid_Field_Utils::get_document_headers($this->from_ds, $ids);
	}


	/**
	 * @return string
	 */
	public function get_type()
	{
		return 'VARCHAR(255)';
	}
	
	/**
	 * @param Model_Widget_Hybrid
	 * @param array $field
	 * @param array $row
	 * @param string $fid
	 * @return mixed
	 */
	public static function fetch_widget_field($widget, $field, $row, $fid, $recurse)
	{
		$related_widget = NULL;

		if ($recurse > 0 AND isset($widget->doc_fetched_widgets[$fid]))
		{
			if (!empty($row[$fid]))
			{
				$related_widget = self::_fetch_related_widget($widget, $row, $fid, $recurse);
			}
		}

		return !empty($related_widget) 
			? $related_widget 
			: $field->get_related_docs($row['id']);
	}


	/**
	 * @param string $value
	 * @param int    $document_id
	 *
	 * @return string
	 * @throws Kohana_Exception
	 */
	public function fetch_headline_value($value, $document_id)
	{
		if (!empty($value))
		{
			$docs = $this->get_related_docs($document_id);

			foreach ($docs as $i => $id)
			{
				$header = DataSource_Hybrid_Field_Utils::get_document_header($this->from_ds, $id);

				$docs[$i] = HTML::anchor(Route::get('datasources')->uri(array(
					'controller' => 'document',
					'directory' => 'hybrid',
					'action' => 'view'
				)) . URL::query(array(
					'ds_id' => $this->from_ds, 'id' => $id
				)), $header, array('target' => 'blank'));
			}

			return implode(', ', $docs);
		}

		return parent::fetch_headline_value($value, $document_id);
	}


	/**
	 * @param Database_Query          $query
	 * @param DataSource_Hybrid_Agent $agent
	 *
	 * @throws Kohana_Exception
	 */
	public function get_query_props(Database_Query $query, DataSource_Hybrid_Agent $agent)
	{
		$sub_query = DB::select(DB::expr("GROUP_CONCAT(related_id SEPARATOR ',')"))
			->from('dshybrid_relations')
			->where('document_id', '=', DB::expr(Database::instance()->quote_column('d.id')))
			->where('field_id', '=', $this->id);

		$query->select(array($sub_query, $this->id));
	}


	/**
	 * @param Database_Query $query
	 * @param string         $condition
	 * @param string         $value
	 * @param array|null     $params
	 */
	public function filter_condition(Database_Query $query, $condition, $value, array $params = NULL)
	{
		$related_table = $this->id.'_f_array';
		$query
			->join(array('dshybrid_relations', $related_table))
			->on($related_table . '.field_id', '=', DB::expr($this->id))
			->on($related_table . '.document_id', '=', 'd.id')
			->where($related_table.'.related_id', $condition, $value);
	}
}