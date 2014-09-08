<?php defined('SYSPATH') or die('No direct access allowed.');

class DataSource_Hybrid_Field_Source_Tags extends DataSource_Hybrid_Field_Source {
	
	const TABLE_NAME = 'hybrid_tags';
	
	protected $_is_indexable = FALSE;

	protected $_props = array(
		'isreq' => FALSE
	);
	
	public function onCreateDocument(DataSource_Hybrid_Document $doc)
	{
		$tags = $doc->get($this->name);
		$tags = empty($tags) ? array() : explode(',', $tags);
		
		$this->update_tags(array(), $tags, $doc->id);
	}
	
	public function onUpdateDocument(DataSource_Hybrid_Document $old = NULL, DataSource_Hybrid_Document $new) 
	{
		$old_tags = array_unique($this->get_tags($old->id));
		$new_tags = $new->get($this->name);

		$new_tags = empty($new_tags) ? array() : explode(',', $new_tags);

		$this->update_tags($old_tags, $new_tags, $new->id);
	}
	
	public function onRemoveDocument( DataSource_Hybrid_Document $doc )
	{
		$tags = explode(',', $doc->get($this->name));
		$this->update_tags($tags, array(), $doc->id);
	}
	
	public function get_tags($doc_id)
	{
		return DB::select('tags.name')
			->from(self::TABLE_NAME)
			->where('doc_id', '=', (int) $doc_id)
			->where('field_id', '=', $this->id)
			->join(array(Model_Tag::tableName(), 'tags'))
				->on('tags.id', '=', 'tag_id')
			->execute()
			->as_array(NULL, 'name');
	}
	
	public function get_type()
	{
		return 'TEXT NOT NULL';
	}
	
	public function update_tags(array $old, array $new, $doc_id)
	{
		$old = array_unique(Arr::map('trim', $old));
		$new = array_unique(Arr::map('trim', $new));

		if(empty($new))
		{
			foreach($old as $tag)
			{
				DB::update(Model_Tag::tableName())
					->set(array('count' => DB::expr('count - 1')))
					->where('name', '=', $tag)
					->execute();
			}
			
			return DB::delete(self::TABLE_NAME)
				->where('doc_id', '=', $doc_id)
				->where('field_id', '=', $this->id)
				->execute();
		}
		
		$old_tags = array_diff($old, $new);
		$new_tags = array_diff($new, $old);

		// insert all tags in the tag table and then populate the page_tag table
		foreach( $new_tags as $index => $tag_name )
		{
			if (empty($tag_name))
			{
				continue;
			}

			$tag = Record::findOneFrom('Model_Tag', array('where' => array(
				array('name', '=', $tag_name))));
			

			// try to get it from tag list, if not we add it to the list
			if (!($tag instanceof Model_Tag))
			{
				$tag = new Model_Tag(array('name' => trim($tag_name)));
			}
			
			$tag->count++;
			$tag->save();
		
			$data = array(
				'field_id' => $this->id,
				'doc_id' => $doc_id,
				'tag_id' => $tag->id
			);

			DB::insert(self::TABLE_NAME)
				->columns(array_keys($data))
				->values($data)
				->execute();
		}
		
		// remove all old tag
		foreach( $old_tags as $index => $tag_name )
		{
			// get the id of the tag
			$tag = Record::findOneFrom('Model_Tag',
					array('where' => array(array('name', '=', $tag_name))));

			DB::delete(self::TABLE_NAME)
				->where('doc_id', '=', $doc_id)
				->where('field_id', '=', $this->id)
				->where('tag_id', '=', $tag->id)
				->execute();

			$tag->count--;
			$tag->save();
		}
	}
	
	public function remove()
	{
		$ids = DB::select('tag_id')
			->from(self::TABLE_NAME)
			->where('field_id', '=', $this->id)
			->execute()
			->as_array(NULL, 'tag_id');
		
		foreach($ids as $id)
		{
			DB::update(Model_Tag::tableName())
				->set(array('count' => DB::expr('count - 1')))
				->where('id', '=', $id)
				->execute();
		}
			
		DB::delete(self::TABLE_NAME)
			->where('field_id', '=', $this->id)
			->execute();
		
		return parent::remove();
	}
	
	public function remove_tags($ids)
	{
		
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
		return !empty($row[$fid]) ? explode(',', $row[$fid]) : array();
	}

	public function fetch_headline_value($value, $document_id)
	{
		if(empty($value)) return NULL;

		$tags = explode(',', $value);
		foreach ($tags as $i => $tag)
		{
			$tags[$i] = UI::label($tag, 'info label-tag');
		}

		return implode(' ', $tags);
	}

	public function filter_condition(Database_Query $query, $condition, $value)
	{
		$query
			->join(array(DataSource_Hybrid_Field_Source_Tags::TABLE_NAME, $this->id.'_f_ht'), 'inner')
			->on($this->id.'_f_ht.field_id', '=', DB::expr( $this->id ))
			->on($this->id.'_f_ht.doc_id', '=', 'd.id')
			->join(array(Model_Tag::TABLE_NAME, $this->id.'_f_tags'), 'inner')
			->on($this->id.'_f_tags.id', '=', $this->id.'_f_ht.tag_id')
			->where($this->id.'_f_tags.name', $condition, $value);
	}
}