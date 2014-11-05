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

	protected $_props = array(
		'isreq' => TRUE,
		'source' => NULL
	);
	
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
	
	public function get_related_docs($doc_id)
	{
		return DB::select('related_id')
			->distinct('related_id')
			->from('dshybrid_relations')
			->where('related_id', '!=', 0)
			->where('document_id', '=', (int) $doc_id)
			->where('field_id', '=', $this->id)
			->execute()
			->as_array(NULL, 'related_id');
	}
	
	public function delete_related_docs($doc_id)
	{
		return DB::delete('dshybrid_relations')
			->where('field_id', '=', $this->id)
			->where('document_id', '=', (int) $doc_id)
			->execute();
	}
	
	public function update_related_docs($doc_id, array $old_ids = array(), array $new_ids = array())
	{
		if (!empty($old_ids))
		{
			DB::delete('dshybrid_relations')
				->where('field_id', '=', $this->id)
				->where('document_id', '=', (int) $doc_id)
				->where('related_id', 'in', $old_ids)
				->execute();
		}
		
		if (!empty($new_ids))
		{
			$insert = DB::insert('dshybrid_relations');

			foreach ($new_ids as $id)
			{
				$insert
					->columns(array('document_id', 'related_id', 'field_id'))
					->values(array((int) $doc_id, $id, $this->id));
			}

			$insert->execute();
		}
	}

	public function onUpdateDocument(DataSource_Hybrid_Document $old = NULL, DataSource_Hybrid_Document $new) 
	{
		$new_docs = $new->get($this->name);

		$current = $this->get_related_docs($old->id);
		$new_ids = empty($new_docs) ? array() : explode(',', $new->get($this->name));

		$old_ids = array_diff($current, $new_ids);
		$new_ids = array_diff($new_ids, $current);

		$this->update_related_docs($new->id, $old_ids, $new_ids);

		if ($this->one_to_many AND !empty($old_ids))
		{
			DataSource_Hybrid_Factory::remove_documents($old_ids);
		}
	}
	
	public function onRemoveDocument(DataSource_Hybrid_Document $doc)
	{
		$ids = explode(',', $doc->get($this->name));
		$this->delete_related_docs($doc->id);
		
		if ($this->one_to_many AND !empty($ids))
		{
			DataSource_Hybrid_Factory::remove_documents($ids);
		}
	}

	public function convert_value( $value ) 
	{
		$ids = !empty($value) ? explode(',', $value) : array();
		return DataSource_Hybrid_Field_Utils::get_document_headers($this->from_ds, $ids);
	}
	
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
	public static function fetch_widget_field( $widget, $field, $row, $fid, $recurse )
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
	
	public function fetch_headline_value( $value, $document_id )
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
	
	public function get_query_props(Database_Query $query, DataSource_Hybrid_Agent $agent)
	{
		$sub_query = DB::select(DB::expr("GROUP_CONCAT(related_id SEPARATOR ',')"))
			->from('dshybrid_relations')
			->where('document_id', '=', DB::expr('d.id'))
			->where('field_id', '=', $this->id);

		$query->select(array($sub_query, $this->id));
	}
}